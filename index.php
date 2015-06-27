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
        }else {
            echo "no esta registrado";
        }
        break;

    case 'logout':
        session_start();
        session_destroy();
        header("Location: ". $appBaseUrl.'index.php');
        break;

    case 'home':

            $images = \triagens\ArangoDb\getAllImages($connection);
            shuffle($images);

            include 'ui/home.php';
        break;

    case 'addBoard':
            $data = $_POST;

           \triagens\ArangoDb\createBoard($connection, $_REQUEST);

            header("Location: ". $appBaseUrl.'index.php?task=userBoard&uid='.$data['uid']);
        break;

    case 'addPin':
            $data = $_POST;

           \triagens\ArangoDb\createPin($connection, $data);

            header("Location: ". $appBaseUrl.'index.php?task=board&idBoard='.$data['id_board']);
        break;

    case 'userBoard':
        $boards = \triagens\ArangoDb\getBoardsbyUser($connection, $_REQUEST['uid']);
        $categories = \triagens\ArangoDb\getCategories($connection);
        $userinfo = \triagens\ArangoDb\getUserInfo($connection, $_REQUEST['uid']);
        $userFollows = \triagens\ArangoDb\getUserFollows($connection, $_REQUEST['uid']);
        $boardFollows = \triagens\ArangoDb\getBoardsFollows($connection, $_REQUEST['uid']);
        include 'ui/userHome.php';
        break;

    case 'board':

        $boardInfo = \triagens\ArangoDb\getBoardInfo($connection, $_REQUEST['idBoard'] );
        $images = \triagens\ArangoDb\getImagesByBoard($connection, $_REQUEST['idBoard']);
        $userinfo = \triagens\ArangoDb\getUserInfo($connection, $_REQUEST['uid']);

        include 'ui/board.php';
        break;

    case 'followBoard':

        \triagens\ArangoDb\insertFollow($connection, $_REQUEST['uid'], '', $_REQUEST['board'],'');
        echo "Seguido con exito";

        break;

    case 'followUser':

        \triagens\ArangoDb\insertFollow($connection, $_REQUEST['uid'], $_REQUEST['user'], '','');
        echo "Seguido con exito";
        break;

    default:
        if(isLogged()){
            header("Location: ". $appBaseUrl.'index.php?task=home');
        }else{
            header("Location: ". $appBaseUrl.'ui/login_register.php');
        }
    break;
}




