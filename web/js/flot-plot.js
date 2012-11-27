var MultiDistributionPlot = Plot.extend({
    init: function(id, distributions, range, options) {
        this._super(id, options);
        this._showPDF = true;
        this._showCDF = false;
        this._pdfValues = [];     // raw values for pdf
        this._cdfValues = [];     // raw values for cdf
        this._maxY = 1;
        this._distributions = distributions;    // underlying PDF
        // Range object for the plot
        if(range != null && Range.validate(range)) {
            this._range = range;
        } else {
        //TODO not sure what to do? Throw exception?
        //this._range = this._distributions[0].getRange(); // no range supplied, use distribution default
        }

        // render
        if(this._distributions != null) {
            this._maxY = this._generateValues();   // create the pdf/cdf values in the ctor
        } else {
            this._options.xaxis = {
                min: range.getMinimum(),
                max: range.getMaximum()
            }
            this._options.yaxis = {
                max: 1
            }
        }

        this._options.series = {
            lines: {
                fill: true
            }
        }

        this.render();
    },
    clear: function() {
        this._super();
        this._distribution = null;
        this._pdfValues = [];
        this._cdfValues = [];
        this.render();
    },
    _generateValues: function() {
        this._cdfValues = [];     // reinitialize the arrays.
        this._pdfValues = [];

        var xs = this._range.getPoints();

        this._options.xaxis = {
            min: xs[0],
            max: xs[xs.length-1]
        }

        for(var i = 0; i < this._distributions.length; i++) {
            var pdfs = this._distributions[i].density(this._range);
            var cdfs = this._distributions[i].cumulativeDensity(this._range);
            var _tempPDFs = [];
            var _tempCDFs = [];
            for(var j = 0; j < xs.length; j++) {
                if(pdfs[j] == Number.POSITIVE_INFINITY || pdfs[j] == Number.NEGATIVE_INFINITY) {
                    pdfs[j] = null;
                }
                if(cdfs[j] == Number.POSITIVE_INFINITY || cdfs[j] == Number.NEGATIVE_INFINITY) {
                    cdfs[j] = null;
                }
                _tempPDFs.push([xs[j], pdfs[j]]);
                _tempCDFs.push([xs[j], cdfs[j]]);
            }
            this._pdfValues[i] = _tempPDFs;
            this._cdfValues[i] = _tempCDFs;
        }


        return jstat.max(pdfs);
    },
    showPDF: function() {
        this._showPDF = true;
        this.render();
    },
    hidePDF: function() {
        this._showPDF = false;
        this.render();
    },
    showCDF: function() {
        this._showCDF = true;
        this.render();
    },
    hideCDF: function() {
        this._showCDF = false;
        this.render();
    },
    setDistribution: function(distribution, range) {
        this._distribution = distribution;
        if(range != null) {
            this._range = range;
        } else {
            this._range = distribution.getRange();
        }
        this._maxY = this._generateValues();
        this._options.yaxis = {
            max: this._maxY*1.1
        }
        this.render();
    },
    getDistribution: function() {
        return this._distribution;
    },
    getRange: function() {
        return this._range;
    },
    setRange: function(range) {
        this._range = range;
        this._generateValues();
        this.render();
    },
    render: function() {
        if(this._distributions.length == 1) {
            this.setData([{
                data:this._pdfValues[0],
                hoverable: true,
                color: 'rgb(196,34,29)',
                clickable: false,
                lines:{
                    fill: true
                },
                lineWidth: 10,
                label: "PDF"
            }]);
        } else {
            var data = [];
            // add the other distributions
            for(var i = 1; i < this._distributions.length; i++) {
                data.push({
                    data:this._pdfValues[i],
                    hoverable: false,
                    color: 'rgb(216,216,192)',
                    shadowSize: 0,
                    clickable: false,
                    lines: {
                        fill: false
                    },

                    label: "PDF"
                });
            }
            // push the main distribution
            data.push({
                data:this._pdfValues[0],
                hoverable: true,
                color: 'rgb(196,34,29)',
                clickable: false,
                shadowSize: 0,
                lines: {
                    fill: true
                },
                label: "PDF"
            });
            this.setData(data);
        }

        this._super();  // Call the parent plot method
    }
});


var ContinuousPlot = DistributionPlot.extend({
    init: function(id, distribution, range, markers, options, maxY) {
        this._showMarkers = true;
        if(markers == null) {
            this._markers = [
            {
                value: null,
                label: 'Lower Quartile'
            },
            {
                value: null,
                label: 'Median'
            },
            {
                value: null,
                label: 'Upper Quartile'
            }
            ]
        } else {
            this._markers = markers;
        }
        if(options != null) {
            if(options.showMarkers != null) this._showMarkers = options.showMarkers;
        }
        this._super(id, distribution, range, options, maxY);
    },
    renderMarkers: function() {
        if(this._showMarkers) {
            for(var i = 0; i < this._markers.length; i++) {
                var label = this._markers[i].label;
                var value = this._markers[i].value;
                var y = this._flotObj.getAxes().yaxis.max;
                var data = [[value, 0], [value, y]];
                var color;
                if(i == 0) {
                    color = 'rgb(49,128,230)';
                } else if (i == 1) {
                    color = 'rgb(230,49,49)'
                } else if(i == 2) {
                    color = 'rgb(49,230,61)'
                }
                this._plots.push({
                    yaxis:1,
                    data: data,
                    label: label,
                    color: color,
                    shadowSize: 0
                });
            }
            this._flotObj.setData(this._plots);
            this._flotObj.setupGrid();
            this._flotObj.draw();
        }
    },
    setMedian: function(value) {
        this._markers[1].value = value;
        this.render();
    },
    setLowerQuartile: function(value) {
        this._markers[0].value = value;
        this.render();
    },
    setUpperQuartile: function(value) {
        this._markers[2].value = value;
        this.render();
    },
    showMarkers: function() {
        this._showMarkers = true;
        this.render();
    },
    hideMarkers: function() {
        this._showMarkers = false;
        this.render();
    },
    render: function() {
        this._super();
        this.renderMarkers();
    },
    clear: function() {
        this._super();
    //this.renderMarkers();
    //this.render();
    }
});
