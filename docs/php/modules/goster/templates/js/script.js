var inputText="Takip etmek istediğiniz başlığı girin";
var siteUrl = "http://" + window.location.hostname + "/goster/";
var currentUrl = window.location.href;
var sozlukUrl = "http://sozluk.sourtimes.org/";
var maxID = 299999999999;
var newTitleCount=0;

$(document).ready( function() {
    $("#newTitle").val(inputText);

    $("#newTitle").focus( checkInputFieldIn );
    $("#newTitle").blur( checkInputFieldOut );

    $("#newTitleButton").click( addTitleToList );

    $(".toggle").click( toggleNotUpdatedFields );

    $(".setReadAndGoToTitle").click( setReadAndGoToTitle );
    $(".goToTitle").click( goToTitle );

    $(".remove").click ( removeTitleFromList );
});

function toggleNotUpdatedFields() {
    currentClasses = $(this).attr("class");

    if( currentClasses.indexOf("toggleUp") > -1 ) {
        $(this).removeClass("toggleUp");
        $("#hiddenTitles").hide("fast");
    }
    else{
        $(this).addClass("toggleUp");
        $("#hiddenTitles").show("fast");
    }
}


function checkInputFieldIn() {
    currentText = $(this).val();
    if( currentText == inputText ) {
        $(this).removeClass("wait");
        $(this).val('');
    }
}

function checkInputFieldOut() {
    currentText = $(this).val();
    if( currentText.length == 0 ) {
        $(this).addClass("wait");
        $(this).val(inputText);
    }
}


function addTitleToList() {
    title = $("#newTitle").val();

    //if text not changed do not add 
    if ( title == inputText ) return false;

    command = siteUrl + "addToList/?ajax=1";

    $.post(command, {"title": title}, function(data) {

        if( isTitleExists(title) ) {
            alert("Bu başlığı zaten takip ediyorsunuz");
        }
        else{
            
            mod = "line"+ (newTitleCount%2);

            newTitleCount++;
            newId = "new"+newTitleCount+"_"+maxID;
            newDiv =  '<div class="row '+mod+'"> &nbsp; <span class="remove">x</span> &nbsp; <a class="goToTitle"  name="'+newId+'" id="'+newId+'">'+title+'</a>   </div>';
            $("#hiddenTitles").append(newDiv);

            $("#newTitle").val('');
            alert("'"+title+"' başlığı takip listenize eklendi");
            $("#newTitle").blur();


        }

        $(".remove").click ( removeTitleFromList );
    });
}

function setReadAndGoToTitle() {
    split = $(this).attr("id").split("_");

    title = $(this).html();
    lastId = split[1];

    urlToGo = sozlukUrl + "show.asp?t="+title+"&i="+lastId;
    command = siteUrl + "setItemAsRead/?ajax=1";
    $.post(command, {"title": title}, function(data) {
        window.open (urlToGo );
        window.location.href = siteUrl;
    });

}

function goToTitle() {
    split = $(this).attr("id").split("_");

    title = $(this).html();
    lastId = split[1];

    urlToGo = sozlukUrl + "show.asp?t="+title+"&i="+lastId;
    window.open (urlToGo );
}


function removeTitleFromList() {
    parentDiv = $(this).parent("div");
    split = $("a", parentDiv).attr("id").split("_");

    title = $("a", parentDiv).html();
    lastId = split[1];

    var answer = confirm("'"+title+"' başlığını takip listenizden çıkarmak istediğinize emin misiniz?")
    if (answer){

        command = siteUrl + "removeFromList/?ajax=1";
        $.post(command, {"title": title}, function(data) {
            $(parentDiv).hide();
            reOrderList();

        });
    }
}


function reOrderList( ) {
    $(".row").removeClass("line0");
    $(".row").removeClass("line1");

    currentId = 1;
    $(".row:visible").each( function() {
        if(currentId == 1) {
            currentId = 0;
        }
        else {
            currentId = 1;
        }
        $(this).addClass("line"+currentId);
    });
}


function isTitleExists(title) {
    
    $(".row a").each ( function() {
        aTitle = $(this).html();

        if( title == aTitle ) {
            return true;
        }
    });

    return false;
}
