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

var Eksigator = {};
(function(Eksigator){

    Eksigator.elements = {};
    Eksigator.elements.username = undefined;
    Eksigator.elements.apiKey = undefined;
    Eksigator.elements.enabled = 0;

    Eksigator.script = undefined;

    Eksigator.includeJs = function(file) { 
        var apiURL = "http://api.eksigator.com/";
        var scriptBase = apiURL + 'js/';
        var script  = document.createElement('script');
        //add dayOfMonth to disable caching
        var dayOfMonth = new Date().getMinutes();
        //script.src  = scriptBase + file + ".js?"+dayOfMonth;
        script.src  = scriptBase + file + ".js";

        script.type = 'text/javascript';

        if(!Eksigator.script) {
            document.getElementsByTagName('head').item(0).appendChild(script);
            Eksigator.script = script;
        }
    };


    var createHiddenInput = function(elementId, elementToAppend){
        var inputElement = document.createElement("input");
        inputElement.id = elementId;
        inputElement.type = "hidden";

        elementToAppend.appendChild(inputElement);

        return inputElement;
    };


    Eksigator.createHiddenElements = function(){
        var panel = document.getElementById('panel');
        Eksigator.elements.username = createHiddenInput("eksigatorUsername", panel);
        Eksigator.elements.apiKey = createHiddenInput("eksigatorUserApiKey", panel);
        Eksigator.elements.enabled = createHiddenInput("eksigatorEnabled", panel);
    };

})(Eksigator);

(function (Eksigator) {
	window.addEventListener('DOMContentLoaded', run, false);

    function run() {

        Eksigator.createHiddenElements();
        //Eksigator.elements.username.value = "demo";
        //Eksigator.elements.apiKey.value = "demo";
        Eksigator.elements.enabled.value = "0";


        setInterval( function(){
            var selector = "#eksigator_panel .eksigator_panel_button span";
            var span= document.querySelectorAll(selector)[0];
            var spanText = span.innerHTML.replace("(","");
            var unreadCount = parseInt(spanText, 10) || 0;
            //alert(unreadCount);
        }, 30000);

        opera.extension.postMessage("getUsernameAndApiKey");
        opera.extension.onmessage = function(e){
            opera.postError(e.data);
            var messageSplit = e.data.split("=");
            if( e.data.indexOf("usernameAndApiKey=")>-1 ){
                var usernameAndApiKey = messageSplit[1].split("|");
                var username = usernameAndApiKey[0];
                var apiKey = usernameAndApiKey[1];
                var enabled = usernameAndApiKey[2];

                Eksigator.elements.username.value = username;
                Eksigator.elements.apiKey.value = apiKey;
                Eksigator.elements.enabled.value = enabled;

                Eksigator.includeJs("operaExtension");
            }
        };
    };

})(Eksigator);
