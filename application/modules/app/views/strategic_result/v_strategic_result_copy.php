<form method="post" id="form_copy" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                  <label class="control-label col-md-3"><b>Periode</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select name="id_periode" class="form-control select2_biasa" placeholder="Periode" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($periode as $row){ ?>
                                <option <?=(@$data->id_periode == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->start_year.'-'.$row->end_year;?></option>
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
                        <select name="id_strategic_theme" class="form-control select2_biasa" placeholder="Strategic Theme" data-bvalidator="required"  >
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
                        <input value="<?=@$data->pic_sr;?>" name="pic_sr" type="text" class="required form-control" placeholder="PIC"  data-bvalidator="required"  />
                    </div>
              </div>

            <div class="form-group">
                  <label class="control-label col-md-3"><b>Code</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->code;?>" name="code" type="text" class="form-control" placeholder="Code" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Strategic Result</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->name;?>" name="name" type="text" class="form-control" placeholder="Strategic Result" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Indikator</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->indikator;?>" name="indikator" type="text" class="form-control" placeholder="Indikator" data-bvalidator="required" autocomplete="off">
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
                                <option <?=(@$data->polarisasi == $row->id ? 'selected=selected':'')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>  
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Ukuran</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->ukuran;?>" name="ukuran" type="text" class="form-control" placeholder="Ukuran" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Long term Target</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <div class="input_single" style="<?=(@$data->polarisasi == '10' ? 'display:none;':'')?>">
                            <input value="<?=@$data->target;?>" name="target" type="text" class="form-control angka" placeholder="Target" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                        </div>
                        <div class="input_range" style="<?=(@$data->polarisasi == '10' ? '':'display:none;')?>">
                            <input value="<?=@$data->target_from;?>" name="target_from" type="text" class="form-control angka" placeholder="Target From" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                            <input value="<?=@$data->target_to;?>" name="target_to" type="text" class="form-control angka" placeholder="Target To" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                        </div>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Description</b>
                    <span class="required" aria-required="true"></span>
                  </label>
                  <div class="col-md-8">
                        <textarea name="description" class="form-control" data-bvalidator="required" rows="2"><?=@$data->description;?></textarea>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?=csrf_get_token();?>">
                <button id="btn_save" class="btn btn-primary">Copy Data</button>
          </div>
      </div>
  </div>
</form>


<script type="text/javascript">
$(document).ready(function () {

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
    
    //select2 biasa
    $("#form_copy .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    });

     //select strategic_theme
     $("#form_copy input[name='id_strategic_theme']").select2({
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
    $("#form_copy input[name='pic_sr']").select2({
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
    $("#form_copy #polarisasi").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        var val = $(this).val();
        if(val == '10'){
            $('#form_copy').find('.input_single').hide();
            $('#form_copy').find('.input_range').show();
        }else{
            $('#form_copy').find('.input_single').show();
            $('#form_copy').find('.input_range').hide();
        }
    });

    //update data
    $('#form_copy #btn_save').on('click',function(){

        //validasi target
        var polarisasi = $("#form_copy #polarisasi").val();
        if(polarisasi == '10'){
            $("#form_copy input[name='target']").attr('data-bvalidator','');
            $("#form_copy input[name='target_from']").attr('data-bvalidator','required');
            $("#form_copy input[name='target_to']").attr('data-bvalidator','required');
        }else{
            $("#form_copy input[name='target']").attr('data-bvalidator','required');
            $("#form_copy input[name='target_from']").attr('data-bvalidator','');
            $("#form_copy input[name='target_to']").attr('data-bvalidator','');
        }

        $('#form_copy').bValidator();
        $('#form_copy').submit();
        if($('#form_copy').data('bValidator').isValid()){
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
                    var url     = "<?=site_url($url);?>/save_add";
                    var param   = $('#form_copy').serializeArray();
                    Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('.modal-dialog');
                        $('#popup_edit').modal('hide');
                        $('#popup_view').modal('hide');
                        $('#popup_copy').modal('hide');
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

});
</script>