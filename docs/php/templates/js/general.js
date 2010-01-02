$(document).ready( function() {
    $("a._blank").click( function() {
        href = $(this).attr("href");
        window.open ( href );
        return false;
    });




});

