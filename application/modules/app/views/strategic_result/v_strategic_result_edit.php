<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                  <label class="control-label col-md-3"><b>Periode</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select disabled name="id_periode"  id="id_periode" class="form-control" placeholder="Periode" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($periode as $row){ ?>
                                <option <?=(@$data->id_periode == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->start_year.'-'.$row->end_year;?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" value="<?=@$data->id_periode?>" name="id_periode"  class="form-control" data-bvalidator="required"/>
                  </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>BSC</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <select <?=$disabled?> name="id_bsc" id="id_bsc"  class="form-control select2_biasa" placeholder="BSC" data-bvalidator="required">
                        <option value=""></option>
                        <?php foreach($bsc as $row){ ?>
                            <option  <?=(@$data->id_bsc == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                  <label class="control-label col-md-3"><b>Strategic Theme</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select <?=$disabled?>  name="id_strategic_theme" class="form-control select2_biasa" placeholder="Strategic Theme" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($strategic_theme as $row){ ?>
                                <option  <?=(@$data->id_strategic_theme == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                  </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>PIC</b>
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input <?=$readonly?> value="<?=@$data->pic_sr;?>" name="pic_sr" id="pic_sr" type="text" class="required form-control" placeholder="PIC"  data-bvalidator="required"  />
                    </div>
              </div>

            <div class="form-group">
                  <label class="control-label col-md-3"><b>Code</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=$readonly?> value="<?=@$data->code;?>" name="code" type="text" class="form-control" placeholder="Code" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Strategic Result</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=$readonly?> value="<?=@$data->name;?>" name="name" type="text" class="form-control" placeholder="Strategic Result" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Indikator</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=$readonly?> value="<?=@$data->indikator;?>" name="indikator" type="text" class="form-control" placeholder="Indikator" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Polarisasi</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select <?=$disabled?> name="polarisasi" id="polarisasi" class="form-control" placeholder="Polarisasi" data-bvalidator="required">
                            <option value=""></option>
                            <?php foreach($polarisasi as $row){ ?>
                                <option <?=(@$data->polarisasi == $row->id ? 'selected' : '') ?>  value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>  
                  </div>
              </div>
            
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Ukuran</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=$readonly?> value="<?=@$data->ukuran;?>" name="ukuran" type="text" class="form-control" placeholder="Ukuran" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Description</b>
                    <span class="required" aria-required="true"></span>
                  </label>
                  <div class="col-md-8">
                        <textarea <?=$readonly?> name="description" class="form-control" data-bvalidator="" rows="2"><?=@$data->description;?></textarea>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Attachments</b>
                    <span class="required" aria-required="true"></span>
                  </label>
                  <label class="control-label col-md-8" style="text-align:left;font-weight:bold;">:
                        <a href="javascript:;" class="btn btn-sm btn-primary btn_upload_file_sr" idnya="<?=@$id;?>"> 
                            Upload File
                        </a>
                        <div id="list_file_sr">
                            <?=@$html_list_file_sr;?>
                        </div>
                  </label>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Long term Target</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <div class="input_single" style="<?=(@$data->polarisasi == '10' ? 'display:none;':'')?>">
                            <input <?=$readonly?> value="<?=@$data->target;?>" name="target" type="text" class="form-control angka" placeholder="Target" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                        </div>
                        <div class="input_range" style="<?=(@$data->polarisasi == '10' ? '':'display:none;')?>">
                            <input <?=$readonly?> value="<?=@$data->target_from;?>" name="target_from" type="text" class="form-control angka" placeholder="Target From" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                            <input <?=$readonly?> value="<?=@$data->target_to;?>" name="target_to" type="text" class="form-control angka" placeholder="Target To" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                        </div>
                  </div>
              </div>

              <div class="col-md-12" style="padding:0 45px 0 45px;">
                <div id="load_table_target_sr"></div>
                <hr style="margin-top:0px;">
              </div>

          </div>
      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                <input type="hidden" name="type" id="type" value="<?=@$type;?>">
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

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');

    //change year
    window.load_table_target_sr = function (){
        var id = "<?=$id?>";
        var periode  =  $("#form_edit select[name='id_periode'] option:selected").text();
        var arr = periode.split('-');
        var start_year = arr[0];
        var end_year = arr[1];
        var url = "<?=site_url($url)?>/load_table_target_sr";
        var param = {id:id, start_year:start_year, end_year:end_year};
        Metronic.blockUI({ target: '#form_edit #load_table_target_sr',  boxed: true});
        $.post(url, param, function(msg){
            $('#form_edit #load_table_target_sr').html(msg);
            Metronic.unblockUI('#form_edit #load_table_target_sr');
        });
    }
    window.load_table_target_sr();

    //select2 biasa
    $("#form_edit #id_periode").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        window.load_table_target_sr();
    });

    //select2 biasa
    $("#form_edit .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    });

     //select strategic_theme
     $("#form_edit input[name='id_strategic_theme']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_strategic_theme')?>",
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
                  url:"<?php echo site_url($url.'/select_strategic_theme')?>",
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


    //select pic sr
    $("#form_edit input[name='pic_sr']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_sr'); ?>",
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
                $.ajax("<?php echo site_url($url.'/select_pic_sr')?>", {
                dataType: "json", type:"POST",
                data:{ id: id}
                }).done( function(data) { callback(data); });
            }
        },
        formatResult: function(item){return item.name;},
        formatSelection: function(item){return item.name;}
    });


    //select2 polarisasi 
    $("#form_edit #polarisasi").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        var val = $(this).val();
        if(val == '10'){
            $('#form_edit').find('.input_single').hide();
            $('#form_edit').find('.input_range').show();
        }else{
            $('#form_edit').find('.input_single').show();
            $('#form_edit').find('.input_range').hide();
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
                    window.reload_table_strategic_result();
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

    //update data
    $('#form_edit #btn_update').on('click',function(){

        //validasi target
        var polarisasi = $("#form_edit #polarisasi").val();
        if(polarisasi == '10'){
            $("#form_edit input[name='target']").attr('data-bvalidator','');
            $("#form_edit input[name='target_from']").attr('data-bvalidator','required');
            $("#form_edit input[name='target_to']").attr('data-bvalidator','required');
        }else{
            $("#form_edit input[name='target']").attr('data-bvalidator','required');
            $("#form_edit input[name='target_from']").attr('data-bvalidator','');
            $("#form_edit input[name='target_to']").attr('data-bvalidator','');
        }


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
                            window.reload_table_strategic_result();
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

    //popup upload
    $('#form_edit').on('click', '.btn_upload_file_sr', function(e) {
        $('#popup_upload_sr').modal();
        var id  = $(this).attr('idnya');
        var url = "<?=site_url($url);?>/load_popup_upload_sr";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_upload_sr',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_upload_sr').html(msg);
            Metronic.unblockUI('#load_upload_sr');
        });
    });
    window.list_file_sr = function(){
        var id          = "<?=$id?>";
        var param       = {id:id};
        var url         = "<?=site_url($url);?>/list_file_sr";
        Metronic.blockUI({ target: '#list_file_sr',  boxed: true});
        $.post(url, param, function(msg){
            $('#form_edit').find('#list_file_sr').html(msg);
            Metronic.unblockUI('#list_file_sr');
        });
    }

    
});
</script>