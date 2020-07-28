<?php
require 'libs/db.php'; if ($_SESSION['lang'] == 'ru') { $lang=true; }
if ($_SESSION['logged_user']) {
    $settings = R::findOne('users', 'id = ?', array($_SESSION['logged_user']->id));
    echo '
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>'; echo $lang ? 'Настройки' : 'Settings'; echo '</title>
            <link rel="stylesheet" href="'.ROOT.'/css/common.css">
            <link rel="stylesheet" href="'.ROOT.'/css/settings.css">
            <link rel="stylesheet" type="text/css" href="'.ROOT.'/css/'.$_SESSION["theme"].'.css" id="theme">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
        </head>
        <body>
            <div id="fullpage">';
                require 'libs/header.php';
    echo '      <div class="content">
                    <div class="settings__content">
                        <h2>'; echo $lang ? 'Настройки профиля' : 'Profile setting'; echo '</h2>
                        <div class="settings-menu">
                            <div class="settings__box settings__url">
                                <h3>'; echo $lang ? 'URL профиля' : 'Profile URL'; echo '</h3>
                                <form class="change email" action="libs/settings" method="post">
                                    <div class="info">
                                        <div class="input-group">
                                            <span>'.ROOT.'/</span>
                                            <input id="url" type="text" name="url" style="width: 300px;" value="'.$settings->url.'" autocomplete="off" required>
                                        </div>
                                        <p class="warning">'; echo $lang ? 'Используйте только цифры, строчные буквы или дефисы.' : 'Use only numbers, lowercase letters or hyphens.'; echo '</p>
                                    </div>
                                    <div class="submit">
                                        <input class="button" type="submit" name="do_url" value="'; echo $lang ? 'Изменить' : 'Change'; echo '">
                                    </div>
                                </form>
                            </div>
                            <div class="settings__box settings__username">
                                <h3>'; echo $lang ? 'Отображаемое имя' : 'Display name'; echo '</h3>
                                <form class="change email" action="libs/settings" method="post">
                                    <div class="info">
                                        <div class="input-group">
                                            <input type="text" name="username" value="'.$settings->username.'" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="submit">
                                        <input class="button" type="submit" name="do_username" value="'; echo $lang ? 'Изменить' : 'Change'; echo '">
                                    </div>
                                </form>
                            </div>
                            <div class="settings__box settings__language">
                                <h3>'; echo $lang ? 'Язык' : 'Language'; echo '</h3>
                                <form class="change lang" action="libs/settings" method="post">
                                    <div class="info">
                                        <div class="input-group">
                                            <select name="lang" onchange="this.form.submit()">';
                                                if ($lang == 'ru') {
                                                    echo '
                                                    <option value="en">English</option>
                                                    <option value="ru" selected>Russian</option>';
                                                } else {
                                                    echo '
                                                    <option value="en" selected>English</option>
                                                    <option value="ru">Russian</option>';
                                                }
                                            echo '
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <h2>'; echo $lang ? 'Безопасность и конфиденциальность' : 'Security and Privacy'; echo '</h2>
                        <div class="settings-menu">
                            <div class="settings__box settings__email">
                                <h3>Email</h3>';
                                echo '<span>'.substr($settings['email'], 0, 2).'***'.substr($settings['email'], strpos($settings['email'], "@")).'</span>';
                                echo '
                                <form class="change email" action="libs/settings" method="post">
                                    <div class="info">
                                        <div class="input-group">
                                            <label for="oldpass">'; echo $lang ? 'Новый email' : 'New email'; echo '</label>
                                            <input type="text" name="email" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="submit">
                                        <input class="button" type="submit" name="do_email" value="'; echo $lang ? 'Изменить' : 'Change'; echo '">
                                    </div>
                                </form>
                            </div>
                            <div class="settings__box settings__password">
                                <h3>'; echo $lang ? 'Пароль' : 'Password'; echo '</h3>
                                <form class="change pass" action="libs/settings" method="post">
                                    <div class="info">
                                        <div class="input-group">
                                            <label>'; echo $lang ? 'Старый пароль' : 'Current password'; echo '</label>
                                            <input type="password" name="oldpassword" required>
                                        </div>
                                        <div class="input-group">
                                            <label>'; echo $lang ? 'Новый пароль' : 'New password'; echo '</label>
                                            <input type="password" name="newpassword" required>
                                        </div>
                                        <div class="input-group">
                                            <label>'; echo $lang ? 'Повторите пароль' : 'Repeat new password'; echo '</label>
                                            <input type="password" name="repeatpassword" required>
                                        </div>
                                    </div>
                                    <div class="submit">
                                        <input class="button" type="submit" name="do_password" value="'; echo $lang ? 'Изменить' : 'Change'; echo '">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="min-footer"><div>ALTRNTV</div></footer>
        </body>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="'.ROOT.'/js/script.js"></script>
        <script>
            jQuery(document).on("keypress", "#url", function(key) {
            console.log(key.charCode);
            if ( 
                    ((key.charCode >= 32) && (key.charCode <= 44)) ||
                    ((key.charCode >= 58) && (key.charCode <= 96)) ||
                    ((key.charCode >= 123) && (key.charCode <= 127)) ||
                    ((key.charCode >= 1040) && (key.charCode <= 1105)) ||
                    (key.charCode == 46) ||
                    (key.charCode == 47) ||
                    (key.charCode == 8470) ||
                    (key.charCode == 1025)
                )  {
                $(".warning").addClass("success");
                return false;
            } else {
                $(".warning").removeClass("success");
            }
            });
        </script>
    </html>';
} else {
    header("Location: /");
    die();
}
?>