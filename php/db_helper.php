<?php
include 'config.php';
include 'opendb.php';
include 'classes/CDBHelper.php';
include 'classes/CTable.php';
include 'classes/BindParam.php';
$operation = $_REQUEST['op_id'];
$tablename = $_REQUEST['tablename'];
$values = $_REQUEST['values'];
/**
 * Created by PhpStorm.
 * User: dito
 * Date: 5/9/15
 * Time: 12:53
 */
CDBHelper::operateOnTable($operation, $tablename,$values);
include 'closedb.php';
?>