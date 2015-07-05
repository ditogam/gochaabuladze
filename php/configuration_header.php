<?php
/**
 * Created by PhpStorm.
 * User: dimitri.gamkrelidze
 * Date: 4/27/2015
 * Time: 2:10 PM
 */
session_start();
$_SESSION['isLoggedIn'] = true;
include 'config.php';
include 'opendb.php';
include 'dictionary_and_languages.php';
?>
