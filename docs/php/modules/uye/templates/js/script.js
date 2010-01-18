$(document).ready( function() {
   
      $("#checkEmail").change( checkEmail ); 
      $("#checkPassword, #checkPasswordAgain").change( checkPassword ); 

      $("#submitButton").click( submitForm );

});


function checkPassword() {

        value = $("#checkPassword").val();

        if( value != undefined) {

            if(value.length > 5) {

                again = $("#checkPasswordAgain").val();
                $(".passwordError").html ( '' );

                if( again != value ) {
                    $(".passwordAgainError").html ( passwordAgainError );
                    return false;
                }
                else{
                    $(".passwordAgainError").html ( '' );
                    return true;
                }

                
            }
            else {
                $(".passwordError").html ( passwordError );
                return false;
            }

        }

        return true;


}


function checkEmail() {
    value = $("#checkEmail").val();

    if(value.length > 0 && validateEmail(value)) {

        $(".emailError").html ( '' );
        return true;
    }
    else {
        $(".emailError").html ( emailError );
        return false;
    }

}


function submitForm() {

    emailStatus = checkEmail();

    passwordStatus = checkPassword();


    if(emailStatus && passwordStatus ) {
        //burda form gonderilir
    }
    else{
        $("#submitButton").addClass("formError"); 
        return false;
    }
}



function validateEmail(address) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(address)){
        return (true);
    }
    return (false);

}
