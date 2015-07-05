<script id="entry-template" type="text/x-handlebars-template">
    <!--    --><? //= $CTable->row_template ?>
    <table>
        <tr>
            <td><img class="editor" style="width: 300px" src="{{main_picture}}" alt=""></td>
            <td>
                <h2>&nbsp;&nbsp;{{{title}}}</h2>
                <ul>
                    {{#if composers}}
                    <li><b style="color: #09D4FF;">{{dictionary_value
                            'composers'}}:&nbsp;</b>{{{composers}}}&nbsp;</li>
                    {{/if}}
                    {{#if calendar}}
                    <li><b style="color: #09D4FF;">{{dictionary_value
                            'dates'}}:&nbsp;</b>{{make_calendar
                        calendar}}&nbsp;</li>
                    {{/if}}
                    {{#if cast}}
                    <li><b style="color: #09D4FF;">{{dictionary_value 'cast'}}:&nbsp;</b>{{{cast}}}&nbsp;</li>
                    {{/if}}
                    {{#if cast}}
                    <li><b style="color: #09D4FF;">{{dictionary_value 'event_type'}}:&nbsp;</b>{{dictionary_value
                        event_type}}
                    </li>
                    {{/if}}
                    {{#if cast}}
                    <li><b style="color: #09D4FF;">{{dictionary_value 'address'}}:&nbsp;</b>{{{address}}}&nbsp;</li>
                    {{/if}}
                </ul>
            </td>
        </tr>
    </table>
    <article>
        {{{event_text}}}
    </article>
    </p>                              <p style="horiz-align: right"> {{#if event_url}} <a
            href='{{event_url}}'
            target="_blank">{{dictionary_value
            'site'}}</a> {{/if}} {{#if buy_tickets_url}} <a href='{{buy_tickets_url}}'
                                                            target="_blank">{{dictionary_value
            'buy_tickets'}}</a> {{/if}} {{#if map_location}} <a href='{{map_location}}'
                                                                target="_blank">{{dictionary_value
            'map'}}</a> {{/if}} </p>
</script>