<meta charset="UTF-8">
<title>Gocha Abuladze</title>
<meta charset="utf-8">

<script src="js/jquery.min.js"></script>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<script src="js/jquery-migrate-1.2.1.js"></script>
<link rel="stylesheet" href="css/camera.css">
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/touchTouch.css">
<link rel="stylesheet" href="css/jquery-ui.min.css">


<script src='js/camera.js'></script>
<script type="text/javascript" src="js/datepicker.js"></script>
<script type="text/javascript" src="js/handlebars.js"></script>
<script type="text/javascript" src="js/modernizr-1.5.min.js"></script>
<script type="text/javascript" src="js/jquery.easing-sooper.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/jquery.sooperfish.js"></script>
<script type="text/javascript" src="js/touchTouch.jquery.js"></script>
<script src="js/global.js"></script>
<script type="text/javascript" src="js/jquery.bpopup.min.js"></script>
<script type="text/javascript" src="js/readmore.min.js"></script>
<meta property="og:locale" content="en_US"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="<?= $dictionary['name'] ?>"/>
<meta property="og:description" content="<?= $dictionary['site_description'] ?>"/>
<meta property="og:url" content="<?= rel2abs($_SERVER['REQUEST_URI']) ?>"/>
<meta property="og:site_name" content="<?= $dictionary['name'] ?>"/>

<?
$sql = "select about_image from `configuration` c  ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        ?>
        <meta property="og:image" content="<?= rel2abs($row['about_image']) ?>"/>
    <?
    }
}
$result->close();
?>


