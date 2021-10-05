<form method="post" id="form_add" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-2"><b>SO Title</b></label>
                <div class="col-md-8">
                    <input value="<?=@$data->code_so;?> - <?=@$data->name_so;?>" name="name_so" id="name_so" readonly="readonly" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2"><b>KPI-SO</b></label>
                <div class="col-md-8">
                    <input value="<?=@$data->code;?> - <?=@$data->name_kpi_so;?>" name="name_kpi_so" id="name_kpi_so" readonly="readonly" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2"><b>PIC KPI-SO</b></label>
                <div class="col-md-3">
                    <input value="<?=@$data->name_pic_kpi_so;?>" name="name_pic_kpi_so" id="name_pic_kpi_so" readonly="readonly" type="text" class="form-control">
                </div>
                <label class="control-label col-md-2"><b>Ukuran</b></label>
                <div class="col-md-3">
                    <input value="<?=@$data->ukuran;?>" readonly="readonly" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2"><b>Polarisasi </b></label>
                <div class="col-md-3">
                    <input value="<?=@$polarisasi_name;?>" readonly="readonly" type="text" class="form-control">
                </div>
                <label class="control-label col-md-2"><b>Frekuensi Pengukuran</b></label>
                <div class="col-md-3">
                    <input value="<?=@$data->frekuensi_pengukuran;?>" readonly="readonly" type="text" class="form-control">
                </div>
            </div>
            <hr style="margin-top:0px;">
            <input value="<?=$year?>" id="year"  type="hidden" class="form-control">
            <input value="<?=$month?>" id="month"  type="hidden" class="form-control">
        </div>

        <div class="tabbable-custom nav-justified">
            <ul class="nav nav-tabs nav-justified" style="background: #e9ecf3;border-top:2px solid black;">
                <?php  for($y=$start_year;$y<=$end_year;$y++){ ?>
                    <li class="<?=($y==$year?'active':'')?>"  style="background: #e9ecf3;border-right:2px solid #ffffff;">
                        <a href="#tab_<?=$y;?>" class="btn_change_year" year="<?=$y;?>" data-toggle="tab"><b><?=$y;?></b></a>
                    </li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <?php  for($y=$start_year;$y<=$end_year;$y++){ ?>
                    <?php $active = ($y==$year?'active':'');
                          if($y == $end_year){
                            $active = ($active== '' ? 'active': '');
                          }
                    ?>
                    <div class="tab-pane <?=$active?>" id="tab_<?=$y;?>">
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

    //change year
    $('.btn_change_year').off().on('click', function(e) {
        var year = $(this).attr('year');
        $('#form_add #year').val(year);
        window.reload_month();
    });


    //load edit month
    $('.btn_edit_month').off().on('click', function(e) {
        $('#popup_edit_month').modal();
        var id  = $(this).attr('id_month');
        var url = "<?=site_url($url);?>/load_edit_month";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_edit_month',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_edit_month').html(msg);
            Metronic.unblockUI('#load_edit_month');
        });
    });

    //reload month
    window.reload_month = function (){
        var id      = "<?=@$data->id;?>";
        var view    = "<?=@$view;?>";
        var year    = $('#form_add #year').val();
        var month   = $('#form_add #month').val();
        var url     = "<?=site_url($url);?>/load_table_month";
        var param   = {id:id, view:view, year:year, month:month};
        Metronic.blockUI({ target: '#tab_'+year,  boxed: true});
        $.post(url, param, function(msg){
            var year = $('#form_add #year').val();
            $('#form_add #tab_'+year).html(msg);
            Metronic.unblockUI('#tab_'+year);
        });
    }
    window.reload_month();
    

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>