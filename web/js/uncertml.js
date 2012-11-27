/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function Range(min, max, points) {
    this.min = parseFloat(min);
    this.max = parseFloat(max);
    this.points = parseFloat(points);

    this.getMinimum = function() {
        return minimum;
    }

    this.getMaximum = function() {
        return maximum;
    }

    this.getNumPoints = function() {
        return numPoints;
    }

    Range.validate = function(range) {
        if(isNaN(range.min) || isNaN(range.max) || isNaN(range.points) || range.max < range.min || range.points <= 0) {
            throw "Invalid range supplied";
        }
        return true;
    }

    this.getPoints = function() {
        var results = [];
        var x = this.min;
        var step = (this.max-this.min)/this.points;
        for(var i = 0; i < this.points; i++) {
            results[i] = x;
            x += step;
        }
        return results;
    }

}


var UncertML={};

UncertML.gammaln = function(xx) {
    var x,y,tmp,ser;
    var coef = [76.18009172947146, -86.50532032941677, 24.01409824083091, -1.231739572450155, 0.1208650973866179e-2, -0.5395239384953e-5];

    y=x=xx;
    tmp=x+5.5;
    tmp -= (x+0.5)*Math.log(tmp);
    ser=1.000000000190015;
    for(var j = 0; j < coef.length; j++) {
        ser += coef[j]/++y;
    }
    return -tmp+Math.log(2.5066282746310005*ser/x);
}

UncertML.gser = function gammap(a,x) {
    var ITMAX = 100;
    var EPS = 3.0e-7;

    var sum,del,ap;
    if(x <= 0.0) {
        if(x < 0.0) {
            throw "x less than 0 in routine gser";
        }
        return 0.0;
    } else {
        ap = a;
        del=sum=1.0/a;
        for(var n = 1; n<=ITMAX; n++) {
            ++ap;
            del *= x/ap;
            sum += del;
            if(Math.abs(del) < Math.abs(sum) * EPS) {
                return sum*Math.exp(-x+a*Math.log(x)-UncertML.gammaln(a));
            }
        }
        throw "a too large, ITMAX too small in routine gser";
    }

}

UncertML.beta = function(z, w) {
    return Math.exp(UncertML.gammaln(z)+UncertML.gammaln(w)-UncertML.gammaln(z+w));
}

UncertML.betacf = function(a, b, x) {
    var MAXIT = 100;
    var EPS = 3.0e-7;
    var FPMIN = 1.0e-30;

    var m,m2,aa,c,d,del,h,qab,qam,qap;

    qab=a+b;
    qap=a+1.0;
    qam=a-1.0;
    c=1.0;
    d=1.0-qab*x/qap;

    if(Math.abs(d) < FPMIN) {
        d=FPMIN;
    }

    d = 1.0/d;
    h=d;
    for(m = 1; m <= MAXIT; m++) {
        m2=2*m;
        aa=m*(b-m)*x/((qam+m2)*qap+m2);
        d=1.0+aa*d;
        if(Math.abs(d) < FPMIN) {
            d = FPMIN;
        }
        c=1.0+aa/c;
        if(Math.abs(c) < FPMIN) {
            c = FPMIN;
        }
        d=1.0/d;
        h *= d*c;
        aa = -(a+m)*(qab+m)*x/((a+m2) * (qap+m2));
        d=1.0+aa*d;
        if(Math.abs(d) < FPMIN) {
            d = FPMIN;
        }
        c=1.0+aa/c;
        if(Math.abs(c) < FPMIN) {
            c=FPMIN;
        }
        d=1.0/d;
        del=d*c;
        h *= del;
        if(Math.abs(del-1.0) < EPS) {
            // are we done?
            break;
        }
    }
    if(m > MAXIT) {
        throw "a or b too big, or MAXIT too small in betacf";
    }
    return h;
}

UncertML.betai = function(a, b, x) {
    var bt;

    if(x < 0.0 || x > 1.0) {
        throw "bad x in routine betai";
    }
    if(x == 0.0 || x == 1.0) {
        bt = 0.0;
    } else {
        bt = Math.exp(UncertML.gammaln(a+b) - UncertML.gammaln(a) - UncertML.gammaln(b) + a * Math.log(x)+ b * Math.log(1.0-x));
    }

    if(x < (a+1.0)/(a+b+2.0)) {
        return bt * UncertML.betacf(a,b,x)/a
    } else {
        return 1.0-bt*UncertML.betacf(b,a,1.0-x)/b;
    }
}


UncertML.erf = function(x) {
    /*
     * ERF constants
     */
    var a1 = 0.254829592;
    var a2 = -0.284496736;
    var a3 = 1.421413741;
    var a4 = -1.453152027;
    var a5 = 1.061405429;
    var p = 0.3275911;

    var sign = 1;
    if (x < 0) {
        sign = -1;
    }

    x = Math.abs(x);

    var t = 1.0/(1.0 + p*x);
    var y = 1.0 - (((((a5*t + a4)*t)+a3)*t + a2)* t + a1)*t*Math.exp(-x*x);
    return sign*y;

}

