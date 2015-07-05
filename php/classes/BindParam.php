<?php

/**
 * Created by PhpStorm.
 * User: dito
 * Date: 5/9/15
 * Time: 15:26
 */
class BindParam
{
    private $values = array(), $types = '';

    public function add($type, &$value)
    {
        if($type=='s')
            $value=&stripslashes($value);
        $this->values[] = $value;
        $this->types .= $type;
    }

    public function get()
    {
//        $func_params = array_unshift($this->values, $this->type);
//        return $func_params;
        return array_merge(array($this->types), $this->values);
    }


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
}

?>