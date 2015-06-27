<?php
/**
 * Created by PhpStorm.
 * User: Rodrigo
 * Date: 16/06/2015
 * Time: 10:42 PM
 */

//echo 'HOME';
session_start();
//var_dump($_SESSION);
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="ui/css/style.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>

<?php
    require "top.php";
?>

<div id="wrapper">
    <hr/>
    <br/>



    <div id="columns">
        <?php
        foreach($images as $image){
        ?>
        <div class="pin">
            <img src="<?=$image->get('ruta')?>" />
            <p>
                <br/>
                Agregado desde:
                    <a href="index.php?task=userBoard&uid=<?=$image->get('id_user')?>"><?=$image->get('username')?></a>
            </p>
        </div>
        <?php
        }
        ?>
    </div>


</div>


</body>
</html>
