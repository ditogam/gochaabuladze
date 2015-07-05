<?php
include 'config.php';
include 'opendb.php';
include 'classes/CDBHelper.php';
include 'classes/CTable.php';
include 'classes/BindParam.php';
$event_id = $_REQUEST['event_id'];
$lang_id = $_REQUEST['lang_id'];
if (is_null(lang_id))
    $lang_id = 1;

function getMaxEventID($criteria)
{
    global $conn;
    $event_id = null;
    $sql = "select max(e.`eventid`) eventid from `events` e ";
    if (!is_null($criteria) and strlen(trim($criteria)) > 0) {
        $sql .= ' ' . $criteria;
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $event_id = $row['eventid'];
        }
    }
    echo $conn->error;
    $result->close();
    return $event_id;
}

/**
 * Created by PhpStorm.
 * User: dito
 * Date: 5/9/15
 * Time: 12:53
 */
$CTable = new CTable();
$CTable->initFromDB($conn, 'events');
if (is_null($event_id) ) {
    $event_id = getMaxEventID(null);
}
$result = new stdClass();
if (isset($event_id)) {
    $crit = new stdClass();
    $crit->id = $event_id;
    $crit->lang_id = $lang_id;
    $result->data = $CTable->getDataReal($crit);
    $result->next_rec = getMaxEventID(' where e.`eventid`<' . $event_id);
    $result->prev_rec = getMaxEventID(' where e.`eventid`>' . $event_id);
    $result->current_rec=$event_id;
}
print json_encode($result);
include 'closedb.php';
?>