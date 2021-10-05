<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3"><b>Periode</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-4">
                    <div class="input-group">
                        <input <?=$disabled?> type="text" value="<?=substr(@$data->start_date,0,7)?>" id="start_date" name="start_date" class="form-control" readonly="readonly" data-bvalidator="required"/>
                        <span class="input-group-addon"> To </span>
                        <input <?=$disabled?> type="text" value="<?=substr(@$data->end_date,0,7)?>" id="end_date" name="end_date"  class="form-control" readonly="readonly" data-bvalidator="required"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                  <label class="control-label col-md-3"><b>BSC</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                        <select <?=$disabled?> name="id_bsc" id="id_bsc" class="form-control" placeholder="BSC" data-bvalidator="required"  >
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
                      <input <?=$readonly?> value="<?=@$data->name;?>" name="name" id="name" type="text" class="form-control" placeholder="SI Title" data-bvalidator="required" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>SI Number</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=$readonly?> value="<?=str_replace(',','.',@$data->code);?>" name="code" id="code"  type="text" class="form-control angka" placeholder="SI Number" data-bvalidator="" autocomplete="off">
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3"><b>PIC</b>
                    <span class="required" aria-required="true">*</span>
                  </label>
                  <div class="col-md-8">
                      <input <?=$readonly?> value="<?=@$data->pic_si;?>" name="pic_si" id="pic_si" type="text" class="required form-control" placeholder="PIC SI"  data-bvalidator="required"  />
                  </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3"><b>Background & Goal</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input <?=$readonly?> value="<?=@$data->background_goal;?>" name="background_goal" type="text" class="form-control" placeholder="Background & Goal" data-bvalidator="" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>Objective & Key Result</b>
                <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <?php 
                        if(@$data->cek_objective_key_result == '1'){ 
                            $objective_key_result = @$data->objective_key_result;
                        }else{
                            $objective_key_result = '-';
                        }
                    ?>
                    <input <?=$readonly?> value="<?=$objective_key_result;?>" name="objective_key_result" type="text" class="form-control" placeholder="Objective & Key Result">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <div style="text-align:center;font-size:1.5em;">
                        <b>Direct-Correlated SO & KPI-SO</b>
                    </div>
                    <div class="">
                        <table class="table table-bordered" id="table_direct_so">
                            <thead>
                                <tr>
                                    <th style="width:50%;text-align:center;background-color: rgb(61, 122, 177);color:white;">SO</th>
                                    <th style="width:50%;text-align:center;background-color: rgb(61, 122, 177);color:white;">KPI-SO</th>
                                </tr>
                            </thead>
                            <tbody id="load_add_direct">
                                <?php foreach(@$direct as $row){?>
                                <tr>
                                    <th style="text-align:center;"><?='('.$row->code_so.') '.$row->name_so?></th>
                                    <th style="text-align:center;"><?='('.$row->code_kpi_so.') '.$row->name_kpi_so?></th>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div style="text-align:center;font-size:1.5em;">
                        <b>Indirect-Correlated SO & KPI-SO</b>
                    </div>
                    <div class="">
                        <table class="table table-bordered" id="table_indirect_so">
                            <thead>
                                <tr>
                                    <th style="width:50%;text-align:center;background-color: rgb(61, 122, 177);color:white;">SO</th>
                                    <th style="width:50%;text-align:center;background-color: rgb(61, 122, 177);color:white;">KPI-SO</th>
                                </tr>
                            </thead>
                            <tbody id="load_add_indirect">
                                <?php foreach(@$indirect as $row){?>
                                <tr>
                                    <th style="text-align:center;"><?='('.$row->code_so.') '.$row->name_so?></th>
                                    <th style="text-align:center;"><?='('.$row->code_kpi_so.') '.$row->name_kpi_so?></th>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
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
                <input type="hidden" id="edit" value="yes" >
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
        $('#end_date').datepicker('setStartDate', minDate);
    });
    $("#form_edit #end_date").datepicker({
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#start_date').datepicker('setEndDate', minDate);
    });

    //select2 biasa
    $("#form_edit .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 biasa2
    $("#form_edit .select2_biasa2").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select2 biasa2
    $("#form_edit #id_bsc").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    }).on('change', function(event) { 
        //change
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

    
    //select pic si
    $("#form_edit input[name='pic_si']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url('app/si/select_pic_si'); ?>",
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
                $.ajax("<?php echo site_url('app/si/select_pic_si')?>", {
                dataType: "json", type:"POST",
                data:{ id: id}
                }).done( function(data) { callback(data); });
            }
        },
        formatResult: function(item){return item.name;},
        formatSelection: function(item){return item.name;}
    });
   

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>