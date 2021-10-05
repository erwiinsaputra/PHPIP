<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
          <div class="col-md-12">
              <div class="form-group">
                  <label class="control-label col-md-3">Status
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="name" type="text" class="form-control" placeholder="Status" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Code
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="code" type="text" class="form-control" placeholder="Code" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Type
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="type" type="text" class="form-control" placeholder="Type" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Description
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="description" type="text" class="form-control" placeholder="Description" data-bvalidator="required" autocomplete="off">
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
                            window.reload_table_status();
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