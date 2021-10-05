<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
          <div class="col-md-12">
              <div class="form-group">
                  <label class="control-label col-md-3">Reminder Name
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="reminder_name" type="text" class="form-control" placeholder="Reminder Name" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Reminder Date
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-4">
                    <div class="input-group">
                        <input name="reminder_date" type="text" class="form-control tanggal" placeholder="Date" >
                        <span class="input-group-btn">
                            <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                        <input name="reminder_time" type="text" class="form-control timepicker-24" >
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
                    <textarea name="description" class="form-control" data-bvalidator="required" rows="3"></textarea>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?=csrf_get_token();?>">
                <button id="btn_save" class="btn btn-primary">Save</button>
          </div>
      </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function () {

    //format tanggal
    $("#form_add .tanggal").datepicker({
        format: 'yyyy-mm-dd',
        viewMode: "days", 
        minViewMode: "days",
        autoclose: true,
    });

    //format jam
    $('#form_add .timepicker-24').timepicker({
        autoclose: true,
        minuteStep: 1,
        showSeconds: false,
        showMeridian: false
    });

    //save data
    $('#form_add #btn_save').on('click',function(){
        $('#form_add').bValidator();
        $('#form_add').submit();
        if($('#form_add').data('bValidator').isValid()){

            var title = "Save Data!";
            var mes = "Are You Sure ?";
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
                    var url   = "<?=site_url($url);?>/save_add";
                    var param = $('#form_add').serializeArray();
                    Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('.modal-dialog');
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            $('#popup_add').modal('hide');
                            window.reload_table_reminder();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    },'json');
            });
        }else{
        //   alert('Data Harus Lengkap! \nCoba Cek Inputan');
        }
    });

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>