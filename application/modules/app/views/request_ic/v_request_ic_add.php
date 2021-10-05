<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-3"><b>BSC</b>
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <select name="id_bsc" class="form-control select2_biasa" placeholder="BSC" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($bsc as $row){ ?>
                                <option value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3"><b>SI</b>
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input name="id_si" type="text" class="required form-control" placeholder="SI"  data-bvalidator="required"  />
                        <input name="pic_si" id="pic_si" type="hidden" class="required form-control" placeholder="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3"><b>PIC SI</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input readonly="readonly" id="pic_si_name" type="text" class="required form-control" placeholder="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3"><b>Keterangan Request</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea name="keterangan" class="form-control" rows="3" data-bvalidator="required"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3"><b>Attachment</b>
                        <span class="required" aria-required="true"></span>
                    </label>
                    <label class="control-label col-md-8" style="text-align:left;font-weight:bold;">:
                            <a href="javascript:;" class="btn btn-sm btn-primary btn_upload_file_request_ic"> 
                                Upload File
                            </a>
                            <input name="id_from" id="id_from" type="hidden" class="required form-control" value="<?='9'.date('Ymdhis').''.rand(1,100);?>"/>
                            <div id="list_file_request_ic"></div>
                    </label>
                </div>
                <br><br><br>

              <hr style="margin-top:0px;">

          </div>
         
      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?=csrf_get_token();?>">
                <button id="btn_save" class="btn btn-primary"> Save </button>
          </div>
      </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function () {
    
    //load awal
    var bsc         =  $(".table_request_ic select[name='global_id_bsc']").val();
    var si          =  $(".table_request_ic input[name='global_id_si']").val();
    if(bsc != ''){
        $("#form_add select[name='id_bsc'] option[value='"+bsc+"']").attr('selected','selected');
    }
    if(si != ''){
        $("#form_add input[name='id_si']").val(si);
    }

    //format angka
    $('.angka').inputmask('decimal', {
        alias: 'numeric',
        autoGroup: true,
        radixPoint:".", 
        groupSeparator: ",", 
        digits: 5,
        rightAlign : false,
        prefix: ''
    });

    //select2 biasa
    $("#form_add .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    });
    


    //select si
    $("#form_add input[name='id_si']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_si_pic')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_bsc = $("#form_add select[name='id_bsc']").val();
            return { q: term, id_bsc:id_bsc};
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            var start_date = $("#form_add #start_date").val();
            var end_date = $("#form_add #end_date").val();
            if (id !== "") {
              $.ajax({
                  url:"<?php echo site_url($url.'/select_si_pic')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res[0]); });
            }
        },
        formatResult: function(item){ return item.name },
        formatSelection: function(item){ 
            $('#form_add #pic_si').val(item.pic_si);
            $('#form_add #pic_si_name').val(item.pic_si_name);
            return item.name; 
        }
    }).on('change', function(event) { 
        //change
    });


    //save data
    $('#form_add #btn_save').on('click',function(){
        
        //cek inputan kosong
        $('#form_add').bValidator();
        $('#form_add').submit();
        if($('#form_add').data('bValidator').isValid()){

            //cek file tidak boleh kosong
            var cek_file = $('#list_file_request_ic').html();
            if(cek_file == ''){ alert('File Upload Masih Kosong !'); return true;}

            //save data
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
                            $('.btn_show_filter').click();
                            $('.btn_show_filter').click();
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

    //======================================================================
    //popup upload
    $('#form_add').on('click', '.btn_upload_file_request_ic', function(e) {
        $('#popup_upload_request_ic').modal();
        var id  = $('#form_add').find('#id_from').val();
        var url = "<?=site_url($url);?>/load_popup_upload_request_ic";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_upload_request_ic',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_upload_request_ic').html(msg);
            Metronic.unblockUI('#load_upload_request_ic');
        });
    });
    window.list_file_request_ic = function(){
        var id          = $('#form_add').find('#id_from').val();
        var param       = {id:id};
        var url         = "<?=site_url($url);?>/list_file_request_ic";
        Metronic.blockUI({ target: '#list_file_request_ic',  boxed: true});
        $.post(url, param, function(msg){
            $('#form_add').find('#list_file_request_ic').html(msg);
            Metronic.unblockUI('#list_file_request_ic');
        });
    }
    //========================================================================

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');

});
</script>