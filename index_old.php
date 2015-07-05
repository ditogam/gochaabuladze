<?php
// Start the session
session_start();
$_SESSION['isLoggedIn'] = true;
//$_SESSION['uploads_dir'] = getcwd() . "/uploads";
//$moxieManagerConfig['filesystem.rootpath'] = $_SESSION['uploads_dir'];
include("php/php_file_tree.php");
include 'php/config.php';
include 'php/classes/CCamera.php';
include 'php/classes/CEvent.php';
include 'php/opendb.php';
include 'php/header_main.php';
$page = $_GET['page'];
if (is_null($page))
    $page='home';
?>
    <script>
        $(window).load(function () {

            //form1
            $('#form1').sForm({
                ownerEmail: '#',
                sitename: 'sitename.link'
            })

            //camera
            jQuery('.camera_wrap').camera();

            // TouchTouch
            $('.thumb').touchTouch();

        });
        $(function () {
            $("#photolist").sortable();
        });
    </script>


    </head>

    <body>
    <?php include 'php/dictionary_and_languages.php'; ?>
    <div class="whitebg">
        <!--==============================header=================================-->
        <header id="header">
            <div class="">
                <h1 class="navbar-brand navbar-brand_"><a href="index.php"><?= $dictionary['name']  ?></a></h1>

                <div class="rightside">
                    <div class="menuheader">
                        <nav class="navbar navbar-default navbar-static-top myNavbar" role="navigation">
                            <ul class="nav sf-menu">
                                <li class="active"><a href="index.php"><strong><?= $dictionary['home'] ?></strong><span></span></a>
                                </li>
                                <li><a href="index.php?page=schedule"><strong><?= $dictionary['schedule'] ?></strong><span></span></a>
                                </li>
                                <li><a href="index.php?page=news"><strong><?= $dictionary['news'] ?></strong><span></span></a>
                                </li>
                                <li><a href="index.php?page=about"><strong><?= $dictionary['about'] ?></strong><span></span></a>
                                </li>
                                <li><a href="#"><strong><?= $dictionary['watch_listen'] ?></strong><span></span><em
                                            class="indicator1"></em></a>
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
                                <li><a href="index.php?page=recordings"><strong><?= $dictionary['recordings'] ?></strong><span></span></a>
                                </li>
                                <li><a href="#"><strong><?= $dictionary['press'] ?></strong><span></span><em
                                            class="indicator1"></em></a>
                                    <ul>
                                        <li>
                                            <a href="index.php?page=schedule">
                                                <?= $dictionary['critical_acclame'] ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="index.php?page=schedule">
                                                <?= $dictionary['features'] ?>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="index.php?page=schedule"><strong><?= $dictionary['contact'] ?></strong><span></span></a>
                                </li>

                                <li><a href="php/storesession.php?lang_id=<?= $current_lang->lng_id ?>"><strong><img
                                                src="<?= $current_lang->flag_url ?>">&nbsp;<?= $current_lang->lng_name ?>
                                        </strong><span></span><em class="indicator1"></em></a>
                                    <ul>
                                        <?foreach ($langs as $lng) echo '
                                            <li><img src="' . $lng->flag_url . '"><a href="php/storesession.php?lang_id=' . $lng->lng_id . '">&nbsp;' . $lng->lng_name . '</a>
                                </li>'; ?>


                                </li>
                            </ul>
                            </li>

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <hr class="line1">
        </header>
        <!--==============================content=================================-->
        <div id="content">

            <? if ($page == 'home')
                include 'home.php';
            ?>
            <? if ($page == 'about')
                include 'about.php';
            ?>
            <!--============================== row_4 =================================-->


    <!--==============================footer=================================-->
    <footer>
        <div class="footerrow1">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 fright">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 footercol1">
                                <p class="title9">our address</p>

                                <div class="smalladdress">
                                    <p class="mainaddress">8901 Minnesota, Bemidji,
                                        <br> 1221 Birchmont Drive Northeast.
                                        <br> Phone: 1(234) 567 8910
                                        <br> E-mail: <a href="#"><?= $dictionary['critical_acclame'] ?></a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 footercol1">
                                <p class="title9">newsletter</p>

                                <form id="form1">
                                    <div class="success">Your subscribe request
                                        <br> has been sent!
                                    </div>
                                    <fieldset>
                                        <label class="email">
                                            <input type="email" value="">
                                            <span class="error">*This is not a valid email address.</span>
                                        </label>
                                        <br class="clear">

                                        <div class="btns"><a href="#" class="btn-link btn-link1"
                                                             data-type="submit">OK</a>
                                        </div>
                                    </fieldset>
                                </form>
                                <a href="#" class="btn-link btn-link2">unsubscribe</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 fleft">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 footercol2">
                                <p class="title9">copyright</p>

                                <p class="footerpriv">&copy; <span id="copyright-year"></span>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="ere">Web Design <a href="http://www.myfreecsstemplates.com" class="sdsr">Free CSS Templates</a>
    </div>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    </body>

    </html>
<?php include 'php/closedb.php'; ?>