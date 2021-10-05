<div class="portlet light table_inbox">
    <div class="portlet-title">
        <div class="" style="text-align:center !important; font-size:1.5em;">
            <!-- <i class="fa fa-table font-green-sharp"></i> -->
            <span class="caption-subject font-green-sharp bold" style="font-size:1.2em;">
                Selamat Datang<br>
            </span>
            <span class="caption-subject bold" style="font-size:1em;color:#006A96;">
                Hi, <?=ucfirst(h_session('NAME'));?>
            </span>
        </div>
        <div class="actions">
        
            <div style="float:right;margin-top:-5em;">
                <a style="background-color:#D4F6FF; color:#2E75B6; font-size:0.85em; padding:0.75em 1em 0.75em 1em;" 
                    href="javascript:" tipe="inbox" class="btn btn-sm btn-primary btn_load_view">
                    Inbox /Assignment</a>
                <a style="background-color:#5B9BD5; color:white; font-size:0.85em; padding:0.10em 2.5em 0.10em 2.5em;" 
                    href="javascript:" tipe="element" class="btn_tes btn btn-sm btn-danger btn_load_view">
                    My Strategic <br>Elements</a>
            </div>
            
        </div>
    </div>

    <div class="portlet-body load_view">
        <?=@$html_load_inbox?>
    </div>
</div>



<script type="text/javascript">
$(document).ready(function() {

    //btn load_view
    $('.btn_load_view').on('click',function(){
        var tipe = $(this).attr('tipe');
        $('.load_view').html('');
        var url = '<?=site_url($url)?>/load_'+tipe;
        var param = {};
        Metronic.blockUI({ target: '.load_view',  boxed: true});
        $.post(url, param, function(msg){
            $('.load_view').html(msg);
            Metronic.unblockUI('.load_view');
        });
    });
    // $('.btn_tes').click();
});
</script>