<?php
/**
 * Created by PhpStorm.
 * User: dimitri.gamkrelidze
 * Date: 5/21/2015
 * Time: 5:51 PM
 */
$image_path=$_REQUEST['image_path'];
$dir_name=dirname($image_path);
$dir_path='../'.$dir_name;

$exts = array('jpg','png','gif');
$files = array();
if($handle = opendir($dir_path)) {
    while(false !== ($file = readdir($handle))) {
        $extension = strtolower(substr(strrchr($file,'.'),1));
        if($extension && in_array($extension,$exts)) {
            $files[] = $dir_name."/".$file;
        }
    }
    closedir($handle);
}
$data = array('result' => $files);
print json_encode($data);
?>