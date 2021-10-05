<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
            <div class="col-md-12">
            
                <div class="form-group">
                  <label class="control-label col-md-3">Periode
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select  name="id_periode" class="form-control select2_biasa" placeholder="Periode" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($periode as $row){ ?>
                                <option value="<?=$row->id;?>"><?=$row->start_year.'-'.$row->end_year;?></option>
                            <?php } ?>
                        </select>
                  </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3"><b>BSC</b>
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <select name="id_bsc" id="id_bsc"  class="form-control" placeholder="BSC" data-bvalidator="required">
                            <option value=""></option>
                            <?php foreach($bsc as $row){ ?>
                                <option value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">File
                    <span class="required" aria-required="true"></span>
                  </label>
                  <div class="col-md-8">
                      <input name="file_upload" type="file" class="form-control" placeholder="Code" data-bvalidator="" autocomplete="off">
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

    
    //select2 biasa2
    $("#form_add #id_bsc").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
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
                            window.reload_table_template_strategy_map();
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