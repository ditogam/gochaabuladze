<?
$sql = "select `youtube_channel`,`youtube_search`,`facebook_account`,`facebook_group`,`tweeter_account`,`email` from `configuration` c  ";
$result = $conn->query($sql);

$social_row = null;
if ($result->num_rows > 0) {
    // output data of each row
    while ($row_val = $result->fetch_assoc()) {
        $social_row = $row_val;
    }
}
$result->close();

?>