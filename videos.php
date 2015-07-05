<h2><?= $dictionary['videos'] ?></h2>

<?
$sql = "select `youtube_channel`,`youtube_search`,`youtube_videos` from `configuration` c  ";
$result = $conn->query($sql);
$youtube_videos = null;
$youtube_search = null;
$youtube_channel = null;
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $youtube_videos = $row["youtube_videos"];
        $youtube_channel = $row["youtube_channel"];
        $youtube_search = $row["youtube_search"];
    }
}
$result->close();

$youtube_videos_json = json_decode($youtube_videos);
?>
<table width="100%">
    <tr>
        <?
        if (isset($youtube_channel)) {
            ?>
            <td>
                <h4><a href="<?= $youtube_channel ?>" target="_blank"><?= $dictionary['youtube_channel'] ?> </a></h4>
            </td>
        <?
        }
        if (isset($youtube_search)) {
            ?>
            <td>
                <h4><a href="<?= $youtube_search ?>" target="_blank"><?= $dictionary['other_youtube_videos'] ?></a></h4>
            </td>
        <?
        }?>
    </tr>
</table>

<div id="main_videos" style="display:none;margin:0 auto;" class="html5gallery" data-skin="mediapage"
     data-autoplayvideo="false" data-onthumbclick="jumpToTop" data-responsive="true" data-resizemode="fill"
     data-html5player="true" data-autoslide="true" data-autoplayvideo="false" data-width="800" data-height="450"
     data-effect="fade" data-shownumbering="true" data-numberingformat="%NUM of %TOTAL - "
     data-googleanalyticsaccount="UA-29319282-1">
</div>


<script>
    var pictures =<?=json_encode($youtube_videos_json)?>;
    var html = '';
    pictures.result.forEach(function (entry) {
        var append = '<a href="http://www.youtube.com/embed/' + Handlebars.Utils.escapeExpression(entry.vid_id) + '">' + Handlebars.Utils.escapeExpression(entry.vid_title) + '<img src="http://img.youtube.com/vi/' + Handlebars.Utils.escapeExpression(entry.vid_id) + '/2.jpg" alt="' + Handlebars.Utils.escapeExpression(entry.vid_title) + ' title="' + Handlebars.Utils.escapeExpression(entry.vid_title) + '"></a>';
        html += (append);
    });
    $(main_videos).html(html);
</script>
<script type="text/javascript" src="js/html5gallery/html5gallery.js"></script>

