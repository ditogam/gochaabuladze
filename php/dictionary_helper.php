<?php
/**
 * Created by PhpStorm.
 * User: dimitri.gamkrelidze
 * Date: 4/27/2015
 * Time: 2:44 PM
 */
include 'config.php';
include 'opendb.php';
$operation = $_REQUEST['op_id'];
$key_name = $_REQUEST['key_name'];
if (is_null($operation) || $operation == 0) {
    $sql = "SELECT lng_id,value FROM dictionary where key_name ='" . $key_name . "'";
    $result = $conn->query($sql);
    $rows = array();
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    $data = array('result' => $rows);
    print json_encode($data);
}


if ($operation == 1) {
    $stmt = $conn->prepare("delete from dictionary  where key_name=?");
    $stmt->bind_param("s", $key_name);
    if ($stmt->execute() == false) {
        echo 'First query failed: ' . $conn->error . '<br>';
    }
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO dictionary(key_name,lng_id,value) VALUES (?,?,?)");
    foreach ($_GET as $key => $value) {
        if (0 === strpos($key, $lang_id_param_prefix)) {
            $val = $value;
            $len = strlen($key);
            $lang_id = substr($key, strlen($lang_id_param_prefix), $len - strlen($lang_id_param_prefix));
            $stmt->bind_param("sis", $key_name, $lang_id, $val);
            if ($stmt->execute() == false) {
            }
        }
    }
    $stmt->close();

    $conn->commit();
    echo $key_name;
}

?>
<?php include 'closedb.php'; ?>