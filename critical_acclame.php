<h2><?= $dictionary['critical_acclame'] ?></h2>
<div class="press-page-contain" style="background: #f3f3f3;">
    <?
    $sql = "select acclame_link,(select `value` from `texts` t where t.`id`=c.`author_id` and t.`lang_id`=" . $current_lang_id . ") author,(select `value` from `texts` t where t.`id`=c.`text_id` and t.`lang_id`=" . $current_lang_id . ") text from `critical_acclame` c  ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $autor = $row['author'];
            $acclame_link = $row['acclame_link'];
            if (isset($autor)) {
                if (isset($acclame_link)) {
                    $autor = '<a href="' . $acclame_link . '" target="_blank">' . $autor . '</a>';
                }
            }
            ?>
            <div class="post-866 post type-post status-publish format-quote hentry category-critical-acclaim clearfix" >
                <div class="entry-quote ">
                    <blockquote><p>“<?= $row['text'] ?>”</p>
                    </blockquote>
                    <span class="quote-author" style="color: #000;">- <?= $autor ?></span>
                </div>
            </div>
        <?
        }
    }
    $result->close();
    ?>
</div>