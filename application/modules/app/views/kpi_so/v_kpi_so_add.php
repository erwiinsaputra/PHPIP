<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-3"><b>Periode</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" id="start_date" name="start_date" class="form-control" readonly="readonly" data-bvalidator="required"/>
                            <span class="input-group-addon"> To </span>
                            <input type="text" id="end_date" name="end_date"  class="form-control" readonly="readonly" data-bvalidator="required"/>
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
                                <option value="<?=$row->id;?>"><?=$row->name;?></option>
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
                        <input name="id_perspective" type="text" class="required form-control" placeholder="Perspective"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>SO</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input name="id_so" type="text" class="required form-control" placeholder="SO"  data-bvalidator="required"  />
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
                      <input name="code" type="text" class="form-control angka" placeholder="Number/Prefix" data-bvalidator="required" autocomplete="off" >
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>KPI-SO</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="name" type="text" class="form-control" placeholder="KPI-SO" data-bvalidator="required" autocomplete="off">
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
                  <label class="control-label col-md-3"><b>PIC KPI-SO</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input name="pic_kpi_so" type="text" class="required form-control" placeholder="PIC KPI-SO"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3"><b>Assigned KPI-SO Manager</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="user_pic_manager" id="user_pic_manager" type="text" class="required form-control" placeholder="KPI-SO (Assigned Manager)"  data-bvalidator="required"  />
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
                  <label class="control-label col-md-3"><b>Frekuensi Pengukuran</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input name="frekuensi_pengukuran" type="text" class="form-control" placeholder="Frekuensi Pengukuran" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>

              <hr style="margin-top:0px;">

          </div>


          <div class="col-md-12" style="padding:0 45px 0 45px;">
            <div id="load_table_target_kpi_so">
                <table class="table table-bordered" id="load_table_target_kpi_so">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align:middle;text-align:center;background:green;color:white;">Year</th>
                            <th rowspan="2" style="vertical-align:middle;text-align:center;background:darkblue;color:white;">Target&nbsp;Year</th>
                            <th colspan="4" style="text-align:center;background:darkblue;color:white;">Target Month</th>
                        </tr>
                        <tr>
                            <?php for($m=1;$m<=12;$m++){ ?>
                                <?php if(in_array($m,array('3','6','9','12'))){ ?>
                                <th style="text-align:center;background:#89C4F4;color:black;"><?=h_month_name($m)?></th>
                                <?php } ?>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" style="text-align:center;"><br/><b>Select Periode</b><br/><br/></td>
                        </tr>     
                    </tbody>
                </table>
            </div>
            <hr style="margin-top:0px;">
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
    
    //range date
    $("#form_add #start_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $("#form_add #end_date").datepicker('setStartDate', minDate);
        window.load_table_target_kpi_so();
    });
    $("#form_add #end_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $("#form_add #start_date").datepicker('setEndDate', minDate);
        window.load_table_target_kpi_so();
    });


    //change year
    window.load_table_target_kpi_so = function (){
        var id = "";
        var start_date = $("#form_add #start_date").val();
        var end_date = $("#form_add #end_date").val();
        if(start_date == '' || end_date == ''){ return true; }
        var url = "<?=site_url($url)?>/load_table_target_kpi_so";
        var param = {id:id, start_date:start_date, end_date:end_date};
        Metronic.blockUI({ target: '#form_add #load_table_target_kpi_so',  boxed: true});
        $.post(url, param, function(msg){
            $('#form_add #load_table_target_kpi_so').html(msg);
            Metronic.unblockUI('#form_add #load_table_target_kpi_so');
        });
    }
 
    //load awal
    var bsc         =  $(".table_kpi_so select[name='global_id_bsc']").val();
    var perspective =  $(".table_kpi_so input[name='global_id_perspective']").val();
    var so          =  $(".table_kpi_so input[name='global_id_so']").val();
    if(bsc != ''){
        $("#form_add select[name='id_bsc'] option[value='"+bsc+"']").attr('selected','selected');
    }
    if(perspective != ''){
        $("#form_add input[name='id_perspective']").val(perspective);
    }
    if(so != ''){
        $("#form_add input[name='id_so']").val(so);
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
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 polarisasi 
    $("#form_add #polarisasi").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
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


    //select perspective
    $("#form_add input[name='id_perspective']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_perspective')?>",
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
    $("#form_add input[name='id_so']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_so')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_perspective = $("#form_add input[name='id_perspective']").val();
            var id_bsc = $("#form_add select[name='id_bsc']").val();
            return { q: term, id_perspective:id_perspective, id_bsc:id_bsc};
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            var start_date = $("#form_add #start_date").val();
            var end_date = $("#form_add #end_date").val();
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
            $('#form_add #pic_so').val(item.pic_so);
            $('#form_add #start_date').val(item.start_date);
            $('#form_add #end_date').val(item.end_date);
            window.load_table_target_kpi_so();
            return item.name; 
        }
    }).on('change', function(event) { 
        //change
    });


    //select pic multiple
    $("#form_add input[name='pic_kpi_so']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_kpi_so'); ?>",
            dataType: 'json', quietMillis: 250, type:"POST",
            data: function (term, page) {
                var pic_so = $('#form_add #pic_so').val();
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

    //select pic kpi so assign manager
    $("#form_add input[name='user_pic_manager']").select2({
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
                            window.reload_table_kpi_so();
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

    //change year
    window.change_year_add = function (){
        var periode  =  $("#form_add select[name='id_periode'] option[selected='selected']").text();
        var pecah = periode.split(" - ");
        var start_year = pecah[0];
        $("#form_add #start_year").val(start_year);
        for(i=0;i<5;i++){
            var year = parseFloat(start_year)+i;
            $("#form_add .target_"+i).children().text(year);
        }
    }
    window.change_year_add();

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');

});
</script>