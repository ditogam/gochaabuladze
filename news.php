<h2><?= $dictionary['news'] ?></h2>
<table class="decorated">
    <tr>
        <th></th>
        <th></th>
    </tr>
    <?
    $sql = "select (select `value` from `texts` t where t.`id`=n.`title_id` and t.`lang_id`=" . $current_lang_id . ") title" . ",(select `value` from `texts` t where t.`id`=n.`text_id` and t.`lang_id`=" . $current_lang_id . ") text from `news` n order by id desc ";
    $result = $conn->query($sql);
    $youtube_videos = null;
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td style="color: #0489B1;font-size: 15px;"><strong><?= $row['title'] ?></strong></td>
                <td><?= $row['text'] ?></td>
            </tr>
        <?
        }
    }
    $result->close();

    ?>
</table>

