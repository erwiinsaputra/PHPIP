<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
          <div class="col-md-12">
              <div class="form-group">
                  <label class="control-label col-md-3">Periode
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="start_year" type="text" class="form-control date_year" placeholder="Start Year" data-bvalidator="required" autocomplete="off" style="width:100px;float:left;">
                      <div style="float:left;font-size:15px;margin: 5px;"><b>-</b></div>
                      <input readonly="readonly" id="end_year" name="end_year" type="text" class="form-control" placeholder="End Year" data-bvalidator="required" autocomplete="off" style="width:100px;float:left;">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Strategic Theme
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="id_strategic_theme" id="id_strategic_theme" type="text" class="form-control" placeholder="Strategic Theme" data-bvalidator="required" autocomplete="off">
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

    //date year
    $('#form_add .date_year').datepicker({
        format:"yyyy", 
        viewMode: 'years', 
        minViewMode:"years", 
        autoclose: true
    }).on('changeDate', function(ev){
    	//end year
        var start_year = $(this).val();
        var end_year = 4 + parseFloat(start_year);
        $('#form_add #end_year').val(end_year);
    });


    //select strategic_theme
    $("#form_add #id_strategic_theme").select2({
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
                            window.reload_table_periode();
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