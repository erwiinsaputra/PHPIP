<style>
    #form_edit_<?=$id?> input {
        text-align:center;
    }
</style>

<form method="post" id="form_edit_<?=$id?>" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-2"><b>Year</b></label>
                <div class="col-md-3">
                    <input value="<?=@$data->year;?>"  name="year" id="year" readonly="readonly" type="text" class="form-control">
                </div>
                <label class="control-label col-md-3"><b>Target Year</b></label>
                <div class="col-md-4">
                    <input value="<?=h_format_angka(@$target_year);?>" readonly="readonly" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2"><b>Month</b></label>
                <div class="col-md-3">
                    <input value="<?=h_month_name(@$data->month);?>"  readonly="readonly" type="text" class="form-control">
                    <input value="<?=@$data->month;?>" name="month"  type="hidden" class="form-control">
                </div>
                <label class="control-label col-md-3"><b>Target Month</b></label>
                <div class="col-md-4">
                    <?php 
                        if((@$polarisasi == '10')){
                            $target = @$data->target_from.' - '. @$data->target_to;
                        }else{
                            $target = @$data->target;
                        }
                    ?>
                    <input value="<?=h_format_angka($target);?>" readonly="readonly" type="text" class="form-control">
                    <input value="<?=$target;?>" name="target" id="target"  type="hidden" class="form-control">
                </div>
            </div>
            <hr/>
            <div class="form-group">
                <label class="control-label col-md-3"><b>Realisasi</b>
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->realisasi;?>" name="realisasi" id="realisasi"  type="text" class="form-control angka" data-bvalidator="" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>Pencapaian</b></label>
                <div class="col-md-8">
                    <input value="<?=@$data->pencapaian.' %';?>" name="pencapaian" id="pencapaian" readonly="readonly" type="text" class="form-control" data-bvalidator="" autocomplete="off">
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-md-3"><b>Performance Analysis</b>
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <div style="text-align:center;font-weight:bold;">Keterangan :</div>
                    <textarea name="keterangan" class="form-control" rows="3" data-bvalidator="" autocomplete="off"><?=@$data->keterangan;?></textarea>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-8">
                    <div style="text-align:center;font-weight:bold;">Recommendations :</div>
                    <textarea name="recommendations" class="form-control" rows="3" data-bvalidator="" autocomplete="off"><?=@$data->recommendations;?></textarea>
                </div>
            </div>
      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-offset-1 col-md-12" style="text-align:center; margin-right:30px;">
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                <input type="hidden" name="polarisasi" id="polarisasi" value="<?=@$polarisasi;?>"  >
                <input type="hidden" value="<?=$target_year;?>" name="target_year" id="target_year" class="form-control" data-bvalidator="" autocomplete="off">
                <button id="btn_update_month" class="btn btn-primary">Update</button>
          </div>
      </div>
  </div>
</form>


<script type="text/javascript">
$(document).ready(function () {

    //format angka
    $('.angka').inputmask('decimal', {
        alias: 'numeric',
        autoGroup: true,
        radixPoint:".", 
        groupSeparator: ",", 
        digits: 5,
        rightAlign : false,
        prefix: ''
    });


    //update data
    $('#form_edit_<?=$id?> #btn_update_month').off().on('click',function(){
        $('#form_edit_<?=$id?>').bValidator();
        $('#form_edit_<?=$id?>').submit();
        if($('#form_edit_<?=$id?>').data('bValidator').isValid()){
            var title = "Save Data!";
            var mes = "Are You Sure ?";
            var sus = "Successfully Save Data!";
            var err = "Failed Save Data!";
            swal({
                    title: title,
                    text: mes,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    closeOnConfirm: true
            },
            function(){
                    var url     = "<?=site_url($url);?>/save_edit_month";
                    var param   = $('#form_edit_<?=$id?>').serializeArray();
                    Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('.modal-dialog');
                        $('#popup_edit_month').modal('hide');
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_month();
                            toastr['success'](sus, "Success");
                        }else{
                            toastr['error'](err, "Error");
                        }
                    },'json');
            });
        }else{
            // alert('Data Harus Lengkap! \nCoba Cek Inputan');
        }
    });

    //change realisasi
    $('#form_edit_<?=$id?> #realisasi').on('keyup',function(){
        //polarisasi

        //target
        var target = $('#form_edit_<?=$id?> #target').val();
        var target = target.replace(/,/g , "");
        if(target == ''){ target = '0';}

        //realisasi
        var realisasi = $(this).val();
        var realisasi = realisasi.replace(/,/g , "");
        if(realisasi == ''){ 
            $('#form_edit_<?=$id?> #pencapaian').val('0 %');
            return true;
        }
        var realisasi = parseFloat(realisasi).toFixed(10);
        
        //jika target 0
        var cek_target = $('#form_edit_<?=$id?> #target').val();
        if(cek_target == '0'){ 
            var target = '1'; 
            var realisasi = parseFloat(parseFloat(realisasi) + parseFloat(1)).toFixed(10); 
        }
        // alert(target+" "+realisasi);

        //cek polarisasi
        var polarisasi = $('#form_edit_<?=$id?> #polarisasi').val();
        if(polarisasi == '10'){ 
            //stabilize
            var pecah = target.split(" - ");
            var from = parseFloat(pecah[0]);
            var to = parseFloat(pecah[1]);
            // alert(from+" "+to+" "+realisasi);
            if(realisasi >= from && realisasi <= to){
                pencapaian = 100;
            }
            if(realisasi < from){
                pencapaian = (realisasi/from)*100;
            }
            if(realisasi > to){
                var a = (realisasi/to);
                if(a >= 2){
                    pencapaian = 0;
                }else{
                    pencapaian = (2-a)*100;
                }
            }
            var pencapaian = parseFloat(pencapaian).toFixed(2);
        }
        if(polarisasi == '9'){ 
            //minimum
            var target = parseFloat(target).toFixed(10);
            var a = (realisasi/target);
            if(a >= 2){
                pencapaian = 0;
            }else{
                pencapaian = (2-a)*100;
            }
            var pencapaian = parseFloat(pencapaian).toFixed(2);
        }
        if(polarisasi == '8'){ 
            //maximum
            var target = parseFloat(target).toFixed(10);
            var pencapaian = (realisasi/target)*100;
            var pencapaian = parseFloat(pencapaian).toFixed(2);
        }
        // alert(target+" "+realisasi+" "+pencapaian);

        if(!isFinite(pencapaian)){ pencapaian=0;}
        if(isNaN(pencapaian)){pencapaian=0;}
        if(pencapaian != 0){ var pencapaian = pencapaian.replace(".00", ""); }
        $('#form_edit_<?=$id?> #pencapaian').val(pencapaian+' %');
    });

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>