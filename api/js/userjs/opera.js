// ==UserScript==
// @include http://*/show.asp*
// @include http://*/cc.asp*
// @include http://*/stats.asp*
// ==/UserScript==

var userName = "yuxel@sonsuzdongu.com";
var userApiKey = "5695743ebdebd5b05a0e756c26f63cc3";
var apiURL = "http://eksigator.sonsuzdongu.com/api/";

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



