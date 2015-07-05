<div class="slider">
    <div class="camera_wrap">
        <?
        $sql = "select fi.img_url,fi.txt FROM   featured f JOIN featured_images fi ON fi.featured_id = f.id AND fi.lang_id = " . $current_lang_id . "  order by sort_order";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo CCamera::generateCamera($row["img_url"], $row["txt"]);
            }
        }
        $result->close();
        ?>

    </div>
    <hr class="line1">
</div>
