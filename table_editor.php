<?php
/**
 * Created by PhpStorm.
 * User: dimitri.gamkrelidze
 * Date: 4/27/2015
 * Time: 2:11 PM
 */
include 'php/configuration_header.php';
include 'php/classes/CTable.php';

$tbl_name = $_REQUEST['tbl_name'];
if (is_null($tbl_name))
    $tbl_name = $_REQUEST['tbl_name'];
if (is_null($tbl_name))
    $tbl_name = 'configuration';
$CTable = new CTable();
$CTable->initFromDB($conn, $tbl_name);

$language_fields = $CTable->language_fields;
$common_fields = $CTable->common_fields;
$field_name_prefix = "FID_";
include 'php/header.php';
?>
    <script type="text/javascript" src="js/addcslashes.js"></script>


    <!-- loads mdp -->
    <script type="text/javascript" src="js/jquery-ui.multidatespicker.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mdp.css">


    <style>


        .ui-tooltip {
            border: 1px solid white;
            background: #111;
            max-width: 300px;
            color: white;
        }

        .sortable_youtube {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .sortable_youtube li {
            margin: 1px 1px 1px 0;
            padding: 1px;
            float: left;
            150px;
            height: 112px;
        }

    </style>
<?
include 'php/dictionary_data_all.php';
?>
    <script id="entry-template" type="text/x-handlebars-template">
        <?= $CTable->row_template ?>
    </script>
    <script type="text/javascript">
    var youtube_dataFields = [{name: 'ID', type: 'string', map: '0'},
        {name: 'Title', type: 'string', map: '2'},
        {name: 'Image', type: 'string', map: '3'}];
    registerHelpers();
    <?
        $language_field_names='';
        $common_field_names='';
        $language_ids='';
       foreach ($language_fields as $lf) {
           if(strlen($language_field_names)>0){
                $language_field_names.=',';
            }
           $language_field_names.="'".$lf->field_name."'";

           if(strlen($common_field_names)>0){
                $common_field_names.=',';
            }
           $common_field_names.="'".$lf->real_field_name."'";
       }

       foreach ($common_fields as $lf) {
           if(strlen($common_field_names)>0){
                $common_field_names.=',';
            }
           $common_field_names.="'".$lf->id_field_name."'";
       }
       foreach ($all_langs as $lng) {
           if(strlen($language_ids)>0)
            $language_ids.=',';
           $language_ids.=$lng->lng_id;
       }
    ?>

    var _language_fields = [<?=$language_field_names?>];
    var _common_fields = [<?=$common_field_names?>];
    var _languages = [<?=$language_ids?>];
    var _field_name_prefix = '<?=$field_name_prefix?>';
    var _calendar_item;
    date_format = '<?=$date_format?>';

    function copy_items(lang_id) {
        _current_values = [];
        _language_fields.forEach(function (entry) {
            _current_values[entry] = $("#" + _field_name_prefix + entry + "_" + lang_id).html();
        });

    }

    function paste_items(lang_id) {
        if (!_current_values || _current_values == null) {
            alert('nothing to copy');
        }
        _language_fields.forEach(function (entry) {
            $("#" + _field_name_prefix + entry + "_" + lang_id).html(_current_values[entry]);
        });

    }

    function paste_items_to_all(lang_id) {
        copy_items(lang_id);
        _languages.forEach(function (entry) {
            paste_items(entry);
        });
    }
    var _empty;

    function getPreviewHtml() {
        var source = $("#entry-template").html();
        source = source.trim();
        var template = Handlebars.compile(source);
        var data = getValues();
        var html = '<?= addslashes($CTable->container_prefix) ?>';
        if (_language_fields && _language_fields.length > 0) {
            try {
                if (_languages) {
                    _languages.forEach(function (lang_id) {
                        var vals = convert_data_to_real(data, lang_id);
                        var t = template(vals) + '\n';
                        html += t;
                    });
                }
            } catch (err) {
                alert(err);
            }
        } else {
            var vals = convert_data_to_real(data, 1);
            try {
                html += template(vals) + '\n';
            } catch (err) {
                alert(err);
            }
        }
        html = html + '<?= addslashes($CTable->container_suffix) ?>';
        return html;
    }

    function previewTemplate() {
        window.open("table_editor_preview.html", "PreviewWindow", "toolbar=no, scrollbars=no, resizable=yes,  width=1200, height=800");
    }
    function setValues(data) {
        if (!data)
            data = {};
        data = data.result;
        if (!data)
            data = [];
        lang_items = data['_language_items_'];
        if (!lang_items)
            lang_items = [];
        _common_fields.forEach(function (entry) {
            var _field = $("#" + _field_name_prefix + entry);
            if (_field) {
                _field.val(data[entry]);
                _field.trigger("change");
            }
        });
        _languages.forEach(function (lang_id) {
            _lang_item = lang_items[lang_id];
            _language_fields.forEach(function (entry) {
                var _field = $("#" + _field_name_prefix + entry + "_" + lang_id);
                var _lang_item_value = _lang_item ? _lang_item[entry] : _empty;
                if (_field)
                    if (_lang_item_value)
                        _field.html(_lang_item_value);
                    else
                        _field.empty();
            });
        });


    }

    function addList(data) {
        $('#sfeatures')
            .empty();
        data = data.result;

        data.forEach(function (entry) {
            opt = $("<option>").val(entry.id_val).text(entry.title);
            $('#sfeatures').append(opt);
            if (entry.data) {
                opt.attr('selected', 'selected');
                var result = [];
                result.result = entry.data;
                setValues(result);

            }
        });

    }
    function listData(id) {
        var _data = {};
        _data.op_id = 2;
        _data.tablename = '<?=$CTable->table_name?>';
        if (id)
            _data.values = id;
        var request = $.ajax({
            url: "php/db_helper.php",
            method: "POST",
            data: _data,
            dataType: "json"
        });
        request.done(function (msg) {
            closeloading();
            addList(msg);
        });
        request.fail(function (jqXHR, textStatus) {
            closeloading();
            alert("Request failed: " + textStatus);
        });


    }


    function getValues() {
        var _values = {};
        _common_fields.forEach(function (entry) {
            var _field = $("#" + _field_name_prefix + entry);
            var _val = _field.val();
            if (_field && !isEmpty(_val))
                _values[entry] = _val;
        });
        _values._language_items_ = {};
        _languages.forEach(function (lang_id) {
            _values._language_items_[lang_id] = {};
            _language_fields.forEach(function (entry) {
                var _field = $("#" + _field_name_prefix + entry + "_" + lang_id);
                if (_field) {
                    var _val = _field.html();
                    if (!isEmpty(_val))
                        _values._language_items_[lang_id][entry] = _val;
                }
            });
        });
        return _values;
    }


    function feature_selected(sel) {
        try {
            loading();
            var _id = sel.options[sel.selectedIndex].value;
            var _data = {};
            _data.op_id = 0;
            _data.tablename = '<?=$CTable->table_name?>';
            _data.values = _id;
            var request = $.ajax({
                url: "php/db_helper.php",
                method: "POST",
                data: _data,
                dataType: "json"
            });
            request.done(function (msg) {
                closeloading();
                setValues(msg);
            });


            request.fail(function (jqXHR, textStatus) {
                closeloading();
                alert("Request failed: " + textStatus);
            });
        } catch (err) {
            closeloading();
        }

    }

    function save_features() {
        loading();
        var _data = {};
        _data.op_id = 1;
        _data.tablename = '<?=$CTable->table_name?>';

        _data.values = getValues();
        var request = $.ajax({
            url: "php/db_helper.php",
            method: "POST",
            data: _data,
            dataType: "html"
        });
        request.done(function (msg) {

            closeloading();
            if (msg != parseInt(msg)) {
                alert(msg);
                return;
            }
            listData(msg);
        });


        request.fail(function (jqXHR, textStatus) {
            closeloading();
            alert("Request failed: " + textStatus);
        });
    }

    </script>
    </head>
    <body onload="listData(-1)">
    <? include "php/conf_nav_header.php" ?>
    <div id="dialog-1" style="width: 100%">
    </div>

    <div id="dialog-message" title="Preview">

    </div>


    <div id="dialog_calendar" title="Calendar">
        <div id="full-year" class="box"></div>
        <script>var today = new Date();
            var y = today.getFullYear();
            $('#full-year').multiDatesPicker({
                numberOfMonths: [3, 4]
            });</script>
    </div>

    <div id="dialog-form" title="Set image url">
        <form>
            <fieldset>
                <label for="img_url">URL</label>
                <input type="text" name="img_url" id="img_url" value="" class="text" style="width: 300px">

                <div class="mce-btn mce-open">
                    <button type="button">
                        <i class="mce-ico mce-i-browse"></i>
                    </button>
                </div>
                <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">

                <div class="mce-container mce-last mce-abs-layout-item"></div>
            </fieldset>
        </form>
    </div>

    <table border="1" style="height: 100%">
    <tr>
        <th>All names</th>
        <th>Edit<? if (is_null($CTable->one_row) or $CTable->one_row == 0) { ?>
                /Insert <input type="button" value="Add"
                               onclick="setValues(_empty) "/>
            <? } ?>
            <input type="button" value="Save" onclick="save_features()">
            <input type="button" value="Preview"
                   onclick="previewTemplate()"/>
        </th>
    </tr>
    <tr>
    <td style="height: 100%;">
        <SELECT NAME="sfeatures" id="sfeatures" style="height: 100%; width:150px" multiple
                onchange="feature_selected(this)" onclick="feature_selected(this)">

        </SELECT>
    </td>
    <td width="100%" valign="top">
    <form id="feature_form">
    <?
    foreach ($language_fields as $lf) {
        $idname = $field_name_prefix . $lf->real_field_name;
        ?>
        <input type="hidden" id="<?= $idname ?>"/>
    <?
    }
    ?>
    <table width="100%" border="1">
        <?
        foreach ($common_fields as $cf) {
            $f_readOnly = '';

            $f_name = $field_name_prefix . $cf->id_field_name;
            $f_type = $cf->type;
            $f_list_values = $cf->list_values;

            $styles = '';
            if (isset($cf->readonly) and $cf->readonly == 1)
                $f_readOnly = 'readonly';
            if (isset($cf->width) and strlen($cf->width) > 0)
                $styles .= 'width: ' . $cf->width;
            if (isset($cf->height) and strlen($cf->height) > 0)
                $styles .= 'height: ' . $cf->height;
            if (isset($styles) and strlen($styles) > 0)
                $styles = ' style="' . $styles . '"';
            ?>
            <tr>
                <td nowrap width="200px"><b><?= ($cf->title) ?>&nbsp;:</td>
                <td>
                    <?if (is_null($f_type) or $f_type == 'text' or strlen($f_type) == 0) {
                        ?><input type="text" id="<?= $f_name ?>" <?= $f_readOnly ?> <?= $styles ?> />
                    <?
                    } else {

                        if ($f_type == 'youtube_videos') {

                            ?>
                            <input type="hidden" id="<?= $f_name ?>"
                                   onchange="youtube_value_changed(this,<?= $f_name ?>_table)"/>
                            <input type="text" id="<?= $f_name ?>_box"/>
                            <input type="button" id="<?= $f_name ?>_add" value="Add"
                                   onclick="addYoutubeLink(<?= $f_name ?>,<?= $f_name ?>_box,<?= $f_name ?>_table);"/>
                            <ul id="<?= $f_name ?>_table" width="100%" border="1" class="sortable_youtube">

                            </ul>
                            <script>
                                $('#<?= $f_name ?>_table').sortable({
                                    stop: function (event, ui) {
                                        youtube_videos_resorted(<?= $f_name ?>, <?= $f_name ?>_table);
                                    }
                                });
                            </script>
                        <?
                        }
                        if ($f_type == 'calendar') {
                            ?>
                            <div style="background: #ffffff;width: 100%;color: #000000">
                                <table width="100%">
                                    <tr>
                                        <td width="100%" style="color: #000000;font-weight: bold;"
                                            id="<?= $f_name ?>_cal_val"></td>
                                        <td style="min-width: 20px;"><img src="img/calendar.gif"
                                                                          style="float: right;cursor: hand"
                                                                          onclick="showCalendar(<?= $f_name ?>)"></td>
                                    </tr>
                                </table>

                            </div>

                            <input type="hidden" id="<?= $f_name ?>" onchange="calendarChanged(this);"
                                />
                            <script>
                                _calendar_item = "<?= $f_name ?>";
                            </script>
                        <?
                        }
                        if ($f_type == 'images') {

                            ?>
                            <input type="hidden" id="<?= $f_name ?>"
                                   onchange="image_value_changed(this,<?= $f_name ?>_table)"/>
                            <input type="hidden" id="<?= $f_name ?>_one"
                                   onchange="addImage(<?= $f_name ?>,this.value,1,1)">
                            <input type="hidden" id="<?= $f_name ?>_dir"
                                   onchange="addImageDir(<?= $f_name ?>,this.value)">
                            <input type="hidden" id="<?= $f_name ?>_ext"
                                   onchange="addImage(<?= $f_name ?>,this.value,null,1)">

                            <input type="button" value="Browse Image"
                                   onclick="browseFile('<?= $f_name ?>_one')"/>
                            <input type="button" value="Browse Dir"
                                   onclick="browseFile('<?= $f_name ?>_dir')"/>
                            <input type="button" value="External url"
                                   onclick="setImageDialog(<?= $f_name ?>_ext)"/>
                            <ul id="<?= $f_name ?>_table" width="100%" border="1" class="sortable_youtube">

                            </ul>
                            <script>
                                $('#<?= $f_name ?>_table').sortable({
                                    stop: function (event, ui) {
                                        images_resorted(<?= $f_name ?>, <?= $f_name ?>_table);
                                    }
                                });
                            </script>
                        <?
                        }
                        if ($f_type == 'image') {
                            ?>
                            <input type="hidden" id="<?= $f_name ?>"
                                   onchange="<?= $f_name ?>_img.src=(this.value);"/>


                            <div class="he-wrap tpl5"><img
                                    onclick="browseFile('<?= $f_name ?>')" src="" <?= $styles ?>
                                    id="<?= $f_name ?>_img"/>

                                <div class="he-view">
                                    <a href="#" class="buy a0" data-animate="rotateInLeft"
                                       onclick="browseFile('<?= $f_name ?>')">Load image</a>

                                    <a class="buy1 a2" href="#" onclick="setImageDialog(<?= $f_name ?>)"
                                       data-animate="bounceInDown">Set URL</a>
                                </div>
                            </div>

                        <?
                        } else {
                            if ($f_type == 'select' and isset($f_list_values) and strlen($f_list_values) > 0) {
                                ?>
                                <SELECT NAME="<?= $f_name ?>" id="<?= $f_name ?>" <?= $styles ?> >
                                    <?
                                    $f_list_values_arr = split(',', $f_list_values);
                                    foreach ($f_list_values_arr as $lv) {
                                        ?>
                                        <option value="<?= $lv ?>"><?= $lv ?></option>
                                    <?
                                    }

                                    ?>
                                </SELECT>


                            <?
                            } else {
                                if ($f_type == 'calendar1') {
                                    ?>

                                    <input class="mycalendar" type="hidden" id="<?= $f_name ?>"
                                           onchange="calendarChanged(this);" style="width: 100%"
                                           onclick='$("#dialog-1").dialog("open");'/>
                                    <div id="widget" title="hhhh">
                                        <div id="widgetField">
                                            <span></span>
                                            <a href="#">Select date range</a>
                                        </div>
                                        <div id="widgetCalendar">
                                        </div>
                                    </div>

                                <?
                                }
                            }
                        }

                    }
                    if (isset($cf->goto_url) and $cf->goto_url == 1) {
                        ?>
                        <input type="button" value="Go"
                               onclick="window.open(<?= $f_name ?>.value,'_blank');"/>
                    <?
                    }
                    ?>
                </td>
            </tr>
        <?
        }
        ?>
    </table>
    <? if (count($language_fields) > 0) { ?>


        <ul class='tabs'>

            <?foreach ($all_langs as $lng) {
                $lng_name = '<img src="' . $lng->flag_url . '"/>&nbsp;<b>' . $lng->lng_name;
                ?>
                <li class="tab"><a href="#tabs1-<?= $lng->lng_id ?>"><?= $lng_name ?></a></li>
            <? } ?>
        </ul>
        <?

        foreach ($all_langs as $lng) {
            $lng_id = $lng->lng_id;
            ?>
            <div id="tabs1-<?= $lng_id ?>">
                <form id="frm"<?= $lng_id ?>>
                    <table border="1" width="100%">
                        <tr>
                            <td></td>
                            <td align="center"><input type="button" value="Copy"
                                                      onclick="copy_items(<?= $lng_id ?>)"/><input
                                    type="button" value="Paste"
                                    onclick="paste_items(<?= $lng_id ?>)"/><input
                                    type="button" value="Copy&Paste to All "
                                    onclick="paste_items_to_all(<?= $lng_id ?>)"/></td>

                        </tr>
                        <?
                        foreach ($language_fields as $lf) {
                            $idname = $field_name_prefix . $lf->field_name . '_' . $lng_id;
                            $title = getFromAllDictionary($lng_id, $lf->field_name);
                            if (is_null($title))
                                $title = $lf->title;
                            ?>
                            <tr style="cursor: hand" onclick="setText('<?= $idname ?>')">
                                <td nowrap width="200px">
                                    <b><?= $title ?>:</b>
                                </td>
                                <td style="width: 100%">
                                    <div id="<?= $idname ?>"></div>
                                </td>
                            </tr>
                        <?
                        }

                        ?>

                    </table>
                </form>

            </div>

        <?
        }
    } ?>
    </form>
    </td>
    </tr>

    </table>
    <div id="toPopup">

        <div class="close"></div>
        <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>

        <div id="popup_content" style="height: 320px"> <!--your content start-->
            <table width="100%">
                <tr style="height: 90%">
                    <td style="width: 100%">
                        <textarea style="width: 100%;height: 100%"
                                  id="textedit"></textarea>
                    </td>
                </tr>
                <tr style="height: 10%">
                    <td align="center"><input type="button" value="Set" onclick="setTextResult()"/></td>
                </tr>
            </table>
        </div>
        <!--your content end-->

    </div>
    <!--toPopup end-->

    <div class="loader"></div>
    <div id="backgroundPopup"></div>

    </body>
<?php include 'php/closedb.php'; ?>