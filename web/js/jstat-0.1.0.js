function jstat(){}
j = jstat;
/* Simple JavaScript Inheritance
 * By John Resig http://ejohn.org/
 * MIT Licensed.
 */
// Inspired by base2 and Prototype
(function(){
    var initializing = false, fnTest = /xyz/.test(function(){
        xyz;
    }) ? /\b_super\b/ : /.*/;
    // The base Class implementation (does nothing)
    this.Class = function(){};

    // Create a new Class that inherits from this class
    Class.extend = function(prop) {
        var _super = this.prototype;

        // Instantiate a base class (but only create the instance,
        // don't run the init constructor)
        initializing = true;
        var prototype = new this();
        initializing = false;

        // Copy the properties over onto the new prototype
        for (var name in prop) {
            // Check if we're overwriting an existing function
            prototype[name] = typeof prop[name] == "function" &&
            typeof _super[name] == "function" && fnTest.test(prop[name]) ?
            (function(name, fn){
                return function() {
                    var tmp = this._super;

                    // Add a new ._super() method that is the same method
                    // but on the super-class
                    this._super = _super[name];

                    // The method only need to be bound temporarily, so we
                    // remove it when we're done executing
                    var ret = fn.apply(this, arguments);
                    this._super = tmp;

                    return ret;
                };
            })(name, prop[name]) :
            prop[name];
        }

        // The dummy class constructor
        function Class() {
            // All construction is actually done in the init method
            if ( !initializing && this.init )
                this.init.apply(this, arguments);
        }

        // Populate our constructed prototype object
        Class.prototype = prototype;

        // Enforce the constructor to be what we expect
        Class.constructor = Class;

        // And make this class extendable
        Class.extend = arguments.callee;

        return Class;
    };
})();

/******************************************************************************/
/*                           Constants                                        */
/******************************************************************************/
jstat.ONE_SQRT_2PI  =   0.3989422804014327;
jstat.LN_SQRT_2PI   =   0.9189385332046727417803297;
jstat.LN_SQRT_PId2  =   0.225791352644727432363097614947;
jstat.DBL_MIN       =   2.22507e-308;
jstat.DBL_EPSILON   =   2.220446049250313e-16;
jstat.SQRT_32       =   5.656854249492380195206754896838;
jstat.TWO_PI        =   6.283185307179586;
jstat.DBL_MIN_EXP   =   -999;
jstat.SQRT_2dPI     =   0.79788456080287;
jstat.LN_SQRT_PI    =   0.5723649429247;
/******************************************************************************/
/*                          jstat   Functions                                 */
/******************************************************************************/
jstat.seq = function(min, max, length) {
    var r = new Range(min, max, length);
    return r.getPoints();
}

jstat.dnorm = function(x, mean, sd, log) {
    if(mean == null) mean = 0;
    if(sd == null) sd = 1;
    if(log == null) log = false;
    var n = new NormalDistribution(mean, sd);
    if(!isNaN(x)) {
        // is a number
        return n._pdf(x, log);
    } else if(x.length) {
        var res = [];
        for(var i = 0; i < x.length; i++) {
            res.push(n._pdf(x[i], log));
        }
        return res;
    } else {
        throw "Illegal argument: x";
    }
}

jstat.pnorm = function(q, mean, sd, lower_tail, log) {
    if(mean == null) mean = 0;
    if(sd == null) sd = 1;
    if(lower_tail == null) lower_tail = true;
    if(log == null) log = false;

    var n = new NormalDistribution(mean, sd);
    if(!isNaN(q)) {
        // is a number
        return n._cdf(q, lower_tail, log);
    } else if(q.length) {
        var res = [];
        for(var i = 0; i < q.length; i++) {
            res.push(n._cdf(q[i], lower_tail, log));
        }
        return res;
    } else {
        throw "Illegal argument: x";
    }
}

jstat.dlnorm = function(x, meanlog, sdlog, log) {
    if(meanlog == null) meanlog = 0;
    if(sdlog == null) sdlog = 1;
    if(log == null) log = false;
    var n = new LogNormalDistribution(meanlog, sdlog);
    if(!isNaN(x)) {
        // is a number
        return n._pdf(x, log);
    } else if(x.length) {
        var res = [];
        for(var i = 0; i < x.length; i++) {
            res.push(n._pdf(x[i], log));
        }
        return res;
    } else {
        throw "Illegal argument: x";
    }
}

jstat.plnorm = function(q, meanlog, sdlog, lower_tail, log) {
    if(meanlog == null) meanlog = 0;
    if(sdlog == null) sdlog = 1;
    if(lower_tail == null) lower_tail = true;
    if(log == null) log = false;

    var n = new LogNormalDistribution(meanlog, sdlog);
    if(!isNaN(q)) {
        // is a number
        return n._cdf(q, lower_tail, log);
    }
    else if(q.length) {
        var res = [];
        for(var i = 0; i < q.length; i++) {
            res.push(n._cdf(q[i], lower_tail, log));
        }
        return res;
    } else {
        throw "Illegal argument: x";
    }
}

jstat.dbeta = function(x, alpha, beta, ncp, log) {
    if(ncp == null) ncp = 0;
    if(log == null) log = false;
    var b = new BetaDistribution(alpha, beta);
    if(!isNaN(x)) {
        // is a number
        return b._pdf(x, log);
    }
    else if(x.length) {
        var res = [];
        for(var i = 0; i < x.length; i++) {
            res.push(b._pdf(x[i], log));
        }
        return res;
    } else {
        throw "Illegal argument: x";
    }
}

jstat.pbeta = function(q, alpha, beta, ncp, lower_tail, log) {
    if(ncp == null) ncp = 0;
    if(log == null) log = false;
    if(lower_tail == null) lower_tail = true;

    var b = new BetaDistribution(alpha, beta);
    if(!isNaN(q)) {
        // is a number
        return b._cdf(q, lower_tail, log);
    } else if(q.length) {
        var res = [];
        for(var i = 0; i < q.length; i++) {
            res.push(b._cdf(q[i], lower_tail, log));
        }
        return res;
    }
    else {
        throw "Illegal argument: x";
    }
}

jstat.dgamma = function(x, shape, rate, scale, log) {
    if(rate == null) rate = 1;
    if(scale == null) scale = 1/rate;
    if(log == null) log = false;

    var g = new GammaDistribution(shape, scale);
    if(!isNaN(x)) {
        // is a number
        return g._pdf(x, log);
    } else if(x.length) {
        var res = [];
        for(var i = 0; i < x.length; i++) {
            res.push(g._pdf(x[i], log));
        }
        return res;
    } else {
        throw "Illegal argument: x";
    }
}

jstat.pgamma = function(q, shape, rate, scale, lower_tail, log) {
    if(rate == null) rate = 1;
    if(scale == null) scale = 1/rate;
    if(lower_tail == null) lower_tail = true;
    if(log == null) log = false;

    var g = new GammaDistribution(shape, scale);
    if(!isNaN(q)) {
        // is a number
        return g._cdf(q, lower_tail, log);
    } else if(q.length) {
        var res = [];
        for(var i = 0; i < q.length; i++) {
            res.push(g._cdf(q[i], lower_tail, log));
        }
        return res;
    } else {
        throw "Illegal argument: x";
    }

}

jstat.dt = function(x, df, ncp, log) {
    if(log == null) log = false;

    var t = new StudentTDistribution(df, ncp);
    if(!isNaN(x)) {
        // is a number
        return t._pdf(x, log);
    } else if(x.length) {
        var res = [];
        for(var i = 0; i < x.length; i++) {
            res.push(t._pdf(x[i], log));
        }
        return res;
    } else {
        throw "Illegal argument: x";
    }

}

jstat.pt = function(q, df, ncp, lower_tail, log) {
    if(lower_tail == null) lower_tail = true;
    if(log == null) log = false;

    var t = new StudentTDistribution(df, ncp);
    if(!isNaN(q)) {
        // is a number
        return t._cdf(q, lower_tail, log);
    } else if(q.length) {
        var res = [];
        for(var i = 0; i < q.length; i++) {
            res.push(t._cdf(q[i], lower_tail, log));
        }
        return res;
    } else {
        throw "Illegal argument: x";
    }

}

jstat.plot = function(x, y, options) {
    if(x == null) {
        throw "x is undefined in jstat.plot";
    }
    if(y == null) {
        throw "y is undefined in jstat.plot";
    }
    if(x.length != y.length) {
        throw "x and y lengths differ in jstat.plot";
    }

    var flotOpt = {
        series: {
            lines: {

            },
            points: {

        }
        }
    };

    // combine x & y
    var series = [];
    if(x.length == undefined) {
        // single point
        series.push([x, y]);
        flotOpt.series.points.show = true;
    } else {
        // array
        for(var i = 0; i < x.length; i++) {
            series.push([x[i], y[i]]);
        }
    }

    var title = 'jstat graph';

    // configure Flot options
    if(options != null) {
        // options = JSON.parse(String(options));
        if(options.type != null) {
            if(options.type == 'l') {
                flotOpt.series.lines.show =  true;
            } else if (options.type == 'p') {
                flotOpt.series.lines.show = false;
                flotOpt.series.points.show = true;
            }
        }

        if(options.main != null) {
            title = options.main;
        }
    }
    var now = new Date();
    var hash = now.getMilliseconds() * now.getMinutes() + now.getSeconds();
    $('body').append('<div title="' + title + '" style="display: none;" id="'+ hash +'"><div id="graph-' + hash + '" style="width:95%; height: 95%"></div></div>');

    $('#' + hash).dialog({
        modal: false,
        width: 475,
        height: 475,
        resizable: true,
        resize: function() {
            $.plot($('#graph-' + hash), [series], flotOpt);
        },
        open: function(event, ui) {
            var id = '#graph-' + hash;
            $.plot($('#graph-' + hash), [series], flotOpt);
        }
    })
}

