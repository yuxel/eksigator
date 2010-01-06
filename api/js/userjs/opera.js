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

//replace these with your username and API key
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

