<style>
    #form_edit input {
        text-align:center;
    }
</style>

<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h3 style="text-align:center;margin-left: 5em;">
                    <b>Performance Analysis</b>
                </h3>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" style="font-size:1.5em;">
                    <b>Keterangan</b>
                </label>
                <div class="col-md-8">
                    <textarea style="font-size:1.5em;" readonly="readonly" name="recommendations" class="form-control required" rows="6" data-bvalidator="required" autocomplete="off"><?=@$data->penyebab1;?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" style="font-size:1.5em;">
                    <b>Recommendations</b>
                </label>
                <div class="col-md-8">
                    <textarea style="font-size:1.5em;" readonly="readonly" name="recommendations" class="form-control required" rows="3" data-bvalidator="required" autocomplete="off"><?=@$data->recommendations;?></textarea>
                </div>
            </div>
            <div class="form-group">
                <h3 style="text-align:center;margin-left: 5em;">
                    <b>Quick Win</b>
                </h3>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" style="font-size:1.5em;">
                    <b>Quick Win</b>
                </label>
                <div class="col-md-8">
                    <textarea style="font-size:1.5em;" readonly="readonly" name="recommendations" class="form-control required" rows="3" data-bvalidator="required" autocomplete="off"><?=@$data->quick_win;?></textarea>
                </div>
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
    $('#form_edit #btn_update_month').off().on('click',function(){
        $('#form_edit').bValidator();
        $('#form_edit').submit();
        if($('#form_edit').data('bValidator').isValid()){
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
                    var param   = $('#form_edit').serializeArray();
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
    $('#form_edit #realisasi').off().on('keyup change',function(){
        var realisasi = $(this).val();
        var realisasi = realisasi.replace(",", ".");
        var realisasi = parseFloat(realisasi).toFixed(10);
        var target    = $('#form_edit #target').val();
        var target    = target.replace(",", ".");

        //cek target range
        var cek = target.indexOf(" - ");
        if(cek != '-1'){
            var pecah = target.split(" - ");
            var from = parseFloat(pecah[0]).toFixed(10);
            var to = parseFloat(pecah[1]).toFixed(10);
            if(realisasi >= from && realisasi <= to){
                pencapaian = 100;
            }
            if(realisasi < from){
                pencapaian = (realisasi/from)*100;
            }
            if(realisasi > to){
                pencapaian = (2-(realisasi/to))*100;
            }
            var pencapaian = parseFloat(pencapaian).toFixed(2);
        }else{
            var target = parseFloat(target).toFixed(10);
            var pencapaian = (realisasi/target)*100;
            var pencapaian = parseFloat(pencapaian).toFixed(2);
        }
        if(!isFinite(pencapaian)){ pencapaian=0;}
        if(isNaN(pencapaian)){pencapaian=0;}
        var pencapaian = pencapaian.replace(".", ",");
        $('#form_edit #pencapaian').val(pencapaian+' %');
    });


    //change prognosa
    $('#form_edit #prognosa').off().on('keyup change',function(){
        var prognosa = $(this).val();
        var prognosa = prognosa.replace(",", ".");
        var prognosa = parseFloat(prognosa).toFixed(10);
        var target   = $('#form_edit #target').val();
        var target   = target.replace(",", ".");

        //cek target range
        var cek = target.indexOf(" - ");
        if(cek != '-1'){
            var pecah = target.split(" - ");
            var from = parseFloat(pecah[0]).toFixed(10);
            var to = parseFloat(pecah[1]).toFixed(10);
            if(prognosa >= from && prognosa <= to){
                pencapaian = 100;
            }
            if(prognosa < from){
                pencapaian = (prognosa/from)*100;
            }
            if(prognosa > to){
                pencapaian = (2-(prognosa/to))*100;
            }
            var pencapaian = parseFloat(pencapaian).toFixed(2);
        }else{
            var target = parseFloat(target).toFixed(10);
            var pencapaian = (prognosa/target)*100;
            var pencapaian = parseFloat(pencapaian).toFixed(2);
        }
        if(!isFinite(pencapaian)){ pencapaian=0;}
        if(isNaN(pencapaian)){pencapaian=0;}
        var pencapaian = pencapaian.replace(".", ",");
        $('#form_edit #prognosa_pencapaian').val(pencapaian+' %');
    });
    
    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>