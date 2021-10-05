<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3">Nama Menu
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <div class="input-icon right">
                        <input value="<?=@$data->name;?>" type="text" name="name" placeholder="Nama Menu" class="form-control required" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3">Icon
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->icon;?>" type="text" name="icon" placeholder="Icon" class="form-control required"/>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3">Menu Parent
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->parent;?>" name="parent" id="parent" type="text" class="required form-control" placeholder="pilih Parent" />
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3">Controler
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->controler;?>" name="controler" id="controler" type="text" class="required form-control" placeholder="Controler" />
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3">Folder
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->folder;?>" name="folder" id="folder" type="text" class="required form-control" placeholder="Folder" />
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3">Link
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->link;?>" name="link" id="link" type="text" class="required form-control" placeholder="Link" />
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3">Description
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->description;?>" name="description" id="description" type="text" class="required form-control" placeholder="Description" />
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

     //cek menu
     $("#form_edit input[name='name']").change(function () {
        var url = '<?=site_url($url)?>/check_menu';
        checkData(this, url);
    });

    //select parent
    $("#form_edit input[name='parent']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_parent')?>",
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
                  url:"<?php echo site_url($url.'/select_parent')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res[0]); });
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
                            window.reload_table_menu();
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


function checkData(ele, url){
    var input = $(ele);
    if (input.val() === "") {
        input.closest('.form-group').removeClass('has-error').removeClass('has-success');
        $('.fa-check, fa-warning', input.closest('.form-group')).remove();
        return;
    }
    input.attr("readonly", true). attr("disabled", true).addClass("spinner");
    var param = { val: input.val() };
    $.post(url, param, function (res) {
        input.attr("readonly", false).attr("disabled", false).removeClass("spinner");
        // change popover font color based on the result
        if (res.status == 1) {
            input.closest('.form-group').removeClass('has-error').addClass('has-success');
            $('.fa-warning', input.closest('.form-group')).remove();
            input.before('<i class="fa fa-check"></i>');
        } else {
            input.closest('.form-group').removeClass('has-success').addClass('has-error');
            $('.fa-check', input.closest('.form-group')).remove();
            input.before('<i class="fa fa-warning"></i>');
        }
    }, 'json');
}

</script>