<form method="post" id="form_edit" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">

    <div class="row">

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3"><b>NIP / Nama</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->nip;?>" readonly="readonly" type="text" name="nip" placeholder="Select" class="form-control" data-bvalidator="required"/>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3"><b>PIC</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input readonly="readonly" name="role_name" id="role_name" type="text" class="form-control" placeholder="Role Name"/>
                </div>
            </div>
        </div>
        <br><br>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3"><b>KPI-SO Title</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->id_kpi_so;?>" name="id_kpi_so" id="id_kpi_so" type="text" class="form-control" placeholder="Select" data-bvalidator="required"/>
                </div>
            </div>
        </div>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
    </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?=csrf_get_token();?>">
                <input type="hidden" name="id" value="<?=$id;?>">
                <button id="btn_save" class="btn btn-primary">Save</button>
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

    
    //select user_kpi_so
    $("#form_edit input[name='nip']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        allowClear:false,
        ajax: {
          url: "<?php echo site_url($url.'/select_nip')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            return { q: term};
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
              $.ajax({
                  url:"<?php echo site_url($url.'/select_nip')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res[0]); });
            }
        },
        formatResult: function(item){ return item.name },
        formatSelection: function(item){   
            $("#form_edit input[name='role_name']").val(item.role_name);
            return item.name;   
        }
    }).on('change', function(event) { 
        //change
    });


    //select role
    $("#form_edit input[name='id_kpi_so']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        allowClear:true,
        multiple: true,
        ajax: {
          url: "<?php echo site_url($url.'/select_kpi_so')?>",
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
                  url:"<?php echo site_url($url.'/select_kpi_so')?>",
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


    //save data
    $('#form_edit #btn_save').on('click',function(){
        $('#form_edit').bValidator();
        $('#form_edit').submit();
        if($('#form_edit').data('bValidator').isValid()){

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
                    var url   = "<?=site_url($url);?>/save_edit";
                    var param = $('#form_edit').serializeArray();
                    Metronic.blockUI({ target: '#form_edit',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('#form_edit');
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            $('#popup_edit').modal('hide');
                            window.reload_table_user_kpi_so();
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