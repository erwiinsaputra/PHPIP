<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-3"><b>Periode</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input <?=@$readonly?> type="text" value="<?=substr(@$data->start_date,0,7)?>" id="start_date" name="start_date" class="form-control" readonly="readonly" data-bvalidator="required"/>
                            <input type="hidden" value="<?=substr(@$data->start_date,0,7)?>" name="start_date_old" class="form-control" data-bvalidator="required"/>
                            <span class="input-group-addon"> To </span>
                            <input <?=@$readonly?> type="text" value="<?=substr(@$data->end_date,0,7)?>" id="end_date" name="end_date"  class="form-control" readonly="readonly" data-bvalidator="required"/>
                            <input type="hidden" value="<?=substr(@$data->end_date,0,7)?>" name="end_date_old"  class="form-control" data-bvalidator="required"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3"><b>BSC</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select <?=@$disabled?>  class="form-control select2_biasa" placeholder="BSC" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($bsc as $row){ ?>
                                <option <?=(@$data->id_bsc == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                        <input name="id_bsc" value="<?=@$data->id_bsc?>"  type="hidden" class="required form-control" placeholder="" />
                  </div>
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Perspective</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input <?=@$readonly?> value="<?=@$data->id_perspective;?>" name="id_perspective" type="text" class="required form-control" placeholder="Perspective"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>SO</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input <?=@$readonly?> value="<?=@$data->id_so;?>"  name="id_so" type="text" class="required form-control" placeholder="SO"  data-bvalidator="required"  />
                        <input name="pic_so" id="pic_so" type="hidden" class="required form-control" placeholder="" />
                  </div>
              </div>
            </div>

            
            <div class="col-md-12">

              <hr style="margin-top:0px;">

              <div class="form-group">
                  <label class="control-label col-md-3"><b>Number/Prefix</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=@$readonly?> value="<?=@$data->code;?>" name="code" type="text" class="form-control angka" placeholder="Number/Prefix" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>KPI-SO</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=@$readonly?> value="<?=@$data->name;?>" name="name" type="text" class="form-control" placeholder="KPI-SO" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Polarisasi</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select  <?=@$disabled?> name="polarisasi" id="polarisasi" class="form-control" placeholder="Polarisasi" data-bvalidator="required">
                            <option value=""></option>
                            <?php foreach($arr_polarisasi as $row){ ?>
                                <option <?=(@$data->polarisasi == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>  
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>PIC KPI-SO</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input <?=@$readonly?> value="<?=str_replace(', ',',',@$data->pic_kpi_so);?>" name="pic_kpi_so" type="text" class="required form-control" placeholder="PIC KPI-SO"  data-bvalidator="required"  />
                        <input value="<?=str_replace(', ',',',@$data->user_pic_kpi_so);?>" name="user_pic_kpi_so" id="user_pic_kpi_so" type="hidden" class="form-control"/>
                        <input value="<?=str_replace(', ',',',@$data->user_pic_kpi_so);?>" name="user_pic_kpi_so_old" type="hidden" class="form-control"/>
                    </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3"><b>Assigned KPI-SO Manager</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input <?=$readonly?> value="<?=str_replace(', ',',',@$data->user_pic_manager);?>" name="user_pic_manager" id="user_pic_manager" type="text" class="required form-control" placeholder="Assigned KPI-SO Manager"  data-bvalidator="required"  />
                    <input value="<?=str_replace(', ',',',@$data->user_pic_manager);?>" name="user_pic_manager_old" type="hidden" class="form-control"/>
                </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Ukuran</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=@$readonly?> value="<?=@$data->ukuran;?>" name="ukuran" type="text" class="form-control" placeholder="Ukuran" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Frekuensi Pengukuran</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input <?=@$readonly?> value="<?=@$data->frekuensi_pengukuran;?>" name="frekuensi_pengukuran" type="text" class="form-control" placeholder="Frekuensi Pengukuran" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Attachment</b>
                    <span class="required" aria-required="true"></span>
                  </label>
                  <label class="control-label col-md-8" style="text-align:left;font-weight:bold;">:
                        <a href="javascript:;" class="btn btn-sm btn-primary btn_upload_file_kpi_so" idnya="<?=@$id;?>"> 
                            Upload File
                        </a>
                        <div id="list_file_kpi_so">
                            <?=@$html_list_file_kpi_so;?>
                        </div>
                  </label>
              </div>

              <hr style="margin-top:0px;">

          </div>

          <div class="col-md-12" style="padding:0 45px 0 45px;">
            <div id="load_table_target_kpi_so"></div>
            <hr style="margin-top:0px;">
          </div>

      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                <input type="hidden" name="type" id="type" value="<?=@$type;?>">
                <?php if($type == 'view'){ ?>
                    <button title="Approve" id="<?=$id?>" val="3" class="btn btn-primary btn_change_status"><i class="fa fa-check"></i> Approve</button>
                    <button title="Reject" id="<?=$id?>" val="4" class="btn btn-danger btn_change_status"><i class="fa fa-times"></i> Reject</button>
                <?php }else{ ?>
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
        $("#form_edit #end_date").datepicker('setStartDate', minDate);
    });
    $("#form_edit #end_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $("#form_edit #start_date").datepicker('setEndDate', minDate);
        window.load_table_target_kpi_so();
    });


    //change year
    window.load_table_target_kpi_so = function (){
        var id = "<?=@$data->id;?>";
        var polarisasi = "<?=@$polarisasi;?>";
        var start_date = $("#form_edit #start_date").val();
        var end_date = $("#form_edit #end_date").val();
        var type = $("#form_edit #type").val();
        if(start_date == '' || end_date == ''){ return true; }
        var url = "<?=site_url($url)?>/load_table_target_kpi_so";
        var param = {id:id, start_date:start_date, end_date:end_date, polarisasi:polarisasi, type:type};
        Metronic.blockUI({ target: '#form_edit #load_table_target_kpi_so',  boxed: true});
        $.post(url, param, function(msg){
            $("#form_edit #load_table_target_kpi_so").html(msg);
            Metronic.unblockUI('#form_edit #load_table_target_kpi_so');
        });
    }
    window.load_table_target_kpi_so();
 
    
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
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 id periode
    $("#form_edit .select2_biasa2").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 polarisasi 
    $("#form_edit #polarisasi").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
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
    $("#form_edit #polarisasi").change();


    //select perspective
    $("#form_edit input[name='id_perspective']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_perspective')?>",
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

    //select so
    $("#form_edit input[name='id_so']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_so')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_perspective = $("#form_edit input[name='id_perspective']").val();
            var id_bsc = $("#form_edit select[name='id_bsc']").val();
            var id_periode = $("#form_edit select[name='id_periode']").val();
            return { q: term, id_perspective:id_perspective, id_bsc:id_bsc, id_periode:id_periode };
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
              $.ajax({
                  url:"<?php echo site_url($url.'/select_so')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res[0]); });
            }
        },
        formatResult: function(item){ return item.name },
        formatSelection: function(item){ 
            $('#form_edit #pic_so').val(item.pic_so);
            return item.name; 
        }
    }).on('change', function(event) { 
        //change
    });


    //select pic multiple
    $("#form_edit input[name='pic_kpi_so']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_kpi_so'); ?>",
            dataType: 'json', quietMillis: 250, type:"POST",
            data: function (term, page) {
                var pic_so = $('#form_edit #pic_so').val();
                if(pic_so == ''){  alert('SO belum dipilih'); }
                return { q: term, pic_so:pic_so};
            },
            results: function (data, page) { return { results: data.item }; },
            cache: true
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
                $.ajax("<?php echo site_url($url.'/select_pic_kpi_so')?>", {
                dataType: "json", type:"POST",
                data:{ id: id}
                }).done( function(data) { callback(data); });
            }
        },
        formatResult: function(item){return item.name;},
        formatSelection: function(item){
            $("#form_edit input[name='user_pic_kpi_so']").val(item.user_pic_kpi_so);
            return item.name;
        }
    });


    //select pic ic
    $("#form_edit input[name='user_pic_manager']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_manager'); ?>",
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
                $.ajax("<?php echo site_url($url.'/select_pic_manager')?>", {
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
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_kpi_so();
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
                    window.reload_table_kpi_so();
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                    $('#popup_edit').modal('hide');
                }, 'json');
        });
    });

    //change year
    window.change_year_edit = function (){
        var periode  =  $("#form_edit select[name='id_periode'] option[selected='selected']").text();
        var pecah = periode.split(" - ");
        var start_year = pecah[0];
        $("#form_edit #start_year").val(start_year);
        for(i=0;i<5;i++){
            var year = parseFloat(start_year)+i;
            var a = $("#form_edit .target_"+i).children().text()+' '+year;
            $("#form_edit .target_"+i).children().text(a);
        }
    }
    window.change_year_edit();


    //popup upload
    $('#form_edit').on('click', '.btn_upload_file_kpi_so', function(e) {
        $('#popup_upload_kpi_so').modal();
        var id  = $(this).attr('idnya');
        var url = "<?=site_url($url);?>/load_popup_upload_kpi_so";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_upload_kpi_so',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_upload_kpi_so').html(msg);
            Metronic.unblockUI('#load_upload_kpi_so');
        });
    });
    window.list_file_kpi_so = function(){
        var id          = "<?=$id?>";
        var param       = {id:id};
        var url         = "<?=site_url($url);?>/list_file_kpi_so";
        Metronic.blockUI({ target: '#list_file_kpi_so',  boxed: true});
        $.post(url, param, function(msg){
            $('#form_edit').find('#list_file_kpi_so').html(msg);
            Metronic.unblockUI('#list_file_kpi_so');
        });
    }

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>