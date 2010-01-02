$(document).ready( function() {
        
    $("#checkNameSurname").change( checkNameSurname ); 
    $("#checkEmail").change( checkEmail ); 

    $(".submitButton").click( submitForm );

});


function checkNameSurname() {
    value = $("#checkNameSurname").val();

    if(value.length > 0) {
        $(".nameSurnameError").html ( okImage );
        return true;
    }
    else {
        $(".nameSurnameError").html ( nameError );
        return false;
    }
}


function checkEmail() {
    value = $("#checkEmail").val();

    if(value.length > 0 && validateEmail(value)) {
        $(".emailError").html ( okImage );
        return true;
    }
    else {
        $(".emailError").html ( emailError );
        return false;
    }

}


function checkMessage() {
    value = $("#checkMessage").val();
     if(value.length > 0) {
        $(".messageError").html ( '' );
        return true;
    }
    else {
        $(".messageError").html ( messageError  );
        return false;
    }
}


function submitForm() {
    nameSurnameStatus = checkNameSurname();
    emailStatus = checkEmail();
    messageStatus = checkMessage();

    if(nameSurnameStatus && emailStatus && messageStatus ) {
        //burda form gonderilir
    }
    else{
        $(".submitButton").addClass("formError"); 
        return false;
    }
}



function validateEmail(address) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(address)){
        return (true);
    }
    return (false);

}
