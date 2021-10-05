<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
  <div class="row">
          <div class="col-md-12">
              <div class="form-group">
                  <label class="control-label col-md-3">Periode
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->start_year;?>" name="start_year" type="text" class="form-control date_year" placeholder="Start Year" data-bvalidator="required" autocomplete="off" style="width:100px;float:left;">
                      <div style="float:left;font-size:15px;margin: 5px;"><b>-</b></div>
                      <input value="<?=@$data->end_year;?>" readonly="readonly" id="end_year" name="end_year" type="text" class="form-control date_year" placeholder="End Year" data-bvalidator="required" autocomplete="off" style="width:100px;float:left;">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Strategic Theme
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=str_replace(', ',',',@$data->id_strategic_theme);?>" name="id_strategic_theme" id="id_strategic_theme" type="text" class="form-control" placeholder="Strategic Theme" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>">
                <button id="btn_update" class="btn btn-primary">Update</button>
          </div>
      </div>
  </div>
</form>


<script type="text/javascript">
$(document).ready(function () {

    //date year
    $('#form_edit .date_year').datepicker({
        format:"yyyy", 
        viewMode: 'years', 
        minViewMode:"years", 
        autoclose: true
    }).on('changeDate', function(ev){
    	//end year
        var start_year = $(this).val();
        var end_year = 4 + parseFloat(start_year);
        $('#form_edit #end_year').val(end_year);
    });

    
    //select strategic_theme
    $("#form_edit #id_strategic_theme").select2({
		allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_strategic_theme'); ?>",
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
                $.ajax("<?php echo site_url($url.'/select_strategic_theme')?>" +"/"+ id, {
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
                            window.reload_table_periode();
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