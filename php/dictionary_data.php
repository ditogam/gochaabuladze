<script>
    all_dictionary = [];
    <?
           $lng_id=intval($current_lang_id);
           $l_dictionary = $all_dictionary[$lng_id];
    ?>all_dictionary[<?=$lng_id?>] =<?=json_encode($l_dictionary)?>;

</script>