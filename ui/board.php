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
            $("#form_add_pin").dialog({
                autoOpen: false,
                show: { effect: 'drop', direction: "up" },
                modal: true,
                draggable: true,
                width: 500
            });

            $("#add_pin").click(function(){
                $("#form_add_pin" ).dialog("open");
            });

            $('#add_board').click(function (event){
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('href')
                    ,success: function(response) {
                        alert(response);
                    }
                })
                return false; //for good measure
            });

            $('#add_user').click(function (event){
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('href')
                    ,success: function(response) {
                        alert(response);
                    }
                })
                return false; //for good measure
            });

        });
    </script>


</head>
<body>

<?php
require "top.php";
?>

<div id="top_board">
    <hr />
    <h1><?=$boardInfo[0]->get('name')?></h1>
    <p><?=$boardInfo[0]->get('description')?></p>

</div>
<div id="menu_buttons">
    <div style="float: left; width: 100%; height: 5px;"></div>
    <?php
    if($userinfo[0]->get('user_id') != $_SESSION['uid']){
    ?>
    <div style="float: left; width: 50%; height: auto;">
        <a id="add_user" class="button icon follow_user"
           href="index.php?task=followUser&board=<?=$boardInfo[0]->get('board_id')?>&uid=<?=$_SESSION['uid']?>&user=<?=$userinfo[0]->get('user_id')?>">
            <span>Seguir a <?=$userinfo[0]->get('username')?></span></a>
        <a id="add_board" class="button icon follow_board"
           href="index.php?task=followBoard&board=<?=$boardInfo[0]->get('board_id')?>&uid=<?=$_SESSION['uid']?>&user=<?=$userinfo[0]->get('user_id')?>">
            <span>Seguir Tablero</span></a>
    </div>
    <?php
    }
    ?>
    <?php
    if($userinfo[0]->get('user_id') == $_SESSION['uid']){
    ?>
    <div style="float: right; width: 50%; height: auto; text-align: right;">
        <a id="add_pin" class="button icon add_image" href="#"><span>A&ntilde;adir Pin</span></a>
        <a id="edit_board" class="button icon edit_board" href="#"><span>Editar Tablero</span></a>
    </div>
    <?php
    }
    ?>
    <div style="float: left; width: 100%; height: 5px;"></div>
    <hr/>
</div>

<div id="columns">
    <?php
    if(count($images) > 0) {

        foreach ($images as $image) {

            ?>
            <div class="pin">
                <img src="<?= $image->get('ruta') ?>"/>

                <p>
            <?php
            if($userinfo[0]->get('user_id') != $_SESSION['uid']){
                ?>
                    <a class="addpin" href="index.php?task=addPin&pinId=31654">
                        <img src="ui/images/push-pin.png" height="32px" style="width: 32px;"/>
                    </a>
                    <br/>
                <?php
                }
                ?>
                    Agregado desde:
                    <a href="index.php?task=userBoard&uid=<?=$_SESSION['uid']?>"> <?=$_SESSION['username']?></a>
                </p>
            </div>
        <?php
        }
    }
    ?>
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
                <span>Descripcid_boardi&oacute;n :</span>
                <textarea id="description" name="description" placeholder="Descripci&oacute;n"></textarea>
            </label>
            <label>
                <span>&nbsp;</span>
                <input type="submit" class="button" value="Crear Pin">
                <input type="hidden" name="task" value="addPin">
                <input type="hidden" name="uid" value="<?=$_SESSION['uid']?>">
                <input type="hidden" name="id_board" value="<?=$boardInfo[0]->get('board_id')?>">

            </label>
        </p>
    </form>
</div>


</body>
</html>