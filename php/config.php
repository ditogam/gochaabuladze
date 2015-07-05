<?php
// This is an example of config.php
$servername = "ditogam.ctanjghmkbii.us";
$username = 'root';
$password = 'Dito1980';
$dbname = 'dito';
$lang_id_param_prefix = "di_";
$lang_id_imgparam_prefix = "ii_";
$all_dictionary = array();
$date_format = 'dd-mm-yy';

function refValues($arr)
{
    if (strnatcmp(phpversion(), '5.3') >= 0) //Reference is required for PHP 5.3+
    {
        $refs = array();
        foreach ($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}

function javascript_escape($str)
{
    $new_str = '';

    $str_len = strlen($str);
    for ($i = 0; $i < $str_len; $i++) {
        $new_str .= '\\x' . dechex(ord(substr($str, $i, 1)));
    }

    return $new_str;
}


function addLink($img, $ref, $text)
{
    if (isset($ref) and strlen(trim($ref)) > 0) {
        $ret = "<a href='" . trim($ref) . "' target='_blank'>";
        if (isset($text) and strlen(trim($text)) > 0) {
            $ret .= $text;
        }
        if (isset($img) and strlen(trim($img)) > 0) {
            $ret .= '<img src="' . trim($img) . '"/>';
        }
        $ret .= '</a>';
        return $ret;
    }

}

?>