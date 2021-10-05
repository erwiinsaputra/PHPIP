<form method="post" id="form_edit_sub<?=$id?>" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
            <div class="col-md-12">

            <div class="form-group">
                <label class="control-label col-md-3"><b>Periode</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" value="<?=$action_plan->start_date?>" id="start_date" name="start_date" class="form-control" readonly="readonly" data-bvalidator="required"/>
                        <span class="input-group-addon"> To </span>
                        <input type="text" value="<?=$action_plan->end_date?>" id="end_date" name="end_date"  class="form-control" readonly="readonly" data-bvalidator="required"/>
                    </div>
                </div>
            </div>
                
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Number</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="code" value="<?=$action_plan->code?>" type="text" class="form-control angka" placeholder="Number" data-bvalidator="required" autocomplete="off" >
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Action Plan</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="name" value="<?=$action_plan->name?>"type="text" class="form-control" placeholder="Action Plan" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>PIC Action Plan</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input name="pic_action_plan" value="<?=$action_plan->pic_action_plan?>" type="text" class="required form-control" placeholder="PIC Action Plan"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Deliverable</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="deliverable" value="<?=$action_plan->deliverable?>" type="text" class="form-control" placeholder="Deliverable" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Weighting Factor (%)</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input name="weighting_factor" value="<?=$action_plan->weighting_factor?>" type="text" class="form-control cek_total_sub_weighting_factor" placeholder="Weighting Factor (%)" data-bvalidator="required" autocomplete="off">
                        <input class="total_sub_weighting_factor_all" value="<?=$action_plan->weighting_factor?>" type="hidden" >
                        <input class="total_sub_weighting_factor_now" value="<?=$total_weighting_factor_now?>" type="hidden" >
                  </div>
              </div>
              <hr style="margin-top:0px;">

          </div>

          <div class="col-md-12" style="padding:0 45px 0 45px;">
            <style>
                #load_table_sub_action_plan_edit>tbody>tr>td{ padding: 1px; }
            </style>
            <table class="table table-bordered" id="load_table_sub_action_plan_edit" style="width: 94%;">
                <tbody>
                    <?php for($y=$start_year;$y<=$end_year;$y++){ ?>
                    <tr>
                        <td style="width:25%;text-align:center;vertical-align:middle;background:darkgreen;color:white;"><b>Budget <?=$y?></b></td>
                        <td style="text-align:center;">
                            <input value="<?=@$budget[$id][$y]?>" name="budget_<?=$y?>" type="text" class="form-control angka" placeholder="0" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <hr style="margin-top:0px;">
          </div>
      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="id" value="<?=$id;?>">
                <input type="hidden" name="ex_csrf_token" value="<?=csrf_get_token();?>">
                <button id="btn_update_sub" class="btn btn-primary">Update</button>
          </div>
      </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function () {
    

    //range date
    $("#form_edit_sub<?=$id?> #start_date").datepicker({
        format: 'yyyy-mm-dd',
        viewMode: "days", 
        minViewMode: "days",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $("#form_edit_sub<?=$id?> #end_date").datepicker('setStartDate', minDate);
        window.load_table_budget_ic();
    });
    $("#form_edit_sub<?=$id?> #end_date").datepicker({
        format: 'yyyy-mm-dd',
        viewMode: "days", 
        minViewMode: "days",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $("#form_edit_sub<?=$id?> #start_date").datepicker('setEndDate', minDate);
        window.load_table_budget_ic();
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

    //select2 biasa
    $("#form_edit_sub<?=$id?> .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });


    //select pic multiple
    $("#form_edit_sub<?=$id?> input[name='pic_action_plan']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_action_plan'); ?>",
            dataType: 'json', quietMillis: 250, type:"POST",
            data: function (term, page) {
                var pic_si = $('#form_edit_sub<?=$id?> #pic_si').val();
                if(pic_si == ''){  alert('SO belum dipilih'); }
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

    //save data
    $('#form_edit_sub<?=$id?>').on('click','#btn_update_sub', function(){
        $('#form_edit_sub<?=$id?>').bValidator();
        $('#form_edit_sub<?=$id?>').submit();
        if($('#form_edit_sub<?=$id?>').data('bValidator').isValid()){

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
                    var url   = "<?=site_url($url);?>/save_edit_sub";
                    var param = $('#form_edit_sub<?=$id?>').serializeArray();
                    Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('.modal-dialog');
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            $('#popup_edit_sub').modal('hide');
                            window.reload_table_sub_action_plan(msg.id);
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


    //==============================================================================

    //btn cek_total_sub_weighting_factor
    $('#form_edit_sub<?=$id?>').on('keyup', '.cek_total_sub_weighting_factor', function(e) {
        var val = $(this).val();
        window.cek_total_sub_weighting_factor(val);
    });

    //cek total total_weighting_factor
    window.cek_total_sub_weighting_factor = function(weighting){
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
                $("#form_edit_sub<?=$id?> .cek_total_sub_weighting_factor").val(c);
            }else{
                var weighting = parseFloat(weighting).toFixed(2); if(isNaN(weighting)){weighting=0;}
                var cek = weighting.indexOf(".00"); if(cek != '-1'){ var weighting = weighting.replace('.00' , ""); var weighting = parseFloat(weighting); }
                // var total_sub_weighting_all = parseFloat($("#form_edit_sub<?=$id?> .total_sub_weighting_factor_all").val()).toFixed(2);
                // var cek = total_sub_weighting_all.indexOf(".00"); if(cek != '-1'){ var total_sub_weighting_all = total_sub_weighting_all.replace('.00' , ""); var total_sub_weighting_all = parseFloat(total_sub_weighting_all); }
                // var total_sub_weighting_now = parseFloat($("#form_edit_sub<?=$id?> .total_sub_weighting_factor_now").val()).toFixed(2);
                // var cek = total_sub_weighting_now.indexOf(".00"); if(cek != '-1'){ var total_sub_weighting_now = total_sub_weighting_now.replace('.00' , ""); var total_sub_weighting_now = parseFloat(total_sub_weighting_now); }
                // var limit = parseFloat(total_sub_weighting_all - total_sub_weighting_now).toFixed(2);
                // var cek = limit.indexOf(".00"); if(cek != '-1'){ var limit = limit.replace('.00' , ""); var limit = parseFloat(limit); }
                // if(weighting > limit){
                //     alert("Total Melebihi Limit = "+limit);
                //     $("#form_edit_sub<?=$id?> .cek_total_sub_weighting_factor").val(limit);
                // }else{
                    $("#form_edit_sub<?=$id?> .cek_total_sub_weighting_factor").val(weighting);
                // }
            }
        }
    }
    //==============================================================================


    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');

});
</script>