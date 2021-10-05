<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3"><b>Periode</b>
                    <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-4">
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
            <div class="form-group">
                <label class="control-label col-md-3"><b>SI Title</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="name" id="name" type="text" class="form-control" placeholder="SI Title" data-bvalidator="required" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>SI Number</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="code" id="code" type="text" class="form-control angka" placeholder="SI Number" data-bvalidator="" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>PIC SI</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="pic_si" id="pic_si" type="text" class="required form-control" placeholder="PIC SI"  data-bvalidator="required"  />
                    <input name="user_pic_si" id="user_pic_si" type="hidden" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>PIC IC (Charter Manager)</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="user_pic_ic" id="user_pic_ic" type="text" class="required form-control" placeholder="PIC IC"  data-bvalidator="required"  />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>Background & Goal</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input name="background_goal" type="text" class="form-control" placeholder="Background & Goal" data-bvalidator="required" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>Objective & Key Result</b>
                <span class="required" aria-required="true"></span>
                </label>
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-addon">
                        <input type="checkbox" name="cek_objective_key_result" style="width:1.3em;height:1.3em;">
                        </span>
                        <input name="objective_key_result" type="text" class="form-control" placeholder="Objective & Key Result">
                    </div>
                
                </div>
            </div>
            
            <!-- <div style="margin-top:1em;margin-bottom:1em;background:#3d7ab1;width:100%;height:0.4em;"></div>
            <div class="form-group">
                <div class="col-md-12" style="text-align:left;font-size:1.5em;"><b>Initiative Mapping (SI to SO & KPISO Mapping)</b></div>
            </div>
            <div style="margin-top:1em;margin-bottom:1em;background:#3d7ab1;width:100%;height:0.4em;"></div> -->

            <div class="form-group">
                <div class="col-md-12">
                    <div style="text-align:center;font-size:1.5em;">
                        <b>Direct-Correlated SO & KPI-SO</b>
                    </div>
                    <span style="float:right;">
                        <a href="javascript:" class="btn btn-sm btn-primary btn_add_direct" data-placement="top" data-container="body"><i class="fa fa-plus"></i> Add Direct</a>
                    </span>
                    <br><br>
                    <div style="overflow:auto;">
                        <table class="table table-bordered" id="table_direct_so" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:30%;text-align:center;background-color: rgb(61, 122, 177);color:white;">SO</th>
                                    <th style="width:70%;text-align:center;background-color: rgb(61, 122, 177);color:white;">KPI-SO</th>
                                    <th style="width:0%;text-align:center;background-color: rgb(61, 122, 177);color:white;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="load_add_direct"></tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <div style="text-align:center;font-size:1.5em;">
                        <b>Indirect-Correlated SO & KPI-SO</b>
                    </div>
                    <div style="float:right;">
                        <a href="javascript:" class="btn btn-sm btn-primary btn_add_indirect" data-placement="top" data-container="body"><i class="fa fa-plus"></i> Add Indirect</a>
                    </div>
                    <br><br>
                    <div style="overflow:auto;">
                        <table class="table table-bordered" id="table_indirect_so">
                            <thead>
                                <tr>
                                    <th style="width:30%;text-align:center;background-color: rgb(61, 122, 177);color:white;">SO</th>
                                    <th style="width:70%;text-align:center;background-color: rgb(61, 122, 177);color:white;">KPI-SO</th>
                                    <th style="width:0%;text-align:center;background-color: rgb(61, 122, 177);color:white;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="load_add_indirect"></tbody>
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
    var bsc  =  $(".table_si select[name='global_id_bsc']").val();
    if(bsc != ''){
        $("#form_add select[name='id_bsc'] option[value='"+bsc+"']").attr('selected','selected');
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
    $("#form_add input[name='pic_si']").select2({
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
        formatSelection: function(item){
            $("#form_add input[name='user_pic_si']").val(item.user_pic_si);
            return item.name;
        }
    });


    //select pic ic
    $("#form_add input[name='user_pic_ic']").select2({
        // minimumInputLength: 1,
        allowClear: true,
        multiple: true,
        ajax: {
            url: "<?php echo site_url($url.'/select_pic_ic'); ?>",
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
                $.ajax("<?php echo site_url($url.'/select_pic_ic')?>", {
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
                            window.reload_table_si();
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
    




    //=================== Tambah SO dan KPI-SO Direct  =============================
    
    //add direct
	var ke = 0;
	$("#form_add").on('click','.btn_add_direct',function(){
		ke += 1;
		var tambah = 
				'<tr class="baris">'+
					'<td><input name="so_direct[]" id="so_direct_'+ke+'" ke="'+ke+'" type="text" class="required form-control" placeholder="SO Direct" /></td>'+
					'<td><input name="kpi_so_direct[]" id="kpi_so_direct_'+ke+'" ke="'+ke+'" type="text" class="required form-control" placeholder="KPI-SO Direct" />'+
					'    <input value="" id="choose_so_direct_'+ke+'" type="hidden" class="form-control" /></td>'+
					'<td><a class="btn red-sunglo remove_item"><i class="fa fa-times"></i></a></td>'+
				'</tr>';
		$("#form_add #load_add_direct").append(tambah);

        //select so
        $("#form_add #so_direct_"+ke).select2({
            allowClear: true,
            ajax: {
                url: "<?php echo site_url($url.'/select_so'); ?>",
                dataType: 'json', quietMillis: 250, type:"POST",
                data: function (term, page) {  
                    var no = $(this).attr('ke');
                    var id_bsc = $('#form_add #id_bsc').val(); 
                    var id_kpi_so = $('#form_add #kpi_so_direct_'+no).val();
                    return { q: term, id_bsc:id_bsc, id_kpi_so:id_kpi_so, no:no }; 
                },
                results: function (data, page) { return { results: data.item }; },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                var no = $(element).attr('ke');
                var id_kpi_so = $('#form_add #kpi_so_direct_'+no).val(); 
                if (id !== "") {
                    $.ajax("<?php echo site_url($url.'/select_so')?>", {  dataType: "json", type:"POST", data:{ id: id, id_kpi_so:id_kpi_so, no:no } }).done( function(data) { callback(data[0]); });
                }
            },
            formatResult: function(item){return item.name;},
            formatSelection: function(item){ 
                var no = item.no;
                $("#form_add #kpi_so_direct_"+no).show();
                var choose_so = $("#form_add #choose_so_direct_"+no).val();
                if(choose_so == ''){
                    $("#form_add #kpi_so_direct_"+no).val('');
                    $("#form_add #kpi_so_direct_"+no).change(); 
                    $("#form_add #choose_so_direct_"+no).val('so');
                }
                if(choose_so == 'so'){
                    $("#form_add #choose_so_direct_"+no).val('');
                    $("#form_add #kpi_so_direct_"+no).val('');
                    $("#form_add #kpi_so_direct_"+no).change(); 
                }
                if(choose_so == 'kpi_so'){
                    $("#form_add #choose_so_direct_"+no).val('');
                    $("#form_add #kpi_so_direct_"+no).val(item.id_kpi_so);
                }
                return item.name;
            }
        });

        //select so
        $("#form_add #kpi_so_direct_"+ke).select2({
            allowClear: true, 
            ajax: {
                url: "<?php echo site_url($url.'/select_kpi_so'); ?>",
                dataType: 'json', quietMillis: 250, type:"POST", cache: true,
                data: function (term, page) { 
                    var no = $(this).attr('ke');
                    var id_so = $('#form_add #so_direct_'+no).val(); 
                    return { q: term, id_so:id_so, no:no };
                },
                results: function (data, page) { return { results: data.item }; }
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                var no = $(element).attr('ke');
                if (id !== "") {
                    $.ajax("<?php echo site_url($url.'/select_kpi_so')?>", { dataType: "json", type:"POST", data:{ id: id, no:no}  }).done( function(data) { callback(data[0]); });
                }
            },
            formatResult: function(item){return item.name;},
            formatSelection: function(item){
                var no = item.no;
                var choose_so = $("#form_add #choose_so_direct_"+no).val();
                if(choose_so == ''){
                    $("#form_add #so_direct_"+no).val(item.id_so); 
                    $("#form_add #so_direct_"+no).change();
                    $("#form_add #choose_so_direct_"+no).val('kpi_so');
                }
                if(choose_so == 'so'){
                    $("#form_add #choose_so_direct_"+no).val('kpi_so');
                }
                if(choose_so == 'kpi_so'){
                    $("#form_add #so_direct_"+no).val(item.id_so); 
                }
                return item.name;
            }
        });

	});
    $("#form_add .btn_add_direct").click();
    //=================== END Direct  ================================================


    //=================== Tambah SO dan KPI-SO Indirect  =============================
     //add indirect
	var ke = 0;
	$("#form_add").on('click','.btn_add_indirect',function(){
		ke += 1;
		var tambah = 
				'<tr class="baris">'+
					'<td><input name="so_indirect[]" id="so_indirect_'+ke+'" ke="'+ke+'" type="text" class="required form-control" placeholder="SO Direct" /></td>'+
					'<td><input name="kpi_so_indirect[]" id="kpi_so_indirect_'+ke+'" ke="'+ke+'" type="text" class="required form-control" placeholder="KPI-SO Direct" />'+
					'    <input value="" id="choose_so_indirect_'+ke+'" type="hidden" class="form-control" /></td>'+
					'<td><a class="btn red-sunglo remove_item"><i class="fa fa-times"></i></a></td>'+
				'</tr>';
		$("#form_add #load_add_indirect").append(tambah);

        //select so
        $("#form_add #so_indirect_"+ke).select2({
            allowClear: true,
            ajax: {
                url: "<?php echo site_url($url.'/select_so'); ?>",
                dataType: 'json', quietMillis: 250, type:"POST",
                data: function (term, page) {  
                    var no = $(this).attr('ke');
                    var id_bsc = $('#form_add #id_bsc').val(); 
                    var id_kpi_so = $('#form_add #kpi_so_indirect_'+no).val(); 
                    return { q: term, id_bsc:id_bsc, id_kpi_so:id_kpi_so, no:no }; 
                },
                results: function (data, page) { return { results: data.item }; },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                var no = $(element).attr('ke');
                var id_kpi_so = $('#form_add #kpi_so_indirect_'+no).val(); 
                if (id !== "") {
                    $.ajax("<?php echo site_url($url.'/select_so')?>", {  dataType: "json", type:"POST", data:{ id: id, id_kpi_so:id_kpi_so, no:no } }).done( function(data) { callback(data[0]); });
                }
            },
            formatResult: function(item){return item.name;},
            formatSelection: function(item){ 
                var no = item.no;
                $("#form_add #kpi_so_indirect_"+no).show();
                var choose_so = $("#form_add #choose_so_indirect_"+no).val();
                if(choose_so == ''){
                    $("#form_add #kpi_so_indirect_"+no).val('');
                    $("#form_add #kpi_so_indirect_"+no).change(); 
                    $("#form_add #choose_so_indirect_"+no).val('so');
                }
                if(choose_so == 'so'){
                    $("#form_add #choose_so_indirect_"+no).val('');
                    $("#form_add #kpi_so_indirect_"+no).val('');
                    $("#form_add #kpi_so_indirect_"+no).change(); 
                }
                if(choose_so == 'kpi_so'){
                    $("#form_add #choose_so_indirect_"+no).val('');
                    $("#form_add #kpi_so_indirect_"+no).val(item.id_kpi_so);
                }
                return item.name;
            }
        });

        //select so
        $("#form_add #kpi_so_indirect_"+ke).select2({
            allowClear: true, 
            ajax: {
                url: "<?php echo site_url($url.'/select_kpi_so'); ?>",
                dataType: 'json', quietMillis: 250, type:"POST", cache: true,
                data: function (term, page) { 
                    var no = $(this).attr('ke');
                    var id_so = $('#form_add #so_indirect_'+no).val(); 
                    return { q: term, id_so:id_so, no:no };
                },
                results: function (data, page) { return { results: data.item }; }
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                var no = $(element).attr('ke');
                if (id !== "") {
                    $.ajax("<?php echo site_url($url.'/select_kpi_so')?>", { dataType: "json", type:"POST", data:{ id: id, no:no}  }).done( function(data) { callback(data[0]); });
                }
            },
            formatResult: function(item){return item.name;},
            formatSelection: function(item){
                var no = item.no;
                var choose_so = $("#form_add #choose_so_indirect_"+no).val();
                if(choose_so == ''){
                    $("#form_add #so_indirect_"+no).val(item.id_so); 
                    $("#form_add #so_indirect_"+no).change();
                    $("#form_add #choose_so_indirect_"+no).val('kpi_so');
                }
                if(choose_so == 'so'){
                    $("#form_add #choose_so_indirect_"+no).val('kpi_so');
                }
                if(choose_so == 'kpi_so'){
                    $("#form_add #so_indirect_"+no).val(item.id_so); 
                }
                return item.name;
            }
        });

	});
    $("#form_add .btn_add_indirect").click();
    //delete 
	$("#form_add").on('click', '.remove_item',function (ev) {
		$(this).parents(".baris").remove();
	});
    //=================== END Indirect  ================================================


	


    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>