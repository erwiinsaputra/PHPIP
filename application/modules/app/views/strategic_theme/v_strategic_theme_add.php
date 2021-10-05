<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
          <div class="col-md-12">
              <div class="form-group">
                  <label class="control-label col-md-3">Strategic Theme
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="name" type="text" class="form-control" placeholder="Strategic Theme" data-bvalidator="required" autocomplete="off">
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
                  <label class="control-label col-md-3">Description
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="description" type="text" class="form-control" placeholder="Description" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Urutan
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="order" type="text" class="form-control angka" placeholder="Urutan" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-3">Icon
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8" style="margin-top:10px;">
                        <!-- <a href="<?=base_url()?>public/files/strategy_map/<?=@$data->file_name;?>.svg" target="_blank"><?=@$data->file_name;?></a> -->
                        <input name="file_upload" id="file_upload" type="file" class="form-control" placeholder="" data-bvalidator="required" autocomplete="off">
                    </div>
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
        //cek file extention file
        var fname = $('#file_upload').val();
        var re = /(\.png|\.PNG)$/i;
        if (!re.exec(fname)) {
            alert("File extension not supported!");
        }
	    $('#form_add').bValidator();
	});

    $('#form_add').on('submit',function(e){
	    e.preventDefault();
	    var param = new FormData(this);
        if($('#form_add').data('bValidator').isValid()){
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
	              var url = "<?=site_url($url);?>/save_add";
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
	                      $('#popup_add').modal('hide');
	                      toastr.options = call_toastr('4000');
	                      if(msg.status == '1'){
                              window.reload_table_strategic_theme();
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

    
    //format angka
    $('.angka').inputmask('numeric', {
        'alias': 'numeric',
        'autoGroup': true,
        rightAlign : false
    });

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>