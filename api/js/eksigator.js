var userList = false;
var loaded=false;
var pageTitle = false;
var currentOrder = 0;
var maxId = 2099999999;
var itemId = maxId;
var pageTitle = "";
var isOnList = false;
var itemStatus = 0;

//var baseUrl="http://sozluk.sourtimes.org/"; //good old days

var baseUrl="http://www.eksisozluk.com/";
var sourPHPUrl=apiURL+ userName + "/" + userApiKey +"/";

//language constants
var _FETCHING_LIST="Liste alınıyor";
var _AUTH_FAILED = "Yetkilendirme başarısız, API anahtarınız yanlış olabilir mi?";
var _PANEL_BUTTON = "Ek$igator";
var _SUBSCRIBE = "[ Takibe al ]";
var _UNSUBSCRIBE = "[ Takipten çıkar ]";
var _TITLE_UNSUBSCRIBED = "'%s' başlığı takip listenizden çıkarıldı";
var _TITLE_SUBSCRIBED = "'%s' başlığı takip listenize eklendi";
var _YOUR_LIST_EMPTY = 'Listeniz boş, başlıkların sağındaki düğmeleri kullanarak ekleyebilirsiniz';
var _ARE_YOU_SURE_UNSUBSCRIBE = "Başlığı takipten çıkarmak istiyor musunuz?";

includeCSS();

$(document).ready( function() {
   initElements(); //title and panel
   getSubscriptionListData(); //read data

   //if data loaded
   $(document).bind("dataLoaded", function() { 

       //if authorization failed
       if ( !isAuthorized ( userList ) ) {
           alert(_AUTH_FAILED);
           return false;
       }

       panelButton = "<a class=\"eksigator_panel_button\">"+_PANEL_BUTTON+"<span></span></a>";
       itemListDiv = "<div id=\"eksigator_item_list\">&nbsp;</div>";
       
       $("#eksigator_panel").html(itemListDiv + panelButton);
    

       //get isOnlist, lastId  and itemStatus
       getTitleStatus ( pageTitle );

       if(itemStatus == 1 ) {
           //if this page is on our list
           setItemAsRead(pageTitle);
       }


       //init susbcriber buttons near title
       initTitleSubscriber ( pageTitle ); 
       
       $(".eksigator_subscribe").click( titleSubscribeButtonClicked ); 

       $(".eksigator_panel_button").click( panelButtonClicked );

       outputListAsHtml();

       $(".eksigator_item_ul.read").hide();
   });

});

/**
 * init pages default elements like panel and header
 */
function initElements() {
    //panel
    loading = '<div class="eksigator_panel_button"><div class="loading">'+ _FETCHING_LIST +'</div></div>';
    $("div.panel").prepend('<div id="eksigator_panel">'+ loading +'</div>');

    //header
    header = $("h1.title");
    header.hover( function() {
        $(".eksigator_subscribe").show();
    },
    function() {
        $(".eksigator_subscribe").hide();
    });

    //get title without superscript
    $("a",header).each( function() {
        isSup = ( $(this).parent("sup").html() == null ) ? false : true;
        //we need to remove superscript elements
        if(!isSup) {
            pageTitle += $(this).text()+" ";
        }
    });

    pageTitle = pageTitle.replace(/^\s*/, "").replace(/\s*$/, "");

}


/**
 * check if user authorized
 */
function isAuthorized ( userList ){
    if($(userList).attr("message") == "AUTH_FAILED") {
        return false;
    }
    return true;
}

/**
 * go to entry id
 */
function goToAnchor ( itemId ) {
    if ( itemId != maxId ) {
        $("#d"+itemId).prepend("<a name=\"d"+itemId+"\"></a>");
        window.location.href = window.location.href +"#d"+itemId;
    }
}


/**
 * subscriber near title bar
 */
function initTitleSubscriber ( pageTitle ) {

    //if its on our list
    if(isOnList == false) {
        itemText = _SUBSCRIBE;
    }
    else {
        goToAnchor ( itemId );
        itemText = _UNSUBSCRIBE;
    }

    title_subscriber = "<span class=\"eksigator_subscribe\">"+itemText+"</span>";
    $("h1.title").append( title_subscriber );
}


/**
 * when clicked to subscribe or unsubscribe
 * button near title
 */
function titleSubscribeButtonClicked ( ) {

    if(isOnList == true) {
        //if its on our list , remove it
        removeFromList(pageTitle);
        text = _TITLE_UNSUBSCRIBED.replace(/%s/, pageTitle);
    }
    else{
        //if not in list, add to our list
        addToList(pageTitle);
        text = _TITLE_SUBSCRIBED.replace(/%s/, pageTitle);
    }
    alert(text);
}

/**
 * when clicked to panel button ek$igator
 */
function panelButtonClicked() {
    //if list is empty
    if ( !userList) {
       alert(_YOUR_LIST_EMPTY);
       return false;
    }

    readItemsStatus = $(".eksigator_item_ul.read").is(':hidden');

    outputListAsHtml();

    //toggle item
    if( readItemsStatus == true ) {
        $(".eksigator_item_ul.read").show('fast');
    }
    else{
        $(".eksigator_item_ul.read").hide('fast');
    }
}


/**
 * get subscription list of user
 */
function getSubscriptionListData(){
    url = sourPHPUrl + "getList/";
    url += "?&jsoncallback=?";

    $.getJSON(url , function(data){
            userList = data;
            $(document).trigger("dataLoaded");
    });
}

/**
 * add pageTitle to users list
 *
 * @param string pageTitle
 */
