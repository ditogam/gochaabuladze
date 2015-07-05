<?php

/**
 * Created by PhpStorm.
 * User: dimitri.gamkrelidze
 * Date: 5/7/2015
 * Time: 11:47 AM
 */
class CEvent
{
    protected $event_id;
    protected $event_desc;
    protected $main_picture;
    protected $title;
    protected $cast;
    protected $composers;
    protected $event_type;
    protected $address;
    protected $event_url;
    protected $map_location;
    protected $search_keywords;
    protected $event_text;
    protected $pictures;
    protected $all_dictionary;


    public function __construct($all_dictionary, $event_id,
                                $event_desc,
                                $main_picture,
                                $title,
                                $cast,
                                $composers,
                                $event_type,
                                $address,
                                $event_url,
                                $map_location,
                                $search_keywords,
                                $event_text,
                                $pictures,
                                $buy_tickets_url)
    {


        $this->all_dictionary = $all_dictionary;
        $this->event_id = $event_id;
        $this->event_desc = $event_desc;
        $this->main_picture = $main_picture;
        $this->title = $title;
        $this->cast = $cast;
        $this->composers = $composers;
        $this->event_type = $event_type;
        $this->address = $address;
        $this->event_url = $event_url;
        $this->map_location = $map_location;
        $this->search_keywords = $search_keywords;
        $this->event_text = $event_text;
        $this->pictures = $pictures;
        $this->buy_tickets_url = $buy_tickets_url;
    }

    public function generateHtml($production, $language_id)
    {


        $new_line = '
        ';
        $tbl = '<div class="row_2">' . $new_line;
        $tbl .= '    <div class="container"><div class="row">' . $new_line;
        $tbl .= '       <div class="col-lg-12 col-md-12 col-sm-12">' . $new_line;
        if ($production or (isset($this->main_picture) and !empty($this->main_picture)))
            $tbl .= '       <figure>' . $this->wrap_editor($production, '<img class="editor" src="' . $this->main_picture . '" alt="">') . ' </figure>' . $new_line;
        $tbl .= '                <div class="textinforow2">' . $new_line;
        $tbl .= '                    <p class="title4">' . $this->title . '</p>' . $new_line;
        $tbl .= $this->addDescription('                    ', 'composers', $this->composers, $language_id);
        $tbl .= $this->addDescription('                    ', 'cast', $this->cast, $language_id);
        $tbl .= $this->addDescription('                    ', 'event_type', $this->all_dictionary[$language_id][$this->event_type], $language_id);
        $tbl .= $this->addDescription('                    ', 'address', $this->address, $language_id);
        $tbl .= '                    <p class="title6">' . $this->event_text . '</p>' . $new_line;
        $tbl .= '                    <p style="horiz-align: right">' . $this->addAhref('site', $this->event_url, $language_id) . $this->addAhref('buy_tickets', $this->buy_tickets_url, $language_id) . $this->addAhref('map', $this->map_location, $language_id) . '</p>' . $new_line;
        $tbl .= '                </div>' . $new_line;
        $tbl .= '            </div>' . $new_line;
        $tbl .= '        </div>' . $new_line;
        $tbl .= '    </div></div>' . $new_line;
        $tbl .= '</div>';
        return $tbl;
    }

    private function wrap_editor($production, $txt)
    {
        if (!$production)
            return '<div class="he-wrap tpl3">' . $txt . '<div class="he-view"><div class="info-bottom a0" data-animate="fadeInUp">HoverEx </div></div></div>';
        else
            return $txt;
    }

    private function addDescription($prefix, $keyName, $txt, $language_id)
    {

        if (isset($txt) and !empty($txt)) {
            $new_line = '
        ';
            return $prefix . '<b>' . $this->all_dictionary[$language_id][$keyName] . ':&nbsp;</b>' . $txt . '&nbsp;<br>' . $new_line;
        }
        return '';
    }

    private function addAhref($keyName, $txt, $language_id)
    {
        if (isset($txt) and !empty($txt)) {
            return '<a href=' . $txt . ' target="_blank">' . $this->all_dictionary[$language_id][$keyName] . '&nbsp;</a>';
        }
        return '';
    }
}
