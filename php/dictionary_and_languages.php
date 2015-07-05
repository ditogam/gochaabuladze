<?php

$langs = array();
$all_langs = array();
$current_lang = NULL;
$current_lang_id = $_SESSION["lang_id"];
$sql = "SELECT lng_id, lng_name,short_name,flag_url,`locale_name` FROM `languages`";
$all_dictionary = array();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $l_obj = (object)array('lng_id' => $row["lng_id"], 'lng_name' => $row["lng_name"], 'short_name' => $row["short_name"], 'flag_url' => $row["flag_url"], 'locale_name' => $row["locale_name"]);

        if (is_null($current_lang_id) or $current_lang_id == $row["lng_id"]) {
            $current_lang = $l_obj;
            $current_lang_id = $row["lng_id"];
        } else
            array_push($langs, $l_obj);
        array_push($all_langs, $l_obj);
//        echo "id: " . $row["lng_id"]. " - Name: " . $row["lng_name"].  "<br>";
    }
}

$_SESSION["lang_id"] = $current_lang_id;
$dictionary = array();
$dictionary_array = array();

$sql = "SELECT lng_id,key_name,value FROM dictionary order by 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $d_obj = (object)array('key_name' => $row["key_name"], 'value' => $row["value"]);
        if ($row["lng_id"] == $current_lang_id) {
            $dictionary[$row["key_name"]] = $row["value"];
            array_push($dictionary_array, $d_obj);
        }
        $k_obj = $all_dictionary[$row["lng_id"]];
        if (is_null($k_obj)) {
            $all_dictionary[$row["lng_id"]] = array();
        }
        $all_dictionary[$row["lng_id"]][$row["key_name"]] = $row["value"];
    }
}
function getFromAllDictionary($lng_id, $key_name)
{
    global $all_dictionary;
    $k_obj = $all_dictionary[$lng_id];
    if(is_null($k_obj))
        return $k_obj;
    return $k_obj[$key_name];
}

function getFromCurrentDictionary($lng_id, $key_name)
{
    global $dictionary_array;
    return $dictionary_array[$key_name];
}

?>