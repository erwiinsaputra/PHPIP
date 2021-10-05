<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
          <div class="col-md-12">

            <div class="form-group">
                  <label class="control-label col-md-3"><b>Periode</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select  name="id_periode" id="id_periode" class="form-control" placeholder="Periode" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($periode as $row){ ?>
                                <option value="<?=$row->id;?>"><?=$row->start_year.'-'.$row->end_year;?></option>
                            <?php } ?>
                        </select>
                  </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3"><b>BSC</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <select name="id_bsc" id="id_bsc"  class="form-control select2_biasa" placeholder="BSC" data-bvalidator="required">
                        <option value=""></option>
                        <?php foreach($bsc as $row){ ?>
                            <option value="<?=$row->id;?>"><?=$row->name;?></option>
                        <?php } ?>
                    </select>
                </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Strategic Theme</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select  name="id_strategic_theme" class="form-control select2_biasa" placeholder="Strategic Theme" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($strategic_theme as $row){ ?>
                                <option value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                  </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3"><b>PIC</b>
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input name="pic_sr" id="pic_sr" type="text" class="required form-control" placeholder="PIC"  data-bvalidator="required"  />
                    </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Code</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="code" type="text" class="form-control" placeholder="Code" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Strategic Result</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="name" type="text" class="form-control" placeholder="Strategic Result" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Indikator</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="indikator" type="text" class="form-control" placeholder="Indikator" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Polarisasi</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select  name="polarisasi" id="polarisasi" class="form-control" placeholder="Polarisasi" data-bvalidator="required">
                            <option value=""></option>
                            <?php foreach($polarisasi as $row){ ?>
                                <option value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>  
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Ukuran</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="ukuran" type="text" class="form-control" placeholder="Ukuran" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Description</b>
                    <span class="required" aria-required="true"></span>
                  </label>
                  <div class="col-md-8">
                        <textarea name="description" class="form-control" data-bvalidator="" rows="2"></textarea>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Long term Target</b> 
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                    <div class="input_single" style="">
                        <input name="target" type="text" class="form-control angka" placeholder="Target" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                    </div>
                    <div class="input_range" style="display:none;">
                        <input name="target_from" type="text" class="form-control angka" placeholder="Target From" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                        <input name="target_to" type="text" class="form-control angka" placeholder="Target To" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
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
                <input type="hidden" name="ex_csrf_token" value="<?=csrf_get_token();?>">
                <button id="btn_save" class="btn btn-primary">Save</button>
          </div>
      </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function () {

    //load awal
    var periode         = $(".table_strategic_result select[name='global_id_periode']").val();
    var bsc = $(".table_strategic_result select[name='global_id_bsc']").val();
    var strategic_theme = $(".table_strategic_result select[name='global_id_strategic_theme']").val();
    if(periode != ''){
        $("#form_add select[name='id_periode'] option[value='"+periode+"']").attr('selected','selected');
    }
    if(bsc != ''){
        $("#form_add select[name='id_bsc'] option[value='"+bsc+"']").attr('selected','selected');
    }
    if(strategic_theme != ''){
        $("#form_add select[name='id_strategic_theme'] option[value='"+strategic_theme+"']").attr('selected','selected');
    }


    //change year
    window.load_table_target_sr = function (){
        var id = "";
        var periode  =  $("#form_add select[name='id_periode'] option:selected").text();
        var arr = periode.split('-');
        var start_year = arr[0];
        var end_year = arr[1];
        var url = "<?=site_url($url)?>/load_table_target_sr";
        var param = {id:id, start_year:start_year, end_year:end_year};
        Metronic.blockUI({ target: '#form_add #load_table_target_sr',  boxed: true});
        $.post(url, param, function(msg){
            $('#form_add #load_table_target_sr').html(msg);
            Metronic.unblockUI('#form_add #load_table_target_sr');
        });
    }
    window.load_table_target_sr();

    //select2 biasa
    $("#form_add #id_periode").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        window.load_table_target_sr();
    });


    //select2 biasa
    $("#form_add .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    });

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

    //select strategic_theme
    $("#form_add input[name='id_strategic_theme']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        allowClear:false,
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
    $("#form_add input[name='pic_sr']").select2({
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
    $("#form_add #polarisasi").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        var val = $(this).val();
        if(val == '10'){
            $('#form_add').find('.input_single').hide();
            $('#form_add').find('.input_range').show();
        }else{
            $('#form_add').find('.input_single').show();
            $('#form_add').find('.input_range').hide();
        }
    });

    //save data
    $('#form_add #btn_save').on('click',function(){

        //validasi target
        var polarisasi = $("#form_add #polarisasi").val();
        if(polarisasi == '10'){
            $("#form_add input[name='target']").attr('data-bvalidator','');
            $("#form_add input[name='target_from']").attr('data-bvalidator','required');
            $("#form_add input[name='target_to']").attr('data-bvalidator','required');
        }else{
            $("#form_add input[name='target']").attr('data-bvalidator','required');
            $("#form_add input[name='target_from']").attr('data-bvalidator','');
            $("#form_add input[name='target_to']").attr('data-bvalidator','');
        }

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
                            window.reload_table_strategic_result();
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