/******************************************************************************/
/*                          Special Functions                                 */
/******************************************************************************/

jstat.trunc = function(x) {
    return (x > 0) ? Math.floor(x) : Math.ceil(x);
}

/**
 *  Tests whether x is a finite number
 */
jstat.isFinite = function(x) {
    return (!isNaN(x) && (x != Number.POSITIVE_INFINITY) && (x != Number.NEGATIVE_INFINITY));
}

/**
 *      dopois_raw() computes the Poisson probability  lb^x exp(-lb) / x!.
 *      This does not check that x is an integer, since dgamma() may
 *      call this with a fractional x argument. Any necessary argument
 *      checks should be done in the calling function.
 */
jstat.dopois_raw = function(x, lambda, give_log) {
    /*       x >= 0 ; integer for dpois(), but not e.g. for pgamma()!
        lambda >= 0
     */
    if (lambda == 0) {
        if(x == 0) {
            return(give_log) ? 0.0 : 1.0; //R_D__1
        }
        return (give_log) ? Number.NEGATIVE_INFINITY : 0.0; // R_D__0
    }
    if (!jstat.isFinite(lambda)) return (give_log) ? Number.NEGATIVE_INFINITY : 0.0; //R_D__0;
    if (x < 0) return(give_log) ? Number.NEGATIVE_INFINITY : 0.0; //R_D__0
    if (x <= lambda * jstat.DBL_MIN) {
        return (give_log) ? -lambda : Math.exp(-lambda);    // R_D_exp(-lambda)
    }
    if (lambda < x * jstat.DBL_MIN) {
        var param = -lambda + x*Math.log(lambda) -jstat.lgamma(x+1);
        return (give_log) ? param : Math.exp(param);    // R_D_exp(-lambda + x*log(lambda) -lgammafn(x+1))
    }
    var param1 = jstat.TWO_PI * x;  // f
    var param2 = -jstat.stirlerr(x)-jstat.bd0(x,lambda);    // x
    return (give_log) ? -0.5*Math.log(param1)+param2 : Math.exp(param2)/Math.sqrt(param1);  // R_D_fexp(M_2PI*x, -stirlerr(x)-bd0(x,lambda))
//return(R_D_fexp( , -stirlerr(x)-bd0(x,lambda) ));
}

/**	Evaluates the "deviance part"
 *	bd0(x,M) :=  M * D0(x/M) = M*[ x/M * log(x/M) + 1 - (x/M) ] =
 *		  =  x * log(x/M) + M - x
 *	where M = E[X] = n*p (or = lambda), for	  x, M > 0
 *
 *	in a manner that should be stable (with small relative error)
 *	for all x and M=np. In particular for x/np close to 1, direct
 *	evaluation fails, and evaluation is based on the Taylor series
 *	of log((1+v)/(1-v)) with v = (x-np)/(x+np).
 */
jstat.bd0 = function(x, np) {
    var ej, s, s1, v, j;
    if(!jstat.isFinite(x) || !jstat.isFinite(np) || np == 0.0) throw "illegal parameter in jstat.bd0";

    if(Math.abs(x-np) > 0.1*(x+np)) {
        v = (x-np)/(x+np);
        s = (x-np)*v;/* s using v -- change by MM */
        ej = 2*x*v;
        v = v*v;
        for (j=1; ; j++) { /* Taylor series */
            ej *= v;
            s1 = s+ej/((j<<1)+1);
            if (s1==s) /* last term was effectively 0 */
                return(s1);
            s = s1;
        }
    }
    /* else:  | x - np |  is not too small */
    return(x*Math.log(x/np)+np-x);
}

/**    Computes the log of the error term in Stirling's formula.
 *      For n > 15, uses the series 1/12n - 1/360n^3 + ...
 *      For n <=15, integers or half-integers, uses stored values.
 *      For other n < 15, uses lgamma directly (don't use this to
 *        write lgamma!)
 */
jstat.stirlerr= function(n) {
    var S0 = 0.083333333333333333333;
    var S1 = 0.00277777777777777777778;
    var S2 = 0.00079365079365079365079365;
    var S3 = 0.000595238095238095238095238;
    var S4 = 0.0008417508417508417508417508;

    var sferr_halves = [
    0.0, /* n=0 - wrong, place holder only */
    0.1534264097200273452913848,  /* 0.5 */
    0.0810614667953272582196702,  /* 1.0 */
    0.0548141210519176538961390,  /* 1.5 */
    0.0413406959554092940938221,  /* 2.0 */
    0.03316287351993628748511048, /* 2.5 */
    0.02767792568499833914878929, /* 3.0 */
    0.02374616365629749597132920, /* 3.5 */
    0.02079067210376509311152277, /* 4.0 */
    0.01848845053267318523077934, /* 4.5 */
    0.01664469118982119216319487, /* 5.0 */
    0.01513497322191737887351255, /* 5.5 */
    0.01387612882307074799874573, /* 6.0 */
    0.01281046524292022692424986, /* 6.5 */
    0.01189670994589177009505572, /* 7.0 */
    0.01110455975820691732662991, /* 7.5 */
    0.010411265261972096497478567, /* 8.0 */
    0.009799416126158803298389475, /* 8.5 */
    0.009255462182712732917728637, /* 9.0 */
    0.008768700134139385462952823, /* 9.5 */
    0.008330563433362871256469318, /* 10.0 */
    0.007934114564314020547248100, /* 10.5 */
    0.007573675487951840794972024, /* 11.0 */
    0.007244554301320383179543912, /* 11.5 */
    0.006942840107209529865664152, /* 12.0 */
    0.006665247032707682442354394, /* 12.5 */
    0.006408994188004207068439631, /* 13.0 */
    0.006171712263039457647532867, /* 13.5 */
    0.005951370112758847735624416, /* 14.0 */
    0.005746216513010115682023589, /* 14.5 */
    0.005554733551962801371038690  /* 15.0 */
    ];

    var nn;

    if (n <= 15.0) {
        nn = n + n;
        if (nn == parseInt(nn)) return(sferr_halves[parseInt(nn)]);
        return(jstat.lgamma(n + 1.0) - (n + 0.5)*Math.log(n) + n - jstat.LN_SQRT_2PI);
    }

    nn = n*n;
    if (n>500) return((S0-S1/nn)/n);
    if (n> 80) return((S0-(S1-S2/nn)/nn)/n);
    if (n> 35) return((S0-(S1-(S2-S3/nn)/nn)/nn)/n);
    /* 15 < n <= 35 : */
    return((S0-(S1-(S2-(S3-S4/nn)/nn)/nn)/nn)/n);
}



/**    The function lgamma computes log|gamma(x)|.  The function
 *    lgammafn_sign in addition assigns the sign of the gamma function
 *    to the address in the second argument if this is not null.
 */
jstat.lgamma = function(x) {
    function lgammafn_sign(x, sgn) {
        var ans, y, sinpiy;
        var xmax = 2.5327372760800758e+305;
        var dxrel = 1.490116119384765696e-8;

        // if (xmax == 0) {/* initialize machine dependent constants _ONCE_ */
        //     xmax = jstat.DBL_MAX/Math.log(jstat.DBL_MAX);/* = 2.533 e305	 for IEEE double */
        //     dxrel = Math.sqrt(jstat.DBL_EPSILON);/* sqrt(Eps) ~ 1.49 e-8  for IEEE double */
        // }

        /* For IEEE double precision DBL_EPSILON = 2^-52 = 2.220446049250313e-16 :
           xmax  = DBL_MAX / log(DBL_MAX) = 2^1024 / (1024 * log(2)) = 2^1014 / log(2)
           dxrel = sqrt(DBL_EPSILON) = 2^-26 = 5^26 * 1e-26 (is *exact* below !)
         */

        if (sgn != null) sgn = 1;

        if(isNaN(x)) return x;

        if (x < 0 && (Math.floor(-x) % 2.0) == 0)
            if (sgn != null) sgn = -1;

        if (x <= 0 && x == jstat.trunc(x)) { /* Negative integer argument */
            console.warn("Negative integer argument in lgammafn_sign");
            return Number.POSITIVE_INFINITY;/* +Inf, since lgamma(x) = log|gamma(x)| */
        }

        y = Math.abs(x);

        if(y <= 10) return Math.log(Math.abs(jstat.gamma(x)));  // TODO: implement jstat.gamma

        if(y > xmax) {
            console.warn("Illegal arguement passed to lgammafn_sign");
            return Number.POSITIVE_INFINITY;
        }

        if(x > 0) {
            if(x > 1e17) {
                return (x*(Math.log(x)-1.0));
            } else if(x > 4934720.0) {
                return (jstat.LN_SQRT_2PI + (x-0.5) * Math.log(x) - x);
            } else {
                return jstat.LN_SQRT_2PI + (x-0.5) * Math.log(x) - x + jstat.lgammacor(x);  // TODO: implement lgammacor
            }
        }

        sinpiy = Math.abs(Math.sin(Math.PI * y));

        if(sinpiy == 0) {
            throw "Should never happen!!";
        }

        ans = jstat.LN_SQRT_PId2 + (x - 0.5) * Math.log(y) - x - Math.log(sinpiy) - jstat.lgammacor(y);

        if(Math.abs((x-jstat.trunc(x-0.5))* ans / x) < dxrel) {
            throw "The answer is less than half the precision argument too close to a negative integer";
        }
        return ans;
    }

    return lgammafn_sign(x, null);
}

