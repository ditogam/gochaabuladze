<h2><?= $dictionary['about'] ?></h2>
<table>
    <tr>
        <?
        $sql = "select about_image,(select `value` from `texts` t where t.`id`=c.`about_id` and t.`lang_id`=" . $current_lang_id . ") abt from `configuration` c  ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                ?>
                <td><img class="editor" style="width: 300px" src="<?= $row['about_image'] ?>" alt=""></td>
                <td valign="top"><?= $row["abt"] ?></td>
            <?
            }
        }
        $result->close();
        ?>
    </tr>
</table>