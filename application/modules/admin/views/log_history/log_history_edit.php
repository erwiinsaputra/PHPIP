<form method="post" action="<?php echo site_url($setting['url'].'update_prospect/'.$ams_id) ?>" class="form-horizontal form-edit">
    
    <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
    <input type="hidden" name="no" value="<?=@$no;?>">

    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button>
        <span>There are some errors on the form. Please check below!</span>
    </div>
    <div class="alert alert-warning display-hide">
        <button class="close" data-close="alert"></button>
        <span>
            <ul></ul>
        </span>
    </div>
    <div class="form-body">
        <div class="form-group">
            <label class="col-sm-3 control-label">Project Type</label>
            <div class="col-sm-8">
                <select name="project_type" id="project_type" class="form-control" placeholder="Project Type" data-bvalidator="required">
                      <option></option>
                      <option value="0" <?=($ams->tpm_project_type == '0'?'selected':'')?> >Retail</option>
                      <?php if(@$ams->gr_type == '1'){?>
                      <option value="1" <?=($ams->tpm_project_type == '1'?'selected':'')?> >Project</option>
                      <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Maintenance</label>
            <div class="col-sm-8">
                <input value="<?=$ams->ams_wt_id;?>" name="maintenance" id="maintenance" type="text" class="required form-control select2" placeholder="Maintenance" data-bvalidator="required" multiple>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Hangar</label>
            <div class="col-sm-8">
                <input value="<?=$ams->loc_id;?>" name="hangar" id="hangar"  type="text" class="required form-control" placeholder="Hangar" data-bvalidator="required"></td>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">
                <?php if(@$ams->ams_ar_id != ''){?>
                    A/C Registrasion
                <?php }else{ ?>
                    Serinumber
                <?php } ?>
            </label>
            <div class="col-sm-8">
                <?php if(@$ams->ams_ar_id != ''){?>
                    <input value="<?=$ams->ams_ar_id;?>" name="ac_reg" id="ac_reg"  type="text" class="required form-control" placeholder="A/C Registration" data-bvalidator="required">
                <?php }else{ ?>
                    <input value="<?=$ams->ams_serinumber;?>" name="seri_number" id="seri_number" type="text" class="required form-control" placeholder="Seri Number" data-bvalidator="required"/>
                <?php } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Sales Plan</label>
            <div class="col-sm-8">
                <input value="<?=$ams->ams_salesplan;?>" name="salesplan" id="salesplan"  type="text" class="required form-control" placeholder="Sales Plane" data-bvalidator="required"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Start Date</label>
            <div class="col-sm-8">
                <input value="<?=$ams->ams_start_date;?>" name="start_date" id="start_date"  type="text" class="required form-control" placeholder="Start Date" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">TAT</label>
            <div class="col-sm-8">
                <input value="<?=$ams->ams_tat;?>" name="tat" id="tat"  type="text" class="required form-control" placeholder="TAT" data-bvalidator="required"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">End Date</label>
            <div class="col-sm-8">
                <input value="<?=$ams->ams_end_date;?>" name="end_Date" id="end_date" readonly type="text" class="required form-control" placeholder="End Date" data-bvalidator="required" />
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12" align="center">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>

    </div>
</form>



<script type="text/javascript">
$(document).ready( function() {

    //select data ac_type 
    $('#project_type').select2();

    //select ac_reg
    // $('#ac_reg').select2({
    //       minimumInputLength: 0,
    //       ajax:{
    //           url: "<?php echo site_url($setting['url'].'select_ac_reg'); ?>",
    //           dataType: 'json',
    //           quietMillis: 250,
    //           cache: true,
    //           data: function (term, page) {
    //               return { q: term, at_id:"<?=@$ams->at_id?>"};
    //           },
    //           results: function (data, page) {
    //               return { results: data.item };
    //           },
    //      },
    //      initSelection: function(element, callback) {
    //         var id = $(element).val();
    //         if (id !== "") {
    //             $.ajax("<?php echo site_url($setting['url'].'select_ac_reg')?>" +"/"+ id, {
    //             dataType: "json"
    //             }).done( function(data) { callback(data[0]); });
    //         }
    //     },
    //      formatResult: function (item){return item.name;},
    //      formatSelection: function (item){return item.name;}
    // });

    //select line
    $('#maintenance').select2({
          minimumInputLength: 0,
          allowClear: true,
          multiple: true,
          ajax:{
              url: "<?php echo site_url($setting['url'].'select_maintenance'); ?>",
              dataType: 'json',
              quietMillis: 250,
              cache: true,
              data: function (term, page) {
                  return { q: term, gr_id:"<?=@$ams->gr_id?>"};
              },
              results: function (data, page) {
                  return { results: data.item };
              },
         },
         initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
                var id = id.replace(/,/g , "-");
                $.ajax("<?php echo site_url($setting['url'].'select_maintenance')?>" +"/"+ id, {
                dataType: "json"
                }).done( function(data) { callback(data); });
            }
        },
        formatResult: function (item){return item.name;},
        formatSelection: function (item){return item.name;}
    });

    //select hangar
    $('#hangar').select2({
          minimumInputLength: 0,
          ajax:{
              url: "<?php echo site_url($setting['url'].'select_location'); ?>",
              dataType: 'json',
              quietMillis: 250,
              cache: true,
              data: function (term, page) {
                  return { q: term, gr_id:"<?=@$ams->gr_id?>", cus_company:"<?=@$ams->cus_company?>", at_id:"<?=@$ams->at_id?>"};
              },
              results: function (data, page) {
                  return { results: data.item };
              },

         },
         initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
                $.ajax("<?php echo site_url($setting['url'].'select_location')?>" +"/"+ id, {
                dataType: "json"
                }).done( function(data) { callback(data[0]); });
            }
        },
        formatResult: function (item){return item.name;},
        formatSelection: function (item){return item.name;}
    });

    //format dolar
    $('#salesplan').inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        prefix: '', //No Space, this will truncate the first character
        oncleared: function () { self.Value(''); }
    });

    //format tanggal
    $('#start_date').datepicker({ format:"yyyy-mm-dd", autoclose: true });

    //format angka
    $('#tat').inputmask({alias: 'numeric', allowMinus: false});

    //hitung end date
    $('#tat, #start_date').on("change", function (e) {
          var start_date    = $('#start_date').val();
          var tat           = parseFloat($('#tat').val());        if(isNaN(tat)) {tat='';}
          if(tat != '' && start_date != ''){
            var new_date = new Date(start_date);
            new_date.setDate(new_date.getDate() + tat -1);
            var day   = new_date.getDate();     if(day < 10){ day = '0'+day;}
            var month = new_date.getMonth()+1;  if(month < 10){ month = '0'+month;}
            var year  = new_date.getFullYear();
            var end_date = ( year + '-' + month + '-' + day);
            $('#end_date').val(end_date);
          }else{
            $('#end_date').val('');
          }
    });

    var form = '.form-edit';
    FormValidation_edit_reg.initDefault(form);

});
</script>

