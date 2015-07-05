<?php

/**
 * Created by PhpStorm.
 * User: dito
 * Date: 5/9/15
 * Time: 12:49
 */
class CDBHelper
{
    public static function operateOnTable($operation, $table_name, $data)
    {
        global $conn;
        try {

        $ctable = new CTable();
        $ctable->initFromDB($conn, $table_name);
        if (is_null($operation) or $operation == 0) {
            $ctable->getData($data);
        }
        if ($operation == 1)
            $ctable->generateInsertOrUpdate($data);
        if ($operation == 2)
            print json_encode($ctable->getList($data));
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

    }

    public static function insertText($id, $lang_id, $value)
    {
        global $conn;

        if (isset($value) and !empty($value)) {
            $stmt = $conn->prepare("INSERT INTO texts(id,lang_id,value) VALUES (?,?,?) ON DUPLICATE KEY UPDATE value=?");
            $stmt->bind_param('iiss', $id, $lang_id, stripslashes($value), stripslashes($value));
            if ($stmt->execute() == false) {
                echo 'insertText: ' . $conn->error . '<br>';
            }
            $stmt->close();
        }
    }

    public static function insertCalendar($id, $values)
    {
        global $conn;

        if (isset($values) and !empty($values)) {
            $stmt = $conn->prepare('delete from calendar where event_id=?');
            $stmt->bind_param('i', $id);
            if ($stmt->execute() == false) {
                echo 'insertCalendar delete: ' . $conn->error . '<br>';
            }
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO calendar(event_id,event_date) VALUES (?,?) ");

            for ($i = 0; $i < count($values); $i++) {
                if (isset($values[$i]) and !empty($values[$i])) {
                    $val=intval($values[$i]) ;
                    $val=$val/1000;

                   $stmt->bind_param('ii', $id,$val );
                    if ($stmt->execute() == false) {
                        echo 'insertCalendar: ' . $conn->error . '<br>';
                    }
                }
            }
            $stmt->close();
        }
    }

}


/*
 *
 truncate table `events` ;
 truncate table `texts` ;

 truncate table `calendar` ;
 truncate table id_table;

 */