function StudentTDistribution(degreesOfFreedom, mean, variance) {
    that = this;
    this.degreesOfFreedom = degreesOfFreedom;
    if(mean != null) {
        this.mean = mean;
    } else {
        this.mean = 0.0;
    }
    if(this.variance != null) {
        this.variance = variance;
    } else {
        this.variance = 1.0;
    }

    this.getName = function() {
        return 'Student T';
    }

    function pdf(x) {

        var t1 = UncertML.gammaln(that.degreesOfFreedom/2 + 0.5);
        var t2 = UncertML.gammaln(that.degreesOfFreedom/2);
        var t3 = -0.5 * Math.log(that.variance * Math.PI* that.degreesOfFreedom);
        var t4 = (-that.degreesOfFreedom / 2 - 0.5) * Math.log(1 + ((Math.pow(x - that.mean, 2)/(that.degreesOfFreedom * that.variance))));

        var ty = t1 - t2 + t3 + t4;

        return Math.exp(ty);
    }

    function cdf(x) {

    }

    this.evalPDF = function(rangeOrValue) {
        if(!isNaN(rangeOrValue)) {
            // single value
            return pdf(rangeOrValue);
        } else if (Range.validate(rangeOrValue)) {
            // multiple values
            var step = (rangeOrValue.max - rangeOrValue.min) / rangeOrValue.points;
            var x = rangeOrValue.min;
            var result = [];
            // For each point in the range
            for(var i = 0; i < rangeOrValue.points; i++) {
                result[i] = pdf(x);
                x += step;
            }
            return result;
        } else {
            throw "Invalid number supplied";
        }
    }

    this.evalCDF = function(rangeOrValue) {
        if(!isNaN(rangeOrValue)) {
            // single value
            return cdf(rangeOrValue);
        } else if (Range.validate(rangeOrValue)) {
            // multiple values
            var step = (rangeOrValue.max - rangeOrValue.min) / rangeOrValue.points;
            var x = rangeOrValue.min;
            var result = [];
            // For each point in the range
            for(var i = 0; i < rangeOrValue.points; i++) {
                result[i] = cdf(x);
                x += step;
            }
            return result;
        } else {
            throw "Invalid number supplied";
        }
    }
}

function GammaDistribution(shape, scale) {
    that = this;
    this.shape = parseFloat(shape);
    this.scale = parseFloat(scale);

    function pdf(x) {
        var y = Math.exp((that.shape-1) * Math.log(x) - (x/that.scale) - UncertML.gammaln(that.shape) - that.shape * Math.log(that.scale));
        return y;
    }

    function cdf(x) {
        return UncertML.gser(that.shape, x/that.scale);
    }

    this.getName = function() {
        return 'Gamma';
    }

    this.evalPDF = function(rangeOrValue) {
        if(!isNaN(rangeOrValue)) {
            // single value
            return pdf(rangeOrValue);
        } else if (Range.validate(rangeOrValue)) {
            // multiple values
            var step = (rangeOrValue.max - rangeOrValue.min) / rangeOrValue.points;
            var x = rangeOrValue.min;
            var result = [];
            // For each point in the range
            for(var i = 0; i < rangeOrValue.points; i++) {
                result[i] = pdf(x);
                x += step;
            }
            return result;
        } else {
            throw "Invalid number supplied";
        }
    }

    this.evalCDF = function(rangeOrValue) {
        if(!isNaN(rangeOrValue)) {
            // single value
            return cdf(rangeOrValue);
        } else if (Range.validate(rangeOrValue)) {
            // multiple values
            var step = (rangeOrValue.max - rangeOrValue.min) / rangeOrValue.points;
            var x = rangeOrValue.min;
            var result = [];
            // For each point in the range
            for(var i = 0; i < rangeOrValue.points; i++) {
                result[i] = cdf(x);
                x += step;
            }
            return result;
        } else {
            throw "Invalid number supplied";
        }
    }
}