jstat.gamma = function(x) {
    var xbig = 171.624;
    var p = [
    -1.71618513886549492533811,
    24.7656508055759199108314,-379.804256470945635097577,
    629.331155312818442661052,866.966202790413211295064,
    -31451.2729688483675254357,-36144.4134186911729807069,
    66456.1438202405440627855
    ];
    var q = [
    -30.8402300119738975254353,
    315.350626979604161529144,-1015.15636749021914166146,
    -3107.77167157231109440444,22538.1184209801510330112,
    4755.84627752788110767815,-134659.959864969306392456,
    -115132.259675553483497211
    ];
    var c = [
    -.001910444077728,8.4171387781295e-4,
    -5.952379913043012e-4,7.93650793500350248e-4,
    -.002777777777777681622553,.08333333333333333331554247,
    .0057083835261
    ];

    var i,n,parity,fact,xden,xnum,y,z,yi,res,sum,ysq;

    parity = (0);
    fact = 1.0;
    n = 0;
    y=x;
    if(y <= 0.0) {
        /* -------------------------------------------------------------
	   Argument is negative
	   ------------------------------------------------------------- */
        y = -x;
        yi = jstat.trunc(y);
        res = y - yi;
        if (res != 0.0) {
            if (yi != jstat.trunc(yi * 0.5) * 2.0)
                parity = (1);
            fact = -Math.PI / Math.sin(Math.PI * res);
            y += 1.0;
        } else {
            return(Number.POSITIVE_INFINITY);
        }
    }
    /* -----------------------------------------------------------------
       Argument is positive
       -----------------------------------------------------------------*/
    if (y < jstat.DBL_EPSILON) {
        /* --------------------------------------------------------------
	   Argument < EPS
	   -------------------------------------------------------------- */
        if (y >= jstat.DBL_MIN) {
            res = 1.0 / y;
        } else {
            return(Number.POSITIVE_INFINITY);
        }
    } else if (y < 12.0) {
        yi = y;
        if (y < 1.0) {
            /* ---------------------------------------------------------
	       EPS < argument < 1
	       --------------------------------------------------------- */
            z = y;
            y += 1.0;
        } else {
            /* -----------------------------------------------------------
	       1 <= argument < 12, reduce argument if necessary
	       ----------------------------------------------------------- */
            n = parseInt(y) - 1;
            y -= parseFloat(n);
            z = y - 1.0;
        }
        /* ---------------------------------------------------------
	   Evaluate approximation for 1. < argument < 2.
	   ---------------------------------------------------------*/
        xnum = 0.0;
        xden = 1.0;
        for (i = 0; i < 8; ++i) {
            xnum = (xnum + p[i]) * z;
            xden = xden * z + q[i];
        }
        res = xnum / xden + 1.0;
        if (yi < y) {
            /* --------------------------------------------------------
	       Adjust result for case  0. < argument < 1.
	       -------------------------------------------------------- */
            res /= yi;
        } else if (yi > y) {
            /* ----------------------------------------------------------
	       Adjust result for case  2. < argument < 12.
	       ---------------------------------------------------------- */
            for (i = 0; i < n; ++i) {
                res *= y;
                y += 1.0;
            }
        }
    } else {
        /* -------------------------------------------------------------
	   Evaluate for argument >= 12.,
	   ------------------------------------------------------------- */
        if (y <= xbig) {
            ysq = y * y;
            sum = c[6];
            for (i = 0; i < 6; ++i) {
                sum = sum / ysq + c[i];
            }
            sum = sum / y - y + jstat.LN_SQRT_2PI;
            sum += (y - 0.5) * Math.log(y);
            res = Math.exp(sum);
        } else {
            return(Number.POSITIVE_INFINITY);
        }
    }
    /* ----------------------------------------------------------------------
       Final adjustments and return
       ----------------------------------------------------------------------*/
    if (parity)
        res = -res;
    if (fact != 1.0)
        res = fact / res;
    return res;
}

/**    Compute the log gamma correction factor for x >= 10 so that
 *
 *    log(gamma(x)) = .5*log(2*pi) + (x-.5)*log(x) -x + lgammacor(x)
 *
 *    [ lgammacor(x) is called	Del(x)	in other contexts (e.g. dcdflib)]
 */
jstat.lgammacor = function(x) {
    var algmcs = [
    +.1666389480451863247205729650822e+0,
    -.1384948176067563840732986059135e-4,
    +.9810825646924729426157171547487e-8,
    -.1809129475572494194263306266719e-10,
    +.6221098041892605227126015543416e-13,
    -.3399615005417721944303330599666e-15,
    +.2683181998482698748957538846666e-17,
    -.2868042435334643284144622399999e-19,
    +.3962837061046434803679306666666e-21,
    -.6831888753985766870111999999999e-23,
    +.1429227355942498147573333333333e-24,
    -.3547598158101070547199999999999e-26,
    +.1025680058010470912000000000000e-27,
    -.3401102254316748799999999999999e-29,
    +.1276642195630062933333333333333e-30
    ];

    var tmp;
    var nalgm = 5;
    var xbig = 94906265.62425156;
    var xmax = 3.745194030963158e306;

    if(x < 10) {
        return Number.NaN;
    } else if (x >= xmax) {
        throw "Underflow error in lgammacor";
    } else if (x < xbig) {
        tmp = 10 / x;
        return jstat.chebyshev(tmp*tmp*2-1,algmcs,nalgm) / x;
    }
    return 1 / (x*12);
}

/*
 * Incomplete Beta function
 */
jstat.incompleteBeta = function(a, b, x) {
    /*
     * Used by incompleteBeta: Evaluates continued fraction for incomplete
     * beta function by modified Lentz's method.
     */
    function betacf(a, b, x) {
        var MAXIT = 100;
        var EPS = 3.0e-12;
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
            aa=m*(b-m)*x/((qam+m2)*(a+m2));
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
            console.warn("a or b too big, or MAXIT too small in betacf: " + a + ", " + b + ", " + x + ", " + h);
            return h;
        }
        if(isNaN(h)) {
            console.warn(a + ", " + b + ", " + x);
        }
        return h;
    }

    var bt;

    if(x < 0.0 || x > 1.0) {
        throw "bad x in routine incompleteBeta";
    }
    if(x == 0.0 || x == 1.0) {
        bt = 0.0;
    } else {
        bt = Math.exp(jstat.lgamma(a+b) - jstat.lgamma(a) - jstat.lgamma(b) + a * Math.log(x)+ b * Math.log(1.0-x));
    }
    if(x < (a + 1.0)/(a+b+2.0)) {
        return bt * betacf(a,b,x)/a;
    } else {
        return 1.0-bt*betacf(b,a,1.0-x)/b;
    }
}

/**   Evaluates the n-term Chebyshev series
 *    "a" at "x".
 */
jstat.chebyshev = function(x, a, n) {
    var b0, b1, b2, twox;
    var i;

    if (n < 1 || n > 1000) return Number.NaN;

    if (x < -1.1 || x > 1.1) return Number.NaN;

    twox = x * 2;
    b2 = b1 = 0;
    b0 = 0;
    for (i = 1; i <= n; i++) {
        b2 = b1;
        b1 = b0;
        b0 = twox * b1 - b2 + a[n - i];
    }
    return (b0 - b2) * 0.5;
}

jstat.fmin2 = function(x, y) {
    return (x < y) ? x : y;
}

jstat.log1p = function(x) {
    // http://kevin.vanzonneveld.net
    // +   original by: Brett Zamir (http://brett-zamir.me)
    // %          note 1: Precision 'n' can be adjusted as desired
    // *     example 1: log1p(1e-15);
    // *     returns 1: 9.999999999999995e-16

    var ret = 0,
    n = 50; // degree of precision
    if (x <= -1) {
        return Number.NEGATIVE_INFINITY; // JavaScript style would be to return Number.NEGATIVE_INFINITY
    }
    if (x < 0 || x > 1) {
        return Math.log(1 + x);
    }
    for (var i = 1; i < n; i++) {
        if ((i % 2) === 0) {
            ret -= Math.pow(x, i) / i;
        } else {
            ret += Math.pow(x, i) / i;
        }
    }
    return ret;
}

jstat.expm1 = function(x) {
    var y, a  = Math.abs(x);
    if(a < jstat.DBL_EPSILON) return x;
    if(a > 0.697) return Math.exp(x) - 1; /* negligable cancellation */
    if(a > 1e-8) {
        y = Math.exp(x) - 1;
    } else {
        y = (x / 2 + 1) * x;
    }

    /* Newton step for solving   log(1 + y) = x   for y : */
    /* WARNING: does not work for y ~ -1: bug in 1.5.0 */
    y -= (1 + y) * (jstat.log1p(y) - x);
    return y;
}

