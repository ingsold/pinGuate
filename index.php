<?php
/**
 * Created by PhpStorm.
 * User: Rodrigo
 * Date: 14/06/2015
 * Time: 11:53 PM
 */

include ("config.php");

$task = isset($_REQUEST['task'])?$_REQUEST['task']:'';



switch($task){

    case 'register':
        echo \triagens\ArangoDb\register($connection,$_REQUEST);
        header("Location: ". $appBaseUrl.'ui/login_register.php?option=login');
        break;

    case 'login':
        if( \triagens\ArangoDb\authenticate($connection, $_REQUEST['username'], $_REQUEST['password'])){
            header("Location: ". $appBaseUrl.'index.php?task=home');
        }else
            echo "no esta registrado";
        break;

    case 'logout':
        session_start();
        session_destroy();
        header("Location: ". $appBaseUrl.'index.php');
        break;

    case 'home':

           // $arrayImages = \triagens\ArangoDb\getImages();

            include 'ui/home.php';
        break;

    case 'userBoard':
        include 'ui/userHome.php';
        break;

    case 'addBoard':

        //$arrayImages = \triagens\ArangoDb\getImages();

        include 'ui/addBoard.php';
        break;

    default:
        if(isLogged()){
            header("Location: ". $appBaseUrl.'index.php?task=home');
        }else{
            header("Location: ". $appBaseUrl.'ui/login_register.php');
        }
    break;
}




