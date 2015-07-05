<h2><?= $dictionary['events'] ?></h2>
<? include 'php/events_template.php' ?>
<script>
    current_lang_id =<?=$current_lang_id?>;
    var _prev_event;

    var _next_event;
    var _current;
    registerHelpers();
    function addPrevNext(elem, event_id) {
        $(elem).hide();
        if (event_id) {
            $(elem).show();
        }
    }

    function requestDone(msg) {


        _current = msg.current_rec;
        _prev_event = msg.prev_rec;
        _next_event = msg.next_rec;
        var result = {result: msg.data};
        var url_val = insertParam('event_id', _current);
        $("meta[property='og:title']").attr("content", result.result.title);
        $("meta[property='og:url']").attr("content", url_val);
        $('.fb-like').attr('data-href', url_val);
        $('.fb-comments').attr('data-href', url_val);
        $('meta[property="og:image"]').attr("content", qualifyURL(result.result.main_picture));
//        FB.ui({
//            method: 'feed',
//            link: url_val,
//            caption: result.result.title,
//            description: result.result.event_text,
//            picture: qualifyURL(result.result.main_picture)
//        }, function (response) {
//            alert(response);
//        });
//        alert($('meta[property="og:image"]').attr('content'));
        var html = getHtmlFromTemplate(result);
        $(event_cont).html(html);
        addPrevNext(prev_item, _prev_event);
        addPrevNext(next_item, _next_event);
        $('article').shorten({
            showChars: '500',
            moreText: getCurrDictValue('more'),
            lessText: getCurrDictValue('less')
        });
    }
    function getEventText(_event_id) {
        var _data = {};
        _data.event_id = _event_id;
        _data.lang_id = current_lang_id;
        var request = $.ajax({
            url: "php/events_helper.php",
            method: "GET",
            data: _data,
            dataType: "json"
        });


        request.done(function (msg) {
            requestDone(msg);
        });

        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });
    }
    $(function () {
        getEventText(<?=$_REQUEST['id']?>);
    });
</script>
<table>
    <tr>
        <td id="prev_item" onclick="getEventText(_prev_event);" style="cursor: hand;min-width: 44px"><img
                src="img/arrowPrev.png"></td>
        <td id="event_cont" width="100%"></td>
        <td id="next_item" onclick="getEventText(_next_event);" style="cursor: hand;min-width: 44px"><img
                src="img/arrowNext.png"></td>
    </tr>
</table>
