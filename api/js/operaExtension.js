// ==UserScript==
// @include http://sozluk.sourtimes.org/show.asp*
// @include http://sozluk.sourtimes.org/cc.asp*
// @include http://sozluk.sourtimes.org/stats.asp*
// @include http://sourtimes.org/show.asp*
// @include http://sourtimes.org/cc.asp*
// @include http://sourtimes.org/stats.asp*
// @include http://eksisozluk.com/show.asp*
// @include http://eksisozluk.com/cc.asp*
// @include http://eksisozluk.com/stats.asp*
// @include http://www.eksisozluk.com/show.asp*
// @include http://www.eksisozluk.com/cc.asp*
// @include http://www.eksisozluk.com/stats.asp*
// @include http://188.132.200.200/show.asp*
// @include http://188.132.200.200/cc.asp*
// @include http://188.132.200.200/stats.asp*
// ==/UserScript==

/**
 * Get values from DOM
 */
var getElementValue = function(elementId){
    var element = document.getElementById(elementId);
    var value = undefined;
    if( element ) {
        value = element.value;
    }

    //0 and null are "false"
    if( value == "null") {
        value = false;
    }
    if( value == "0"){
        value = false;
    }

    return value;
}

//get values from DOM
var enabled = getElementValue('eksigatorEnabled');
var userName = getElementValue('eksigatorUsername');
var userApiKey = getElementValue('eksigatorUserApiKey');


var apiURL = "http://api.eksigator.com/";

/**
 * include javascript file
 */
function include(file)
{
    var scriptBase = apiURL + 'js/';
    var script  = document.createElement('script');
    //add dayOfMonth to disable caching
    var dayOfMonth = new Date().getDate();
    script.src  = scriptBase + file + ".js?"+dayOfMonth;

    script.type = 'text/javascript';
    script.defer = true;

    document.getElementsByTagName('head').item(0).appendChild(script);
}

//if enabled load userjs
if(enabled && userName && userApiKey) {
    include ( 'loader' );
}
