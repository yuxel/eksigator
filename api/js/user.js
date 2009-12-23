/**
 * user javascript
 *
 * replace these strings below
 */
var userName = "replaceThisWithUsername";
var userApiKey = "replaceThisWithApiKey";

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

