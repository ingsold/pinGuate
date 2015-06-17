<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <link rel="canonical" href="http://codepen.io/ehermanson/pen/KwKWEv">
    <link href="http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/assets/reset/normalize.css">
    <link rel="stylesheet" href="css/login_register.css">
</head>

<body>

<div class="form">

    <ul class="tab-group">
        <li class="tab active"><a href="#signup">Registro</a></li>
        <li class="tab"><a href="#login">Log In</a></li>
    </ul>

    <div class="tab-content">
        <div id="signup">
            <h1>Registrate</h1>

            <form action="/pinGuate/index.php" method="post">

                <div class="top-row">
                    <div class="field-wrap">
                        <label>
                            Nombre<span class="req">*</span>
                        </label>
                        <input type="text" required autocomplete="off" id="name" name="name" value="" />
                    </div>

                    <div class="field-wrap">
                        <label>
                            Apellido<span class="req">*</span>
                        </label>
                        <input type="text"required autocomplete="off" id="lastname" name="lastname" value="" />
                    </div>
                </div>

                <div class="field-wrap">
                    <label>
                        Nombre Usuario<span class="req">*</span>
                    </label>
                    <input type="text"required autocomplete="off" id="username" name="username" value="" />
                </div>

                <div class="field-wrap">
                    <label>
                        Email<span class="req">*</span>
                    </label>
                    <input type="email"required autocomplete="off" id="email" name="email" value="" />
                </div>

                <div class="field-wrap">
                    <label>
                        Contrase&ntilde;a<span class="req">*</span>
                    </label>
                    <input type="password"required autocomplete="off" id="password" name="password" value="" />
                </div>

                <button type="submit" class="button button-block"/>Empieza!</button>
                <input type="hidden" name="task" value="register">

            </form>

        </div>

        <div id="login">
            <h1>Bienvenido</h1>

            <form action="/pinGuate/index.php" method="post">

                <div class="field-wrap">
                    <label>
                        Nombre Usuario<span class="req">*</span>
                    </label>
                    <input type="text"required autocomplete="off" id="username" name="username" value="" />
                </div>

                <div class="field-wrap">
                    <label>
                        Contrase&ntilde;a<a href=""></a><span class="req">*</span>
                    </label>
                    <input type="password"required autocomplete="off" id="password" name="password" value="" />
                </div>

                <button class="button button-block"/>Log In</button>
                <input type="hidden" name="task" value="login">

            </form>

        </div>

    </div><!-- tab-content -->

</div> <!-- /form -->
<script src="//assets.codepen.io/assets/common/stopExecutionOnTimeout.js?t=1"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    $('.form').find('input, textarea').on('keyup blur focus', function (e) {
        var $this = $(this), label = $this.prev('label');
        if (e.type === 'keyup') {
            if ($this.val() === '') {
                label.removeClass('active highlight');
            } else {
                label.addClass('active highlight');
            }
        } else if (e.type === 'blur') {
            if ($this.val() === '') {
                label.removeClass('active highlight');
            } else {
                label.removeClass('highlight');
            }
        } else if (e.type === 'focus') {
            if ($this.val() === '') {
                label.removeClass('highlight');
            } else if ($this.val() !== '') {
                label.addClass('highlight');
            }
        }
    });
    $('.tab a').on('click', function (e) {
        e.preventDefault();
        $(this).parent().addClass('active');
        $(this).parent().siblings().removeClass('active');
        target = $(this).attr('href');
        $('.tab-content > div').not(target).hide();
        $(target).fadeIn(600);
    });

    var option = getParameterByName("option");
    if(option == 'login'){
        $('.tab a').click();
    }
    //@ sourceURL=pen.js
</script>
<script src="/assets/editor/live/css_live_reload_init.js"></script>
</body>
</html>
