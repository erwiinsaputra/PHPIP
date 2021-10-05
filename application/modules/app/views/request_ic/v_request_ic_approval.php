<form method="post" id="form_approval" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-3"><b>BSC</b>
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <select disabled="disabled" class="form-control select2_biasa" placeholder="BSC" data-bvalidator="required"  >
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
                        <input readonly="readonly" value="<?=@$data->id_si;?>" name="id_si" type="text" class="required form-control" placeholder="SI"  data-bvalidator="required"  />
                        <input value="<?=@$data->id_pic;?>" name="pic_si" id="pic_si" type="hidden" class="required form-control" placeholder="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3"><b>Keterangan Request</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea readonly="readonly" name="keterangan" class="form-control" rows="3" data-bvalidator="required"><?=@$data->keterangan;?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3"><b>Keterangan Approval</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea name="keterangan_approval" id="keterangan_approval" class="form-control" rows="3" data-bvalidator="required"><?=@$data->keterangan_approval;?></textarea>
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
                            <br><br>
                            <div id="list_file_request_ic"><?=@$html_list_file_request_ic?></div>
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
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                <?php if(@$data->status_request == '2'){ ?>
                    <button title="Approve" id="<?=$id?>" id_si="<?=@$data->id_si?>" val="3" class="btn btn-primary btn_change_status"><i class="fa fa-check"></i> Approve</button>
                    <button title="Reject" id="<?=$id?>" id_si="<?=@$data->id_si?>" val="4" class="btn btn-danger btn_change_status"><i class="fa fa-times"></i> Reject</button>
                <?php }elseif(@$data->status_request == '3'){ ?>
                    <button title="Reject" id="<?=$id?>" id_si="<?=@$data->id_si?>" val="4" class="btn btn-danger btn_change_status"><i class="fa fa-times"></i> Reject</button>
                <?php }elseif(@$data->status_request == '4'){ ?>
                    <button title="Approve" id="<?=$id?>" id_si="<?=@$data->id_si?>" val="3" class="btn btn-primary btn_change_status"><i class="fa fa-check"></i> Approve</button>
                <?php } ?>
          </div>
      </div>
  </div>
  
</form>

<script type="text/javascript">
$(document).ready(function () {

    //select2 biasa
    $("#form_approval .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    });
    
    //select si
    $("#form_approval input[name='id_si']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_si_pic')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_bsc = $("#form_approval select[name='id_bsc']").val();
            return { q: term, id_bsc:id_bsc};
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            var start_date = $("#form_approval #start_date").val();
            var end_date = $("#form_approval #end_date").val();
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
            $('#form_approval #pic_si').val(item.pic_si);
            $('#form_approval #pic_si_name').val(item.pic_si_name);
            return item.name; 
        }
    }).on('change', function(event) { 
        //change
    });

    //load awal
    $("#form_approval input[name='id_si']").change();

    //btn change status
    $('#form_approval').on('click', '.btn_change_status', function(e) {
        
        //confirm
        var id = $(this).attr('id');
        var val = $(this).attr('val');
        var id_si = $(this).attr('id_si');
        var keterangan_approval = $("#form_approval #keterangan_approval").val();
        var status = $(this).attr('title');
        var mes = "Are you sure to "+status+" ?";
        var title = status;
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
                //change status
                var token  = $('#ex_csrf_token').val();
                var url    = '<?=site_url($url)?>/change_status';
                var param  = {id:id, id_si:id_si, val:val, keterangan_approval:keterangan_approval, token:token};
                $.post(url, param, function(msg){
                    window.reload_table_request_ic();
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                    $('#popup_approval').modal('hide');
                }, 'json');
        });
    });


     //======================================================================
    //popup upload
    $('#form_approval').on('click', '.btn_upload_file_request_ic', function(e) {
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
            $('#form_approval').find('#list_file_request_ic').html(msg);
            Metronic.unblockUI('#list_file_request_ic');
        });
    }
    //========================================================================

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');

});
</script>
