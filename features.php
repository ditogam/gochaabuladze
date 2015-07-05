<?php
/**
 * Created by PhpStorm.
 * User: dimitri.gamkrelidze
 * Date: 4/27/2015
 * Time: 2:11 PM
 */
include 'php/configuration_header.php';

?>
    <head>
    <meta charset="utf-8">
    <script src="js/jquery.js"></script>
    <script src='js/tinymce/tinymce.min.js'></script>
    <link rel="stylesheet" href="css/camera.css">
    <link rel="stylesheet" href="css/displaypopup.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="fonts/font-awesome.css">
    <link rel="stylesheet" href="css/camera.css">
    <link rel="stylesheet" href="css/touchTouch.css">
    <link rel="stylesheet" href="css/tree.css">

    <script src="js/jquery-migrate-1.2.1.js"></script>
    <script src="js/touchTouch.jquery.js"></script>
    <script src="js/superfish.js"></script>
    <script src="js/jquery.mobilemenu.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/jquery.ui.totop.js"></script>
    <script src="js/jquery.touchSwipe.min.js"></script>
    <script src="js/jquery.equalheights.js"></script>
    <script src="js/sForm.js"></script>
    <script src="js/roxy.filebrowser.js"></script>
    <script src="js/php_file_tree_jquery.js" type="text/javascript"></script>

    <script src='js/camera.js'></script>
    <script src='js/moxiemanager/js/moxman.api.min.js'></script>
    <link type="text/css" rel="stylesheet" href="js/moxiemanager/skins/lightgray/skin.min.css"/>
    <script type="text/javascript">

        $(window).load(function () {
            tinymce.init({
                selector: "textarea",
                theme: "modern",
                mode : "textareas",
                force_br_newlines : false,
                force_p_newlines : false,
                forced_root_block : '',
                plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste moxiemanager textcolor"
                ],
                content_css: "css/content.css",
                external_plugins: {
                    "moxiemanager": "../moxiemanager/plugin.min.js"
                },
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                style_formats: [
                    {title: 'Bold text', inline: 'b'},
                    {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                    {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                    {title: 'Example 1', inline: 'span', classes: 'example1'},
                    {title: 'Example 2', inline: 'span', classes: 'example2'},
                    {title: 'Table styles'},
                    {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                ]
            });
        });
        var current_event = null;
        function clear_form() {
            current_event = null;
            feature_form.reset();
        }


        function add_if_not_exists(name, _descr) {
            var x = sfeatures;
            var i;
            for (i = 0; i < x.length; i++) {
                var txt = x.options[i].value;
                if (txt == name) {
                    x.options[i].text = _descr;
                    return;
                }
            }


            var option = document.createElement("option");
            option.value = name;
            option.text = _descr;
            x.add(option);
            x.selectedIndex = x.length - 1;
        }

        var IMG_URLS = ["uploads/2015/4/1.png", "uploads/2015/4/2.png"];
        var IMG_TXTS = ["test1", "test1"];
        function preview() {
            IMG_URLS = [];
            IMG_TXTS = [];
            <?
                foreach ($all_langs as $lng) {
                    echo '
                    ';
                    echo 'var _el_uid="' .$lang_id_imgparam_prefix.$lng->lng_id.'";
                    ';
                    echo 'var _el_id="' .$lang_id_param_prefix.$lng->lng_id.'";
                    ';
                    echo 'IMG_URLS.push(feature_form.elements[_el_uid].value);
                    ';
                    echo 'IMG_TXTS.push(tinyMCE.get(_el_id).getContent());
                    ';
                }
            ?>
            window.open("preview_window.html", "PreviewWindow", "toolbar=no, scrollbars=no, resizable=yes,  width=1200, height=800");
        }


        function browseFile(field_name) {
            moxman.browse({
                    fields: field_name,
                    view: 'thumbs',
                    relative_urls: true,
                    document_base_url: 'js/moxiemanager'

                }
            )
            ;
        }

        function feature_selected(sel) {
            var _id = sel.options[sel.selectedIndex].value;
            feature_form.hid.value = _id;
            var request = $.ajax({
                url: "php/feature_helper.php",
                method: "GET",
                data: {op_id: 0, id: _id},
                dataType: "json"
            });
            request.done(function (msg) {
                clear_form();
                feature_form.hid.value = _id;
                for (x in msg.result) {
                    var v = msg.result[x];
                    var _el_id = "<?=$lang_id_param_prefix?>" + v.lang_id;
                    var _el_uid = "<?=$lang_id_imgparam_prefix?>" + v.lang_id;
                    feature_form.elements[_el_uid].value = v.img_url;
                    tinyMCE.get(_el_id).setContent(v.txt);
                    feature_form.hid.value = _id;
                    feature_form.hdescr.value = v.descr;
                }
            });

            request.fail(function (jqXHR, textStatus) {
                clear_form();
                alert("Request failed: " + textStatus);
            });
        }

        function copy_items(lang_id) {
            var _el_uid = "<?=$lang_id_imgparam_prefix?>" + lang_id;
            var _el_id = "<?=$lang_id_param_prefix?>" + lang_id;
            current_event = [];
            current_event['<?=$lang_id_imgparam_prefix?>'] = (feature_form.elements[_el_uid].value);
            current_event['<?=$lang_id_param_prefix?>'] = (tinyMCE.get(_el_id).getContent());
        }

        function paste_items(lang_id) {
            if (!current_event || current_event == null) {
                alert('nothing to copy');
            }
            var _el_uid = "<?=$lang_id_imgparam_prefix?>" + lang_id;
            var _el_id = "<?=$lang_id_param_prefix?>" + lang_id;
            feature_form.elements[_el_uid].value = current_event['<?=$lang_id_imgparam_prefix?>'];
            tinyMCE.get(_el_id).setContent(current_event['<?=$lang_id_param_prefix?>']);
        }


        function save_features() {
            var _data = {};
            var _id = feature_form.hid.value;
            _data.op_id = 1;
            _data.id = _id;
            var _descr = feature_form.hdescr.value;
            _data.descr = _descr;
            var x = feature_form;
            var i;

            <?
                 foreach ($all_langs as $lng) {
                     echo '
                     ';
                     echo 'var _el_uid="' .$lang_id_imgparam_prefix.$lng->lng_id.'";
                     ';
                     echo 'var _el_id="' .$lang_id_param_prefix.$lng->lng_id.'";
                     ';
                     echo '_data[_el_uid]=(feature_form.elements[_el_uid].value);
                     ';
                     echo '_data[_el_id]=(tinyMCE.get(_el_id).getContent());
                     ';
                 }
             ?>


            var request = $.ajax({
                url: "php/feature_helper.php",
                method: "GET",
                data: _data,
                dataType: "html"
            });
            request.done(function (msg) {
                if (msg != parseInt(msg)) {
                    alert(msg);
                    return;
                }
                add_if_not_exists(msg, _descr);
            });


            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        }


    </script>
    </head>
    <body>
    <? include "php/conf_nav_header.php" ?>

    <table border="1" style="height: 100%">
        <tr>
            <th>All names</th>
            <th>Edit/Insert <input type="button" value="Add"
                                   onclick="clear_form(); "/>
                <input type="button" value="Save" onclick="save_features()">
                <input type="button" value="Preview"
                       onclick="preview()"/>
            </th>
        </tr>
        <tr>
            <td style="height: 100%;">
                <SELECT NAME="sfeatures" id="sfeatures" style="height: 100%; width:150px" multiple
                        onchange="feature_selected(this)">
                    <? $sql = "SELECT id,descr FROM `featured` order by sort_order";
                    $result = $conn->query($sql);
                    $rows = array();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<OPTION value='" . $row["id"] . "'>" . $row["descr"] . "\n";
                        }
                    }

                    ?>
                </SELECT>
            </td>
            <td width="100%" valign="top">
                <form id="feature_form">

                    <table width="100%">
                        <tr>
                            <td><b>ID&nbsp;:</td>
                            <td><input type="text" id="hid" readonly/></td>
                        </tr>
                        <tr>
                            <td><b>Descript&nbsp;:</td>
                            <td width="100%"><input type="text" id="hdescr" style="width: 100%"/></td>
                        </tr>
                        <?foreach ($all_langs as $lng) {
                            $img_field = $lang_id_imgparam_prefix . $lng->lng_id;
                            $txt_field = $lang_id_param_prefix . $lng->lng_id;
                            ?>

                            <tr>
                                <td style="width: 12%"><img src="<?= $lng->flag_url ?>"/>&nbsp;<b><?= $lng->lng_name ?>
                                        &nbsp;:</td>
                                <td style="width: 88%">
                                    <table width='100%' border="1">
                                        <tr>
                                            <td width="100%">
                                                <input type="button" value="Browse image"
                                                       onclick="browseFile('<?= $img_field ?>')"/>
                                                <input type="button" value="Copy"
                                                       onclick="copy_items(<?= $lng->lng_id ?>)"/>
                                                <input type="button" value="Paste"
                                                       onclick="paste_items(<?= $lng->lng_id ?>)"/>
                                                <input style="width: 100%"
                                                       id="<?= $img_field ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="100%"><textarea style="width: 100%"
                                                                       id="<?= $txt_field ?>"></textarea>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        <?
                        } ?>


                    </table>


                </form>
            </td>
        </tr>
    </table>
    </body>
<?php include 'php/closedb.php'; ?>