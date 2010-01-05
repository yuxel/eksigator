// ==UserScript==
// @include http://*/show.asp*
// @include http://*/cc.asp*
// @include http://*/stats.asp*
// ==/UserScript==

var userName = "demo";
var userApiKey = "demo";
var apiURL = "http://eksigator.com/api/";

/**
 * include javascript file
 */
function include(file)
{
    var scriptBase = apiURL + 'js/';
    var script  = document.createElement('script');
    //add dayOfMonth to disable caching
    dayOfMonth = new Date().getDate();
    script.src  = scriptBase + file + ".js?"+dayOfMonth;

    script.type = 'text/javascript';
    script.defer = true;

    document.getElementsByTagName('head').item(0).appendChild(script);
}

include ( 'loader' );

