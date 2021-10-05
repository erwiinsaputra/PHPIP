<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">

    <div class="row">

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3"><b>NIP / Nama</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input type="text" name="nip" placeholder="Select" class="form-control" data-bvalidator="required"/>
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
                <label class="control-label col-md-3"><b>SI Title</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="id_si" id="role" type="text" class="required form-control" placeholder="Select" data-bvalidator="required"/>
                </div>
            </div>
        </div>
        <br><br>
        <br><br>
        <br><br>
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
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    });

    
    //select user_ic_si
    $("#form_add input[name='nip']").select2({
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
            $("#form_add input[name='role_name']").val(item.role_name);
            window.cek_user_ic_si(item.id);
            return item.name; 
        }
    }).on('change', function(event) { 
        //change
    });


    //select role
    $("#form_add input[name='id_si']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        allowClear:true,
        multiple: true,
        ajax: {
          url: "<?php echo site_url($url.'/select_si')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_bsc = $("#form_add select[name='id_bsc']").val();
            return { q: term, id_bsc:id_bsc };
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
              $.ajax({
                  url:"<?php echo site_url($url.'/select_si')?>",
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
                            window.reload_table_user_ic_si();
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

    //cek user_ic_si sudah ada
    
    
    //cek user_ic_si
    window.cek_user_ic_si = function(nip){
        var url = "<?=site_url($url);?>/cek_user_ic_si";
        var param = {nip:nip};
        $.post(url, param, function(msg){
            if(msg.cek == '1'){
                alert('User Sudah Terdaftar, \nHarap cek pada table, \nHarap Edit Data \nUbah status Active Atau Tambahkan Role');
                $("#form_add input[name='nip']").val('');
                $("#form_add input[name='nip']").change();
                $("#form_add input[name='role_name']").val('');
            }
        },'json');
    }

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});


</script>