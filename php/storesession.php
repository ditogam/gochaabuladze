<?php
session_start();
$_SESSION['lang_id'] = $_REQUEST['lang_id'];
echo $_SESSION['lang_id'];
?>