jstat.logBeta = function(a, b) {
    var corr, p, q;
    p = q = a;
    if(b < p) p = b;/* := min(a,b) */
    if(b > q) q = b;/* := max(a,b) */

    /* both arguments must be >= 0 */
    if (p < 0) {
        console.warn('Both arguements must be >= 0');
        return Number.NaN;
    }
    else if (p == 0) {
        return Number.POSITIVE_INFINITY;
    }
    else if (!jstat.isFinite(q)) { /* q == +Inf */
        return Number.NEGATIVE_INFINITY;
    }

    if (p >= 10) {
        /* p and q are big. */
        corr = jstat.lgammacor(p) + jstat.lgammacor(q) - jstat.lgammacor(p + q);
        return Math.log(q) * -0.5 + jstat.LN_SQRT_2PI + corr
        + (p - 0.5) * Math.log(p / (p + q)) + q * jstat.log1p(-p / (p + q));
    }
    else if (q >= 10) {
        /* p is small, but q is big. */
        corr = jstat.lgammacor(q) - jstat.lgammacor(p + q);
        return jstat.lgamma(p) + corr + p - p * Math.log(p + q)
        + (q - 0.5) * jstat.log1p(-p / (p + q));
    }
    else
        /* p and q are small: p <= q < 10. */
        return Math.log(jstat.gamma(p) * (jstat.gamma(q) / jstat.gamma(p + q)));
}

jstat.dbinom_raw = function(x, n, p, q, give_log) {
    if(give_log == null) give_log = false;
    var lf, lc;

    if(p == 0) {
        if(x == 0) {
            // R_D__1
            return (give_log) ? 0.0 : 1.0;
        } else {
            // R_D__0
            return (give_log) ? Number.NEGATIVE_INFINITY : 0.0;
        }
    }
    if(q == 0) {
        if(x == n) {
            // R_D__1
            return (give_log) ? 0.0 : 1.0;
        } else {
            // R_D__0
            return (give_log) ? Number.NEGATIVE_INFINITY : 0.0;
        }
    }

    if (x == 0) {
        if(n == 0) return (give_log) ? 0.0 : 1.0;   //R_D__1;
        lc = (p < 0.1) ? -jstat.bd0(n,n*q) - n*p : n*Math.log(q);
        return ( give_log ) ? lc : Math.exp(lc); //R_D_exp(lc)
    }

    if (x == n) {
        lc = (q < 0.1) ? -jstat.bd0(n,n*p) - n*q : n*Math.log(p);
        return ( give_log ) ? lc : Math.exp(lc); //R_D_exp(lc)
    }

    if (x < 0 || x > n) return (give_log) ? Number.NEGATIVE_INFINITY : 0.0; // R_D__0;

    /* n*p or n*q can underflow to zero if n and p or q are small.  This
       used to occur in dbeta, and gives NaN as from R 2.3.0.  */
    lc = jstat.stirlerr(n) - jstat.stirlerr(x) - jstat.stirlerr(n-x) - jstat.bd0(x,n*p) - jstat.bd0(n-x,n*q);

    /* f = (M_2PI*x*(n-x))/n; could overflow or underflow */
    /* Upto R 2.7.1:
     * lf = log(M_2PI) + log(x) + log(n-x) - log(n);
     * -- following is much better for  x << n : */
    lf = Math.log(jstat.TWO_PI) + Math.log(x) + jstat.log1p(- x/n);

    return (give_log) ? lc - 0.5*lf : Math.exp(lc - 0.5*lf); // R_D_exp(lc - 0.5*lf);
}

jstat.max = function(values) {
    var max = Number.NEGATIVE_INFINITY;
    for(var i = 0; i < values.length; i++) {
        if(values[i] > max) {
            max = values[i];
        }
    }
    return max;
}

/******************************************************************************/
/*                      Probability Distributions                             */
/******************************************************************************/

/**
 * Range class
 */
var Range = Class.extend({
    init: function(min, max, numPoints) {
        this._minimum = parseFloat(min);
        this._maximum = parseFloat(max);
        this._numPoints = parseFloat(numPoints);
    },
    getMinimum: function() {
        return this._minimum;
    },
    getMaximum: function() {
        return this._maximum;
    },
    getNumPoints: function() {
        return this._numPoints;
    },
    getPoints: function() {
        var results = [];
        var x = this._minimum;
        var step = (this._maximum-this._minimum)/(this._numPoints-1);
        for(var i = 0; i < this._numPoints; i++) {
            results[i] = parseFloat(x.toFixed(6));
            x += step;
        }
        return results;
    }
});

Range.validate = function(range) {
    if( ! range instanceof Range) {
        return false;
    }
    if(isNaN(range.getMinimum()) || isNaN(range.getMaximum()) || isNaN(range.getNumPoints()) || range.getMaximum() < range.getMinimum() || range.getNumPoints() <= 0) {
        return false;
    }
    return true;
}

var ContinuousDistribution = Class.extend({
    init: function(name) {
        this._name = name;
    },
    toString: function() {
        return this._string;
    },
    getName: function() {
        return this._name;
    },
    getClassName: function() {
        return this._name + 'Distribution';
    },
    density: function(valueOrRange) {
        if(!isNaN(valueOrRange)) {
            // single value
            return parseFloat(this._pdf(valueOrRange).toFixed(15));
        } else if (Range.validate(valueOrRange)) {
            // multiple values
            var step = (valueOrRange.getMaximum() - valueOrRange.getMinimum()) / valueOrRange.getNumPoints();
            var x = valueOrRange.getMinimum();
            var result = [];
            // For each point in the range
            for(var i = 0; i < valueOrRange.getNumPoints(); i++) {
                result[i] = parseFloat(this._pdf(x).toFixed(15));
                x += step;
            }
            return result;
        } else {
            // neither value or range
            throw "Invalid parameter supplied to " + this.getClassName() + ".density()";
        }
    },
    cumulativeDensity: function(valueOrRange) {
        if(!isNaN(valueOrRange)) {
            // single value
            return parseFloat(this._cdf(valueOrRange).toFixed(15));
        } else if (Range.validate(valueOrRange)) {
            // multiple values
            var step = (valueOrRange.getMaximum() - valueOrRange.getMinimum()) / valueOrRange.getNumPoints();
            var x = valueOrRange.getMinimum();
            var result = [];
            // For each point in the range
            for(var i = 0; i < valueOrRange.getNumPoints(); i++) {
                result[i] = parseFloat(this._cdf(x).toFixed(15));
                x += step;
            }
            return result;
        } else {
            // neither value or range
            throw "Invalid parameter supplied to " + this.getClassName() + ".cumulativeDensity()";
        }
    },
    getRange: function(standardDeviations, numPoints) {
        if(standardDeviations == null) {
            standardDeviations = 5;
        }
        if(numPoints == null) {
            numPoints = 100;
        }
        var min = this.getMean() - standardDeviations * Math.sqrt(this.getVariance());
        var max = this.getMean() + standardDeviations * Math.sqrt(this.getVariance());

        if(this.getClassName() == 'GammaDistribution' || this.getClassName() == 'LogNormalDistribution') {
            min = 0.0;
            max = this.getMean() + standardDeviations * Math.sqrt(this.getVariance());
        } else if(this.getClassName() == 'BetaDistribution') {
            min = 0.0;
            max = 1.0;
        }


        var range = new Range(min, max, numPoints);
        return range;
    },
    getVariance: function(){},
    getMean: function(){},
    getQuantile: function(p) {
        var self = this;
        /*
         *  Recursive function to find the closest match
         */
        function findClosestMatch(range, p) {
            var ERR = 1.0e-5;
            var xs = range.getPoints();
            var closestIndex = 0;
            var closestDistance = 999;

            for(var i=0; i<xs.length; i++) {
                var pp = self.cumulativeDensity(xs[i]);
                var distance = Math.abs(pp - p);
                if(distance < closestDistance) {
                    // closer value found
                    closestIndex = i;
                    closestDistance = distance;
                }
            }
            if(closestDistance <= ERR) {
                // Acceptable - return value;
                return xs[closestIndex];
            } else {
                // Calculate the new range
                var newRange = new Range(xs[Math.max(closestIndex-1, 0)], xs[Math.min(closestIndex+1, xs.length -1)],20);
                return findClosestMatch(newRange, p);
            }
        }
        var range = this.getRange(5, 20);
        return findClosestMatch(range, p);
    }
});

/**
 * A normal distribution object
 */
