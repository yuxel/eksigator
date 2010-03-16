<?
//http://www.jonasjohn.de/snippets/php/xor-encryption.htm
class Encryption
{

    /**
     * XOR encrypts a given string with a given key phrase.
     *
     * @param     string    $InputString    Input string
     * @param     string    $KeyPhrase      Key phrase
     * @return    string    Encrypted string    
     */    
    function XOREncryption($InputString, $KeyPhrase){

        $KeyPhraseLength = strlen($KeyPhrase);

        // Loop trough input string
        for ($i = 0; $i < strlen($InputString); $i++){

            // Get key phrase character position
            $rPos = $i % $KeyPhraseLength;

            // Magic happens here:
            $r = ord($InputString[$i]) ^ ord($KeyPhrase[$rPos]);

            // Replace characters
            $InputString[$i] = chr($r);
        }

        return $InputString;
    }

    function encrypt($InputString, $KeyPhrase){
        $InputString = self::XOREncryption($InputString, $KeyPhrase);
        $InputString = base64_encode($InputString);
        return $InputString;
    }

    function decrypt($InputString, $KeyPhrase){
        $InputString = base64_decode($InputString);
        $InputString = self::XOREncryption($InputString, $KeyPhrase);
        return $InputString;
    }
}


