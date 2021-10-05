<form method="post" id="form_copy" action="javascript:;" class="form-horizontal">
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
                            <input type="text" value="<?=substr(@$data->start_date,0,7)?>" id="start_date" name="start_date" class="form-control" readonly="readonly" data-bvalidator="required"/>
                            <input type="hidden" value="<?=substr(@$data->start_date,0,7)?>" name="start_date_old" class="form-control" data-bvalidator="required"/>
                            <span class="input-group-addon"> To </span>
                            <input type="text" value="<?=substr(@$data->end_date,0,7)?>" id="end_date" name="end_date"  class="form-control" readonly="readonly" data-bvalidator="required"/>
                            <input type="hidden" value="<?=substr(@$data->end_date,0,7)?>" name="end_date_old"  class="form-control" data-bvalidator="required"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3"><b>BSC</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select  name="id_bsc" class="form-control select2_biasa" placeholder="BSC" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($bsc as $row){ ?>
                                <option <?=(@$data->id_bsc == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                  </div>
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Perspective</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input value="<?=@$data->id_perspective;?>" name="id_perspective" type="text" class="required form-control" placeholder="Perspective"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>SO</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input value="<?=@$data->id_so;?>"  name="id_so" type="text" class="required form-control" placeholder="SO"  data-bvalidator="required"  />
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
                      <input value="<?=@$data->code;?>" name="code" type="text" class="form-control angka" placeholder="Number/Prefix" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>KPI-SO</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->name;?>" name="name" type="text" class="form-control" placeholder="KPI-SO" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Polarisasi</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select  name="polarisasi" id="polarisasi" class="form-control" placeholder="Polarisasi" data-bvalidator="required">
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
                        <input value="<?=str_replace(', ',',',@$data->pic_kpi_so);?>" name="pic_kpi_so" type="text" class="required form-control" placeholder="PIC KPI-SO"  data-bvalidator="required"  />
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
                  <label class="control-label col-md-3"><b>Frekuensi Pengukuran</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input value="<?=@$data->frekuensi_pengukuran;?>" name="frekuensi_pengukuran" type="text" class="form-control" placeholder="Frekuensi Pengukuran" data-bvalidator="required" autocomplete="off">
                  </div>
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
                <button id="btn_update" class="btn btn-primary">Save Copy Data</button>
          </div>
      </div>
  </div>
</form>


<script type="text/javascript">
$(document).ready(function () {

    //range date
    $("#form_copy #start_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $("#form_copy #end_date").datepicker('setStartDate', minDate);
    });
    $("#form_copy #end_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $("#form_copy #start_date").datepicker('setEndDate', minDate);
        window.load_table_target_kpi_so();
    });


    //change year
    window.load_table_target_kpi_so = function (){
        var id = "<?=@$data->id;?>";
        var polarisasi = "<?=@$polarisasi;?>";
        var start_date = $("#form_copy #start_date").val();
        var end_date = $("#form_copy #end_date").val();
        if(start_date == '' || end_date == ''){ return true; }
        var url = "<?=site_url($url)?>/load_table_target_kpi_so";
        var param = {id:id, start_date:start_date, end_date:end_date, polarisasi:polarisasi};
        Metronic.blockUI({ target: '#form_copy #load_table_target_kpi_so',  boxed: true});
        $.post(url, param, function(msg){
            $("#form_copy #load_table_target_kpi_so").html(msg);
            Metronic.unblockUI('#form_copy #load_table_target_kpi_so');
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
    $("#form_copy .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 id periode
    $("#form_copy .select2_biasa2").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 polarisasi 
    $("#form_copy #polarisasi").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
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
    $("#form_copy #polarisasi").change();


    //select perspective
    $("#form_copy input[name='id_perspective']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_perspective')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_bsc = $("#form_copy select[name='id_bsc']").val();
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
    $("#form_copy input[name='id_so']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_so')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_perspective = $("#form_copy input[name='id_perspective']").val();
            var id_bsc = $("#form_copy select[name='id_bsc']").val();
            var id_periode = $("#form_copy select[name='id_periode']").val();
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
            $('#form_copy #pic_so').val(item.pic_so);
            return item.name; 
        }
    }).on('change', function(event) { 
        //change
    });


    //select pic multiple
    $("#form_copy input[name='pic_kpi_so']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_kpi_so'); ?>",
            dataType: 'json', quietMillis: 250, type:"POST",
            data: function (term, page) {
                var pic_so = $('#form_copy #pic_so').val();
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
        formatSelection: function(item){return item.name;}
    });

    //update data
    $('#form_copy #btn_update').on('click',function(){
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
                    var url     = "<?=site_url($url);?>/save_copy";
                    var param   = $('#form_copy').serializeArray();
                    Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('.modal-dialog');
                        $('#popup_copy').modal('hide');
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

    //change year
    window.change_year_copy = function (){
        var periode  =  $("#form_copy select[name='id_periode'] option[selected='selected']").text();
        var pecah = periode.split(" - ");
        var start_year = pecah[0];
        $("#form_copy #start_year").val(start_year);
        for(i=0;i<5;i++){
            var year = parseFloat(start_year)+i;
            var a = $("#form_copy .target_"+i).children().text()+' '+year;
            $("#form_copy .target_"+i).children().text(a);
        }
    }
    window.change_year_copy();

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>