<?php
    echo '
    <header class="header" role="banner">
        <div class="menu">
            <div class="logo"><a class="logo-link" href="'.ROOT.'/">ALTRNTV</a></div>
            <div class="left-menu">
                <ul>
                    <li><a href="'.ROOT.'/">'; echo $lang ? 'Домой' : 'Home'; echo '</a></li>
                    <li><a href="#">'; echo $lang ? 'Музыка' : 'Music'; echo '</a></li>
                    <li><a href="'.ROOT.'/artists">'; echo $lang ? 'Исполнители' : 'Artists'; echo '</a></li>
                    <li><a href="#">'; echo $lang ? 'Блог' : 'Blog'; echo '</a></li>
                </ul>
            </div>
            <div class="search">
                <div class="search-middle" role="search">
                    <form class="form-search" action="/search">
                        <input id="search" class="search-input" placeholder="'; echo $lang ? 'Поиск' : 'Search'; echo '" type="search" autocomplete="off" aria-label="Search" aria-autocomplete="list" name="q" required>
                        <button class="search-button" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="col-md-5" style="position: absolute; width: 100%; margin-top: 10px;">
                    <div class="list-group" id="show-list">

                    </div>
                </div>
            </div>';
            if ($_SESSION['logged_user']) {
                echo '
                <div class="right-menu">
                    <div class="right-menu-in">
                        <ul>
                            <li><a href="'.ROOT.'/upload">'; echo $lang ? 'Загрузить' : 'Upload'; echo '</a></li>
                            <li><a href="'.ROOT.'/create">'; echo $lang ? 'Создать' : 'Create'; echo '</a></li>
                        </ul>
                        <div class="account">
                            <a href="'.ROOT.'/'.$_SESSION['logged_user']->url.'" class="account-link" role="button">
                                <div class="avatar" style="width: 30px; height: 30px;">
                                    <span class="avatar-image" aria-role="img" style="'.image(ROOT.'/img/avatar/'.md5($_SESSION['logged_user']->username).'.jpg').' width: 30px; height: 30px;"></span>
                                </div>
                                <div class="user">
                                    <div class="username">'.$_SESSION['logged_user']->username.'</div>
                                </div>
                            </a>
                        </div>
                        <div class="notifications">
                            <button id="notificationsCheck">
                                <i class="fas fa-bell"></i>';
                                $badge = R::getCell('SELECT `status` FROM notifications WHERE `id_recipient` = ? LIMIT 1', [$_SESSION['logged_user']->id]);
                                echo $badge ? '<div class="badge"></div>' : '';
                            echo '
                            </button>
                            <div id="show-notifications"></div>
                        </div>
                        <div class="setting">
                            <button id="btn-exit"><i class="fas fa-ellipsis-h"></i></button>
                        </div>
                    </div>
                    <div class="more-menu">
                        <ul class="more-menu-list">
                            <li><a><i class="fas fa-info-circle"></i> '; echo $lang ? 'О Нас' : 'About Us'; echo '</a></li>
                            <li><a><i class="fas fa-microphone"></i> '; echo $lang ? 'Для создателей' : 'For Creators'; echo '</a></li>
                            <li><a href="'.ROOT.'/help"><i class="fas fa-question-circle"></i> '; echo $lang ? 'Помощь' : 'Help'; echo '</a></li>
                            <li><hr></li>';
                            if ($_SESSION['logged_user']->admin) { echo '<li><a href="'.ROOT.'/check"><i class="fas fa-check-circle"></i> '; echo $lang ? 'Проверить музыку' : 'Check music'; echo '</a></li><li><hr></li>'; }
                            echo '
                            <li>
                                <div class="item__theme">
                                    <label for="switch"><i class="fas fa-moon"></i> '; echo $lang ? 'Тёмная тема' : 'Dark Theme'; echo '</label>
                                    <div>
                                        <input type="checkbox" id="switch" class="hidden__input" '; echo $_SESSION["theme"] == 'dark' ? 'checked' : ''; echo ' /><label class="switch" for="switch"></label>  
                                    </div>
                                </div>
                            
                            </li>
                            <li><a href="'.ROOT.'/settings"><i class="fas fa-cog"></i> '; echo $lang ? 'Настройки' : 'Settings'; echo '</a></li>
                            <li><hr></li>
                            <li><a class="exit" href="'.ROOT.'/libs/logout"><i class="fas fa-sign-out-alt"></i> '; echo $lang ? 'Выход' : 'Exit'; echo '</a></li>
                        </ul>
                    </div>
                </div>';
            } else {
                echo '
                <div class="right-menu">
                    <div class="right-menu-in">
                        <ul>
                            <li><a href="'.ROOT.'/login" class="login">'; echo $lang ? 'Войти' : 'Log In'; echo '</a></li>
                            <li><a href="'.ROOT.'/signup" class="signup">'; echo $lang ? 'Создать аккаунт' : 'Create account'; echo '</a></li>
                            <li><a href="#">'; echo $lang ? 'Для создателей' : 'For Creators'; echo '</a></li>
                        </ul>
                    </div>
                </div>';
            }
            echo '
        </div>
    </header>';
?>