var NormalDistribution = ContinuousDistribution.extend({
    init: function(mean, sigma) {
        this._super('Normal');
        this._mean = parseFloat(mean);
        this._sigma = parseFloat(sigma);
        this._string = "Normal ("+this._mean.toFixed(2)+", " + this._sigma.toFixed(2) + ")";
    },
    _pdf: function(x, give_log) {
        if(give_log == null) {
            give_log=false;
        }  // default is false;
        var sigma = this._sigma;
        var mu = this._mean;
        if(!jstat.isFinite(sigma)) {
            return (give_log) ? Number.NEGATIVE_INFINITY : 0.0
        }
        if(!jstat.isFinite(x) && mu == x) {
            return Number.NaN;
        }
        if(sigma<=0) {
            if(sigma < 0) {
                throw "invalid sigma in _pdf";
            }
            return (x==mu)?Number.POSITIVE_INFINITY:(give_log)?Number.NEGATIVE_INFINITY:0.0;
        }
        x=(x-mu)/sigma;
        if(!jstat.isFinite(x)){
            return (give_log)?Number.NEGATIVE_INFINITY:0.0;
        }
        return (give_log ? -(jstat.LN_SQRT_2PI + 0.5 * x * x + Math.log(sigma)) :
            jstat.ONE_SQRT_2PI * Math.exp(-0.5 * x * x) / sigma);
    },
    _cdf: function(x, lower_tail, log_p) {

        if(lower_tail == null) lower_tail = true;
        if(log_p == null) log_p = false;

        function pnorm_both(x, cum, ccum, i_tail, log_p) {
            /*  i_tail in {0,1,2} means: "lower", "upper", or "both" :
                if(lower) return  *cum := P[X <= x]
                if(upper) return *ccum := P[X >  x] = 1 - P[X <= x]
             */

            var a = [
            2.2352520354606839287,
            161.02823106855587881,
            1067.6894854603709582,
            18154.981253343561249,
            0.065682337918207449113
            ];
            var b = [
            47.20258190468824187,
            976.09855173777669322,
            10260.932208618978205,
            45507.789335026729956
            ];
            var c = [
            0.39894151208813466764,
            8.8831497943883759412,
            93.506656132177855979,
            597.27027639480026226,
            2494.5375852903726711,
            6848.1904505362823326,
            11602.651437647350124,
            9842.7148383839780218,
            1.0765576773720192317e-8
            ];
            var d = [
            22.266688044328115691,
            235.38790178262499861,
            1519.377599407554805,
            6485.558298266760755,
            18615.571640885098091,
            34900.952721145977266,
            38912.003286093271411,
            19685.429676859990727
            ];
            var p = [
            0.21589853405795699,
            0.1274011611602473639,
            0.022235277870649807,
            0.001421619193227893466,
            2.9112874951168792e-5,
            0.02307344176494017303
            ];
            var q = [
            1.28426009614491121,
            0.468238212480865118,
            0.0659881378689285515,
            0.00378239633202758244,
            7.29751555083966205e-5
            ];

            var xden, xnum, temp, del, eps, xsq, y, i, lower, upper;

            /* Consider changing these : */
            eps = jstat.DBL_EPSILON * 0.5;

            /* i_tail in {0,1,2} =^= {lower, upper, both} */
            lower = i_tail != 1;
            upper = i_tail != 0;

            y = Math.abs(x);

            if (y <= 0.67448975) { /* qnorm(3/4) = .6744.... -- earlier had 0.66291 */
                if (y > eps) {
                    xsq = x * x;
                    xnum = a[4] * xsq;
                    xden = xsq;
                    for (i = 0; i < 3; ++i) {
                        xnum = (xnum + a[i]) * xsq;
                        xden = (xden + b[i]) * xsq;
                    }
                } else {
                    xnum = xden = 0.0;
                }
                temp = x * (xnum + a[3]) / (xden + b[3]);
                if(lower)  cum = 0.5 + temp;
                if(upper) ccum = 0.5 - temp;
                if(log_p) {
                    if(lower)  cum = Math.log(cum);
                    if(upper) ccum = Math.log(ccum);
                }

            } else if (y <= jstat.SQRT_32) {
                /* Evaluate pnorm for 0.674.. = qnorm(3/4) < |x| <= sqrt(32) ~= 5.657 */

                xnum = c[8] * y;
                xden = y;
                for (i = 0; i < 7; ++i) {
                    xnum = (xnum + c[i]) * y;
                    xden = (xden + d[i]) * y;
                }
                temp = (xnum + c[7]) / (xden + d[7]);

                /* do_del */
                xsq = jstat.trunc(x * 16) / 16;
                del = (x - xsq) * (x + xsq);
                if(log_p) {
                    cum = (-xsq * xsq * 0.5) + (-del * 0.5) + Math.log(temp);
                    if((lower && x > 0.) || (upper && x <= 0.))
                        ccum = jstat.log1p(-Math.exp(-xsq * xsq * 0.5) *
                            Math.exp(-del * 0.5) * temp);
                }
                else {
                    cum = Math.exp(-xsq * xsq * 0.5) * Math.exp(-del * 0.5) * temp;
                    ccum = 1.0 - cum;
                }
                /* end do_del */

                /* swap_tail */
                if (x > 0.0) {/* swap  ccum <--> cum */
                    temp = cum;
                    if(lower) {
                        cum = ccum;

                    }
                    ccum = temp;
                }
            /* end swap_tail */

            }
            /* else	  |x| > sqrt(32) = 5.657 :
             * the next two case differentiations were really for lower=T, log=F
             * Particularly	 *not*	for  log_p !

             * Cody had (-37.5193 < x  &&  x < 8.2924) ; R originally had y < 50
             *
             * Note that we do want symmetry(0), lower/upper -> hence use y
             */

            else if((log_p && y < 1e170)|| (lower && -37.5193 < x  &&  x < 8.2924)
                || (upper && -8.2924  < x  &&  x < 37.5193)) {
                /* Evaluate pnorm for x in (-37.5, -5.657) union (5.657, 37.5) */
                xsq = 1.0 / (x * x); /* (1./x)*(1./x) might be better */
                xnum = p[5] * xsq;
                xden = xsq;
                for (i = 0; i < 4; ++i) {
                    xnum = (xnum + p[i]) * xsq;
                    xden = (xden + q[i]) * xsq;
                }
                temp = xsq * (xnum + p[4]) / (xden + q[4]);
                temp = (jstat.ONE_SQRT_2PI - temp) / y;

                /* do_del */
                xsq = jstat.trunc(x * 16) / 16;
                del = (x - xsq) * (x + xsq);
                if(log_p) {
                    cum = (-xsq * xsq * 0.5) + (-del * 0.5) + Math.log(temp);
                    if((lower && x > 0.) || (upper && x <= 0.))
                        ccum = jstat.log1p(-Math.exp(-xsq * xsq * 0.5) *
                            Math.exp(-del * 0.5) * temp);
                }
                else {
                    cum = Math.exp(-xsq * xsq * 0.5) * Math.exp(-del * 0.5) * temp;
                    ccum = 1.0 - cum;
                }
                /* end do_del */

                /* swap_tail */
                if (x > 0.0) {/* swap  ccum <--> cum */
                    temp = cum;
                    if(lower) {
                        cum = ccum;

                    }
                    ccum = temp;
                }
            /* end swap_tail */

            } else { /* large x such that probs are 0 or 1 */
                if(x > 0) {
                    cum = (log_p) ? 0.0 : 1.0;  // R_D__1
                    ccum = (log_p) ? Number.NEGATIVE_INFINITY : 0.0;  //R_D__0;
                } else {
                    cum = (log_p) ? Number.NEGATIVE_INFINITY : 0.0;  //R_D__0;
                    ccum = (log_p) ? 0.0 : 1.0;  // R_D__1
                }
            }

            return [cum, ccum];
        }

        var p, cp;
        var mu = this._mean;
        var sigma = this._sigma;
        var R_DT_0, R_DT_1;

        if(lower_tail) {
            if(log_p) {
                R_DT_0 = Number.NEGATIVE_INFINITY;
                R_DT_1 = 0.0;
            } else {
                R_DT_0 = 0.0;
                R_DT_1 = 1.0;
            }
        } else {
            if(log_p) {
                R_DT_0 = 0.0;
                R_DT_1 = Number.NEGATIVE_INFINITY;
            } else {
                R_DT_0 = 1.0;
                R_DT_1 = 0.0;
            }
        }

        if(!jstat.isFinite(x) && mu == x) return Number.NaN;
        if(sigma <= 0) {
            if(sigma < 0) {
                console.warn("Sigma is less than 0");
                return Number.NaN;
            }
            return (x < mu) ? R_DT_0 : R_DT_1;
        }

        p = (x - mu) / sigma;

        if(!jstat.isFinite(p)) {
            return (x < mu) ? R_DT_0 : R_DT_1;
        }

        x = p;

        // pnorm_both(x, &p, &cp, (lower_tail ? 0 : 1), log_p);
        // result[0] == &p
        // result[1] == &cp

        var result = pnorm_both(x, p, cp, (lower_tail ? false : true), log_p);

        return (lower_tail ? result[0] : result[1]);

    },
    getMean: function() {
        return this._mean;
    },
    getSigma: function() {
        return this._sigma;
    },
    getVariance: function() {
        return this._sigma*this._sigma;
    }
});

/**
 *  A Log-normal distribution object
 */
var LogNormalDistribution = ContinuousDistribution.extend({
    init: function(location, scale) {
        this._super('LogNormal')
        this._location = parseFloat(location);
        this._scale = parseFloat(scale);
        this._string = "LogNormal ("+this._location.toFixed(2)+", " + this._scale.toFixed(2) + ")";
    },
    _pdf: function(x, give_log) {
        var y;
        var sdlog = this._scale;
        var meanlog = this._location;
        if(give_log == null) {
            give_log = false;
        }

        if(sdlog <= 0) throw "Illegal parameter in _pdf";

        if(x <= 0) {
            return (give_log) ? Number.NEGATIVE_INFINITY : 0.0;
        }

        y = (Math.log(x) - meanlog) / sdlog;

        return (give_log ? -(jstat.LN_SQRT_2PI + 0.5 * y * y + Math.log(x * sdlog)) :
            jstat.ONE_SQRT_2PI * Math.exp(-0.5 * y * y) / (x * sdlog));

    },
    _cdf: function(x, lower_tail, log_p) {
        var sdlog = this._scale;
        var meanlog = this._location;
        if(lower_tail == null) {
            lower_tail = true;
        }
        if(log_p == null) {
            log_p = false;
        }


        if(sdlog <= 0) {
            throw "illegal std in _cdf";
        }

        if(x > 0) {
            var nd = new NormalDistribution(meanlog, sdlog);
            return nd._cdf(Math.log(x), lower_tail, log_p);
        }
        if(lower_tail) {
            return (log_p) ? Number.NEGATIVE_INFINITY : 0.0;    // R_D__0
        } else {
            return (log_p) ? 0.0 : 1.0;                         // R_D__1
        }
    },
    getLocation: function() {
        return this._location;
    },
    getScale: function() {
        return this._scale;
    },
    getMean: function() {
        return Math.exp((this._location + this._scale) /  2);
    },
    getVariance: function() {
        var ans = (Math.exp(this._scale)-1)*Math.exp(2*this._location+this._scale);
        return ans;
    }
});


