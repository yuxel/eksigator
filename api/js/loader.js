/**
 * eksisozluk has a redirection rule for referrers
 * so we didn't run this script on redirection
 */
function checkReferrer(){
    referrer = document.referrer;

    if(!referrer ||
       referrer.indexOf("http://sozluk.sourtimes.org") == 0 ||
       referrer.indexOf("http://sourtimes.org") == 0 ||
       referrer.indexOf("http://eksisozluk.com") == 0 ||
       referrer.indexOf("http://www.eksisozluk.com") == 0 ||
       referrer.indexOf("http://www.eksisozluk.com") == 0 ||
       referrer.indexOf"(188.132.200.200") == 0 
      ) {

        return true;
    }

    return false;
}

alert ('foo');
alert ( validReferrer );

validReferrer = checkReferrer();

if( validReferrer ){
    include ( 'jquery' );
    include ( 'eksigator' );
}



