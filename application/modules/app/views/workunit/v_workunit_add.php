<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
          <div class="col-md-12">
              <div class="form-group">
                  <label class="control-label col-md-3">Workunit
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="name" type="text" class="form-control" placeholder="Workunit" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Is Corporate
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select name="is_corporate" placeholder="Is Corporate" class="form-control select2_biasa"  data-bvalidator="required" >
                            <option value=""></option>
                            <option value="t">Corporate</option>
                            <option value="f">Non Corporate</option>
                        </select>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Code
                    <span class="required" aria-required="true"></span>
                  </label>
                  <div class="col-md-8">
                      <input name="code" type="text" class="form-control" placeholder="Code" data-bvalidator="" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Description
                    <span class="required" aria-required="true"></span>
                  </label>
                  <div class="col-md-8">
                      <input name="description" type="text" class="form-control" placeholder="Description" data-bvalidator="" autocomplete="off">
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

    //select2 biasa
    $("#form_add .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
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
                            window.reload_table_workunit();
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