/**
 *  Gamma distribution object
 */
var GammaDistribution = ContinuousDistribution.extend({
    init: function(shape, scale) {
        this._super('Gamma');
        this._shape = parseFloat(shape);
        this._scale = parseFloat(scale);
        this._string = "Gamma ("+this._shape.toFixed(2)+", " + this._scale.toFixed(2) + ")";
    },
    _pdf: function(x, give_log) {
        var pr;
        var shape = this._shape;
        var scale = this._scale;
        if(give_log == null) {
            give_log = false;    // default value
        }

        if(shape < 0 || scale <= 0) {
            throw "Illegal argument in _pdf";
        }

        if(x < 0) {
            return (give_log) ? Number.NEGATIVE_INFINITY : 0.0; // R_D__0
        }
        if(shape == 0) { /* point mass at 0 */
            return (x == 0) ? Number.POSITIVE_INFINITY : (give_log) ? Number.NEGATIVE_INFINITY : 0.0;   // R_D__0
        }
        if(x == 0) {
            if(shape < 1) return Number.POSITIVE_INFINITY;
            if(shape > 1) return (give_log) ? Number.NEGATIVE_INFINITY : 0.0; // R_D__0
            /* else */
            return (give_log) ? -Math.log(scale) : 1/scale;
        }

        if(shape < 1) {
            pr = jstat.dopois_raw(shape, x/scale, give_log);
            return give_log ? pr + Math.log(shape/x) : pr*shape/x;
        }
        /* else shape >= 1 */
        pr = jstat.dopois_raw(shape-1, x/scale, give_log);
        return give_log ? pr - Math.log(scale) : pr/scale;

    },
    /**
     *	This function computes the distribution function for the
     *	gamma distribution with shape parameter alph and scale parameter
     *	scale.	This is also known as the incomplete gamma function.
     *	See Abramowitz and Stegun (6.5.1) for example.
     */
    _cdf: function(x, lower_tail, log_p) {
        /* define USE_PNORM */
        function USE_PNORM() {
            pn1 = Math.sqrt(alph) * 3.0 * (Math.pow(x/alph,1.0/3.0) + 1.0 / (9.0 * alph) - 1.0);
            var norm_dist = new NormalDistribution(0.0, 1.0);
            return norm_dist._cdf(pn1, lower_tail, log_p);
        }

        /* Defaults */
        if(lower_tail == null) lower_tail = true;
        if(log_p == null) log_p = false;
        var alph = this._shape;
        var scale = this._scale;
        var xbig = 1.0e+8;
        var xlarge = 1.0e+37;
        var alphlimit = 1e5;
        var pn1,pn2,pn3,pn4,pn5,pn6,arg,a,b,c,an,osum,sum,n,pearson;

        if(alph <= 0. || scale <= 0.) {
            console.warn('Invalid gamma params in _cdf');
            return Number.NaN;
        }

        x/=scale;
        if(isNaN(x)) return x;
        if(x <= 0.0) {
            // R_DT_0
            if(lower_tail) {
                // R_D__0
                return (log_p) ? Number.NEGATIVE_INFINITY : 0.0;
            } else {
                // R_D__1
                return (log_p) ? 0.0 : 1.0;
            }
        }

        if(alph > alphlimit) {
            return USE_PNORM();
        }

        if(x > xbig * alph) {
            if(x > jstat.DBL_MAX * alph) {
                // R_DT_1
                if(lower_tail) {
                    // R_D__1
                    return (log_p) ? 0.0 : 1.0;
                } else {
                    // R_D__0
                    return (log_p) ? Number.NEGATIVE_INFINITY : 0.0;
                }
            } else {
                return USE_PNORM();
            }
        }

        if(x <= 1.0 || x < alph) {
            pearson = 1; /* use pearson's series expansion */
            arg = alph * Math.log(x) - x - jstat.lgamma(alph + 1.0);

            c = 1.0;
            sum = 1.0;
            a = alph;
            do {
                a += 1.0;
                c *= x / a;
                sum += c;
            } while(c > jstat.DBL_EPSILON * sum);
        } else { /* x >= max( 1, alph) */
            pearson = 0;/* use a continued fraction expansion */
            arg = alph * Math.log(x) - x - jstat.lgamma(alph);

            a = 1. - alph;
            b = a + x + 1.;
            pn1 = 1.;
            pn2 = x;
            pn3 = x + 1.;
            pn4 = x * b;
            sum = pn3 / pn4;

            for (n = 1; ; n++) {
                a += 1.;/* =   n+1 -alph */
                b += 2.;/* = 2(n+1)-alph+x */
                an = a * n;
                pn5 = b * pn3 - an * pn1;
                pn6 = b * pn4 - an * pn2;
                if (Math.abs(pn6) > 0.) {
                    osum = sum;
                    sum = pn5 / pn6;
                    if (Math.abs(osum - sum) <= jstat.DBL_EPSILON * jstat.fmin2(1.0, sum))
                        break;
                }
                pn1 = pn3;
                pn2 = pn4;
                pn3 = pn5;
                pn4 = pn6;

                if (Math.abs(pn5) >= xlarge) {
                    pn1 /= xlarge;
                    pn2 /= xlarge;
                    pn3 /= xlarge;
                    pn4 /= xlarge;
                }
            }
        }
        arg += Math.log(sum);
        lower_tail = (lower_tail == pearson);

        if (log_p && lower_tail)
            return(arg);
        /* else */
        /* sum = exp(arg); and return   if(lower_tail) sum	else 1-sum : */

        if(lower_tail) {
            return Math.exp(arg);
        } else {
            if(log_p) {
                // R_Log1_Exp(arg);
                return (arg > -Math.LN2)  ? Math.log(-jstat.expm1(arg)) : jstat.log1p(-Math.exp(arg));
            } else {
                return -jstat.expm1(arg);
            }
        }
    },
    getShape: function() {
        return this._shape;
    },
    getScale: function() {
        return this._scale;
    },
    getMean: function() {
        return this._shape * this._scale;
    },
    getVariance: function() {
        return this._shape*Math.pow(this._scale,2);
    }
});

/**
 *  A Beta distribution object
 */
var BetaDistribution = ContinuousDistribution.extend({
    init: function(alpha, beta) {
        this._super('Beta');
        this._alpha = parseFloat(alpha);
        this._beta = parseFloat(beta);
        this._string = "Beta ("+this._alpha.toFixed(2)+", " + this._beta.toFixed(2) + ")";
    },
    _pdf: function(x, give_log) {
        if(give_log == null) give_log = false; // default;
        var a = this._alpha;
        var b = this._beta;
        var lval;
        if(a <= 0 || b <= 0) {
            console.warn('Illegal arguments in _pdf');
            return Number.NaN;
        }
        if(x < 0 || x > 1) {
            // R_D__0
            return (give_log) ? Number.NEGATIVE_INFINITY : 0.0;
        }
        if(x == 0) {
            if(a > 1) {
                // R_D__0
                return (give_log) ? Number.NEGATIVE_INFINITY : 0.0;
            }
            if(a < 1) {
                return Number.POSITIVE_INFINITY;
            }
            /*a == 1 */ return (give_log) ? Math.log(b) : b;    // R_D_val(b)
        }
        if(x == 1) {
            if(b > 1) {
                // R_D__0
                return (give_log) ? Number.NEGATIVE_INFINITY : 0.0;
            }
            if(b < 1) {
                return Number.POSITIVE_INFINITY;
            }
            /* b == 1 */ return (give_log) ? Math.log(a) : a;   // R_D_val(a)
        }
        if(a<=2||b<=2) {
            lval = (a-1)*Math.log(x) + (b-1)*jstat.log1p(-x) - jstat.logBeta(a, b);
        } else {
            lval = Math.log(a+b-1) + jstat.dbinom_raw(a-1, a+b-2, x, 1-x, true);
        }
        //R_D_exp(lval)
        return (give_log) ? lval : Math.exp(lval);
    },
    _cdf: function(x, lower_tail, log_p) {
        if(lower_tail == null) lower_tail = true;
        if(log_p == null) log_p = false;

        var pin = this._alpha;
        var qin = this._beta;

        if(pin <= 0 || qin <= 0) {
            console.warn('Invalid argument in _cdf');
            return Number.NaN;
        }

        if(x <= 0) {
            //R_DT_0;
            if(lower_tail) {
                // R_D__0
                return (log_p) ? Number.NEGATIVE_INFINITY : 0.0;
            } else {
                // R_D__1
                return (log_p) ? 0.1 : 1.0;
            }
        }

        if(x >= 1){
            // R_DT_1
            if(lower_tail) {
                // R_D__1
                return (log_p) ? 0.1 : 1.0;
            } else {
                // R_D__0
                return (log_p) ? Number.NEGATIVE_INFINITY : 0.0;
            }
        }

        /* else */
        return jstat.incompleteBeta(pin, qin, x);
    },
    getAlpha: function() {
        return this._alpha;
    },
    getBeta: function() {
        return this._beta;
    },
    getMean: function() {
        return this._alpha / (this._alpha+ this._beta);
    },
    getVariance: function() {
        var ans = (this._alpha * this._beta) / (Math.pow(this._alpha+this._beta,2)*(this._alpha+this._beta+1));
        return ans;
    }
});

