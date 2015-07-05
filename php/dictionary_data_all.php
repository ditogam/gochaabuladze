<script>
    all_dictionary = [];
    <?
    foreach ($all_langs as $lng) {
           $lng_id=intval($lng->lng_id);
           $l_dictionary = $all_dictionary[$lng_id];
    ?>all_dictionary[<?=$lng_id?>] =<?=json_encode($l_dictionary)?>;
    <?
           }
        ?>
</script>