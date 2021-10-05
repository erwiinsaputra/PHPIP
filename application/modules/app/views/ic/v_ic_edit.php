<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
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
                      <input <?=@$readonly?> value="<?=@$data->code;?>" name="code" type="text" class="form-control angka2" placeholder="Number/Prefix" data-bvalidator="required" autocomplete="off">
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
                        <input <?=@$readonly?> value="<?=@$data->weighting_factor;?>" name="weighting_factor" type="text" class="form-control cek_total_weighting_factor" placeholder="Weighting Factor (%)" data-bvalidator="required" autocomplete="off">
                        <input class="total_weighting_factor_all" value="100" type="hidden" class="form-control">
                        <input class="total_weighting_factor_now" value="<?=@$total_weighting_factor_now-@$data->weighting_factor?>" type="hidden" class="form-control">
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
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Attachment</b>
                    <span class="required" aria-required="true"></span>
                  </label>
                  <label class="control-label col-md-8" style="text-align:left;font-weight:bold;">:
                        <a href="javascript:;" class="btn btn-sm btn-primary btn_upload_file_ic" idnya="<?=@$id;?>"> 
                            Upload File
                        </a>
                        <div id="list_file_ic">
                            <?=@$html_list_file_ic;?>
                        </div>
                  </label>
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
                <input type="hidden" name="status_action_plan" value="<?=@$data->status_action_plan;?>">
                <?php if($type == 'view'){ ?>
                    <button title="Approve" id="<?=$id?>" val="3" class="btn btn-primary btn_change_status"><i class="fa fa-check"></i> Approve</button>
                    <button title="Reject" id="<?=$id?>" val="4" class="btn btn-danger btn_change_status"><i class="fa fa-times"></i> Reject</button>
                <?php }else{ ?>
                    <?php if(@$data->status_action_plan == '3'){ ?>
                        <button title="Reject" id="<?=$id?>" val="4" class="btn btn-danger btn_change_status"><i class="fa fa-times"></i> Reject</button>
                    <?php } ?>
                    <?php if(@$data->status_action_plan == '4'){ ?>
                        <button title="Approve" id="<?=$id?>" val="3" class="btn btn-primary btn_change_status"><i class="fa fa-check"></i> Approve</button>
                    <?php } ?>
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
        window.load_table_budget_ic();
    });


    //change year
    window.load_table_budget_ic = function (){
        var id = "<?=@$data->id;?>";
        var start_date = $("#form_edit #start_date").val();
        var end_date = $("#form_edit #end_date").val();
        var type = $("#form_edit #type").val();
        if(start_date == '' || end_date == ''){ return true; }
        var url = "<?=site_url($url)?>/load_table_budget_ic";
        var param = {id:id, start_date:start_date, end_date:end_date, type:type};
        Metronic.blockUI({ target: '#form_edit #load_table_budget_ic',  boxed: true});
        $.post(url, param, function(msg){
            $("#form_edit #load_table_budget_ic").html(msg);
            Metronic.unblockUI('#form_edit #load_table_budget_ic');
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

    //format angka tanpa koma
    $('.angka2').keyup(function () {  
        this.value = this.value.replace(/[^0-9]/g,''); 
    });

    //select2 biasa
    $("#form_edit .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });


    //select si
    $("#form_edit input[name='id_si']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_si')?>",
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
                  url:"<?php echo site_url($url.'/select_si')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res[0]); });
            }
        },
        formatResult: function(item){ return item.name },
        formatSelection: function(item){ 
            $('#form_edit #pic_si').val(item.pic_si);
            var type = $("#form_edit #type").val();
            if(type == 'edit' || type == 'view'){
                //kosong
            }else{
                $('#form_edit #start_date').val(item.start_date);
                $('#form_edit #end_date').val(item.end_date);
                window.load_table_budget_ic();
            }
            return item.name; 
        }
    }).on('change', function(event) { 
        //cek si
        var id_si = $("#form_edit input[name='id_si']").val();
        if(id_si != ''){
            //cek total weighting
            var url = "<?=site_url($url);?>/get_total_weighting_factor";
            var param = {id_si:id_si};
            $.post(url, param, function(msg){
                $('.total_weighting_factor_now').val(msg.val);
                //total weight default
                var total_weighting_now = msg.val;
                var total_weighting_now = parseFloat(total_weighting_now); if(isNaN(total_weighting_now)){total_weighting_now=0;}
                var limit = parseFloat(100 - total_weighting_now);
                $('#form_edit .cek_total_weighting_factor').val(limit);
            },'json');
        }
    });


    //select pic multiple
    $("#form_edit input[name='pic_action_plan']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_action_plan'); ?>",
            dataType: 'json', quietMillis: 250, type:"POST",
            data: function (term, page) {
                var pic_si = $('#form_edit #pic_si').val();
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
                            window.reload_table_action_plan();
                            window.get_total_weighting_factor();
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
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        window.reload_table_action_plan();
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
            var a = $("#form_edit .budget_"+i).children().text()+' '+year;
            $("#form_edit .budget_"+i).children().text(a);
        }
    }
    window.change_year_edit();


    //popup upload
    $('#form_edit').on('click', '.btn_upload_file_ic', function(e) {
        $('#popup_upload_ic').modal();
        var id  = $(this).attr('idnya');
        var url = "<?=site_url($url);?>/load_popup_upload_ic";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_upload_ic',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_upload_ic').html(msg);
            Metronic.unblockUI('#load_upload_ic');
        });
    });
    window.list_file_ic = function(){
        var id          = "<?=$id?>";
        var param       = {id:id};
        var url         = "<?=site_url($url);?>/list_file_ic";
        Metronic.blockUI({ target: '#list_file_ic',  boxed: true});
        $.post(url, param, function(msg){
            $('#form_edit').find('#list_file_ic').html(msg);
            Metronic.unblockUI('#list_file_ic');
        });
    }

    //load table budget
    window.load_table_budget_ic();


    //==============================================================================

    //btn cek_total_weighting_factor
    $('#form_edit').on('keyup', '.cek_total_weighting_factor', function(e) {
        var val = $(this).val();
        window.cek_total_weighting_factor(val);
    });

    //cek total total_weighting_factor
    window.cek_total_weighting_factor = function(weighting){
        if(weighting != ''){
            //cek decimal
            var cek = weighting.indexOf("."); 
            if(cek != '-1'){
                var pecah = weighting.split('.');
                var a = parseFloat(pecah[0]); if(isNaN(a)){a=0;}
                var b = parseFloat(pecah[1]); if(isNaN(b)){b=0;}
                if(b > 100){ var b = 99; }
                var c = a+'.'+b;
                if(b == 0){ var c = c;}
                if(b <= 9 ){ var c = parseFloat(c).toFixed(1); }
                if(b > 9){ var c = parseFloat(c).toFixed(2); }
                $("#form_edit input[name='weighting_factor']").val(c);
            }else{
                //cek angka biasa
                var weighting = parseFloat(weighting).toFixed(2); if(isNaN(weighting)){weighting=0;}
                var cek = weighting.indexOf(".00"); if(cek != '-1'){ var weighting = weighting.replace('.00' , ""); var weighting = parseFloat(weighting); }
                // var total_weighting_all = parseFloat($("#form_edit .total_weighting_factor_all").val()).toFixed(2);
                // var cek = total_weighting_all.indexOf(".00"); if(cek != '-1'){ var total_weighting_all = total_weighting_all.replace('.00' , ""); var total_weighting_now = parseFloat(total_weighting_now); }
                // var total_weighting_now = parseFloat($("#form_edit .total_weighting_factor_now").val()).toFixed(2);
                // var cek = total_weighting_now.indexOf(".00"); if(cek != '-1'){ var total_weighting_now = total_weighting_now.replace('.00' , ""); var total_weighting_now = parseFloat(total_weighting_now); }
                // var total_weighting_now = parseFloat(total_weighting_now).toFixed(2); if(isNaN(total_weighting_now)){total_weighting_now=0;}
                // var limit = parseFloat( total_weighting_all - total_weighting_now).toFixed(2);
                // var cek = limit.indexOf(".00"); if(cek != '-1'){ var limit = limit.replace('.00' , ""); var limit = parseFloat(limit); }
                
                // if(weighting >= limit){
                //     alert("Total Melebihi Limit = "+limit);
                //     $("#form_edit input[name='weighting_factor']").val(limit);
                // }else{
                    $("#form_edit input[name='weighting_factor']").val(weighting);
                // }
            }
        }
    }


    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>