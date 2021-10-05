<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3">Nama User
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <div class="input-icon right">
                        <input value="[<?=@$data->nip;?>] <?=@$data->fullname;?>" type="text" name="name" readonly="readonly" placeholder="Fullname" class="form-control required" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3">Status
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <select name="status" value="" class="form-control form-filter select-filter input-md select2_biasa"  placeholder="Status" >
                        <option value=""></option>
                        <option <?=(@$data->status == '1' ? 'selected' : '') ?> value="1">Active</option>
                        <option <?=(@$data->status == '2' ? 'selected' : '') ?> value="2">Disabled</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3">Role 
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->role_id;?>" name="role_id" id="role" type="text" class="required form-control" placeholder="pilih Role" />
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="form-group">
                <div class="col-md-12">
                   <br>
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
                <button id="btn_reset_password" idnya="<?=@$data->id;?>" class="btn btn-danger">Reset Password</button>
          </div>
      </div>
  </div>
</form>


<script type="text/javascript">
$(document).ready(function () {

    //select2 biasa
    $("#form_edit .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    });

    //select role
    $("#form_edit input[name='role_id']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        allowClear:true,
        multiple: true,
        ajax: {
          url: "<?php echo site_url($url.'/select_role')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_bsc = $("#form_edit select[name='id_bsc']").val();
            return { q: term, id_bsc:id_bsc };
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
              $.ajax({
                  url:"<?php echo site_url($url.'/select_role')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res); });
            }
        },
        formatResult: function(item){ return item.name },
        formatSelection: function(item){ return item.name; }
    }).on('change', function(event) { 
        //change
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
                            window.reload_table_user();
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


    //btn delete
    $('#form_edit #btn_reset_password').on('click',function(){
            var idnya = $(this).attr('idnya');
            var mes = "Are you sure to Reset Password ?";
            var title = "Are You Sure ?";
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
                    var token  = $('#ex_csrf_token').val();
                    var url    = '<?=site_url($url)?>/reset_password';
                    var param  = {id:idnya, token:token};
                    $.post(url, param, function(msg){
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_user();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    }, 'json');
            });
        });

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});

</script>