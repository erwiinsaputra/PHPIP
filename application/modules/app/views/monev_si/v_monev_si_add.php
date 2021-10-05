<form method="post" id="form_add_progress_<?=$id_si?>" action="javascript:;" class="form-horizontal">
    <div class="form-body">
        <div class="row">
            <input value="<?=$year?>" id="si_year" type="hidden" class="form-control">
            <input value="<?=$month?>" id="si_month" type="hidden" class="form-control">
            <div class="tabbable-custom nav-justified">
                <ul class="nav nav-tabs nav-justified" style="background: #e9ecf3;border-top:2px solid black;">
                    <?php  for($y=$start_year;$y<=$end_year;$y++){ ?>
                        <li class="<?=($y == $year ? 'active' : '')?>"  style="background: #e9ecf3;border-right:2px solid #ffffff;">
                            <a href="#tab_<?=$y;?>" class="btn_change_year" year="<?=$y;?>" data-toggle="tab">
                                <b><?=$y;?></b>
                                <?php if(@$arr_notif_tot[$y] != ''){ ?>
                                    <span title="<?=join(', ',$arr_notif_month[$y])?>" class="badge badge-warning" style="margin-right:0em;"><?=$arr_notif_tot[$y]?></span>
                                <?php } ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <?php for($y=$start_year;$y<=$end_year;$y++){ ?>
                        <div class="tab-pane <?=($y == $year ? 'active' : '')?>" id="tab_<?=$y;?>">
                            <div style="height:200px;"></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
$(document).ready(function () {

    //reload month
    window.load_table_action_plan = function (){
        var tipe    = "<?=$tipe?>";
        var id      = "<?=$id_si?>";
        var year    = $('#form_add_progress_<?=$id_si?> #si_year').val();
        var month   = $('#form_add_progress_<?=$id_si?> #si_month').val();
        var url     = "<?=site_url($url);?>/load_table_action_plan";
        var param   = {id:id, year:year, month:month, tipe:tipe};
        Metronic.blockUI({ target: '#tab_'+year,  boxed: true});
        $.post(url, param, function(msg){
            var year = $('#form_add_progress_<?=$id_si?> #si_year').val();
            $('#form_add_progress_<?=$id_si?> #tab_'+year).html(msg);
            Metronic.unblockUI('#tab_'+year);
        });
    }
    window.load_table_action_plan();

     //change year
     $('#form_add_progress_<?=$id_si?>').off().on('click','.btn_change_year', function(e) {
        var year = $(this).attr('year');
        $('#form_add_progress_<?=$id_si?> #si_year').val(year);
        window.load_table_action_plan();
    });

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>