function addToList(pageTitle){
    pageTitle = fixUriChars(pageTitle);
    url = sourPHPUrl + "addToList/"+pageTitle+"/";
    url += "?&jsoncallback=?";

    $.getJSON(url , function(data){
            userList = data;

            //reload list
            outputListAsHtml();

            //change title status
            subscribeText = _UNSUBSCRIBE;
            isOnList = true;
            $(".eksigator_subscribe").html(subscribeText);
    });
}


/**
 * when cliecked to remove button on list
 */
function clickRemove(parentLi) {
    title = $("a", parentLi).attr("title");
    //confirm deletion
    var wantToDelete = confirm(_ARE_YOU_SURE_UNSUBSCRIBE);

    if (wantToDelete){
        removeFromList(title);
    }
    else{
        return false;
    }
}

/**
 * remove pageTitle from users list
 *
 * @param string pageTitle
 */
function removeFromList(pageTitle){
    pageTitle = fixUriChars(pageTitle);
    url = sourPHPUrl + "removeFromList/"+pageTitle+"/";
    url += "?&jsoncallback=?";

    $.getJSON(url , function(data){
            userList = data;

            //reload list
            outputListAsHtml();

            //alter subscription list at title
            subscribeText = _SUBSCRIBE;
            isOnList = false;
            $(".eksigator_subscribe").html(subscribeText);
    });
}

/**
 * set item as read
 *
 * @todo there's a bug related to time difference between our server and eksisozluk
 *
 * @param string pageTitle
 */
function setItemAsRead(pageTitle){
    pageTitle = fixUriChars(pageTitle);
    url = sourPHPUrl + "setItemAsRead/"+pageTitle+"/";
    url += "?&jsoncallback=?";

    $.getJSON(url , function(data){
            userList = data;

            //reload list
            outputListAsHtml();
    });
}


/**
 * get elements as Html such <ul><li><a href="foo">bar</a></li></ul>
 *
 * @param enum readStatus "read"|"unread"
 */
function getItemsAsHtml (readStatus ) {

    if(readStatus == "read")  {
        //read's status is 0
        itemsWanted = 0;
    }
    else{
        //unread's status is 1
        itemsWanted = 1;
    }

    output = "";
    count = 0;
    for(var i=0; i< foundCount; i++) {

        item = $(userList[i]);
        itemStatus = item.attr("status");

        if( itemStatus == itemsWanted ) {
            count ++;
            itemTitle = item.attr("title");
            itemUrl = baseUrl + "show.asp?t="+fixUriChars(itemTitle)+"&i="+item.attr("lastId");

            itemTitleTruncated = itemTitle;
            if(itemTitleTruncated.length > 17) {
                itemTitleTruncated = itemTitleTruncated.substr(0,15) + "...";
            }

            text = "<a title=\""+itemTitle+"\" href=\""+itemUrl+"\">"+itemTitleTruncated+"</a>";

            removeSpan = "<span class=\"eksigator_remove\"> x </span>";
            output += "<li>"+ removeSpan + text+"</li>";
        }
    }

    $(".eksigator_panel_button span").empty();

    if( count > 0  ){
        //put unread item count near button like ( 2 )
        if( readStatus == 'unread' ){
            $(".eksigator_panel_button span").html(" ("+count+")");
        }
        //if found any item, return output
        return "<ul class=\"eksigator_item_ul "+readStatus+"\">" + output + "</ul>";
    }

    return false;
}


/**
 * fetch unread items and read items and generate html 
 */
function outputListAsHtml() {

    // readItemsStatus should be same
    previousReadItemsStatus = $(".eksigator_item_ul.read").is(':hidden');

    $("#eksigator_item_list").empty();
    
    if(!userList) {
        return false;
    }

    foundCount = userList.length;

    if(foundCount >  0 ) {

        readItems = getItemsAsHtml( 'read');
        unreadItems = getItemsAsHtml( 'unread');

        //append unreadItems
        if(unreadItems) {
            $("#eksigator_item_list").append(unreadItems);
        }


        //append readItems
        if(readItems) {
            $("#eksigator_item_list").append(readItems);
        }
           
        //when cliked to remove button on list
        $(".eksigator_remove").click( function() {
          parentLi = $(this).parent();
          clickRemove(parentLi);
        });

        //unread items are always shown
        $(".eksigator_item_ul.unread").show("fast");

    }

    //if read item is hidden previously
    if(previousReadItemsStatus == true) {
        $(".eksigator_item_ul.read").hide();
    }
}

/**
 * get title's status for this page 
 *
 * @param string pageTitle
 */
function getTitleStatus(pageTitle){
        if(!userList) {
            return false;
        }

        foundCount = userList.length;

        //try to find this title on users list
        for(var i=0; i< foundCount; i++) {
        
            text = $(userList[i]).attr("title");
            itemStatus = $(userList[i]).attr("status");
            itemId = $(userList[i]).attr("lastId");

            if(text == pageTitle) {
                isOnList = true;
                return true;
            }
        }
        return false;
}

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

/**
 * load eksigator.css from remote host
 */
function includeCSS(){
  var fileref=document.createElement("link");
  linkHref = apiURL + "css/eksigator.css";
  fileref.setAttribute("rel", "stylesheet");
  fileref.setAttribute("type", "text/css");
  fileref.setAttribute("href", linkHref);
  document.getElementsByTagName("head")[0].appendChild(fileref);
}


/**
 * Fixes uri chars for eksi++ issue
 */
function fixUriChars(text) {
   text = text.replace(/\+/g,"%2B"); 
   return text;
}
