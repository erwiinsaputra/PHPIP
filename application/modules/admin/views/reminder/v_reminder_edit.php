<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
            <div class="col-md-12">

            <div class="form-group">
                  <label class="control-label col-md-3">Reminder Name
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->reminder_name;?>" name="reminder_name" type="text" class="form-control" placeholder="Reminder Name" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Reminder Date
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-4">
                    <div class="input-group">
                        <input value="<?=substr(@$data->reminder_date,0,10)?>" name="reminder_date" type="text" class="form-control tanggal" placeholder="Date" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                        <input value="<?=substr(@$data->reminder_date,11,5)?>" name="reminder_time" type="text" class="form-control timepicker-24" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
                        </span>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Description
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                    <textarea name="description" class="form-control" data-bvalidator="required" rows="3"><?=@$data->description;?></textarea>
                  </div>
              </div>
          </div>

      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                <button id="btn_update" class="btn btn-primary">Update</button>
          </div>
      </div>
  </div>
</form>


<script type="text/javascript">
$(document).ready(function () {

    //format tanggal
    $("#form_edit .tanggal").datepicker({
        format: 'yyyy-mm-dd',
        viewMode: "days", 
        minViewMode: "days",
        autoclose: true,
    });

    //format jam
    $('#form_edit .timepicker-24').timepicker({
        autoclose: true,
        minuteStep: 1,
        showSeconds: false,
        showMeridian: false
    });

    //update data
    $('#form_edit #btn_update').on('click',function(){
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
                    var url     = "<?=site_url($url);?>/save_edit";
                    var param   = $('#form_edit').serializeArray();
                    Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('.modal-dialog');
                        $('#popup_edit').modal('hide');
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_reminder();
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

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>