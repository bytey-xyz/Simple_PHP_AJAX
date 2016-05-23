<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Вход</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="/css/signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/js/jquery-2.1.4.js"></script>
    <script src="/js/jquery.maskedinput.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
</head>

<body>

<div class="container">

    <div class="form-signin">
        <h2 class="form-signin-heading">Пожалуйста, войдите</h2>
        <label for="inputPhone" class="sr-only">Email</label>
        <div class="input-group">
            <span class="input-group-addon">+7</span>
            <input type="phone" id="inputPhone" class="form-control" placeholder="Телефон" required autofocus>
        </div>
        <label for="inputCode" class="sr-only">Код</label>
        <input type="text" id="inputCode" maxlength="5" class="form-control" placeholder="Код" required>
        <a href="#" id="register" data-toggle="modal" data-target="#modalRegister">
            Зарегистрироваться
        </a>
        <hr>
        <button class="btn btn-lg btn-primary btn-block" id="makeLogin">Вход</button>
    </div>
<script>
    $(document).ready(function(){
        var phoneS = $('#inputPhone');
        var phoneRS = $('#inputPhoneRegister');
        var codeS = $('#inputCode');
        phoneS.mask('(999) 999-9999');
        phoneRS.mask('(999) 999-9999');
        codeS.bind('keypress', function (event) {
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });
        var modal  = {
            title: $('#titleOne'),
            content: $("#bodyOne"),
            modal: $('#modalOne')
        };
        $('#makeLogin').click(function(){
            var login = phoneS.val();
            var code = codeS.val();
            $.ajax({
                type: "POST",
                url: "/api.php?login=true",
                data: {
                    phone: login,
                    code_post: code,
                },
                dataType: "json",
                success: function(data){
                    if(data.error)
                    {
                        modal.title.html("Ошибка");
                        modal.content.html(data.error);
                    }
                    if(data.success)
                    {
                        modal.title.html("Успех!");
                        modal.content.html("Вход выполнен успешно");
                        setTimeout('location="/index.php"',2000);
                    }
                    modal.modal.modal();
                }
            });
        }); // makeLogin
        $('#makeRegistration').click(function(){
            var phone = phoneRS.val();
            var args = {
                phone: phone,
            };
            $.ajax({
                type: "POST",
                url: "/api.php?register=true",
                data: args,
                dataType: "json",
                success: function(data)
                {
                    console.log(data);
                    if(data.error)
                    {
                        modal.title.html("Ошибка");
                        modal.content.html(data.error);
                    }
                    if(data.success)
                    {

                        $('#phoneSuccess').html(data.phone);
                        $('#codeSuccess').html(data.verification_code);
                        modal.title.html("Успех!");
                        modal.content.html($('#successRegister').html());
                    }
                    $('#modalRegister').modal('hide'); // Hide this modal
                    modal.modal.modal(); // Show new modal with registration results
                }
            });
        });
    });
</script>
</div> <!-- /container -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalOne">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleOne"></h4>
            </div>
            <div class="modal-body" id="bodyOne">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalRegister">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleTwo">Регистрация</h4>
            </div>
            <div class="modal-body" id="bodyTwo">
                <div class="form-signin">
                    <label for="inputPhoneRegister">
                        Телефон
                    </label>
                    <div class="input-group">
                        <span class="input-group-addon">+7 </span>
                        <input type="phone" class="form-control" name="inputPhoneRegister" id="inputPhoneRegister" placeholder="Телефон">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="makeRegistration">Зарегистироваться</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="hidden" id="successRegister">
    <h5>Аккаунт был успешно зарегистрирован</h5>
    <p><b>Ваш телефон:</b> <span id="phoneSuccess"></span></p>
    <p><b>Ваш код для входа:</b> <span id="codeSuccess"></span></p>
</div>
</body>
</html>