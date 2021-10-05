<form method="post" id="form_edit" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
            <div class="form-group">
                  <label class="control-label col-md-3">File Name
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input value="<?=@$data->name;?>" name="name" type="text" class="form-control" placeholder="Name" data-bvalidator="required" autocomplete="off">
                  </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                  <label class="control-label col-md-3">File
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8" style="margin-top:10px;">
                      <!-- <a href="<?=base_url()?>public/files/strategy_map/<?=@$data->file_name;?>.svg" target="_blank"><?=@$data->file_name;?></a> -->
                      <input name="file_upload" type="file" class="form-control" placeholder="" data-bvalidator="required" autocomplete="off">
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
                <input type="hidden" name="id_bsc" value="<?=@$data->id_bsc;?>"  >
                <input type="hidden" name="id_periode" value="<?=@$data->id_periode;?>"  >
                <button id="btn_update" class="btn btn-primary">Update</button>
          </div>
      </div>
  </div>
</form>


<script type="text/javascript">
$(document).ready(function () {

    //save data
	$('#form_edit #btn_update').on('click',function(){
	    $('#form_edit').bValidator();
	});


    //submit
    $('#form_edit').on('submit',function(e){
	    e.preventDefault();
	    var param = new FormData(this);
        if($('#form_edit').data('bValidator').isValid()){
	        //alert
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
	              //upload file
	              var url = "<?=site_url($url);?>/save_edit";
	              Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
	              $.ajax({
	                  type:'POST',
	                  url: url,
	                  data: param,
	                  dataType: 'json',
	                  cache:false,
	                  contentType: false,
	                  processData: false,
	                  success:function(msg){
	                      Metronic.unblockUI('.modal-dialog');
	                      $('#popup_edit').modal('hide');
	                      toastr.options = call_toastr('4000');
	                      if(msg.status == '1'){
	                          window.reload_table_template_excel_ic();
	                          toastr['success'](msg.message, "Success");
	                      }else{
	                          toastr['error'](msg.message, "Error");
	                      }
	                  }
	              });

	        });
	    }else{
	      alert('Data Harus Lengkap! \nCoba Cek Inputan');
	    }

    });

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>