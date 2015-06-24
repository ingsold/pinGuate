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

                $("#add_board").click(function(){
                    $("#form_add_board" ).dialog("open");
                });

                $("#add_pin").click(function(){
                    $("#form_add_pin" ).dialog("open");
                });
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
    Rodrigo L&oacute;pez

</div>

<div id="menu_buttons">

    <a id="add_board" class="button icon board" href="#"><span>A&ntilde;adir Tablero</span></a>
    <a id="add_pin" class="button icon add_image" href="#"><span>A&ntilde;adir Pin</span></a>

</div>

<div id="listBoards">
    <hr />
    <br />

    <div id="columns">
    <div class="pin">
        <div style="float: left; width: 150px; height: 150px;
         background-image: url('http://cssdeck.com/uploads/media/items/2/2v3VhAp.png'); border:1px solid; margin: 5px;">
            </div>
            <div style="float: left; width: 40px; height: 50px;
         background-image: url('http://cssdeck.com/uploads/media/items/1/1swi3Qy.png');
         border:1px solid; margin: 5px;">
            </div>
                <div style="float: left; width: 40px; height: 50px;
         background-image: url('http://cssdeck.com/uploads/media/items/2/2v3VhAp.png');
         border:1px solid; margin: 5px;">
                </div>
                    <div style="float: left; width: 40px; height: 50px;
         background-image: url('http://cssdeck.com/uploads/media/items/2/2v3VhAp.png');
         border:1px solid; margin: 5px;">
                    </div>
        <p>
            <b>Tablero 1</b>
        </p>
    </div>
    </div>


</div>

<div id="form_add_board">

    <form action="http://google.com" method="post" class="basic-grey">

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
                    <option value="0">Arte</option>
                    <option value="1">Deportes</option>
                </select>
            </label>
            <label>
                <span>&nbsp;</span>
                <input type="button" class="button" value="Crear Tablero">
                <input type="hidden" name="task" value="addBoard">
            </label>
        </p>
    </form>
</div>

<div id="form_add_pin">

    <form action="http://google.com" method="post" class="basic-grey">

        <h1>A&ntilde;adir Pin<span>Por favor llene todos los campos.</span></h1>

        <p>

            <label>
                <span>Pin Web :</span>
                <input type="text" id="img_web" name="img_web" placeholder="Pega la url">
            </label>
            <label>
                <span>Pin Local :</span>
                <input type="file" id="img_local" name="img_local" placeholder="Selecciona">
            </label>
            <label>
                <span>Descripci&oacute;n :</span>
                <textarea id="description" name="description" placeholder="Descripci&oacute;n"></textarea>
            </label>
            <label>
                <span>Tablero :</span>
                <select name="category">
                    <option value="0">Tablero 1</option>
                    <option value="1">Tablero 2</option>
                </select>
            </label>
            <label>
                <span>&nbsp;</span>
                <input type="button" class="button" value="Crear Pin">
                <input type="hidden" name="task" value="addPin">
            </label>
        </p>
    </form>
</div>

</body>
</html>