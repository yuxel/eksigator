var Eksigator = {};
var opera = opera || {};

if (!window.console) {window.console = {};}
if (!window.console.log) {window.console.log = function() {};}

(function(Eksigator, opera, $){
    Eksigator.sortedList = undefined;

    var userName = "yuxel@sonsuzdongu.com";
    var userApiKey = "5695743ebdebd5b05a0e756c26f63cc3";

    var apiURL = "http://api.eksigator.com/";

    var checkInterval = 60*30*1000; //30 min
    var timer;
    var defaultLogo = "icons/logo_32.png";
 

    var toolbarButton;
    var toolbarButtonProperties = {
        disabled: false,
        title: 'Ek$igator',
        icon: defaultLogo,
        popup: {
            href: 'popup.html',
            width: 200, /* 200 */
            height: 200 /* 400 */
        },
        badge: {
            display: "none",
            textContent: "",
            color: "white",
            backgroundColor: "rgba(211, 0, 4, 1)"
         }
    };


    var loading = {
        loadingTimer : undefined,
        current : 0,
        start: function(){
            if(this.current > 0) {
                return false;
            }
            clearInterval(this.loadingTimer);
            this.loadingTimer = setInterval( function(){
                var currentImage = (loading.current % 4);
                currentImage = "icons/loading/"+currentImage+".png";
                toolbarButton.icon = currentImage;
                loading.current++;
                Eksigator.hideBadge();
            }, 250);
        },
        stop: function(){
            toolbarButton.icon = defaultLogo;
            Eksigator.showBadge();
            clearInterval(this.loadingTimer);
            this.current = 0;
        }
    };

    var authFailed = function(){
        Eksigator.setBadgeContent("HATA");
        clearInterval(timer);
    };


    var getJSONData = function(url, callback){
        loading.start();
        callback = callback || function(){};
        url += "?jsoncallback=?";
        $.getJSON(url, function(data){
            loading.stop();
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

        Eksigator.sortedList = sortedList;
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
        return false;
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
        var userName = localStorage.getItem("eksigator.userName");
        var userApiKey = localStorage.getItem("eksigator.apiKey");
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

    Eksigator.getBadgeContent = function(){
        return toolbarButton.badge.textContent;
    };

    Eksigator.setBadgeContent = function(text){
        console.log("badge content set to = " + text);
        toolbarButton.badge.textContent = text; 
        return this;
    };

    Eksigator.listenUserDataChangeEvents = function(){
        opera.extension.onmessage = function(e){
            var messageSplit = e.data.split("=");
            if( e.data.indexOf("eksigator.userName")>-1 ){
                localStorage.setItem("eksigator.userName", messageSplit[1]);
            }
            if (e.data.indexOf("eksigator.apiKey")>-1){
                localStorage.setItem("eksigator.apiKey", messageSplit[1]);
            }

            Eksigator.getList();
        };
    };


    Eksigator.initTimer = function(){
        clearInterval(timer);
        Eksigator.getList();
        if(!timer) {
            timer = setInterval( function(){
                Eksigator.getList();
            }, checkInterval);
        }
    };

})(Eksigator, opera, jQuery);
