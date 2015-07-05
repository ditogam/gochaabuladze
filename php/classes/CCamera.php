<?php

/**
 * Created by PhpStorm.
 * User: dimitri.gamkrelidze
 * Date: 5/6/2015
 * Time: 12:31 PM
 */
class CCamera
{
    static public function generateCamera($img_url, $txt)
    {
        $result = '<div data-src="' . $img_url . '">';

        if (isset($txt)) {
            $result .= '    <div class="camera_caption fadeIn">';
            $result .= '        <div class="camera_caption_bg">';
            $result .= '            <div class="title1">' . $txt . '</div>';
            $result .= '        </div>';
            $result .= '    </div>';
        }

        $result .= '</div>';
        return $result;
    }

    static public function generateCameraold($img_url, $txt)
    {
        $result = '<div data-src="' . $img_url . '">';

        if (isset($txt)) {
            $result .= '    <div class="camera_caption fadeIn">';
            $result .= '        <div class="camera_caption_bg">';
            $result .= '            <div class="title1">' . $txt . '</div>';
            $result .= '        </div>';
            $result .= '    </div>';
        }

        $result .= '</div>';
        return $result;
    }
} 