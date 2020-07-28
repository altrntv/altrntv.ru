<?php require '../libs/db.php'; if ($_SESSION['lang'] == 'ru') { $lang=true; } ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $lang ? 'Правила публикации комментариев' : 'Review Policy'; ?></title>
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
                                <div class="text">Правила публикации комментариев</div>
                            </div>
                        </header>
                        <div class="content">
                            <div class="info">
                                <p>Оставляя комментарии к приложению, соблюдайте следующие правила:</p>
                                <ul>
                                    <li>Не уходите от темы. Комментарий должен быть связан с выбранным приложением.</li>
                                    <li>Не указывайте в своем обзоре игровые и партнерские коды, адреса электронной почты или ссылки.</li>
                                    <li>Не публикуйте фиктивные отзывы с целью повысить или понизить оценку.</li>
                                    <li>Не публикуйте контент сексуального характера и материалы, содержащие нецензурную лексику.</li>
                                    <li>Не публикуйте материалы, содержащие оскорбления, дискриминационные высказывания или угрозы.</li>
                                    <li>Не выдавайте себя за другого человека и не вводите пользователей в заблуждение относительно вашей связи с определенным лицом или какой-либо организацией.</li>
                                    <li>Не публикуйте личную и конфиденциальную информацию (свою и других людей), например почтовые адреса, номера документов и прочие сведения, отсутствующие в открытом доступе.</li>
                                </ul>
                                <p>Вам также могут пригодиться следующие рекомендации:</p>
                                <ul>
                                    <li>Текст должен легко читаться, поэтому не злоупотребляйте заглавными буквами и знаками препинания.</li>
                                    <li>Публикуйте точную, полезную и понятную информацию.</li>
                                    <li>Старайтесь осветить как положительные, так и отрицательные стороны.</li>
                                    <li>Будьте вежливы и терпимо относитесь к другим.</li>
                                    <li>Следуйте правилам грамматики и проверяйте правописание.</li>
                                    <li>Старайтесь, чтобы ваши комментарии были полезны и информативны.</li>
                                </ul>
                            </div>
                        </div>
                    ';
                } else {
                    echo '
                        <header class="header" role="banner">
                            <div class="menu">
                                <div class="logo"><a class="logo-l" href="/">ALTRNTV</a></div>
                                <div class="text">Comment posting policy</div>
                            </div>
                        </header>
                        <div class="content">
                            <div class="info">
                                <p>Please follow these policies when commenting on an application:</p>
                                <ul>
                                    <li>Stay on topic. Comments should be about the application that you&#039re reviewing.</li>
                                    <li>Don&#039t include game or affiliate codes, email addresses or links in your review.</li>
                                    <li>Don&#039t post fake reviews intended to boost or lower ratings.</li>
                                    <li>Don&#039t post content that is sexually explicit or contains profanity.</li>
                                    <li>Don&#039t post content that is abusive or hateful or threatens or harasses others.</li>
                                    <li>Don&#039t impersonate any person or falsely state, or otherwise misrepresent your affiliation with a person or entity.</li>
                                    <li>Don&#039t post your or other people&#039s private and/or confidential information, such as a physical address or driver&#039s license number, or any other information that is not publicly accessible.</li>
                                </ul>
                                <p>You might also like to follow these guidelines and tips:</p>
                                <ul>
                                    <li>Keep it readable; don&#039t use excessive capitalisation and punctuation.</li>
                                    <li>Post clear, valuable and honest information.</li>
                                    <li>Try to include both positives and drawbacks.</li>
                                    <li>Be nice to others; don&#039t attack others.</li>
                                    <li>Use proper grammar and check your spelling.</li>
                                    <li>Make your comments useful and informative.</li>
                                </ul>
                            </div>
                        </div>
                    ';
                }
            ?>
        </div>
        <footer class="min-footer"><div>ALTRNTV</div></footer>
    </body>
</html>