var StudentTDistribution = ContinuousDistribution.extend({
    init: function(degreesOfFreedom, mu) {
        this._super('StudentT');
        this._dof = parseFloat(degreesOfFreedom);

        if(mu != null) {
            this._mu = parseFloat(mu);
            this._string = "StudentT ("+this._dof.toFixed(2)+", " + this._mu.toFixed(2)+ ")";
        } else {
            this._mu = 0.0;
            this._string = "StudentT ("+this._dof.toFixed(2)+")";
        }

    },
    _pdf: function(x, give_log) {
        if(give_log == null) give_log = false;
        if(this._mu == null) {
            return this._dt(x, give_log);
        } else {
            var y = this._dnt(x, give_log);
            if(y > 1){
                console.warn('x:' + x + ', y: ' + y);
            }
            return y;
        }
    },
    _cdf: function(x, lower_tail, give_log) {
        if(lower_tail == null) lower_tail = true;
        if(give_log == null) give_log = false;
        if(this._mu == null) {
            return this._pt(x, lower_tail, give_log);
        } else {
            return this._pnt(x, lower_tail, give_log);
        }
    },
    _dt: function(x, give_log) {
        var t,u;
        var n = this._dof;
        if (n <= 0){
            console.warn('Invalid parameters in _dt');
            return Number.NaN;
        }
        if(!jstat.isFinite(x)) {
            return (give_log) ? Number.NEGATIVE_INFINITY : 0.0; // R_D__0;
        }

        if(!jstat.isFinite(n)) {
            var norm = new NormalDistribution(0.0, 1.0);
            return norm.density(x, give_log);
        }


        t = -jstat.bd0(n/2.0,(n+1)/2.0) + jstat.stirlerr((n+1)/2.0) - jstat.stirlerr(n/2.0);
        if ( x*x > 0.2*n )
            u = Math.log( 1+ x*x/n ) * n/2;
        else
            u = -jstat.bd0(n/2.0,(n+x*x)/2.0) + x*x/2.0;

        var p1 = jstat.TWO_PI *(1+x*x/n);
        var p2 = t-u;

        return (give_log) ? -0.5*Math.log(p1) + p2 : Math.exp(p2)/Math.sqrt(p1);       // R_D_fexp(M_2PI*(1+x*x/n), t-u);
    },
    _dnt: function(x, give_log) {
        if(give_log == null) give_log = false;
        var df = this._dof;
        var ncp = this._mu;
        var u;

        if(df <= 0.0) {
            console.warn("Illegal arguments _dnf");
            return Number.NaN;
        }
        if(ncp == 0.0) {
            return this._dt(x, give_log);
        }

        if(!jstat.isFinite(x)) {
            // R_D__0
            if(give_log) {
                return Number.NEGATIVE_INFINITY;
            } else {
                return 0.0;
            }
        }

        /* If infinite df then the density is identical to a
           normal distribution with mean = ncp.  However, the formula
           loses a lot of accuracy around df=1e9
         */
        if(!isFinite(df) || df > 1e8) {
            var dist = new NormalDistribution(ncp, 1.);
            return dist.density(x, give_log);
        }

        /* Do calculations on log scale to stabilize */

        /* Consider two cases: x ~= 0 or not */
        if (Math.abs(x) > Math.sqrt(df * jstat.DBL_EPSILON)) {
            var newT = new StudentTDistribution(df+2, ncp);
            u = Math.log(df) - Math.log(Math.abs(x)) +
            Math.log(Math.abs(newT._pnt(x*Math.sqrt((df+2)/df), true, false) -
                this._pnt(x, true, false)));
        /* FIXME: the above still suffers from cancellation (but not horribly) */
        }
        else {  /* x ~= 0 : -> same value as for  x = 0 */
            u = jstat.lgamma((df+1)/2) - jstat.lgamma(df/2)
            - .5*(Math.log(Math.PI) + Math.log(df) + ncp*ncp);
        }

        return (give_log ? u : Math.exp(u));
    },
    _pt: function(x, lower_tail, log_p) {
        if(lower_tail == null) lower_tail = true;
        if(log_p == null) log_p = false;
        var val, nx;
        var n = this._dof;
        var DT_0, DT_1;

        if(lower_tail) {
            if(log_p) {
                DT_0 = Number.NEGATIVE_INFINITY;
                DT_1 = 1.;
            } else {
                DT_0 = 0.;
                DT_1 = 1.;
            }
        } else {
            if(log_p) {
                // not lower_tail but log_p
                DT_0 = 0.;
                DT_1 = Number.NEGATIVE_INFINITY;
            } else {
                // not lower_tail and not log_p
                DT_0 = 1.;
                DT_1 = 0.;
            }
        }

        if(n <= 0.0) {
            console.warn("Invalid T distribution _pt");
            return Number.NaN;
        }
        var norm = new NormalDistribution(0,1);
        if(!jstat.isFinite(x)) {
            return (x < 0) ? DT_0 : DT_1;
        }
        if(!jstat.isFinite(n)) {
            return norm._cdf(x, lower_tail, log_p);
        }

        if (n > 4e5) { /*-- Fixme(?): test should depend on `n' AND `x' ! */
            /* Approx. from	 Abramowitz & Stegun 26.7.8 (p.949) */
            val = 1./(4.*n);
            return norm._cdf(x*(1. - val)/sqrt(1. + x*x*2.*val), lower_tail, log_p);
        }

        nx = 1 + (x/n)*x;
        /* FIXME: This test is probably losing rather than gaining precision,
         * now that pbeta(*, log_p = TRUE) is much better.
         * Note however that a version of this test *is* needed for x*x > D_MAX */
        if(nx > 1e100) { /* <==>  x*x > 1e100 * n  */
            /* Danger of underflow. So use Abramowitz & Stegun 26.5.4
	   pbeta(z, a, b) ~ z^a(1-z)^b / aB(a,b) ~ z^a / aB(a,b),
	   with z = 1/nx,  a = n/2,  b= 1/2 :
             */
            var lval;
            lval = -0.5*n*(2*Math.log(Math.abs(x)) - Math.log(n))
            - jstat.logBeta(0.5*n, 0.5) - Math.log(0.5*n);
            val = log_p ? lval : Math.exp(lval);
        } else {
            /*
            val = (n > x * x)
            //    ? pbeta (x * x / (n + x * x), 0.5, n / 2., 0, log_p)
           // : pbeta (1. / nx,             n / 2., 0.5, 1, log_p);
             */
            if(n > x * x) {
                var beta = new BetaDistribution(0.5, n/2.);
                return beta._cdf(x*x/ (n + x * x), false, log_p);
            } else {
                beta = new BetaDistribution(n / 2., 0.5);
                return beta._cdf(1. / nx, true, log_p);
            }


        }

        /* Use "1 - v"  if	lower_tail  and	 x > 0 (but not both):*/
        if(x <= 0.)
            lower_tail = !lower_tail;

        if(log_p) {
            if(lower_tail) return jstat.log1p(-0.5*Math.exp(val));
            else return val - M_LN2; /* = log(.5* pbeta(....)) */
        }
        else {
            val /= 2.;
            if(lower_tail) {
                return (0.5 - val + 0.5);
            } else {
                return val;
            }
        }
    },
    _pnt: function(t, lower_tail, log_p) {

        var dof = this._dof;
        var ncp = this._mu;
        var DT_0, DT_1;

        if(lower_tail) {
            if(log_p) {
                DT_0 = Number.NEGATIVE_INFINITY;
                DT_1 = 1.;
            } else {
                DT_0 = 0.;
                DT_1 = 1.;
            }
        } else {
            if(log_p) {
                // not lower_tail but log_p
                DT_0 = 0.;
                DT_1 = Number.NEGATIVE_INFINITY;
            } else {
                // not lower_tail and not log_p
                DT_0 = 1.;
                DT_1 = 0.;
            }
        }

        var albeta, a, b, del, errbd, lambda, rxb, tt, x;
        var geven, godd, p, q, s, tnc, xeven, xodd;
        var it, negdel;

        /* note - itrmax and errmax may be changed to suit one's needs. */
        var ITRMAX = 1000;
        var ERRMAX = 1.e-7;

        if(dof <= 0.0) {
            return Number.NaN;
        } else if (dof == 0.0) {
            return this._pt(t);
        }

        if(!jstat.isFinite(t)) {
            return (t < 0) ? DT_0 : DT_1;
        }
        if(t >= 0.) {
            negdel = false;
            tt = t;
            del = ncp;
        } else {
            /* We deal quickly with left tail if extreme,
	   since pt(q, df, ncp) <= pt(0, df, ncp) = \Phi(-ncp) */
            if(ncp >= 40 && (!log_p || !lower_tail)) {
                return DT_0;
            }
            negdel = true;
            tt = -t;
            del = -ncp;
        }

        if(dof > 4e5 || del*del > 2* Math.LN2 * (-(jstat.DBL_MIN_EXP))) {
            /*-- 2nd part: if del > 37.62, then p=0 below
	    FIXME: test should depend on `df', `tt' AND `del' ! */
            /* Approx. from	 Abramowitz & Stegun 26.7.10 (p.949) */
            s=1./(4.*dof);
            var norm = new NormalDistribution(del, Math.sqrt(1. + tt*tt*2.*s));
            var result = norm._cdf(tt*(1.-s), lower_tail != negdel, log_p);
            return result;
        }

        /* initialize twin series */
        /* Guenther, J. (1978). Statist. Computn. Simuln. vol.6, 199. */
        x = t * t;
        rxb = dof/(x + dof);/* := (1 - x) {x below} -- but more accurately */
        x = x / (x + dof);/* in [0,1) */
        if (x > 0.) {/* <==>  t != 0 */
            lambda = del * del;
            p = .5 * Math.exp(-.5 * lambda);
            if(p == 0.) {   // underflow!
                console.warn("underflow in _pnt");
                return DT_0;
            }
            q = jstat.SQRT_2dPI * p * del;
            s = .5 - p;
            if(s < 1e-7) {
                s = -0.5 * jstat.expm1(-0.5 * lambda);
            }
            a = .5;
            b = .5 * dof;
            /* rxb = (1 - x) ^ b   [ ~= 1 - b*x for tiny x --> see 'xeven' below]
             * where '(1 - x)' =: rxb {accurately!} above */
            rxb = Math.pow(rxb, b);
            albeta = jstat.LN_SQRT_PI + jstat.lgamma(b) - jstat.lgamma(.5 + b);
            /* TODO: change incompleteBeta function to accept lower_tail and p_log */
            xodd = jstat.incompleteBeta(a, b, x);
            godd = 2. * rxb * Math.exp(a * Math.log(x) - albeta);
            tnc = b * x;
            xeven = (tnc < jstat.DBL_EPSILON) ? tnc : 1. - rxb;
            geven = tnc * rxb;
            tnc = p * xodd + q * xeven;

            /* repeat until convergence or iteration limit */
            for(it = 1; it <= ITRMAX; it++) {
                a += 1.;
                xodd  -= godd;
                xeven -= geven;
                godd  *= x * (a + b - 1.) / a;
                geven *= x * (a + b - .5) / (a + .5);
                p *= lambda / (2 * it);
                q *= lambda / (2 * it + 1);
                tnc += p * xodd + q * xeven;
                s -= p;
                /* R 2.4.0 added test for rounding error here. */
                if(s < -1.e-10) { /* happens e.g. for (t,df,ncp)=(40,10,38.5), after 799 it.*/
                    console.write("precision error _pnt");
                    break;
                }
                if(s <= 0 && it > 1) break;

                errbd = 2. * s * (xodd - godd);

                if(Math.abs(errbd) < ERRMAX) break;/*convergence*/
            }

            if(it == ITRMAX) {
                throw "Non-convergence _pnt";
            }
        } else {
            tnc = 0.;
        }
        norm = new NormalDistribution(0,1);
        tnc += norm._cdf(-del, /*lower*/true, /*log_p*/ false);

        lower_tail = lower_tail != negdel; /* xor */
        if(tnc > 1 - 1e-10 && lower_tail) {
            console.warn("precision error _pnt");
        }
        var res = jstat.fmin2(tnc, 1.);
        if(lower_tail) {
            if(log_p) {
                return Math.log(res);
            } else {
                return res;
            }
        } else {
            if(log_p) {
                return jstat.log1p(-(res));
            } else {
                return (0.5 - (res) + 0.5);
            }
        }
    },
    getDegreesOfFreedom: function() {
        return this._dof;
    },
    getNonCentralityParameter: function() {
        return this._mu;
    },
    getMean: function() {
        if(this._dof > 1) {
            var ans = (1/2)*Math.log(this._dof/2) + jstat.lgamma((this._dof-1)/2) - jstat.lgamma(this._dof/2)
            return Math.exp(ans) * this._mu;
        } else {
            return Number.NaN;
        }
    },
    getVariance: function() {
        if(this._dof > 2) {
            var ans = this._dof * (1 + this._mu*this._mu)/(this._dof-2) - (((this._mu*this._mu * this._dof) / 2) * Math.pow(Math.exp(jstat.lgamma((this._dof - 1)/2)-jstat.lgamma(this._dof/2)), 2));
            return ans;
        } else {
            return Number.NaN;
        }
    }
});


