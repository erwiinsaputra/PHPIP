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
                        <input type="hidden" value="<?=@$data->start_date?>" name="start_date_old"  class="form-control" />
                        <input type="hidden" value="<?=@$data->end_date?>" name="end_date_old"  class="form-control" />
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
                <label class="control-label col-md-3"><b>PIC SI</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input <?=$readonly?> value="<?=@$data->pic_si;?>" name="pic_si" id="pic_si" type="text" class="required form-control" placeholder="PIC SI"  data-bvalidator="required"  />
                    <input value="<?=str_replace(', ',',',@$data->user_pic_si);?>" name="user_pic_si" id="user_pic_si" type="hidden" class="form-control"/>
                    <input value="<?=str_replace(', ',',',@$data->user_pic_si);?>" name="user_pic_si_old" type="hidden" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>PIC IC (Charter Manager)</b>
                <span class="required" aria-required="true">*</span>
                </label>
                <div class="col-md-8">
                    <input <?=$readonly?> value="<?=str_replace(', ',',',@$data->user_pic_ic);?>" name="user_pic_ic" id="user_pic_ic" type="text" class="required form-control" placeholder="PIC IC"  data-bvalidator="required"  />
                    <input value="<?=str_replace(', ',',',@$data->user_pic_ic);?>" name="user_pic_ic_old" type="hidden" class="form-control"/>
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
                    <div class="input-group">
                        <span class="input-group-addon">
                        <input <?=(@$data->cek_objective_key_result == '1' ? 'checked="checked"' : '')?> type="checkbox" name="cek_objective_key_result" style="width:1.3em;height:1.3em;">
                        </span>
                        <input <?=$readonly?> value="<?=@$data->objective_key_result;?>" name="objective_key_result" type="text" class="form-control" placeholder="Objective & Key Result">
                    </div>
                
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><b>Attachments</b>
                <span class="required" aria-required="true"></span>
                </label>
                <label class="control-label col-md-8" style="text-align:left;font-weight:bold;">:
                    <a href="javascript:;" class="btn btn-sm btn-primary btn_upload_file_si" idnya="<?=@$id;?>"> 
                        Upload File
                    </a>
                    <div id="list_file_si">
                        <?=@$html_list_file_si;?>
                    </div>
                </label>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div style="text-align:center;font-size:1.5em;">
                        <b>Direct-Correlated SO & KPI-SO</b>
                    </div>
                    <div style="float:right;">
                        <a href="javascript:" class="btn btn-sm btn-primary btn_add_direct" data-placement="top" data-container="body"><i class="fa fa-plus"></i> Add Direct</a>
                    </div>
                    <br><br>
                    <div style="overflow:auto;">
                        <table class="table table-bordered" id="table_direct_so">
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
            </div>
            <div class="form-group">
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
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="status" value="<?=@$data->status;?>"  >
                <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                <input type="hidden" id="edit" value="yes" >
                <input type="hidden" id="tot_load" value="1" >
                <?php if($type == 'view'){ ?>
                    <br/><br/>
                    <button title="Approve" id="<?=$id?>" val="3" class="btn btn-primary btn_approval"><i class="fa fa-check"></i> Approve</button>
                    <button title="Reject" id="<?=$id?>" val="4" class="btn btn-danger btn_approval"><i class="fa fa-times"></i> Reject</button>
                <?php }else{ ?>
                    <br/><br/>
                    <button title="Reject" id="<?=$id?>" val="4" class="btn btn-danger btn_approval"><i class="fa fa-times"></i> Reject</button>
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
            $("#form_edit input[name='user_pic_si']").val(item.user_pic_si);
            return item.name;
        }
    });


    //select pic ic
    $("#form_edit input[name='user_pic_ic']").select2({
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
                        $('#popup_view').modal('hide');
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

    //btn change status
    $('#form_edit').on('click', '.btn_approval', function(e) {
        //cek file
        var jum_file = $('#jum_file').val();
        if(jum_file <= 0){
            alert('File Attachment Masih Kosing!');
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
                var url    = '<?=site_url($url)?>/change_status_approval';
                var param  = {id:id, val:val, token:token};
                $.post(url, param, function(msg){
                    window.reload_table_si();
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                    $('#popup_edit').modal('hide');
                    $('#popup_view').modal('hide');
                }, 'json');
        });
    });

    //popup upload
    $('#form_edit').on('click', '.btn_upload_file_si', function(e) {
        $('#popup_upload_si').modal();
        var id  = $(this).attr('idnya');
        var url = "<?=site_url($url);?>/load_popup_upload_si";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_upload_si',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_upload_si').html(msg);
            Metronic.unblockUI('#load_upload_si');
        });
    });
    window.list_file_si = function(){
        var id          = "<?=$id?>";
        var param       = {id:id};
        var url         = "<?=site_url($url);?>/list_file_si";
        Metronic.blockUI({ target: '#list_file_si',  boxed: true});
        $.post(url, param, function(msg){
            $('#form_edit').find('#list_file_si').html(msg);
            Metronic.unblockUI('#list_file_si');
        });
    }


    


    //=================== Tambah SO dan KPI-SO Direct  =============================
    //add direct
    var direct = <?=@$direct?>;
    var indirect = <?=@$indirect?>;
    var tot_data = direct.length + indirect.length;

	var a = -1;
	var ke = 0;
	$("#form_edit").on('click','.btn_add_direct',function(){
		a += 1;
		ke += 1;

        //data id kpi-so
        if(typeof direct[a] === "undefined"){ 
            var data_id_kpi_so = ""; 
        }else { 
            var data_id_so = direct[a].id_so; 
            var data_id_kpi_so = direct[a].id_kpi_so; 
        }

		var tambah = 
				'<tr class="baris">'+
					'<td><input <?=$readonly?> value="'+data_id_so+'" name="so_direct[]" id="so_direct_'+ke+'" ke="'+ke+'" type="text" class="required form-control" placeholder="SO Direct" data-bvalidator="required"/></td>'+
					'<td><input <?=$readonly?> value="'+data_id_kpi_so+'" name="kpi_so_direct[]" id="kpi_so_direct_'+ke+'" ke="'+ke+'" type="text" class="required form-control" placeholder="KPI-SO Direct" data-bvalidator="required"/>'+
					'    <input value="edit" id="choose_so_direct_'+ke+'" type="hidden" class="form-control choose_so_direct" /></td>'+
					'<td><a class="btn red-sunglo remove_item"><i class="fa fa-times"></i></a></td>'+
				'</tr>';
		$("#form_edit #load_add_direct").append(tambah);

        //select so
        $("#form_edit #so_direct_"+ke).select2({
            allowClear: true,
            ajax: {
                url: "<?php echo site_url($url.'/select_so'); ?>",
                dataType: 'json', quietMillis: 250, type:"POST",
                data: function (term, page) {  
                    var no = $(this).attr('ke');
                    var id_bsc = $('#form_edit #id_bsc').val(); 
                    var id_kpi_so = $('#form_edit #kpi_so_direct_'+no).val(); 
                    return { q: term, id_bsc:id_bsc, id_kpi_so:id_kpi_so, no:no }; 
                },
                results: function (data, page) { return { results: data.item }; },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                var no = $(element).attr('ke');
                var id_kpi_so = $('#form_edit #kpi_so_direct_'+no).val(); 
                if (id !== "") {
                    $.ajax("<?php echo site_url($url.'/select_so')?>", {  dataType: "json", type:"POST", data:{ id: id, id_kpi_so:id_kpi_so, no:no } }).done( function(data) { callback(data[0]); });
                }
            },
            formatResult: function(item){return item.name;},
            formatSelection: function(item){ 
                var no = item.no;
                $("#form_edit #kpi_so_direct_"+no).show();
                var choose_so = $("#form_edit #choose_so_direct_"+no).val();
                if(choose_so == ''){
                    $("#form_edit #kpi_so_direct_"+no).val('');
                    $("#form_edit #kpi_so_direct_"+no).change();
                    $("#form_edit #choose_so_direct_"+no).val('so');
                }
                if(choose_so == 'so'){
                    $("#form_edit #choose_so_direct_"+no).val('');
                    $("#form_edit #kpi_so_direct_"+no).val('');
                    $("#form_edit #kpi_so_direct_"+no).change(); 
                }
                if(choose_so == 'kpi_so'){
                    $("#form_edit #choose_so_direct_"+no).val('');
                    $("#form_edit #kpi_so_direct_"+no).val(item.id_kpi_so);
                }
                if(choose_so == 'edit'){
                    var tot_load = parseFloat($("#form_edit #tot_load").val());
                    if(tot_data == tot_load){
                        $("#form_edit .choose_so_direct").val('');
                        $("#form_edit .choose_so_indirect").val('');
                    }
                    var tot = tot_load + 1;
                    $("#form_edit #tot_load").val(tot);
                }
                return item.name;
            }
        });

        //select so
        $("#form_edit #kpi_so_direct_"+ke).select2({
            allowClear: true, 
            ajax: {
                url: "<?php echo site_url($url.'/select_kpi_so'); ?>",
                dataType: 'json', quietMillis: 250, type:"POST", cache: true,
                data: function (term, page) { 
                    var no = $(this).attr('ke');
                    var id_so = $('#form_edit #so_direct_'+no).val(); 
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
                var choose_so = $("#form_edit #choose_so_direct_"+no).val();
                if(choose_so == ''){
                    $("#form_edit #so_direct_"+no).val(item.id_so); 
                    $("#form_edit #so_direct_"+no).change();
                    $("#form_edit #choose_so_direct_"+no).val('kpi_so');
                }
                if(choose_so == 'so'){
                    $("#form_edit #choose_so_direct_"+no).val('kpi_so');
                }
                if(choose_so == 'kpi_so'){
                    $("#form_edit #so_direct_"+no).val(item.id_so); 
                    $("#form_edit #so_direct_"+no).change();
                }
                return item.name;
            }
        });

	});
    //=================== END Direct  ================================================



    //=================== Tambah SO dan KPI-SO InDirect  =============================
    
    //add indirect
    var indirect = <?=@$indirect?>;
	var z2 = -1;
	var ke2 = 0;
	$("#form_edit").on('click','.btn_add_indirect',function(){
		z2 += 1;
		ke2 += 1;

        //data id kpi-so
        if(typeof indirect[z2] === "undefined"){ 
            var data_id_kpi_so = ""; 
        }else { 
            var data_id_so = indirect[z2].id_so; 
            var data_id_kpi_so = indirect[z2].id_kpi_so; 
        }

		var tambah = 
				'<tr class="baris">'+
					'<td><input <?=$readonly?> value="'+data_id_so+'" name="so_indirect[]" id="so_indirect_'+ke2+'" ke2="'+ke2+'" type="text" class="required form-control" placeholder="SO Direct" data-bvalidator="required"/></td>'+
					'<td><input <?=$readonly?>  value="'+data_id_kpi_so+'" name="kpi_so_indirect[]" id="kpi_so_indirect_'+ke2+'" ke2="'+ke2+'" type="text" class="required form-control" placeholder="KPI-SO Direct" data-bvalidator="required"/>'+
					'    <input value="edit" id="choose_so_indirect_'+ke2+'" type="hidden" class="form-control choose_so_direct" /></td>'+
					'<td><a class="btn red-sunglo remove_item"><i class="fa fa-times"></i></a></td>'+
				'</tr>';
		$("#form_edit #load_add_indirect").append(tambah);

        //select so
        $("#form_edit #so_indirect_"+ke2).select2({
            allowClear: true,
            ajax: {
                url: "<?php echo site_url($url.'/select_so'); ?>",
                dataType: 'json', quietMillis: 250, type:"POST",
                data: function (term, page) {  
                    var no = $(this).attr('ke2');
                    var id_bsc = $('#form_edit #id_bsc').val(); 
                    var id_kpi_so = $('#form_edit #kpi_so_indirect_'+no).val(); 
                    return { q: term, id_bsc:id_bsc, id_kpi_so:id_kpi_so, no:no }; 
                },
                results: function (data, page) { return { results: data.item }; },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                var no = $(element).attr('ke2');
                var id_kpi_so = $('#form_edit #kpi_so_indirect_'+no).val(); 
                if (id !== "") {
                    $.ajax("<?php echo site_url($url.'/select_so')?>", {  dataType: "json", type:"POST", data:{ id: id, id_kpi_so:id_kpi_so, no:no } }).done( function(data) { callback(data[0]); });
                }
            },
            formatResult: function(item){return item.name;},
            formatSelection: function(item){ 
                var no = item.no;
                $("#form_edit #kpi_so_indirect_"+no).show();
                var choose_so = $("#form_edit #choose_so_indirect_"+no).val();
                if(choose_so == ''){
                    $("#form_edit #kpi_so_indirect_"+no).val('');
                    $("#form_edit #kpi_so_indirect_"+no).change(); 
                    $("#form_edit #choose_so_indirect_"+no).val('so');
                }
                if(choose_so == 'so'){
                    $("#form_edit #choose_so_indirect_"+no).val('');
                    $("#form_edit #kpi_so_indirect_"+no).val('');
                    $("#form_edit #kpi_so_indirect_"+no).change(); 
                }
                if(choose_so == 'kpi_so'){
                    $("#form_edit #choose_so_indirect_"+no).val('');
                    $("#form_edit #kpi_so_indirect_"+no).val(item.id_kpi_so);
                }
                if(choose_so == 'edit'){
                    var tot_load = parseFloat($("#form_edit #tot_load").val());
                    if(tot_data == tot_load){
                        $("#form_edit .choose_so_direct").val('');
                        $("#form_edit .choose_so_indirect").val('');
                    }
                    var tot = tot_load + 1;
                    $("#form_edit #tot_load").val(tot);
                }
                return item.name;
            }
        });

        //select so
        $("#form_edit #kpi_so_indirect_"+ke2).select2({
            allowClear: true, 
            ajax: {
                url: "<?php echo site_url($url.'/select_kpi_so'); ?>",
                dataType: 'json', quietMillis: 250, type:"POST", cache: true,
                data: function (term, page) { 
                    var no = $(this).attr('ke2');
                    var id_so = $('#form_edit #so_indirect_'+no).val(); 
                    return { q: term, id_so:id_so, no:no };
                },
                results: function (data, page) { return { results: data.item }; }
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                var no = $(element).attr('ke2');
                if (id !== "") {
                    $.ajax("<?php echo site_url($url.'/select_kpi_so')?>", { dataType: "json", type:"POST", data:{ id: id, no:no}  }).done( function(data) { callback(data[0]); });
                }
            },
            formatResult: function(item){return item.name;},
            formatSelection: function(item){
                var no = item.no;
                var choose_so = $("#form_edit #choose_so_indirect_"+no).val();
                if(choose_so == ''){
                    $("#form_edit #so_indirect_"+no).val(item.id_so); 
                    $("#form_edit #so_indirect_"+no).change();
                    $("#form_edit #choose_so_indirect_"+no).val('kpi_so');
                }
                if(choose_so == 'so'){
                    $("#form_edit #choose_so_indirect_"+no).val('kpi_so');
                }
                if(choose_so == 'kpi_so'){
                    $("#form_edit #so_indirect_"+no).val(item.id_so); 
                    $("#form_edit #so_indirect_"+no).change();
                }
                return item.name;
            }
        });

	});

    //delete
	$("#form_edit").on('click', '.remove_item',function (ev) {
		$(this).parents(".baris").remove();
	});
    //=================== END Direct  ================================================


    



    //=================== load data awal Direct  ========================================
    var arr = <?=@$direct?>;
    $.each(arr,function(i,val){
        $("#form_edit .btn_add_direct").click();
	});
    var arr = <?=@$indirect?>;
    $.each(arr,function(i,val){
        $("#form_edit .btn_add_indirect").click();
	});

    //==============================================================================

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>