<?php
require 'libs/db.php';

echo '
<html>
    <head>
        <meta charset="UTF-8">
        <title>Artists</title>
        <link rel="stylesheet" href="'.ROOT.'/css/common.css">
        <link rel="stylesheet" type="text/css" href="'.ROOT.'/css/'.$_SESSION["theme"].'.css" id="theme">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.0/css/all.css">
        <style>
        .artists {
            display: grid;
            grid-template: "a b c d i f g";
        }            

        .artist__block {
            display: flex;
            justify-content: center;
            padding: 20px 18px;
            transition: .4s;
        }

        .artist__block:hover {
            z-index: 1;
            box-shadow: 0 2px 5.3px rgba(0, 0, 0, 0.046), 0 6.7px 17.9px rgba(0, 0, 0, 0.098), 0 30px 80px rgba(0, 0, 0, 0.25);
        }

        .artist__block span {
            width: 120px;
            text-align: center;
            display: block;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            word-break: normal;
        }
        </style>
    </head>
    <body>
        <div id="fullpage">';
            require 'libs/header.php';
            echo '
            <div class="content">
                <div class="artists">';
                $users = R::getCursor('SELECT username, url FROM users ORDER BY username');
                while ($row = $users->getNextItem()) {
                    echo '
                    <div class="artist__block">
                        <a href="/'.$row['url'].'">
                            <span class="avatar-image" style="'.image(ROOT.'/img/avatar/'.md5($row['username']).'.jpg').'width: 120px; height: 120px; margin-bottom: 10px;"></span>
                            <span>'.$row['username'].'</span>
                        </a>
                    </div>';   
                }
                echo '
                </div>
            </div>
        </div>
        <footer class="min-footer"><div>ALTRNTV</div></footer>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="'.ROOT.'/js/script.js"></script>
</html>';
?>