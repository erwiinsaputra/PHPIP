<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3"><b>Periode</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <div class="input-group">
                        <input <?=$disabled?> type="text" value="<?=substr(@$data->start_date,0,7)?>" id="start_date" name="start_date" class="form-control" readonly="readonly" data-bvalidator="required"/>
                        <span class="input-group-addon"> To </span>
                        <input <?=$disabled?> type="text" value="<?=substr(@$data->end_date,0,7)?>" id="end_date" name="end_date"  class="form-control" readonly="readonly" data-bvalidator="required"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                  <label class="control-label col-md-3"><b>BSC</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select <?=$disabled?> name="id_bsc" id="id_bsc" class="form-control" placeholder="BSC" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($bsc as $row){ ?>
                                <option <?=(@$data->id_bsc == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                  </div>
            </div>
            <div id="input_parent_so" style="<?=(@$data->parent_so == '' ? 'display:none;' : '')?>">
                    <div class="form-group">
                        <label class="control-label col-md-3"><b>SO Parent</b>
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-8">
                            <input <?=$readonly?> value="<?=@$data->parent_so;?>" name="parent_so" id="parent_so"  type="text" class="required form-control" placeholder="SO Parent" />
                        </div>
                    </div>
            </div>
            <div class="form-group">
                  <label class="control-label col-md-3"><b>Perspective</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                     <input <?=$readonly?> value="<?=@$data->id_perspective;?>" name="id_perspective" id="id_perspective" type="text" class="required form-control" placeholder="Perspective"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>PIC</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=$readonly?> value="<?=@$data->pic_so;?>" name="pic_so" id="pic_so" type="text" class="required form-control" placeholder="PIC SO"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>SO Title</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=$readonly?> value="<?=@$data->name;?>" name="name" id="name" type="text" class="form-control" placeholder="SO Title" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>SO Number</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=$readonly?> value="<?=@$data->code;?>" name="code" id="code"  type="text" class="form-control" placeholder="SO Number" data-bvalidator="" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Description</b>
                    <span class="required" aria-required="true"></span>
                  </label>
                  <div class="col-md-8">
                      <input <?=$readonly?> value="<?=@$data->description;?>" name="description" type="text" class="form-control" placeholder="Description" data-bvalidator="" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Attachments</b>
                    <span class="required" aria-required="true"></span>
                  </label>
                  <label class="control-label col-md-8" style="text-align:left;font-weight:bold;">:
                        <a href="javascript:;" class="btn btn-sm btn-primary btn_upload_file_so" idnya="<?=@$id;?>"> 
                            Upload File
                        </a>
                        <div id="list_file_so">
                            <?=@$html_list_file_so;?>
                        </div>
                  </label>
              </div>
          </div>
      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                <input type="hidden" id="edit" value="yes" >
                <?php if($type == 'view'){ ?>
                    <br/><br/>
                    <button title="Approve" id="<?=$id?>" val="3" class="btn btn-primary btn_change_status"><i class="fa fa-check"></i> Approve</button>
                    <button title="Reject" id="<?=$id?>" val="4" class="btn btn-danger btn_change_status"><i class="fa fa-times"></i> Reject</button>
                <?php }else{ ?>
                    <br/><br/>
                    <button title="Reject" id="<?=$id?>" val="4" class="btn btn-danger btn_change_status"><i class="fa fa-times"></i> Reject</button>
                    <button id="btn_update" class="btn btn-primary">Update</button>
                <?php } ?>
          </div>
      </div>
  </div>
</form>


<script type="text/javascript">
$(document).ready(function () {

    //range date
    $("#form_edit #start_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#end_date').datepicker('setStartDate', minDate);
    });
    $("#form_edit #end_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#start_date').datepicker('setEndDate', minDate);
    });

    //select2 biasa
    $("#form_edit .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 biasa2
    $("#form_edit .select2_biasa2").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 biasa2
    $("#form_edit #id_bsc").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        //change
        var id_bsc = $(this).val();
        if(id_bsc == '1'){
            $("#form_edit #input_parent_so").hide();
        }else{
            $("#form_edit #input_parent_so").show();
        }
    });

    //select perspective
    $("#form_edit input[name='id_perspective']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_perspective')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            return { q: term };
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
              $.ajax({
                  url:"<?php echo site_url($url.'/select_perspective')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res[0]); });
            }
        },
        formatResult: function(item){ return item.name },
        formatSelection: function(item){ return item.name; }
    }).on('change', function(event) { 
        //change
    });


    //select parent so
    $("#form_edit input[name='parent_so']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_parent_so')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_perspective = $("#form_edit input[name='id_perspective']").val();
            var id_periode = $("#form_edit select[name='id_periode']").val();
            return { q: term, id_perspective:id_perspective, id_periode:id_periode };
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
              $.ajax({
                  url:"<?php echo site_url($url.'/select_parent_so')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res[0]); });
            }
        },
        formatResult: function(item){ return item.name },
        formatSelection: function(item){ 
            var edit = $('#form_edit #edit').val();
            if(edit == 'yes'){ $('#form_edit #edit').val('no'); return item.name;}
            $('#form_edit #pic_so').val(item.pic_so);
            $('#form_edit #id_perspective').val(item.id_perspective);
            $('#form_edit #name').val(item.name_so);
            $('#form_edit #code').val(item.code);
            $('#form_edit #pic_so').change();
            $('#form_edit #id_perspective').change();
            return item.name; 
        }
    });


    //select pic so
    $("#form_edit input[name='pic_so']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_so'); ?>",
            dataType: 'json', quietMillis: 250, type:"POST",
            data: function (term, page) {
                return { q: term};
            },
            results: function (data, page) { return { results: data.item }; },
            cache: true
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
                $.ajax("<?php echo site_url($url.'/select_pic_so')?>", {
                dataType: "json", type:"POST",
                data:{ id: id}
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
                        $('#popup_view').modal('hide');
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_so();
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

    //btn change status
    $('#form_edit').on('click', '.btn_change_status', function(e) {
        //cek file
        var jum_file = $('#jum_file').val();
        if(jum_file <= 0){
            alert('File Attachment Masih Kosong!');
            return true;
        }
        //confirm
        var id = $(this).attr('id');
        var val = $(this).attr('val');
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
                var param  = {id:id, val:val, token:token};
                $.post(url, param, function(msg){
                    window.reload_table_so();
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                    $('#popup_edit').modal('hide');
                    $('#popup_view').modal('hide');
                }, 'json');
        });
    });

    //popup upload
    $('#form_edit').on('click', '.btn_upload_file_so', function(e) {
        $('#popup_upload_so').modal();
        var id  = $(this).attr('idnya');
        var url = "<?=site_url($url);?>/load_popup_upload_so";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_upload_so',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_upload_so').html(msg);
            Metronic.unblockUI('#load_upload_so');
        });
    });
    window.list_file_so = function(){
        var id          = "<?=$id?>";
        var param       = {id:id};
        var url         = "<?=site_url($url);?>/list_file_so";
        Metronic.blockUI({ target: '#list_file_so',  boxed: true});
        $.post(url, param, function(msg){
            $('#form_edit').find('#list_file_so').html(msg);
            Metronic.unblockUI('#list_file_so');
        });
    }

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>