var userList = false;
var loaded=false;
var pageTitle = false;
var currentOrder = 0;
var maxId = 2099999999;
var itemId = maxId;
var pageTitle = "";
var isOnList = false;
var removeSpan = "<span class=\"eksigator_remove\"> x </span>";
var itemStatus = 0;

var baseUrl="http://sozluk.sourtimes.org/";
var apiURL = "http://eksigator.sonsuzdongu.com/api/";
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


$(document).ready( function() {
   includeCSS();
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
    $("#panel").prepend('<div id="eksigator_panel">'+_FETCHING_LIST+'</div>');

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
        subscribeText = _SUBSCRIBE;
        text = _TITLE_UNSUBSCRIBED.replace(/%s/, pageTitle);
        isOnList = false;
    }
    else{
        //if not in list, add to our list
        addToList(pageTitle);
        subscribeText = _UNSUBSCRIBE;
        text = _TITLE_SUBSCRIBED.replace(/%s/, pageTitle);
        isOnList = true;
    }
    alert(text);
    $(".eksigator_subscribe").html(subscribeText);
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



function getSubscriptionListData(){
    url = sourPHPUrl + "getList/";
    url += "?&jsoncallback=?";

    $.getJSON(url , function(data){
            userList = data;
            $(document).trigger("dataLoaded");
    });
}


function addToList(pageTitle){
    url = sourPHPUrl + "addToList/"+pageTitle+"/";
    url += "?&jsoncallback=?";

    $.getJSON(url , function(data){
            userList = data;
            outputListAsHtml();
    });
}


function clickRemove(parentLi) {
    title = $("a", parentLi).attr("title");
    var wantToDelete = confirm("Başlığı takipten çıkarmak istiyor musunuz?")
    if (wantToDelete){
        removeFromList(title);
    }
    else{
        return false;
    }
}

function removeFromList(pageTitle){
    url = sourPHPUrl + "removeFromList/"+pageTitle+"/";
    url += "?&jsoncallback=?";

    $.getJSON(url , function(data){
            userList = data;
            outputListAsHtml();
    });
}

function setItemAsRead(pageTitle){
    url = sourPHPUrl + "setItemAsRead/"+pageTitle+"/";
    url += "?&jsoncallback=?";

    $.getJSON(url , function(data){
            userList = data;
            outputListAsHtml();
    });
}


function getItemsAsHtml (readStatus ) {

    if(readStatus == "read")  {
        itemsWanted = 0;
    }
    else{
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
            itemUrl = baseUrl + "show.asp?t="+itemTitle+"&i="+item.attr("lastId");

            itemTitleTruncated = itemTitle;
            if(itemTitleTruncated.length > 20) {
                itemTitleTruncated = itemTitleTruncated.substr(0,18) + "...";
            }

            text = "<a title=\""+itemTitle+"\" href=\""+itemUrl+"\">"+itemTitleTruncated+"</a>";
            output += "<li>"+ removeSpan + text+"</li>";
        }
    }

    $(".eksigator_panel_button span").empty();
    if( count > 0  ){
        if( readStatus == 'unread' ){
            $(".eksigator_panel_button span").html(" ("+count+")");
        }
        return "<ul class=\"eksigator_item_ul "+readStatus+"\">" + output + "</ul>";
    }

    return false;
}

function outputListAsHtml() {

    previousReadItemsStatus = $(".eksigator_item_ul.read").is(':hidden');
    $("#eksigator_item_list").empty();
    
    if(!userList) {
        return false;
    }

    foundCount = userList.length;

    if(foundCount >  0 ) {

        readItems = getItemsAsHtml( 'read');
        unreadItems = getItemsAsHtml( 'unread');

        if(unreadItems) {
            $("#eksigator_item_list").append(unreadItems);
        }


        if(readItems) {
            $("#eksigator_item_list").append(readItems);
        }
           
        $(".eksigator_remove").click( function() {
          parentLi = $(this).parent();
          clickRemove(parentLi);
        });

        $(".eksigator_item_ul.unread").show("fast");

    }

    //if read item is hidden previously
    if(previousReadItemsStatus == true) {
        $(".eksigator_item_ul.read").hide();
    }
}


function getTitleStatus(pageTitle){
        if(!userList) {
            return false;
        }
        foundCount = userList.length;
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