/******************************************************************************/
/*                      jQuery Flot graph objects                             */
/******************************************************************************/

var Plot = Class.extend({
    init: function(id, options) {
        this._container = '#' + String(id);
        this._plots = [];
        this._flotObj = null;
        this._locked = false;
        if(options != null) {
            this._options = options;
        } else {
            this._options = {};
        }
    },
    getContainer: function() {
        return this._container;
    },
    getGraph: function() {
        return this._flotObj;
    },
    setData: function(data) {
        this._plots = data;
    },
    clear: function() {
        this._plots = [];
    //this.render();
    },
    showLegend: function() {
        this._options.legend = {
            show: true
        }
        this.render();
    },
    hideLegend: function() {
        this._options.legend = {
            show: false
        }
        this.render();
    },
    render: function() {
        this._flotObj = null;
        this._flotObj = $.plot($(this._container), this._plots, this._options);
    }
});

var DistributionPlot = Plot.extend({
    init: function(id, distribution, range, options, maxY) {
        this._super(id, options);
        this._showPDF = true;
        this._showCDF = false;
        this._pdfValues = [];     // raw values for pdf
        this._cdfValues = [];     // raw values for cdf
        this._maxY = 1;
        this._distribution = distribution;    // underlying PDF
        // Range object for the plot

        if(range != null && Range.validate(range)) {
            this._range = range;
        } else {
            this._range = this._distribution.getRange(); // no range supplied, use distribution default
        }

        // render
        if(this._distribution != null) {
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
				if( ! isFinite(maxY)) {
					// maxY is infinity, assign to something sensible
					maxY = this._maxY; // should be the generated values.
				}
				
        if(maxY != null) {
            this._maxY = maxY;
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
        var pdfs = this._distribution.density(this._range);
        var cdfs = this._distribution.cumulativeDensity(this._range);
        for(var i = 0; i < xs.length; i++) {
            if(pdfs[i] == Number.POSITIVE_INFINITY || pdfs[i] == Number.NEGATIVE_INFINITY) {
                pdfs[i] = null;
            }
            if(cdfs[i] == Number.POSITIVE_INFINITY || cdfs[i] == Number.NEGATIVE_INFINITY) {
                cdfs[i] = null;
            }
            this._pdfValues.push([xs[i], pdfs[i]]);
            this._cdfValues.push([xs[i], cdfs[i]]);
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
        if(this._distribution != null) {
            if(this._showPDF && this._showCDF) {
                this.setData([{
                    yaxis: 1,
                    data:this._pdfValues,
                    color: 'rgb(237,194,64)',
                    clickable: false,
                    hoverable: true,
                    label: "PDF"
                }, {
                    yaxis: 2,
                    data:this._cdfValues,
                    clickable: false,
                    color: 'rgb(175,216,248)',
                    hoverable: true,
                    label: "CDF"
                }]);
                this._options.yaxis = {
                    max: this._maxY*1.1
                }
            } else if(this._showPDF) {
                this.setData([{
                    data:this._pdfValues,
                    hoverable: true,
                    color: 'rgb(237,194,64)',
                    clickable: false,
                    label: "PDF"
                }]);
                this._options.yaxis = {
                    max: this._maxY*1.1
                }
            } else if(this._showCDF) {
                this.setData([{
                    data:this._cdfValues,
                    hoverable: true,
                    color: 'rgb(175,216,248)',
                    clickable: false,
                    label: "CDF"
                }]);
                this._options.yaxis = {
                    max: 1.1
                }
            }
        } else {
            this.setData([]);
        }
        this._super();  // Call the parent plot method
    }
});

var DistributionFactory = {};
DistributionFactory.build = function(json) {
    /*
    if(json.name == null) {
        try{
            json = JSON.parse(json);
        }
        catch(err) {
            throw "invalid JSON";
        }

    // try to parse it
    }*/

    /*
    if(json.name != null) {
        var name = json.name;
    } else {
        throw "Malformed JSON provided to DistributionBuilder " + json;
    }
     */
    if(json.NormalDistribution) {
        if(json.NormalDistribution.mean != null && json.NormalDistribution.standardDeviation != null) {
            return new NormalDistribution(json.NormalDistribution.mean[0], json.NormalDistribution.standardDeviation[0]);
        } else {
            throw "Malformed JSON provided to DistributionBuilder " + JSON.stringify(json);
        }
    } else if (json.LogNormalDistribution) {
        if(json.LogNormalDistribution.location != null && json.LogNormalDistribution.scale != null) {
            return new LogNormalDistribution(json.LogNormalDistribution.location[0], json.LogNormalDistribution.scale[0]);
        } else {
            throw "Malformed JSON provided to DistributionBuilder " + JSON.stringify(json);
        }
    } else if (json.BetaDistribution) {
        if(json.BetaDistribution.alpha != null && json.BetaDistribution.beta != null) {
            return new BetaDistribution(json.BetaDistribution.alpha[0], json.BetaDistribution.beta[0]);
        } else {
            throw "Malformed JSON provided to DistributionBuilder " + JSON.stringify(json);
        }
    } else if (json.GammaDistribution) {
        if(json.GammaDistribution.shape != null && json.GammaDistribution.scale != null) {
            return new GammaDistribution(json.GammaDistribution.shape[0], json.GammaDistribution.scale[0]);
        } else {
            throw "Malformed JSON provided to DistributionBuilder " + JSON.stringify(json);
        }
    } else if (json.StudentTDistribution) {
        if(json.StudentTDistribution.degreesOfFreedom != null && json.StudentTDistribution.nonCentralityParameter != null) {
            return new StudentTDistribution(json.StudentTDistribution.degreesOfFreedom[0], json.StudentTDistribution.nonCentralityParameter[0]);
        } else if(json.StudentTDistribution.degreesOfFreedom != null) {
            return new StudentTDistribution(json.StudentTDistribution.degreesOfFreedom[0]);
        } else {
            throw "Malformed JSON provided to DistributionBuilder " + JSON.stringify(json);
        }
    } else {
        throw "Malformed JSON provided to DistributionBuilder " + JSON.stringify(json);
    }
}


