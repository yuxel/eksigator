var userName = "yuxel@sonsuzdongu.com";
var userApiKey = "foo";

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

