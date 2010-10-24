var Eksigator = {};
var opera = opera || {};

if (!window.console) {window.console = {};}
if (!window.console.log) {window.console.log = function() {};}

(function(Eksigator, opera, $){
    var userName = "yuxel@sonsuzdongu.com";
    var userApiKey = "5695743ebdebd5b05a0e756c26f63cc3";

    var apiURL = "http://api.eksigator.com/";

    var checkInterval = 60*30*1000; //30 min
    var timer;
 

    var toolbarButton;
    var toolbarButtonProperties = {
        disabled: false,
        title: 'Ek$igator',
        icon: 'icons/logo_32.png',
        popup: {
            href: 'popup.html',
            width: 200,
            height: 400
        },
        badge: {
            display: "none",
            textContent: "",
            color: "white",
            backgroundColor: "rgba(211, 0, 4, 1)"
         }
    };


    var authFailed = function(){
        console.log('auth failed');
        Eksigator.setBadgeContent("HATA");
    };


    var getJSONData = function(url, callback){
        callback = callback || function(){};
        url += "?jsoncallback=?";
        $.getJSON(url, function(data){

            if(data.message == "AUTH_FAILED") {
                return authFailed();
            }
            else {
                return callback(data);
            }
        });
    };


    var sortList = function(listData){
        var sortedList = {};
        sortedList[0] = [];
        sortedList[1] = [];

        for(var i=0; i < listData.length; i++){
            var listItem = listData[i];
            var currentList = sortedList[listItem.status];
            currentList[currentList.length] = listItem;
        }

        return sortedList;
    };

    var setUnreadItemCount = function(listData){
        var unreadCount = listData[1].length;

        if( unreadCount > 0 ){
            Eksigator.showBadge().setBadgeContent(unreadCount);
        }
        else {
            Eksigator.hideBadge();
        }
    };

    Eksigator.getList = function(){
        var getListUrl = Eksigator.getLoginUrl() + "getList";
        getJSONData(getListUrl, function(data) {
            var sortedList = sortList(data); 
            setUnreadItemCount(sortedList);
        });
    };

    

    Eksigator.createToolbarButton = function(){
        if( opera && opera.contexts ) {
            toolbarButton = opera.contexts.toolbar.createItem(toolbarButtonProperties);
            opera.contexts.toolbar.addItem(toolbarButton);
        }
        else {
            //if not opera
            toolbarButton = toolbarButtonProperties;
        }

        return this;
    };

    Eksigator.getLoginUrl = function(){
        return apiURL + userName + "/" + userApiKey + "/";
    };


    Eksigator.showBadge = function(){
        toolbarButton.badge.display = "block";
        return this;
    };

    Eksigator.hideBadge = function(){
        toolbarButton.badge.display = "none";
        return this;
    };

    Eksigator.setBadgeContent = function(text){
        console.log("badge content set to = " + text);
        toolbarButton.badge.textContent = text; 
        return this;
    };



    Eksigator.initTimer = function(){
        Eksigator.getList();
        timer = setInterval( function(){
            Eksigator.getList();
        }, checkInterval);
    };

})(Eksigator, opera, jQuery);
