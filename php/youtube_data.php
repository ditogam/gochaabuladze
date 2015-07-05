<?php
/**
 * Created by PhpStorm.
 * User: dimitri.gamkrelidze
 * Date: 5/21/2015
 * Time: 5:51 PM
 */
$video_id=$_REQUEST['video_id'];
$url = "http://www.youtube.com/watch?v=".$video_id;
$youtube = "http://www.youtube.com/oembed?url=".urlencode($url)."&format=json";
$json =  json_decode(file_get_contents($youtube));
$data = array('result' => $json);
print json_encode($data);
?>