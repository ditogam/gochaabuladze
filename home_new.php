<section id="main-slider" class="no-margin">
    <div class="carousel slide">
        <ol class="carousel-indicators">
            <?
            $all_cameras = array();
            $sql = "select fi.img_url,fi.txt FROM   featured f JOIN featured_images fi ON fi.featured_id = f.id AND fi.lang_id = " . $current_lang_id . "  order by sort_order";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $l_obj = (object)array('img_url' => $row["img_url"], 'txt' => $row["txt"]);
                    array_push($all_cameras, $l_obj);
//                echo CCamera::generateCamera($row["img_url"], $row["txt"]);
                }
            }
            $result->close();
            $ind = 0;
            $active_class = 'class="active"';
            foreach ($all_cameras as $obj) {
                ?>
                <li data-target="#main-slider" data-slide-to="<?= $ind ?>" <?=$active_class?></li>
            <?$ind = $ind + 1; $active_class = '';} ?>

        </ol>
        <div class="carousel-inner">
            <?
            $active_class = 'active';
            foreach ($all_cameras as $obj) {
                ?>

                <div class="item <?= $active_class ?>"
                     style="background-image: url(<?= $obj->img_url ?>)">
                    <?if (isset($obj->txt)) {
                        ?>
                        <div class="container">
                            <div class="row slide-margin">
                                <div class="col-sm-6">
                                    <div class="carousel-content">
                                        <h1 class="animation animated-item-1"><?= $obj->txt ?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?
                    }?>

                </div>


                <? $active_class = '';
            } ?>
        </div>
    </div>
    <!--/.carousel-->
    <a class="prev hidden-xs" href="#main-slider" data-slide="prev">
        <i class="fa fa-chevron-left"></i>
    </a>
    <a class="next hidden-xs" href="#main-slider" data-slide="next">
        <i class="fa fa-chevron-right"></i>
    </a>
</section>


