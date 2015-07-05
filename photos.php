<h2><?= $dictionary['photos'] ?></h2>
<?
$sql = "select `image_files` from `configuration` c  ";
$result = $conn->query($sql);
$image_files = null;
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $image_files = $row["image_files"];
    }
}
$result->close();
$image_file_json = json_decode($image_files);
?>
<ul id="main_photoes" class="list2">
</ul>
<script>
    var pictures =<?=json_encode($image_file_json)?>;
    pictures.result.forEach(function (entry) {
        var append = '<li><figure><a href="' + Handlebars.Utils.escapeExpression(entry.url) + '" class="thumb"><img src="' + Handlebars.Utils.escapeExpression(entry.url) + '" alt=""><span></span><img class="zoom" src="img/zoom2.png" alt=""></a></figure></li>';
        append += '</li>';
        $(main_photoes).append(append);
    });
    try {
        $('.thumb').touchTouch();
    } catch (err) {//camera
    }
</script>

