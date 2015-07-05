<?php

/**
 * Created by PhpStorm.
 * User: dito
 * Date: 5/8/15
 * Time: 12:13
 */
class CTable
{
    public $table_name;
    public $description;
    public $primary_field;
    public $description_fields;
    public $sort_fields;
    public $common_fields;
    public $language_fields;
    public $row_template;
    public $container_prefix;
    public $container_suffix;
    public $one_row;

    public function initFromDB($conn, $table_name)
    {
        $sql = "select * from table_cont where table_name='" . $table_name . "'";
        $description = '';
        $primary_field = '';
        $description_fields = array();
        $sort_fields = '';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $description = $row["description"];
                $primary_field = $row["primary_field"];
                $description_fields = split(',', $row["description_fields"]);
                $sort_fields = $row["sort_fields"];
                $row_template = $row["row_template"];
                $container_prefix = $row["container_prefix"];
                $container_suffix = $row["container_suffix"];
                $one_row = $row["one_row"];
            }
        }

        $result->close();
        $sql = "select * from table_descrs where table_name='" . $table_name . "'";

        $result = $conn->query($sql);
        $common_fields = array();

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $obj = (object)array('id_field_name' => $row["id_field_name"], 'title' => $row["title"], 'readonly' => $row["readonly"],
                    'width' => $row["width"], 'height' => $row["height"], 'type' => $row["type"], 'goto_url' => $row["goto_url"], 'list_values' => $row["list_values"], 'prepare_str' => $row["prepare_str"]);
                array_push($common_fields, $obj);
                $rows[] = $row;
            }
        }
        $result->close();

        $sql = "select * from language_fields where table_name='" . $table_name . "' order by sort_order";
        $result = $conn->query($sql);
        $language_fields = array();

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $obj = (object)array('field_name' => $row["field_name"], 'real_field_name' => $row["real_field_name"], 'title' => addslashes($row["title"]));
                array_push($language_fields, $obj);
            }
        }
        $result->close();

        $this->init($table_name, $description, $primary_field, $description_fields, $common_fields, $language_fields, $sort_fields, $row_template, $container_suffix, $container_prefix, $one_row);
    }

    public function init($table_name, $description, $primary_field, $description_fields, $common_fields, $language_fields,
                         $sort_fields, $row_template, $container_suffix, $container_prefix, $one_row)
    {
        $this->table_name = $table_name;
        $this->description = $description;
        $this->primary_field = $primary_field;
        $this->description_fields = $description_fields;
        $this->common_fields = $common_fields;
        $this->language_fields = $language_fields;
        $this->sort_fields = $sort_fields;
        $this->row_template = $row_template;
        $this->container_suffix = $container_suffix;
        $this->container_prefix = $container_prefix;
        $this->one_row = $one_row;
    }


    public function generateInsertOrUpdate($data)
    {
        $id = $data[$this->primary_field];
        if (isset($id) and $id > 0)
            $this->generateUpdate($data);
        else
            $this->generateInsert($data);
    }

    public function generateUpdate($data)
    {
        global $conn;
        $fields = '';
        $prepare_str = '';
        $calendar_field_value = null;
        $bindParam = new BindParam();

        foreach ($this->common_fields as $cf) {
            if ($cf->type == 'calendar' or $cf->id_field_name == $this->primary_field) {
                if ($cf->type == 'calendar') {
                    $calendar_field_value = $data[$cf->id_field_name];
                }
                if ($cf->id_field_name == $this->primary_field) {
                    $primary_field = $cf;
                }
                continue;
            }
            if (strlen($fields) > 0) {
                $fields .= ',';
            }
            $fields .= '`' . $cf->id_field_name . '`=?';
            $prepare_str .= $cf->prepare_str;
            $bindParam->add($cf->prepare_str, $data[$cf->id_field_name]);
        }
        $language_key_val = array();
        foreach ($this->language_fields as $cf) {
            if (isset($data[$cf->real_field_name])) {
            }
            $language_key_val[$cf->field_name] = $data[$cf->real_field_name];
        }
        $sql = "update `" . $this->table_name . "` set" . $fields . " where `" . $this->primary_field . "`=?";
        $id = $data[$this->primary_field];
        $bindParam->add($primary_field->prepare_str, $id);
        $stmt = $conn->prepare($sql);
        call_user_func_array(array($stmt, 'bind_param'), $bindParam->get());
        if ($stmt->execute() == false) {
            echo 'First query failed: ' . $conn->error . '<br>';
            return;
        }
        $stmt->close();
        $languages = $data["_language_items_"];
        if (isset($languages) and count($languages) > 0){
        foreach ($languages as $lang_id => $value) {
            foreach ($value as $val_type => $val) {
                CDBHelper::insertText($language_key_val[$val_type], $lang_id, $val);
            }
        }
    }
        if (isset($calendar_field_value) and strlen($calendar_field_value) > 0) {
            $elements = explode(',', $calendar_field_value);
            CDBHelper::insertCalendar($id, $elements);
        }

        echo $id;
    }

    public function generateInsert($data)
    {
        global $conn;
        $fields = '';
        $params = '';
        $bindParam = new BindParam();
        $calendar_field_value = null;
        foreach ($this->common_fields as $cf) {
            if ($cf->type == 'calendar') {
                $calendar_field_value = $data[$cf->id_field_name];
                continue;
            }
            if ($cf->id_field_name == $this->primary_field)
                continue;
            if (strlen($fields) > 0) {
                $fields .= ',';
                $params .= ',';
            }
            $fields .= '`' . $cf->id_field_name . '`';
            $params .= '?';
            $bindParam->add($cf->prepare_str, $data[$cf->id_field_name]);
        }
        $language_key_val = array();
        foreach ($this->language_fields as $cf) {
            if (strlen($fields) > 0) {
                $fields .= ',';
                $params .= ',';
            }
            $fields .= '`' . $cf->real_field_name . '`';
            $params .= '?';
            $new_id = CTable::getNewId();
            $language_key_val[$cf->field_name] = $new_id;
            $bindParam->add('i', $new_id);
        }
        $sql = "insert into `" . $this->table_name . "` (" . $fields . ") values (" . $params . ")";

        $stmt = $conn->prepare($sql);
        call_user_func_array(array($stmt, 'bind_param'), $bindParam->get());
        if ($stmt->execute() == false) {
            echo 'First query failed: ' . $conn->error . '<br>';
            return;
        }

        $id = $stmt->insert_id;
        $stmt->close();
        $languages = $data["_language_items_"];
        foreach ($languages as $lang_id => $value) {
            foreach ($value as $val_type => $val) {
                CDBHelper::insertText($language_key_val[$val_type], $lang_id, $val);
            }
        }
        if (isset($calendar_field_value) and strlen($calendar_field_value) > 0) {
            $elements = explode(',', $calendar_field_value);
            CDBHelper::insertCalendar($id, $elements);
        }
        echo $id;
    }

    public static function getNewId()
    {
        global $conn;
        $sql = "insert into `id_table` (`v`) values(?)";
        $stmt = $conn->prepare($sql);
        $v = '';
        $stmt->bind_param("s", $v);
        if ($stmt->execute() == false) {
            echo 'First query failed: ' . $conn->error . '<br>';
        }
        $stmt->close();
        return $conn->insert_id;
    }

    public function getList($id)
    {
        global $conn;
        $sql = $this->getListSQL();
        $result = $conn->query($sql);
        $rows = array();
        $selected_item = null;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $obj = (object)array('id_val' => $row[$this->primary_field], 'title' => $row[$this->description_fields[0]]);
                if (is_null($selected_item))
                    $selected_item = $obj;
                if ($obj->id_val == $id) {
                    $obj->data = $this->getDataReal($id);
                    $selected_item = $obj;
                }
                array_push($rows, $obj);
            }
        }
        if (isset($selected_item) and is_null($selected_item->data)) {
            $selected_item->data = $this->getDataReal($selected_item->id_val);
        }
        $result->close();
        $rows = array('result' => $rows);
        return $rows;
    }

    public function getListSQL()
    {
        $sql = "select " . $this->primary_field . " ";
        foreach ($this->description_fields as $df)
            $sql .= ',' . $df;
        $sql .= ' from ' . $this->table_name;
        $sql .= ' order by ';
        if (isset($this->sort_fields) && strlen($this->sort_fields) > 0)
            $sql .= $this->sort_fields;
        else
            $sql .= $this->primary_field;
        return $sql;
    }

    public function getDataReal($id)
    {
        global $conn;
        if(is_object($id)){

            $my_language=$id->lang_id;
            $id=$id->id;
        }
        $fields = '';
        foreach ($this->common_fields as $cf) {

            if (strlen($fields) > 0) {
                $fields .= ',';
            }
            if ($cf->type == 'calendar') {
                $fields .= "(SELECT GROUP_CONCAT( (c.`event_date`*1000) SEPARATOR ',') FROM `calendar` c where `event_id`=tbl.`" . $this->primary_field . "` GROUP BY `event_id`)";
            }
            $fields .= '`' . $cf->id_field_name . '`';
        }

        foreach ($this->language_fields as $cf) {
            if (strlen($fields) > 0) {
                $fields .= ',';
            }
            $fields .= '`' . $cf->real_field_name . '`';
        }
        $sql = 'select ' . $fields . ' from `' . $this->table_name . "` tbl where `" . $this->primary_field . "`=" . $id;

        $stmt = $conn->query($sql);
        if ($stmt->num_rows > 0) {
            $data = $stmt->fetch_assoc();
        }
        $stmt->close();
        $lids = '';
        $language_key_val = array();
        foreach ($this->language_fields as $lf) {
            if (strlen($lids) > 0) {
                $lids .= ',';
            }
            $key = $lf->real_field_name;
            $val = $data[$key];
            if (isset($val) and !empty($val)) {
                $lids .= '' . $val;
                $language_key_val[$val] = $lf->field_name;
            }
        }
        if (strlen(trim($lids)) > 0) {
            $sql = 'SELECT `id`,`lang_id`,`value` FROM `texts` WHERE `id` in (' . $lids . ')';
            if(isset($my_language)){
                $sql.=' and `lang_id`='.$my_language;
            }
            $stmt = $conn->query($sql);
            $languages = array();
            if ($stmt->num_rows > 0) {
                while ($row = $stmt->fetch_assoc()) {
                    $lang_id = $row["lang_id"];
                    $_id = $row["id"];
                    $value = $row["value"];
                    if (is_null($languages[$lang_id]) or !isset($languages[$lang_id])) {
                        $languages[$lang_id] = array();
                    }
                    $key = $language_key_val[$_id];
                    if (isset($key) and !empty($key)) {
                        $languages[$lang_id][$key] = $value;
                    }
                }
            }
            $stmt->close();
            $data["_language_items_"] = $languages;
        }
        return $data;
    }

    public function getData($id)
    {
        $data = $this->getDataReal($id);
        $data = array('result' => $data);
        print json_encode($data);
    }
} 