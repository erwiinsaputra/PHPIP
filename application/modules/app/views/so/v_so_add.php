<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
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
                    <select name="id_bsc" id="id_bsc"  class="form-control" placeholder="BSC" data-bvalidator="required">
                        <option value=""></option>
                        <?php foreach($bsc as $row){ ?>
                            <option value="<?=$row->id;?>"><?=$row->name;?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div id="input_parent_so" style="display:none;">
                <div class="form-group">
                    <label class="control-label col-md-3"><b>SO Parent</b>
                        <span class="required" aria-required="true"></span>
                    </label>
                    <div class="col-md-8">
                        <input name="parent_so" type="text" class="required form-control" placeholder="SO Parent"  data-bvalidator=""  />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>Perspective</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="id_perspective" id="id_perspective" type="text" class="required form-control" placeholder="Perspective"  data-bvalidator="required"  />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>PIC</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="pic_so" id="pic_so" type="text" class="required form-control" placeholder="PIC SO"  data-bvalidator="required"  />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>SO Title</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="name" id="name" type="text" class="form-control" placeholder="SO Title" data-bvalidator="required" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>SO Number</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="code" id="code" type="text" class="form-control" placeholder="SO Number" data-bvalidator="" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>Description</b>
                <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <input name="description" type="text" class="form-control" placeholder="Description" data-bvalidator="" autocomplete="off">
                </div>
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

    //range date
    $("#form_add #start_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#end_date').datepicker('setStartDate', minDate);
    });
    $("#form_add #end_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#start_date').datepicker('setEndDate', minDate);
    });
 
    //load awal
    var bsc  =  $(".table_so select[name='global_id_bsc']").val();
    var perspective   =  $(".table_so input[name='global_id_perspective']").val();
    if(bsc != ''){
        $("#form_add select[name='id_bsc'] option[value='"+bsc+"']").attr('selected','selected');
    }
    if(perspective != ''){
        $("#form_add input[name='id_perspective']").val(perspective);
    }

    //select2 biasa
    $("#form_add .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 biasa2
    $("#form_add .select2_biasa2").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });


    //select2 biasa
    $("#form_add select[name='id_periode']").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        window.change_year_add();
    });


    //select2 biasa2
    $("#form_add #id_bsc").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        //change
        var id_bsc = $(this).val();
        if(id_bsc == '1'){
            $("#form_add #input_parent_so").hide();
        }else{
            $("#form_add #input_parent_so").show();
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


    //select parent so
    $("#form_add input[name='parent_so']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_parent_so')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_perspective = $("#form_add input[name='id_perspective']").val();
            var id_periode = $("#form_add select[name='id_periode']").val();
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
            $('#form_add #pic_so').val(item.pic_so);
            $('#form_add #id_perspective').val(item.id_perspective);
            $('#form_add #name').val(item.name_so);
            $('#form_add #code').val(item.code);
            $('#form_add #pic_so').change();
            $('#form_add #id_perspective').change();
            return item.name; 
        }
    });
    $('#form_add #id_bsc').change();


    //select pic so
    $("#form_add input[name='pic_so']").select2({
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
                            window.reload_table_so();
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