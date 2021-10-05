<form method="post" id="form_edit" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-3"><b>BSC</b>
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <select <?=@$disabled?> class="form-control select2_biasa" placeholder="BSC" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($bsc as $row){ ?>
                                <option <?=(@$data->id_bsc == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                        <input name="id_bsc" value="<?=@$data->id_bsc?>"  type="hidden" class="required form-control" placeholder="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3"><b>SI</b>
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input <?=@$readonly?> value="<?=@$data->id_si;?>" name="id_si" type="text" class="required form-control" placeholder="SI"  data-bvalidator="required"  />
                        <input value="<?=@$data->id_pic;?>" name="pic_si" id="pic_si" type="hidden" class="required form-control" placeholder="" />
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
                    <label class="control-label col-md-3"><b>Keterangan <br>Request</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea <?=@$readonly?> name="keterangan" class="form-control" rows="3" data-bvalidator="required"><?=@$data->keterangan;?></textarea>
                    </div>
                </div>

                <?php if(@$data->keterangan_approval != ''){?>
                <div class="form-group">
                    <label class="control-label col-md-3"><b>Keterangan <br>Approval</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea <?=@$readonly?> name="keterangan" class="form-control" rows="3" data-bvalidator="required"><?=@$data->keterangan_approval;?></textarea>
                    </div>
                </div>
                <?php } ?>

                <?php if(@$data->status_send_to_admin == '' || @$data->keterangan_send_to_admin != ''){ ?>
                <div class="form-group">
                    <label class="control-label col-md-3"><b>Keterangan <br>Request Assist Admin</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea <?=(@$data->status_send_to_admin == '' ? '' : @$readonly)?> id="keterangan_send_to_admin" name="keterangan_send_to_admin" class="form-control" rows="3" data-bvalidator="required"><?=@$data->keterangan_send_to_admin;?></textarea>
                    </div>
                </div>
                <?php } ?>

                <?php if(@$data->status_send_to_admin == '2' || @$data->keterangan_done_request_admin != ''){ ?>
                <div class="form-group">
                    <label class="control-label col-md-3"><b>Keterangan <br>Done Request Assist Admin</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea <?=(@$data->status_send_to_admin == '2' ? '' : @$readonly)?> id="keterangan_done_request_admin"name="keterangan_done_request_admin" class="form-control" rows="3" data-bvalidator="required"><?=@$data->keterangan_done_request_admin;?></textarea>
                    </div>
                </div>
                <?php } ?>

                <?php if(@$readonly == ''){ ?>
                    <div class="form-group">
                        <label class="control-label col-md-3"><b>Attachment</b>
                            <span class="required" aria-required="true"></span>
                        </label>
                        <label class="control-label col-md-8" style="text-align:left;font-weight:bold;">:
                                <a href="javascript:;" class="btn btn-sm btn-primary btn_upload_file_request_ic"> 
                                    Upload File
                                </a>
                                <br><br>
                                <div id="list_file_request_ic"><?=@$html_list_file_request_ic?></div>
                        </label>
                    </div>
                    <br><br><br>
                <?php }else{ ?>
                    <div class="form-group">
                        <label class="control-label col-md-3"><b>Attachment</b>
                            <span class="required" aria-required="true"></span>
                        </label>
                        <div style="margin-top:0.5em;font-size:1.2em !important;"><?=@$html_list_file_request_ic?></div>
                    </div>
                    <br><br><br>
                <?php }?>

              <hr style="margin-top:0px;">

          </div>
         
      </div>
  </div>

  <?php if(@$readonly == ''){ ?>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                <input type="hidden" name="type" id="type" value="<?=@$type;?>">
                <button id="btn_update" class="btn btn-primary">Update</button>
          </div>
      </div>
  </div>
  <?php } ?>


  
</form>

<script type="text/javascript">
$(document).ready(function () {
    
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
    $("#form_edit .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    });
    
    //select si
    $("#form_edit input[name='id_si']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_si_pic')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_bsc = $("#form_edit select[name='id_bsc']").val();
            return { q: term, id_bsc:id_bsc};
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            var start_date = $("#form_edit #start_date").val();
            var end_date = $("#form_edit #end_date").val();
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
            $('#form_edit #pic_si').val(item.pic_si);
            $('#form_edit #pic_si_name').val(item.pic_si_name);
            return item.name; 
        }
    }).on('change', function(event) { 
        //change
    });

    //load awal
    $("#form_edit input[name='id_si']").change();


    //update data
    $('#form_edit #btn_update').on('click',function(){
        $('#form_edit').bValidator();
        $('#form_edit').submit();
        if($('#form_edit').data('bValidator').isValid()){

            //cek file tidak boleh kosong
            var cek_file = $('#list_file_request_ic').html();
            if(cek_file == ''){ alert('File Upload Masih Kosong !'); return true;}

            //save data
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
                            window.reload_table_request_ic();
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


    //======================================================================
    //popup upload
    $('#form_edit').on('click', '.btn_upload_file_request_ic', function(e) {
        $('#popup_upload_request_ic').modal();
        var id  = "<?=@$data->id;?>";
        var url = "<?=site_url($url);?>/load_popup_upload_request_ic";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_upload_request_ic',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_upload_request_ic').html(msg);
            Metronic.unblockUI('#load_upload_request_ic');
        });
    });
    window.list_file_request_ic = function(){
        var id          = "<?=@$data->id;?>";
        var param       = {id:id};
        var url         = "<?=site_url($url);?>/list_file_request_ic";
        Metronic.blockUI({ target: '#list_file_request_ic',  boxed: true});
        $.post(url, param, function(msg){
            $('#form_edit').find('#list_file_request_ic').html(msg);
            Metronic.unblockUI('#list_file_request_ic');
        });
    }
    //========================================================================

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');

});
</script>