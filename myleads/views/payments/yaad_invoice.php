<script>
    function printContent(el){
        var restorepage = jQuery('body').html();
        var printcontent = jQuery('#' + el).clone();
        jQuery('body').empty().html(printcontent);
        window.print();
        jQuery('body').html(restorepage);
    }
</script>


<button id='print' onclick="printContent('heshbonit_to_print');" >לחץ כאן להדפסת החשבונית</button>
<div id='heshbonit_to_print'>
    <?= $info ?>
</div>	