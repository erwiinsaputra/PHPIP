<form method="post" id="form_copy" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
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
            <div id="input_parent_so" style="<?=(@$data->parent_so == '' ? 'display:none;' : '')?>">
                    <div class="form-group">
                        <label class="control-label col-md-3"><b>SO Parent</b>
                            <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-8">
                            <input value="<?=@$data->parent_so;?>" name="parent_so" id="parent_so"  type="text" class="required form-control" placeholder="SO Parent" />
                        </div>
                    </div>
            </div>
            <div class="form-group">
                  <label class="control-label col-md-3"><b>Perspective</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                     <input value="<?=@$data->id_perspective;?>" name="id_perspective" id="id_perspective" type="text" class="required form-control" placeholder="Perspective"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>PIC</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->pic_so;?>" name="pic_so" id="pic_so" type="text" class="required form-control" placeholder="PIC SO"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>SO Title</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->name;?>" name="name" id="name" type="text" class="form-control" placeholder="SO Title" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>SO Number</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->code;?>" name="code" id="code"  type="text" class="form-control" placeholder="SO Number" data-bvalidator="" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Description</b>
                    <span class="required" aria-required="true"></span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=@$data->description;?>" name="description" type="text" class="form-control" placeholder="Description" data-bvalidator="" autocomplete="off">
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="form-actions">
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
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        //change
        var id_bsc = $(this).val();
        if(id_bsc == '1'){
            $("#form_copy #input_parent_so").hide();
        }else{
            $("#form_copy #input_parent_so").show();
        }
    });

    //select perspective
    $("#form_copy input[name='id_perspective']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_perspective')?>",
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


    //select parent so
    $("#form_copy input[name='parent_so']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_parent_so')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_perspective = $("#form_copy input[name='id_perspective']").val();
            var id_periode = $("#form_copy select[name='id_periode']").val();
            return { q: term, id_perspective:id_perspective, id_periode:id_periode };
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
              $.ajax({
                  url:"<?php echo site_url($url.'/select_parent_so')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res[0]); });
            }
        },
        formatResult: function(item){ return item.name },
        formatSelection: function(item){ 
            var copy = $('#form_copy #copy').val();
            if(copy == 'yes'){ $('#form_copy #copy').val('no'); return item.name;}
            $('#form_copy #pic_so').val(item.pic_so);
            $('#form_copy #id_perspective').val(item.id_perspective);
            $('#form_copy #name').val(item.name_so);
            $('#form_copy #code').val(item.code);
            $('#form_copy #pic_so').change();
            $('#form_copy #id_perspective').change();
            return item.name; 
        }
    });


    //select pic so
    $("#form_copy input[name='pic_so']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_so'); ?>",
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
                $.ajax("<?php echo site_url($url.'/select_pic_so')?>", {
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
                    var url     = "<?=site_url($url);?>/save_add";
                    var param   = $('#form_copy').serializeArray();
                    Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('.modal-dialog');
                        $('#popup_copy').modal('hide');
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_so();
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