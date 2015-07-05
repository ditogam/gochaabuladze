<?php
/**
 * Created by PhpStorm.
 * User: dimitri.gamkrelidze
 * Date: 6/26/2015
 * Time: 12:58 PM
 */


function getBaseUrl()
{
    // output: /myproject/index.php
    $currentPath = $_SERVER['PHP_SELF'];

    // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index )
    $pathInfo = pathinfo($currentPath);

    // output: localhost
    $hostName = $_SERVER['HTTP_HOST'];

    // output: http://
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

    // return: http://localhost/myproject/
    return $protocol.$hostName.$pathInfo['dirname'];
}

function my_base_url($atRoot = TRUE, $atCore = FALSE, $parse = FALSE)
{
    if (isset($_SERVER['HTTP_HOST'])) {
        $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];
        $dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
        $core = $core[0];

        $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
        $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
        $base_url = sprintf($tmplt, $http, $hostname, $end);
    } else $base_url = 'http://localhost/';

    if ($parse) {
        $base_url = parse_url($base_url);
        if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
    }

    return $base_url;
}

function rel2abs($url)
{
    $page = my_base_url();
    // Plug-in 21: Relative To Absolute URL
    //
    // This plug-in accepts the absolute URL of a web page
    // and a link featured within that page. The link is then
    // turned into an absolute URL which can be independently
    // accessed. Only applies to http:// URLs. The arguments
    // required are:
    //
    //    $page: The web page containing the URL
    //    $url:  The URL to convert to absolute

    if (substr($page, 0, 7) != "http://") return $url;

    $parse = parse_url($page);
    $root = $parse['scheme'] . "://" . $parse['host'];
    $p = strrpos(substr($page, 7), '/');

    if ($p) $base = substr($page, 0, $p + 8);
    else $base = "$page/";

    if (substr($url, 0, 1) == '/') $url = $root . $url;
    elseif (substr($url, 0, 7) != "http://") $url = $base . $url;

    return $url;
}
