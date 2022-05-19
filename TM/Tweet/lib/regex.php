<?php

function validateUser($user){
    $regex = '/^[A-Za-z0-9_]{1,15}$/';
    if(preg_match($regex, $user)){
        return true;
    }
    return false;
}

function validatePassword($password){
    $regex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/';
    if(preg_match($regex, $password)){
        return true;
    }
    return false;
}

function validateName($name){
    $regex = '/^[A-Za-z ]{1,15}$/';
    if(preg_match($regex, $name)){
        return true;
    }
    return false;
}

function validateLastName($lastName){
    $regex = '/^[A-Za-z ]{1,15}$/';
    if(preg_match($regex, $lastName)){
        return true;
    }
    return false;
}

function validateDate($date){
    $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
    if(preg_match($regex, $date)){
        return true;
    }
    return false;
}

function validateEmail($email){
    $regex = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/';
    if(preg_match($regex, $email)){
        return true;
    }
    return false;
}

function validateID($id){
    $regex = '/^[0-9]{1,15}$/';
    if(preg_match($regex, $id)){
        return true;
    }
    return false;
}

function validateNumberDocument($document){
    $regex = '/^[0-9]{1,15}$/';
    if(preg_match($regex, $document)){
        return true;
    }
    return false;
}

function validateAddresses($addresses){
    $regex = '/^[#.0-9a-zA-Z\s,-]+$/';
    if(preg_match($regex, $addresses)){
        return true;
    }
    return false;
}

function validateContentScript($content){
    $regex = '/<script>.*<\/script>/i';
    if(preg_match($regex, $content)){
        return true;
    }
    return false;
}

function validateTweet($tweet){
    // $regex = '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,140}$/';  
    $regex = '/^[A-Za-z0-9_ ]{1,140}$/';
    if(preg_match($regex, $tweet)){
        return true;
    }
    return false;
}

function validateBool($bool){
    $regex = '/^[0-1]{1}$/';
    if(preg_match($regex, $bool)){
        return true;
    }
    return false;
}


?>