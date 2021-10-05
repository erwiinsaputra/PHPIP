<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                  <label class="control-label col-md-3">Balanced Scorecard
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->name;?>" name="name" type="text" class="form-control" placeholder="Balanced Scorecard" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Workunit
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select  name="id_workunit" class="form-control select2_biasa" placeholder="Workunit" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($workunit as $row){ ?>
                                <option <?=(@$data->id_workunit == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Type
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select  name="id_bsc_type" class="form-control select2_biasa" placeholder="Type" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($bsc_type as $row){ ?>
                                <option <?=(@$data->id_bsc_type == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Perspective
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=str_replace(', ',',',@$data->id_perspective);?>" name="id_perspective" id="id_perspective" type="text" class="form-control" placeholder="Perspective" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Code
                    <span class="required" aria-required="true"></span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->code;?>" name="code" type="text" class="form-control" placeholder="Code" data-bvalidator="" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Description
                    <span class="required" aria-required="true"></span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->description;?>" name="description" type="text" class="form-control" placeholder="Description" data-bvalidator="" autocomplete="off">
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

    //select2 biasa
    $("#form_edit .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select perspective
    $("#form_edit #id_perspective").select2({
		allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_perspective'); ?>",
            dataType: 'json',
            quietMillis: 250,
            data: function (term, page) { return { q: term }; },
            results: function (data, page) { return { results: data.item }; },
            cache: true
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
                var id = id.replace(/,/g , "-");
                $.ajax("<?php echo site_url($url.'/select_perspective')?>" +"/"+ id, {
                dataType: "json"
                }).done( function(data) { callback(data); });
            }
        },
        formatResult: function(item){return item.name;},
        formatSelection: function(item){return item.name;}
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
                            window.reload_table_bsc();
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