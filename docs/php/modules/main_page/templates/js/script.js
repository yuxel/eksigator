$(document).ready( function()  {
    
    $(".bubble a").click ( function() {
        href = $(this).attr("href");
        window.open ( href );
        return false;
    });

});
