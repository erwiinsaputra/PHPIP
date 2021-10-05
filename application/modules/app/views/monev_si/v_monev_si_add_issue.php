<form method="post" id="form_add_issue" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
            <div class="col-md-12">

              <hr style="margin-top:0px;">

              <div class="form-group">
                  <label class="control-label col-md-3"><b>Action Plan</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input value="<?=$id_action_plan?>" name="id_action_plan" type="text" class="form-control" placeholder="Action Plan" data-bvalidator="required" autocomplete="off" >
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Issue</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input name="issue" type="text" class="form-control" placeholder="Issue" data-bvalidator="required" autocomplete="off" >
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Category</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select name="category" class="form-control select2_biasa" data-bvalidator="required">
                            <option value="0">Internal</option>
                            <option value="1">External</option>
                        </select>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Follow Up</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input name="followup" type="text" class="form-control" placeholder="Follow Up"  data-bvalidator="required" autocomplete="off"/>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Executor</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                    <input name="executor" id="executor" type="text" class="form-control" placeholder="Executor" data-bvalidator="required" autocomplete="off"/>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Email</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input name="email" type="text" class="form-control" placeholder="Email"  data-bvalidator="required" autocomplete="off"/>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>No HP</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input name="no_hp" type="text" class="form-control angka" placeholder="No HP"  data-bvalidator="required" autocomplete="off"/>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Due Date</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <input readonly='readonly' name="due_date" type="text" class="form-control tanggal" placeholder=""  data-bvalidator="required" autocomplete="off"/>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>Issue Status</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select name="status_issue" class="form-control select2_biasa" data-bvalidator="required">
                            <option value="0">Open</option>
                            <option value="1">Close</option>
                        </select>
                  </div>
              </div>

              <hr style="margin-top:0px;">

          </div>

      </div>
  </div>
  <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?=csrf_get_token();?>">
                <input type="hidden" name="id_si" value="<?=@$id_si;?>">
                <input type="hidden" name="year" value="<?=@$year;?>">
                <button id="btn_save" class="btn btn-primary">Save</button>
          </div>
      </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function () {

     //select2 biasa
     $("#form_add_issue .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    });

    
    //format tanggal
    $("#form_add_issue .tanggal").datepicker({
        format: 'yyyy-mm-dd',
        viewMode: "days", 
        minViewMode: "days",
        autoclose: true,
    });

    //format angka
    $('#form_add_issue .angka').keyup(function () {  
        this.value = this.value.replace(/[^0-9\.]/g,''); 
    });

    //select executor
    $("#form_add_issue input[name='executor']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        // multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_executor'); ?>",
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
                $.ajax("<?php echo site_url($url.'/select_executor')?>", {
                dataType: "json", type:"POST",
                data:{ id: id}
                }).done( function(data) { callback(data[0]); });
            }
        },
        formatResult: function(item){return item.name;},
        formatSelection: function(item){
            $("#form_add_issue input[name='email']").val(item.email);
            $("#form_add_issue input[name='no_hp']").val(item.no_hp);
            return item.name;
        }
    });

    //select action_plan
    $("#form_add_issue input[name='id_action_plan']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        // multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_action_plan'); ?>",
            dataType: 'json', quietMillis: 250, type:"POST",
            data: function (term, page) {
                return { q: term, id_si:'<?=$id_si?>'};
            },
            results: function (data, page) { return { results: data.item }; },
            cache: true
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
                $.ajax("<?php echo site_url($url.'/select_action_plan')?>", {
                dataType: "json", type:"POST",
                data:{ id: id}
                }).done( function(data) { callback(data[0]); });
            }
        },
        formatResult: function(item){return item.name;},
        formatSelection: function(item){
            return item.name;
        }
    });

    //save data
    $('#form_add_issue #btn_save').on('click',function(){
        $('#form_add_issue').bValidator();
        $('#form_add_issue').submit();
        if($('#form_add_issue').data('bValidator').isValid()){

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
                    var url   = "<?=site_url($url);?>/save_add_issue";
                    var param = $('#form_add_issue').serializeArray();
                    Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('.modal-dialog');
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            $('#popup_add_issue').modal('hide');
                            window.reload_table_issue('<?=@$id_si?>','<?=@$id_action_plan?>','<?=@$year?>');
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

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');

});
</script>