<?php

function validateText($string){
    $remove[] = "'";
    $remove[] = '"';
    $remove[] = "-";
    $remove[] = "_";
    $remove[] = "@";
    $remove[] = "/";
    $remove[] = " ";
    $StringReplace= str_replace( $remove, "", $string );
    $htmlEntities = htmlentities($StringReplace);
    $validated = filter_var($htmlEntities, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    return $validated;
}

//validate email address;

function validateEmail($email){
    if (!filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_STRIP_HIGH) === false) {
        return true;
    } else {
        return false;
    }
}

function ValidateEmailImproved($email){

    if(!empty($email)){
        if(validateEmail($email)){
            $emailToValidate = $email;
            $findme   = "'";
            $findme2   = '`';
            $pos = strpos($emailToValidate, $findme);
            $pos2 = strpos($emailToValidate, $findme2);

            if ($pos === false && $pos2 === false) {
                return true;
            } else {
                return false;
            }
        }
    }else{
        return false;
    }
}

function ValidateEmailExtra($email){
    $pattern = "/[^a-z0-9_@.]+/i";

    $email_to_validate = $email;

    if(!preg_match($pattern, $email_to_validate)){
        if(!empty($email)){
            if(validateEmail($email_to_validate)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }else{
        return false;
    }

}

?>