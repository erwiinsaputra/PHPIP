<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                  <label class="control-label col-md-3">Strategic Theme
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->name;?>" name="name" type="text" class="form-control" placeholder="Strategic Theme" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Code
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->code;?>" name="code" type="text" class="form-control" placeholder="Code" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Description
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->description;?>" name="description" type="text" class="form-control" placeholder="Description" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3">Urutan
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->order;?>" name="order" type="text" class="form-control angka" placeholder="Urutan" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-3">Icon
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8" style="margin-top:10px;">
                        <a href="<?=base_url()?>public/files/icon_strategic_theme/<?=@$data->icon;?>" target="_blank"><?=@$data->icon;?></a>
                        <input name="file_upload" id="file_upload" type="file" class="form-control" placeholder="" data-bvalidator="required" autocomplete="off">
                        <input type="hidden" name="file_old" value="<?=@$data->icon;?>">
                    </div>
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
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //update data
	$('#form_edit #btn_update').on('click',function(){
        //cek file extention file
        var fname = $('#file_upload').val();
        if(fname != ''){
            var re = /(\.png|\.PNG)$/i;
            if (!re.exec(fname)) {
                alert("File extension not supported!");
            }
        }
	    $('#form_edit').bValidator();
	});

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