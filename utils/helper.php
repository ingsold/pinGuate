<?php


function isLogged(){
    session_start();

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
        return true;
    }

    return false;
}