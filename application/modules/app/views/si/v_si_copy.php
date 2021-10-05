<form method="post" id="form_copy" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3"><b>SI COPY</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input type="text" value="(<?=str_replace(',','.',@$data->code);?>) <?=@$data->name;?>" class="form-control" readonly="readonly">
                </div>
            </div>
            <!-- <div class="form-group">
                <label class="control-label col-md-3"><b>COPY Type</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <select  name="copy_type" id="copy_type" class="form-control" placeholder="Select" data-bvalidator="required">
                        <option value=""></option>
                        <option value="1">Data SI Only </option>
                        <option value="2">Data SI & Action Plan</option>
                    </select>
                </div>
            </div> -->
            <hr>
        </div>
        <div class="col-md-12 load_form_copy" style="display:;">
            <div class="form-group">
                <label class="control-label col-md-3"><b>Periode</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" value="<?=substr(@$data->start_date,0,7)?>" id="start_date" name="start_date" class="form-control" readonly="readonly" data-bvalidator="required"/>
                        <span class="input-group-addon"> To </span>
                        <input type="text" value="<?=substr(@$data->end_date,0,7)?>" id="end_date" name="end_date"  class="form-control" readonly="readonly" data-bvalidator="required"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>BSC</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <select  name="id_bsc" id="id_bsc" class="form-control" placeholder="BSC" data-bvalidator="required"  >
                        <option value=""></option>
                        <?php foreach($bsc as $row){ ?>
                            <option <?=(@$data->id_bsc == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>SI Title</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input value="<?=@$data->name;?>" name="name" id="name" type="text" class="form-control" placeholder="SI Title" data-bvalidator="required" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>SI Number</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input value="<?=str_replace(',','.',@$data->code);?>" name="code" id="code"  type="text" class="form-control" placeholder="SI Number" data-bvalidator="" autocomplete="off">
                </div>
            </div>

          </div>
      </div>
  </div>
  <div class="form-actions load_form_copy" style="display:;">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                <input type="hidden" id="copy" value="yes" >
                <button id="btn_update" class="btn btn-primary">Save Copy Data</button>
          </div>
      </div>
  </div>
</form>


<script type="text/javascript">
$(document).ready(function () {

    //pilih copy
    // $("#form_copy #copy_type").select2({
    //     minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    // }).on('change', function(event) { 
    //     $('.load_form_copy').show();
    // });

    //range date
    $("#form_copy #start_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#end_date').datepicker('setStartDate', minDate);
    });
    $("#form_copy #end_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#start_date').datepicker('setEndDate', minDate);
    });

    //select2 biasa
    $("#form_copy .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 biasa2
    $("#form_copy .select2_biasa2").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 biasa2
    $("#form_copy #id_bsc").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        //change
    });

    //select pic si
    $("#form_copy input[name='pic_si']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_si'); ?>",
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
                $.ajax("<?php echo site_url($url.'/select_pic_si')?>", {
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
                            window.reload_table_si();
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

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>