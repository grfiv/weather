/** @file
 *
 * Financial Calculations
 *
 * FV, PV, PVGAD, NPMT, NPER
 *
 */

/**
 * Future Value
 *
 * @param PV {float} the current amount of the portfolio
 * @param i {number} the percent rate of inflation (example: 3 represents 3%)
 * @param t {float} the number of periods
 *
 * @returns {number} the Future Value of PV adjusted for i over t periods
 *
 * citation: https://en.wikipedia.org/wiki/Future_value
 *
 */
function FV(PV, i, t) {
    return PV * Math.pow((1 + (i / 100)), t);
}

/**
 * Present Value
 *
 * @param FV {float} the amount in the future to be discounted
 * @param i {number} the percent discount rate (example: 3 means 3%)
 * @param t {float} the number of periods to discount
 *
 * @returns {number} the PV of FV discounted at i for t periods
 *
 * citation: https://en.wikipedia.org/wiki/Present_value
 */
function PV(FV, i, t) {
    return FV / Math.pow((1 + (i / 100)), t);
}

/**
 * Present Value of a Growing Annuity Due
 *
 * @param P {float} The first-period's payment
 * @param r {number} percent investment return (example: 5 represents 5%)
 * @param g {number} percent rate at which P grows (e.g. inflation; example: 3 represents 3%)
 * @param n {float} number of periods
 *
 * @returns {number} the size of the investment portfolio required to support payment P
 *                   with an investment return of r and a rate of inflation of g
 *
 * citation: http://www.double-entry-bookkeeping.com/present-value/present-value-of-a-growing-annuity-due-formula/
 *
 */
function PVGAD(P, r, g, n) {
    var _r = r / 100;
    var _g = g / 100;
    if (_r < _g) {
        return 0;
    }
    return (P * (1 + _r) * (1 - Math.pow((1 + _g), n) * Math.pow((1 + _r), -n) ) / (_r - _g));
}


/**
 * Number Conversion
 *
 * @param expr
 * @param decplaces
 * @returns {string}
 */
function conv_number(expr, decplaces) { // This function is from David Goodman's Javascript Bible.
    var str = "" + Math.round(eval(expr) * Math.pow(10, decplaces));

    while (str.length <= decplaces) {
        str = "0" + str;
    }

    var decpoint = str.length - decplaces;
    return (str.substring(0, decpoint) + "." + str.substring(decpoint, str.length));
}
/**
 * PMT the payments made each period
 *
 * @param rate {number} percent investment return as a percent (5 == 5%)
 * @param per {number} number of periods per year
 * @param nper {number} number of total periods
 * @param pv {number} NEGATIVE value of current portfolio
 * @param fv {number} desired FV of portfolio
 *
 * @returns {*}
 *
 * Citation: http://www.mohaniyer.com/old/colorjs.htm
 */
function PMT (rate, per, nper, pv, fv)
{
    fv = parseFloat(fv);
    nper = parseFloat(nper);
    pv = parseFloat(pv);
    per = parseFloat(per);

    if (( per == 0 ) || ( nper == 0 )){
        return(0);
    }

    rate = eval((rate)/(per * 100));

    if ( rate == 0 ) // Interest rate is 0
    {
        pmt_value = - (fv + pv)/nper;
    }
    else
    {
        var x = Math.pow(1 + rate, nper);
        pmt_value = -((rate * (fv + x * pv))/(-1 + x));
    }

    pmt_value = conv_number(pmt_value,2);
    return (pmt_value);
}


/**
 * NPER number of payments
 *
 * @param rate
 * @param per
 * @param pmt
 * @param pv
 * @param fv
 * @returns {*}
 */
function nper(rate, per, pmt, pv, fv)
{
    fv = parseFloat(fv);
    pmt = parseFloat(pmt);
    pv = parseFloat(pv);
    per = parseFloat(per);

    if (( per == 0 ) || ( pmt == 0 )){
        //alert("Why do you want to test me with zeros?");
        return(0);
    }

    rate = eval((rate)/(per * 100));

    if ( rate == 0 ) // Interest rate is 0
    {
        nper_value = - (fv + pv)/pmt;
    }
    else
    {
        nper_value = Math.log((-fv * rate + pmt)/(pmt + rate * pv))/ Math.log(1 + rate);
    }

    nper_value = conv_number(nper_value,2);
    return (nper_value);
}


