<?php require 'libs/db.php'; if ($_SESSION['lang'] == 'ru') { $lang=true; } ?>
<?php if (isset($_SESSION['logged_user'])) : header('Location: /'); ?>
<?php else : ?>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $lang ? 'Регистрация' : 'Sign Up'; ?></title>
    <link rel="stylesheet" href="css/min-common.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
    
<body>
    <div id="fullpage">
        <header class="header" role="banner">
            <div class="menu" style="justify-content: space-between;">
                <div class="logo"><a href="/">ALTRNTV</a></div>
                <div class="navbar">
                    <ul>
                        <li><a href="/login"><?php echo $lang ? 'Войти' : 'Log In'; ?></a></li>
                        <li><a href="/signup"><?php echo $lang ? 'Создать аккаунт' : 'Create account'; ?></a></li>
                    </ul>
                </div>
            </div>
        </header>
        <div class="content">
            <div class="auth__block">
                <div class="auth">
                    <div class="auth__header"><?php echo $lang ? 'Регистрация' : 'Register'; ?></div>
                    <?php messageShow() ?>
                    <div class="auth__body">
                        <form method="POST" action="libs/signup">
                            <div class="form__group row">
                                <label for="name" class="form__label"><?php echo $lang ? 'Имя' : 'Name'; ?></label>
                                <div class="form__input">
                                    <input id="name" type="text" class="form__control" name="name" autofocus="" required>
                                </div>
                            </div>
                           
                            <div class="form__group row">
                                <label for="email" class="form__label"><?php echo $lang ? 'E-Mail адрес' : 'E-Mail Address'; ?></label>
                                <div class="form__input">
                                    <input id="email" type="email" class="form__control" name="email" required>
                                </div>
                            </div>

                            <div class="form__group row">
                                <label for="password" class="form__label"><?php echo $lang ? 'Пароль' : 'Password'; ?></label>
                                <div class="form__input">
                                    <input id="password" type="password" class="form__control" name="password" required>
                                </div>
                            </div>
                            
                            <div class="form__group row">
                                <label for="password-confirm" class="form__label"><?php echo $lang ? 'Повторите пароль' : 'Confirm Password'; ?></label>
                                <div class="form__input">
                                  <input id="password-confirm" type="password" class="form__control" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form__group row" style="margin-left: 30%;">
                                <button type="submit" name="do_signup" class="button"><?php echo $lang ? 'Отправить' : 'Sign Up'; ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php endif; ?>