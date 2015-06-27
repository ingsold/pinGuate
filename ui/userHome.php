<?php

session_start();

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="ui/css/style.css">
        <link rel="stylesheet" type="text/css" href="ui/css/buttons.css">
        <link rel="stylesheet" type="text/css" href="ui/css/forms.css">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <script>
            $(document).ready(function() {
                $("#form_add_board").dialog({
                    autoOpen: false,
                    show: { effect: 'drop', direction: "up" },
                    modal: true,
                    draggable: true,
                    width: 500
                });

                $("#form_add_pin").dialog({
                    autoOpen: false,
                    show: { effect: 'drop', direction: "up" },
                    modal: true,
                    draggable: true,
                    width: 500
                });

                $("#user_follows").dialog({
                    autoOpen: false,
                    show: { effect: 'drop', direction: "up" },
                    modal: true,
                    draggable: true,
                    width: 500
                });

                $("#add_board").click(function(){
                    $("#form_add_board" ).dialog("open");
                });

                $("#add_pin").click(function(){
                    $("#form_add_pin" ).dialog("open");
                });

                $("#show_user_follow").click(function(){
                    $("#user_follows" ).dialog("open");
                });

                //adBoard

            });
        </script>


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
   <?=$userinfo[0]->get('username')?>

</div>
<?php
    if($userinfo[0]->get('user_id') == $_SESSION['uid']){
?>
<div id="menu_buttons">

    <a id="add_board" class="button icon board" href="#"><span>A&ntilde;adir Tablero</span></a>
    <a id="add_pin" class="button icon add_image" href="#"><span>A&ntilde;adir Pin</span></a>
    <a id="show_user_follow" class="button icon user_follow" href="#"><span>A qui&eacute;n sigo?</span></a>
</div>

<?php
    }
?>

<div id="listBoards">
    <hr />
    <br />
    <div id="columns">
    <?php
    //echo 'COUNT: '.count($boards);
    if(count($boards) > 0){

    foreach($boards as $board){

        $images = \triagens\ArangoDb\getImagesByBoard($connection, $board->get('board_id'));

        //var_dump($images);

        $rutas = array();

        foreach($images as $image){
            $rutas[] = $image->get('ruta');
        }
    ?>
    <div class="pin">
        <div class="main_image" style="background-image: url('<?=isset($rutas[0])?$rutas[0]:'';?>');">
        </div>
        <div class="second_image" style="background-image: url('<?=isset($rutas[1])?$rutas[1]:'';?>');">
        </div>
        <div class="second_image" style="background-image: url('<?=isset($rutas[2])?$rutas[2]:'';?>');">
        </div>
        <div class="second_image" style="background-image: url('<?=isset($rutas[3])?$rutas[3]:'';?>');">
        </div>
        <div class="board_title">
            <b><a href="index.php?task=board&idBoard=<?=$board->get('board_id');?>&uid=<?=$userinfo[0]->get('user_id')?>"> <?=$board->get('name');?> </a></b>
        </div>
    </div>


    <?php
    }
    }
    ?>
    </div>
</div>
<div style="float: left; height: 1px; width: 100%; background-color: #2b303b;"></div>
<div style="float: left; height: 1px; width: 100%; ">

    <h3>Tableros que sigo</h3>
    <ul>
        <?php
            foreach($boardFollows as $board){
        ?>
        <li>
            <a href="index.php?task=board&idBoard=<?=$board->get('id_board');?>&uid=<?=$board->get('user_id');?>"> <?=$board->get('name');?> </a>
        </li>
        <?php
            }
        ?>
    </ul>

</div>



<div id="form_add_board">

    <form action="index.php" method="post" class="basic-grey">

        <h1>A&ntilde;adir Tablero<span>Por favor llene todos los campos.</span></h1>

        <p>
            <label>
                <span>Nombre :</span>
                <input id="name" type="text" name="name" placeholder="Nombre Tablero">
            </label>
            <label>
                <span>Descripci&oacute;n :</span>
                <input id="description" type="text" name="description" placeholder="Descripci&oacute;n">
            </label>
            <label>
                <span>Es privado? :</span>
                <select name="private">
                    <option value="true">S&iacute;</option>
                    <option value="false">No</option>
                </select>
            </label>
            <label>
                <span>Categor&iacute;a :</span>
                <select name="category">
                    <?php
                        foreach($categories as $category){
                    ?>
                     <option value="<?=$category->get('category_id')?>"><?=$category->get('name')?></option>
                    <?php
                        }
                    ?>
                </select>
            </label>
            <label>
                <span>&nbsp;</span>
                <input type="submit" class="button" value="Crear Tablero">
                <input type="hidden" name="task" value="addBoard">
                <input type="hidden" name="uid" value="<?=$_SESSION['uid']?>">
            </label>
        </p>
    </form>
</div>

<div id="form_add_pin">

    <form action="index.php" method="post" class="basic-grey">

        <h1>A&ntilde;adir Pin<span>Por favor llene todos los campos.</span></h1>

        <p>

            <label>
                <span>Pin Web :</span>
                <input type="text" id="img_web" name="img_web" placeholder="Pega la url">
            </label>
           <!-- <label>
                <span>Pin Local :</span>
                <input type="file" id="img_local" name="img_local" placeholder="Selecciona">
            </label>-->
            <label>
                <span>Descripci&oacute;n :</span>
                <textarea id="description" name="description" placeholder="Descripci&oacute;n"></textarea>
            </label>
            <label>
                <span>Tablero :</span>
                <select name="id_board">
                    <?php
                    foreach($boards as $board){
                    ?>
                    <option value="<?=$board->get('board_id');?>"><?=$board->get('name');?></option>
                    <?php
                    }
                    ?>
                </select>
            </label>
            <label>
                <span>&nbsp;</span>
                <input type="submit" class="button" value="Crear Pin">
                <input type="hidden" name="task" value="addPin">
                <input type="hidden" name="uid" value="<?=$_SESSION['uid']?>">
            </label>
        </p>
    </form>
</div>

<div id="user_follows">
    <?php
        foreach($userFollows as $ufollow){
            echo $ufollow->get('user').' '.$ufollow->get('lastname');
            echo '<br />';
        }
    ?>
</div>

</body>
</html>