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
$id = $_REQUEST['id'];
$descr = $_REQUEST['descr'];
if (is_null($operation) || $operation == 0) {
    $sql = "SELECT descr,lang_id,img_url,txt FROM featured f left join featured_images fi on fi.featured_id=f.id where id=" . $id;
    $result = $conn->query($sql);
    $rows = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    $data = array('result' => $rows);
    print json_encode($data);
}


if ($operation == 1) {

    if (is_null($id) or $id < 1) {
        $sql = 'insert into featured (sort_order,descr) values((select max(f.sort_order)+1 from featured f), ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $descr);
        if ($stmt->execute() == false) {
            echo 'First query failed: ' . $conn->error . '<br>';
        }
        $stmt->close();
        $id = $conn->insert_id;
    } else {
        $sql = 'update featured set descr =  ? where id=?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $descr, $id);
        if ($stmt->execute() == false) {
            echo 'First query failed: ' . $conn->error . '<br>';
        }
        $stmt->close();
    }

    $stmt = $conn->prepare("delete from featured_images  where featured_id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute() == false) {
        echo 'First query failed: ' . $conn->error . '<br>';
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO featured_images(featured_id,lang_id,img_url,txt) VALUES (?,?,?,?)");
    foreach ($_GET as $key => $value) {
        if (0 === strpos($key, $lang_id_imgparam_prefix)) {
            $val = $value;
            $len = strlen($key);
            $lang_id = substr($key, strlen($lang_id_imgparam_prefix), $len - strlen($lang_id_imgparam_prefix));
            $val1 = $_GET[$lang_id_param_prefix . $lang_id];
            $stmt->bind_param("siss", $id, $lang_id, $val, $val1);
            if ($stmt->execute() == false) {
                echo 'First query failed: ' . $conn->error . '<br>';
            }
        }
    }
    $stmt->close();

    $conn->commit();
    echo $id;
}
if ($operation == 2) {
    $sql = "SELECT id,descr FROM `featured` order by sort_order";
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

?>
<?php include 'closedb.php'; ?>