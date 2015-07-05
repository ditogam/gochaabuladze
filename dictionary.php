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
        <script type="text/javascript">
            function clear_form() {
                dictionary_form.reset();
            }
            function removeOptions(selectbox) {
                var i;
                for (i = selectbox.options.length - 1; i >= 0; i--) {
                    selectbox.remove(i);
                }
            }

            function add_if_not_exists(name) {
                var x = sdictionary;
                var i;
                var names = [];
                for (i = 0; i < x.length; i++) {
                    var txt = x.options[i].text;
                    if (txt == name)
                        return;
                    names.push(txt);
                }
                names.push(name);
                names.sort();


                removeOptions(sdictionary);
                var indx_n = -1;
                for (i = 0; i < names.length; i++) {
                    var option = document.createElement("option");
                    option.text = names[i];
                    if (option.text == name) {
                        indx_n = i;
                    }
                    x.add(option);
                }
                x.selectedIndex = indx_n;
            }

            function dictionary_selected(sel) {
                var _name = sel.options[sel.selectedIndex].value;
                dictionary_form.hdkay.value = _name;
                dictionary_form.hdkay.readOnly = true;
                var request = $.ajax({
                    url: "php/dictionary_helper.php",
                    method: "GET",
                    data: {op_id: 0, key_name: _name},
                    dataType: "json"
                });
                request.done(function (msg) {
                    clear_form();
                    dictionary_form.hdkay.value = _name;
                    for (x in msg.result) {
                        var v = msg.result[x];
                        var el = dictionary_form.elements["di_" + v.lng_id];
                        el.value = v.value;
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    clear_form();
                    alert("Request failed: " + textStatus);
                });
            }

            function save_dictionary() {
                var _data = {};
                var _name = dictionary_form.hdkay.value;
                _data.op_id = 1;
                _data.key_name = _name;
                var x = dictionary_form;
                var i;
                for (i = 0; i < x.length; i++) {
                    _data[x.elements[i].id] = x.elements[i].value;
                }
                var request = $.ajax({
                    url: "php/dictionary_helper.php",
                    method: "GET",
                    data: _data,
                    dataType: "html"
                });
                request.done(function (msg) {
                    add_if_not_exists(msg);
                });


                request.fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
            }


        </script>
    </head>
    <body>
    <? include "php/conf_nav_header.php" ?>
    <table style="height: 100%;width: 100%" border="1">
        <tr>
            <th>All names</th>
            <th>Edit/Insert <input type="button" value="Add"
                                   onclick="clear_form();dictionary_form.hdkay.readOnly = false; "/>
            </th>
        </tr>
        <tr>
            <td style="height: 100%;">
                <SELECT NAME="sdictionary" id="sdictionary" style="height: 100%; width:150px" multiple
                        onchange="dictionary_selected(this)">
                    <?foreach ($dictionary_array as $lng) {
                        echo "<OPTION >" . $lng->key_name . "\n";
                    }
                    ?>
                </SELECT>
            </td>
            <td width="100%" valign="top">
                <form id="dictionary_form">

                    <table width="100%">
                        <td><b>Name&nbsp;:</td>
                        <td><input type="text" id="hdkay"/></td>

                        <?foreach ($all_langs as $lng) {
                            echo ' <tr>';

                            echo '<td><img src="' . $lng->flag_url . '"/>&nbsp;<b>' . $lng->lng_name . '&nbsp;:</td>';
                            echo '<td width="100%"><input style="width: 100%" type="text" id="' . $lang_id_param_prefix . $lng->lng_id . '"/></td>';
                            echo ' </tr>';
                        } ?>
                        <tr>
                            <td></td>
                            <td align="center"><input type="button" value="Save" onclick="save_dictionary()"></td>
                        </tr>

                    </table>


                </form>
            </td>
        </tr>
    </table>
    </body>
<?php include 'php/closedb.php'; ?>