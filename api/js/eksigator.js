var userList = false;
var loaded=false;
var pageTitle = false;
var currentOrder = 0;
var maxId = 2099999999;
var itemId = maxId;

var baseUrl="http://sozluk.sourtimes.org/";
var apiURL = "http://eksigator.sonsuzdongu.com/api/";
var sourPHPUrl=apiURL+ userName + "/" + userApiKey +"/";

 $(document).ready( function() {

    panel = $("#panel").html();
    if(panel == null) return false; //iframe instance'lari gelmesin

    $("#panel").prepend('<div id="sourPHP_panel">Liste alınıyor...</div>');
    getSubscriptionListData();

    //$("#el").append("<a name=\"d"+maxId+"\"></a>");

    header = $("h1.title");

    header.hover( function() {
        $(".sourPHP_subscribe").show();
    },
    function() {
        $(".sourPHP_subscribe").hide();
    });

    pageTitle="";
    $("a",header).each( function() {
        isSup = ( $(this).parent("sup").html() == null ) ? false : true;
        if(!isSup) {
            pageTitle += $(this).text()+" ";
        }
    });

    pageTitle = pageTitle.replace(/^\s*/, "").replace(/\s*$/, "");

    $("body").bind("dataLoaded", function() {

        panelCSS = "text-align:center; display:block; cursor:pointer;font-weight:bold; background:#5b7118"; 
        listDiv = '<div style="border:1px solid #5b7118;display:none; background:#a6b255;" id="sourPHP_listDiv"></div>';
        $("#sourPHP_panel").html(listDiv + '<a style="'+panelCSS+'" class="sourPHP_subscriptionList">Ek$igator</a>');
        isOnList = isTitleOnList(pageTitle);
       
        if(isOnList == false) {
            subscribeText = "[Takibe al]";
        }
        else {
            $("#d"+itemId).prepend("<a name=\"d"+itemId+"\"></a>");
            window.location.href = window.location.href +"#d"+itemId;
            if(itemStatus == 1 ) {
                setItemAsRead(pageTitle);
            }
            subscribeText = "[Takipten çıkar]";
        }

        
        titleCSS = "cursor:pointer; font-weight:bold; background-color:#5b7118; display:none; color:#FFF; padding:0 4px 0 4px; margin:0 5px 0 5px;"; 

        $("h1.title").append(' <span style="'+titleCSS+'" class="sourPHP_subscribe">'+subscribeText+'</span> ');


        $(".sourPHP_subscribe").click( function() {

            if(isOnList == true) {
                removeFromList(pageTitle);
                subscribeText = "[Takibe al]";
                text = "'"+pageTitle+"'"+ " basligi takip listenizden çıkarıldı";
                isOnList = false;
            }
            else{
                addToList(pageTitle);
                subscribeText = "[Takipten çıkar]";
                text = "'"+pageTitle+"'"+ " basligi takip listenize eklendi";
                isOnList = true;
            }
            alert(text);
            $(".sourPHP_subscribe").html(subscribeText);
        });


        $(".sourPHP_subscriptionList").click( function() {
            if ( userList == null) {
                alert('Listeniz boş, başlıkların sağındaki düğmelerden ekleyebilirsiniz');
                return false;
            }
            else if($(userList).attr("message") == "AUTH_FAILED") {
                alert('Yetkilendirme basarisiz, API anahtariniz yanlis olabilir mi?');
                return false;
             }
            outputListAsHtml();
            $("#sourPHP_listDiv").toggle("fast");
        });

    });

});







function getSubscriptionListData(){

    //@todo cookie'den de bakabilmeli
    url = sourPHPUrl + "getList/";
    url += "?&jsoncallback=?";

    if(!userList) {
        //data = readCookie("userList");
        $.getJSON(url , function(data){
                userList = data;
                if($(userList).attr("message") == "AUTH_FAILED") {
                    alert('Yetkilendirme basarisiz, API anahtariniz yanlis olabilir mi?');
                }
                //createCookie("userList", userList); 
                $("body").trigger("dataLoaded");
        });
    }

}


function addToList(pageTitle){
    url = sourPHPUrl + "addToList/"+pageTitle+"/";
    url += "?&jsoncallback=?";

    $.getJSON(url , function(data){
            userList = data;
            outputListAsHtml();
    });
}


function clickRemove(title) {
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


function outputListAsHtml() {

        if(!userList) {
            $("#sourPHP_listDiv").empty();
            return false;
        }

        ulCSS = "margin:0; padding:0;";
        liCSS = "margin:0; padding:0; list-style-type:none;";
        removeSpan = "<span class=\"sourPHP_remove\" style=\"color:#c5050c; cursor:pointer; font-weight:bold\"> x </span>";


        foundCount = userList.length;

        if(foundCount == 0 ) {
            output = "---Liste boş --";
        }
        else{
            output = "<ul style=\""+ulCSS+"\">";

            for(var i=0; i< foundCount; i++) {
                item = $(userList[i]);
                itemTitle = item.attr("title");
                itemUrl = baseUrl + "show.asp?t="+itemTitle+"&i="+item.attr("lastId");

                order = item.attr("order");
                readCSS = "";

                itemStatus = item.attr("status");

                if(itemStatus == 1) {
                    readCSS = "color:green !important; font-weight:bold";
                }


                itemTitleTruncated = itemTitle;
                if(itemTitleTruncated.length > 20) {
                    itemTitleTruncated = itemTitleTruncated.substr(0,18) + "...";
                }

                text = "<a style=\""+readCSS+"\" title=\""+itemTitle+"\" href=\""+itemUrl+"\">"+itemTitleTruncated+"</a>";
                output += "<li style=\""+liCSS+"\">"+removeSpan + text+"</li>";
            }
            output += "</ul>";
        }
        $("#sourPHP_listDiv").html(output);
        $(".sourPHP_remove").click( function() {
              parentLi = $(this).parent();
              title = $("a", parentLi).attr("title");
              clickRemove(title);

        });
}


function isTitleOnList(pageTitle){
        if(!userList) return false;

        foundCount = userList.length;
        for(var i=0; i< foundCount; i++) {
            text = $(userList[i]).attr("title");
            itemId = $(userList[i]).attr("lastId");
            itemStatus = $(userList[i]).attr("status");

            if(text == pageTitle) {
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


