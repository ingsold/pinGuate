<?php

session_start();

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="ui/css/style.css">
        <link rel="stylesheet" type="text/css" href="ui/css/buttons.css">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
<body>

<?php
    require "top.php";
?>

<div id="top_user">
    <hr>
    <br />
    <br />
    <img src="ui/images/pin_user.png" width="75px" height="75px"/>
    <br />
    <br />
    Rodrigo L&oacute;pez

</div>

<div id="menu_buttons">

    <a class="button icon board" href="#"><span>A&ntilde;adir Tablero</span></a>
    <a class="button icon add_image" href="#"><span>A&ntilde;adir Imagen</span></a>

</div>

</body>
</html>