function BetaDistribution(alpha, beta) {
    that = this;
    this.alpha = alpha;
    this.beta = beta;

    function pdf(x) {
        if (x < 0 || x > 1) {
            return 0;
        }else if (x == 0) {
            if (that.alpha < 1) {
                throw "Cannot compute beta density at 0 when alpha = " + that.alpha;
            }
            return 0;
        }else if (x == 1) {
            if (that.beta < 1) {
                throw "Cannot compute beta density at 1 when beta = " + that.beta;
            }
            return 0;
        } else {
            var y = (Math.pow(x, that.alpha-1.0) * Math.pow(1.0-x, that.beta-1.0))/UncertML.beta(that.alpha, that.beta);
            return y;
        }
    }

    function cdf(x) {
        return UncertML.betai(that.alpha, that.beta, x);
    }

    this.getName = function() {
        return 'Beta';
    }

    this.evalPDF = function(rangeOrValue) {
        if(!isNaN(rangeOrValue)) {
            // single value
            return pdf(rangeOrValue);
        } else if (Range.validate(rangeOrValue)) {
            // multiple values
            var step = (rangeOrValue.max - rangeOrValue.min) / rangeOrValue.points;
            var x = rangeOrValue.min;
            var result = [];
            // For each point in the range
            for(var i = 0; i < rangeOrValue.points; i++) {
                result[i] = pdf(x);
                x += step;
            }
            return result;
        } else {
            throw "Invalid number supplied";
        }
    }

    this.evalCDF = function(rangeOrValue) {
        if(!isNaN(rangeOrValue)) {
            // single value
            return cdf(rangeOrValue);
        } else if (Range.validate(rangeOrValue)) {
            // multiple values
            var step = (rangeOrValue.max - rangeOrValue.min) / rangeOrValue.points;
            var x = rangeOrValue.min;
            var result = [];
            // For each point in the range
            for(var i = 0; i < rangeOrValue.points; i++) {
                result[i] = cdf(x);
                x += step;
            }
            return result;
        } else {
            throw "Invalid number supplied";
        }
    }
}


function LogNormalDistribution(mean, variance) {
    that = this;
    this.mean = mean;
    this.variance = variance;

    function pdf(x) {
        var y = (1.0 / (x * Math.sqrt(2 * Math.PI * that.variance))) *
        Math.exp(-Math.pow(Math.log(x) - that.mean, 2)/ (2 * that.variance));
        return y;
    }

    function cdf(x) {
        var y = 0.5 + (0.5 * UncertML.erf((Math.log(x)-that.mean)/Math.sqrt(2*that.variance)));
        return y;
    }

    this.getName = function() {
        return 'Log-normal';
    }

    this.evalPDF = function(rangeOrValue) {
        if(!isNaN(rangeOrValue)) {
            // single value
            return pdf(rangeOrValue);
        } else if (Range.validate(rangeOrValue)) {
            // multiple values
            var step = (rangeOrValue.max - rangeOrValue.min) / rangeOrValue.points;
            var x = rangeOrValue.min;
            var result = [];
            // For each point in the range
            for(var i = 0; i < rangeOrValue.points; i++) {
                result[i] = pdf(x);
                x += step;
            }
            return result;
        } else {
            throw "Invalid number supplied";
        }
    }

    this.evalCDF = function(rangeOrValue) {
        if(!isNaN(rangeOrValue)) {
            // single value
            return cdf(rangeOrValue);
        } else if (Range.validate(rangeOrValue)) {
            // multiple values
            var step = (rangeOrValue.max - rangeOrValue.min) / rangeOrValue.points;
            var x = rangeOrValue.min;
            var result = [];
            // For each point in the range
            for(var i = 0; i < rangeOrValue.points; i++) {
                result[i] = cdf(x);
                x += step;
            }
            return result;
        } else {
            throw "Invalid number supplied";
        }
    }
}


function GaussianDistribution(mean, variance) {
    var that = this;
    this.mean = mean;
    this.variance = variance;

    function pdf(x) {
        var y = (1 / Math.sqrt(2 * Math.PI * that.variance)) *
        Math.exp(-(Math.pow(x-that.mean,2)/(2*that.variance)));
        return y;
    }

    function cdf(x) {
        var y = 0.5 * (1 + UncertML.erf((x-that.mean)/Math.sqrt(2*that.variance)));
        return y;
    }

    this.getName = function() {
        return 'Gaussian';
    }

    this.evalPDF = function(rangeOrValue) {
        if(!isNaN(rangeOrValue)) {
            // single value
            return pdf(rangeOrValue);
        } else if (Range.validate(rangeOrValue)) {
            // multiple values
            var step = (rangeOrValue.max - rangeOrValue.min) / rangeOrValue.points;
            var x = rangeOrValue.min;
            var result = [];
            // For each point in the range
            for(var i = 0; i < rangeOrValue.points; i++) {
                result[i] = pdf(x);
                x += step;
            }
            return result;
        } else {
            throw "Invalid number supplied";
        }
    }

    this.evalCDF = function(rangeOrValue) {
        if(!isNaN(rangeOrValue)) {
            // single value
            return cdf(rangeOrValue);
        } else if (Range.validate(rangeOrValue)) {
            // multiple values
            var step = (rangeOrValue.max - rangeOrValue.min) / rangeOrValue.points;
            var x = rangeOrValue.min;
            var result = [];
            // For each point in the range
            for(var i = 0; i < rangeOrValue.points; i++) {
                result[i] = cdf(x);
                x += step;
            }
            return result;
        } else {
            throw "Invalid number supplied";
        }
    }
}