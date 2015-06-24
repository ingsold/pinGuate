<div id="top_center_left">

    <div id="img_icon">
       <a href="index.php"> <img src="ui/images/pinterest_icon.png" width="50px" height="50px"/>
       </a>
    </div>
    <div id="searchDiv" class="">
        <div class="container-4">

            <form action="index.php" method="post">
                <input type="search" id="search" placeholder="Buscar..." />
                <input type="hidden" name="task" value="search">
                <button class="icon"><i class="fa fa-search"></i></button>
            </form>

        </div>

    </div>
    <div id="userDiv" align="center">
        <img src="ui/images/pin.png" width="25px" height="25px" />
        <a href="index.php?task=userBoard&uid=<?=$_SESSION['uid']?>"> Rodrigo </a>
    </div>

</div>

<div id="top_center_right">

    <div class="container_top">
        <br />
        <a href="index.php?task=logout">Cerrar Sesi&oacute;n</a>
    </div>
</div>