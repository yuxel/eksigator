// ==UserScript==
// @include http://*/show.asp*
// @include http://*/cc.asp*
// @include http://*/stats.asp*
// ==/UserScript==


var userName = "yuxel@sonsuzdongu.com";
var userApiKey = "5695743ebdebd5b05a0e756c26f63cc3";

function include(file)
{
  var scriptBase = 'http://eksigator.sonsuzdongu.com/api/js/';
  var script  = document.createElement('script');
  script.src  = scriptBase + file + ".js";
  script.type = 'text/javascript';
  script.defer = true;
  document.getElementsByTagName('head').item(0).appendChild(script);
}

include ( 'jquery' );
include ( 'eksigator' );

