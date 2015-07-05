<h2><?= $dictionary['schedule'] ?></h2>
<div id='calendar'></div>


<link href='css/fullcalendar.min.css' rel='stylesheet'/>
<link href='css/fullcalendar.print.css' rel='stylesheet' media='print'/>

<script src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/moment.min.js"></script>
<script type="text/javascript" src="js/fullcalendar.min.js"></script>
<script src="js/fullcalendar.lang-all.js"></script>
<script src="js/fullcalendar.ka.js"></script>
<script src="js/moment.min.js"></script>

<style>
    #calendar {
        max-width: 95%;
        margin: 0 auto;
    }

</style>

<div id="element_to_pop_up" style="display: none"></div>
<script>
    registerHelpers();
    current_lang_id =<?=$current_lang_id?>;
    function getPreviewHtml(data) {
        var html = getHtmlFromTemplate(data);
        $(element_to_pop_up).html(html);
        $('article').shorten({
            showChars: '500',
            moreText: getCurrDictValue('more'),
            lessText: getCurrDictValue('less')
        });
        $('#element_to_pop_up').bPopup();

    }

    var _events = [];
    <?
    include 'php/classes/CTable.php';
    $CTable = new CTable();
    $CTable->initFromDB($conn, 'events');

        $sql = "select e.`eventid` id,`event_date`,(select `value` from `texts` t where t.`id`=e.`title_id` and t.`lang_id`=" . $current_lang_id . ") title from `events` e inner join  `calendar` c on c.`event_id`=e.`eventid` ";
        $result = $conn->query($sql);
        $youtube_videos = null;
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
            $row['title']=stripslashes($row['title']);
                ?>
    _events.push(<?=json_encode($row)?>);
    <?
    }
    }
    $result->close();

    ?>

    _events.forEach(function (entry) {
        entry.start = datestr(new Date(entry.event_date * 1000));
        delete  entry.event_date;
    });
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            theme: true,
            lang: '<?=$current_lang->short_name?>',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: _events,
            eventRender: function (event, element, view) {
                var title = event.title;
                if (title)
                    element.find('.fc-title ').html(title);
            },
            eventClick: function (calEvent, jsEvent, view) {
                var _id = calEvent.id;
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
                    getPreviewHtml(msg);
                });


                request.fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
            }
        });

    });

</script>
<? include 'php/events_template.php' ?>