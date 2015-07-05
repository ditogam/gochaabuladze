<head>
    <BASE href="http://gochaabuladze.herobo.com">
    <?php
    $this_page = $_GET['page'];
    if (is_null($this_page))
        $this_page = 'home';
    include("php/check_url_type.php");
    include 'php/config.php';
    include 'php/classes/CCamera.php';
    include 'php/opendb.php';
    $lng_name = $_REQUEST["lang"];
//    $sql = "SELECT short_name FROM `languages`";
//    $found = null;
//    $first = null;
//    $result = $conn->query($sql);
//    if ($result->num_rows > 0) {
//        while ($row = $result->fetch_assoc()) {
//            if (is_null($first)) {
//                $first = $row["short_name"];
//            }
//            if ((is_null($lng_name) or $lng_name == $row["short_name"])) {
//                $found = $lng_name;
//            }
//        }
//    }
//    if (is_null($found)) {
//       // header("Location: " . getBaseUrl() . $first);
//        $redirect=getBaseUrl() . $first.'/'.$this_page.'/'.$_GET['id'];
//        include 'php/closedb.php';
//        header("Location: " . $redirect);
//        die();
//    }


    // Start the session
    session_start();
    $_SESSION['isLoggedIn'] = true;
    //$_SESSION['uploads_dir'] = getcwd() . "/uploads";
    //$moxieManagerConfig['filesystem.rootpath'] = $_SESSION['uploads_dir'];
    include 'php/header_main.php';

    include 'php/dictionary_and_languages.php';
    include 'php/dictionary_data.php';
    include 'php/header_main.php';

    function getSelected($name)
    {
        global $this_page;
        if ($this_page == $name) return 'class="selected"'; else '';
    }

    function getSelectedArr($names)
    {
        global $this_page;
        foreach ($names as $name) {
            if ($this_page == $name)
                return 'class="selected"';
        }
        return '';
    }

    ?>
    <script>
        $(window).load(function () {


            try {
                jQuery('.camera_wrap').camera();
            } catch (err) {//camera

            }
        });
        $(document).ready(function () {
            $('ul.sf-menu').sooperfish();
        });
        function change_language(lang_id) {
            var _data = {};
            _data.lang_id = lang_id;
            var request = $.ajax({
                url: "php/storesession.php",
                method: "GET",
                data: _data,
                dataType: "html"
            });
            request.done(function (msg) {
                location.reload();
            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        }
    </script>


</head>

<body>
<div id="fb-root"></div>


<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/<?=$current_lang->locale_name?>/sdk.js#xfbml=1&version=v2.3&appId=361608957382137";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<div id="main">
    <header>
        <div id="logo">
            <div id="logo_text">
                <!-- class="logo_colour", allows you to change the colour of the text -->
                <h1><a href="index.php"><?= $dictionary['name'] ?></a></a></h1>
            </div>
        </div>
        <nav>
            <ul class="sf-menu" id="nav">
                <li <?= getSelected('home') ?>><a href="index.php"><?= $dictionary['home'] ?></a></li>
                <li <?= getSelected('schedule') ?>>
                    <a href="index.php?page=schedule"><?= $dictionary['schedule'] ?></a>
                </li>
                <li <?= getSelected('news') ?>>
                    <a href="index.php?page=news"><?= $dictionary['news'] ?></a>
                </li>
                <li <?= getSelected('about') ?>>
                    <a href="index.php?page=about"><?= $dictionary['about'] ?></a>
                </li>
                <li <?= getSelected('events') ?>>
                    <a href="index.php?page=events"><?= $dictionary['events'] ?></a>
                </li>
                <li <?= getSelectedArr(array('photos', 'videos')) ?>><a
                        href="#"><?= $dictionary['watch_listen'] ?></a>
                    <ul>
                        <li>
                            <a href="index.php?page=photos">
                                <?= $dictionary['photos'] ?>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=videos">
                                <?= $dictionary['videos'] ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <li <?= getSelectedArr(array('features', 'critical_acclame')) ?>><a
                        href="#"><?= $dictionary['press'] ?></a>
                    <ul>
                        <li>
                            <a href="index.php?page=critical_acclame">
                                <?= $dictionary['critical_acclame'] ?>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=features">
                                <?= $dictionary['features'] ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <li <?= getSelected('contact') ?>>
                    <a href="index.php?page=contact"><?= $dictionary['contact'] ?></a>
                </li>

                <li><a href="#"><img
                            src="<?= $current_lang->flag_url ?>">&nbsp;<?= $current_lang->lng_name ?></a>
                    <ul>
                        <? foreach ($langs as $lng) { ?>
                            <li>
                                <a href="#" onclick="change_language(<?= $lng->lng_id ?>)">
                                    <table>
                                        <tr>
                                            <td><img src="<?= $lng->flag_url ?>"></td>
                                            <td>&nbsp;<?= $lng->lng_name ?></td>
                                        </tr>
                                    </table>

                                </a>
                            </li>
                        <? } ?>


                </li>
            </ul>
        </nav>
    </header>
    <div id="site_content">
        <? if ($this_page == 'home')
            include 'home.php';
        ?>
        <? if ($this_page == 'about')
            include 'about.php';
        ?>
        <? if ($this_page == 'photos')
            include 'photos.php';
        ?>
        <? if ($this_page == 'videos')
            include 'videos.php';
        ?>
        <? if ($this_page == 'schedule')
            include 'schedule.php';
        ?>
        <? if ($this_page == 'events')
            include 'events.php';
        ?>
        <? if ($this_page == 'news')
            include 'news.php';
        ?>
        <? if ($this_page == 'critical_acclame')
            include 'critical_acclame.php';
        ?>

    </div>
    <div class="fb-like" data-href="<?= rel2abs($_SERVER['REQUEST_URI']) ?>" data-layout="button_count"
         data-action="like"
         data-show-faces="true" data-share="true" data-show-faces="true"></div>

    <br>

    <div class="fb-comments" data-href="<?= rel2abs($_SERVER['REQUEST_URI']) ?>" data-numposts="5"></div>

</div>
<footer>

    <?
    $sql = "select `youtube_channel`,`youtube_search`,`facebook_account`,`facebook_group`,`tweeter_account`,`email` from `configuration` c  ";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo addLink("img/youtube.png", $row['youtube_channel'], '');
            echo addLink("img/youtube.png", $row['youtube_search'], '');
            echo addLink("img/facebook.png", $row['facebook_account'], '');
            echo addLink("img/facebook.png", $row['facebook_group'], '');
            echo addLink("img/tweeter.png", $row['tweeter_account'], '');

        }
    }
    $result->close();

    ?>
    <a href="<>"
</footer>
</body>

</html>
<?php include 'php/closedb.php'; ?>