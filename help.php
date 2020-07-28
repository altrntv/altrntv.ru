<?php require 'libs/db.php'; if ($_SESSION['lang'] == 'ru') { $lang=true; } ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $lang ? 'Помощь' : 'Help'; ?></title>
        <link rel="stylesheet" href="/css/min-common.css">
        <style>
            .content {
                width: 1240px;
                background: white;
                margin: 24px auto;
            }
            
            .info {
                padding: 16px 30px 100px;
            }
        </style>
    </head>
    
    <body>
        <div id="fullpage">
            <?php
                if ($lang) {
                    echo '
                        <header class="header" role="banner">
                            <div class="menu">
                                <div class="logo"><a class="logo-l" href="/">ALTRNTV</a></div>
                                <div class="text">Помощь</div>
                            </div>
                        </header>
                        <div class="content">
                            <div class="info">
                                <h2>Связаться с нами</h2>
                                <p>Email Us: support@altrntv.ru</p>
                                <h2>Важная информцация</h2>
                                <ul>
                                    <li><a href="'.ROOT.'/about/comment-posting-policy">Правила публикации отзывов</a></li>
                                </ul>
                                <h2>Часто задаваемые вопросы</h2>
                                <h3>Авторизация</h3>
                                <h4>Как я могу восстановить свой пароль?</h4>
                                <p>Нажмите на кнопку <b>Забыли пароль</b> в процессе входа и введите свой адрес электронной почты. Затем нажмите кнопку <b>Сбросить пароль</b>.</p>
                                <h3>Мой аккаунт</h3>
                                <h4>Где я могу редактировать информацию об аккаунте?</h4>
                                <p>Вы можете изменить свою информацию в <b>настройках</b>.</p>
                            </div>
                        </div>
                    ';
                } else {
                    echo '
                        <header class="header" role="banner">
                            <div class="menu">
                                <div class="logo"><a class="logo-l" href="/">ALTRNTV</a></div>
                                <div class="text">Help</div>
                            </div>
                        </header>
                        <div class="content">
                            <div class="info">
                                <h2>Contact Us</h2>
                                <p>Email Us: support@altrntv.ru</p>
                                <h2>Important Information</h2>
                                <ul>
                                    <li><a href="'.ROOT.'/about/comment-posting-policy">Review Policy</a></li>
                                </ul>
                                <h2>Frequently Asked Questions</h2>
                                <h3>Signing In</h3>
                                <h4>How can I recover my password?</h4>
                                <p>Click on the <b>Forgot Password</b> button on the sign in process and enter your e-mail. Then click <b>Reset the Password</b>.</p>
                                <h3>My Account</h3>
                                <h4>How do I edit my information?</h4>
                                <p>You can edit your information in the settings.</p>
                            </div>
                        </div>
                    ';
                }
            ?>
        </div>
        <footer class="min-footer"><div>ALTRNTV</div></footer>
    </body>
</html>