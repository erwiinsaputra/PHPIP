<form method="post" id="form_copy" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3"><b>BSC</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <select <?=@$disabled?> name="id_bsc" class="form-control select2_biasa" placeholder="BSC" data-bvalidator="required"  >
                        <option value=""></option>
                        <?php foreach($bsc as $row){ ?>
                            <option <?=(@$data->id_bsc == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>SI</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input <?=@$readonly?> value="<?=@$data->id_si;?>"  name="id_si" type="text" class="required form-control" placeholder="SI"  data-bvalidator="required"  />
                    <input name="pic_si" id="pic_si" type="hidden" class="required form-control" placeholder="" />
                </div>
            </div>
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
                  <label class="control-label col-md-3"><b>Action Plan</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=@$readonly?> value="<?=@$data->name;?>" name="name" type="text" class="form-control" placeholder="Action Plan" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>PIC Action Plan</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input <?=@$readonly?> value="<?=str_replace(', ',',',@$data->pic_action_plan);?>" name="pic_action_plan" type="text" class="required form-control" placeholder="PIC Action Plan"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Deliverable</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=@$readonly?> value="<?=@$data->deliverable;?>" name="deliverable" type="text" class="form-control" placeholder="Deliverable" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Weighting Factor (%)</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input <?=@$readonly?> value="<?=@$data->weighting_factor;?>" name="weighting_factor" type="text" class="form-control angka" placeholder="Weighting Factor (%)" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Budget Currency</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input <?=@$readonly?> value="<?=@$data->budget_currency;?>" name="budget_currency" type="text" class="form-control" placeholder="Budget Currency" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>

              <hr style="margin-top:0px;">

        </div>

        <div class="col-md-12" style="padding:0 45px 0 45px;">
            <div id="load_table_budget_ic"></div>
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
        window.load_table_budget_ic();
    });


    //change year
    window.load_table_budget_ic = function (){
        var id = "<?=@$data->id;?>";
        var start_date = $("#form_copy #start_date").val();
        var end_date = $("#form_copy #end_date").val();
        var type = $("#form_copy #type").val();
        if(start_date == '' || end_date == ''){ return true; }
        var url = "<?=site_url($url)?>/load_table_budget_ic";
        var param = {id:id, start_date:start_date, end_date:end_date, type:type};
        Metronic.blockUI({ target: '#form_copy #load_table_budget_ic',  boxed: true});
        $.post(url, param, function(msg){
            $("#form_copy #load_table_budget_ic").html(msg);
            Metronic.unblockUI('#form_copy #load_table_budget_ic');
        });
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
    $("#form_copy .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });


    //select si
    $("#form_copy input[name='id_si']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_si')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_bsc = $("#form_copy select[name='id_bsc']").val();
            return { q: term, id_bsc:id_bsc};
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            var start_date = $("#form_copy #start_date").val();
            var end_date = $("#form_copy #end_date").val();
            if (id !== "") {
              $.ajax({
                  url:"<?php echo site_url($url.'/select_si')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res[0]); });
            }
        },
        formatResult: function(item){ return item.name },
        formatSelection: function(item){ 
            $('#form_copy #pic_si').val(item.pic_si);
            var type = $("#form_copy #type").val();
            if(type == 'copy' || type == 'view'){
                //kosong
            }else{
                $('#form_copy #start_date').val(item.start_date);
                $('#form_copy #end_date').val(item.end_date);
                window.load_table_budget_ic();
            }
            return item.name; 
        }
    }).on('change', function(event) { 
        //change
    });



    //select pic multiple
    $("#form_copy input[name='pic_action_plan']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_action_plan'); ?>",
            dataType: 'json', quietMillis: 250, type:"POST",
            data: function (term, page) {
                var pic_si = $('#form_copy #pic_si').val();
                if(pic_si == ''){  alert('SI belum dipilih'); }
                return { q: term, pic_si:pic_si};
            },
            results: function (data, page) { return { results: data.item }; },
            cache: true
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
                $.ajax("<?php echo site_url($url.'/select_pic_action_plan')?>", {
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
                            window.reload_table_ic();
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
    $('#form_copy').on('click', '.btn_change_status', function(e) {
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
                    window.reload_table_ic();
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                    $('#popup_copy').modal('hide');
                }, 'json');
        });
    });

    //change year
    window.change_year_copy = function (){
        var periode  =  $("#form_copy select[name='id_periode'] option[selected='selected']").text();
        var pecah = periode.split(" - ");
        var start_year = pecah[0];
        $("#form_copy #start_year").val(start_year);
        for(i=0;i<5;i++){
            var year = parseFloat(start_year)+i;
            var a = $("#form_copy .budget_"+i).children().text()+' '+year;
            $("#form_copy .budget_"+i).children().text(a);
        }
    }
    window.change_year_copy();


    //load table budget
    window.load_table_budget_ic();

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>