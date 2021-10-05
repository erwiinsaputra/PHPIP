<form id='export_excel' name='export_excel'  action="javascript:;" target="_blank" method="post" class="form-horizontal">
    <input type="hidden" name="year" id="year2" />
    <input type="hidden" name="log_us_id" id="log_us_id2" />
    <input type="hidden" name="log_role_id" id="log_role_id2" />
    <input type="hidden" name="log_ip_address" id="log_ip_address2" />
    <input type="hidden" name="log_other_user" id="log_other_user2" />
    <input type="hidden" name="log_type" id="log_type2" />
    <input type="hidden" name="log_created_date" id="log_created_date2" />
    <input type="hidden" name="log_activity" id="log_activity2" />
    <input type="hidden" name="log_param_id" id="log_param_id2" />
</form>

<div class="row table_tpr_log_history">
    <div class="col-md-12">
        <div class="portlet light ">


            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-table font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Table <?php echo $setting['pagetitle']?></span>
                </div>
                <div class="actions">
                    <div style="float:right;">
                        <a class="btn btn-primary btn-circle btn-sm " href="<?=site_url($url)?>" data-original-title="Refresh" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a> <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
                    </div>
                    <div style="float:right;">
                            <button class="btn btn-primary btn-sm" type="button" id="btn_export_excel">Export To Excel</button>
                            &nbsp;&nbsp;&nbsp;
                    </div>
                </div>
            </div>


            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div style="float:right; font-size:15px; font-family: 'Open Sans', sans-serif; font-weight:border;">
                             <table>
                                <tr>
                                    <td style="padding-bottom:5px;"><b>Year :</b></td>
                                    <td style="padding-bottom:5px;">
                                        <select class="form-control input-md input-inline global-filter"  name="year" id="year" style="width:150px;">
                                            <option value="all" selected>ALL</option>
                                            <?php for ($year=2014; $year <= 2040; $year++) { ?>
                                                <option value="<?=$year;?>" ><?=$year;?></option>
                                            <?php  } ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

 
                <hr style="margin: 10px;">


                <style type="text/css">
                    .table-wrap thead tr th, .table-wrap thead tr td, .table-wrap tbody tr td { white-space: nowrap; } 
                    .dataTables_scrollHeadInner{ padding-left:0px !important;}
                    .DTFC_RightHeadBlocker { background: #eee; border: 1px solid #eee;}
                    .DTFC_RightBodyLiner { overflow-y: none;}
                    .filter td{ padding: 0px !important;}
                </style>

                
                <div class="table-container">

                    <div class="table-actions-wrapper">
                        <div style="float:right; padding-left:10px;">
                            <div class="showhide_column"></div>
                        </div>
                        <div style="float:right;">
                            <a href="javascript:" class="btn btn-sm yellow-crusta btn_show_filter"  data-placement="top" data-container="body"><i class="fa fa-search"></i> Search</a>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_tpr_log_history">
                        <thead>
                            <tr role="row" class="heading">
                                <th width="40px">No</th>
                                <th>User</th>
                                <th>Login As</th>
                                <th>Other User</th>
                                <th>IP Address</th>
                                <th>Type</th>
                                <th>Create Date</th>
                                <th>Activity</th>
                                <th>Param Id</th>
                                <th>Action</th>
                            </tr>
                            <tr role="row" class="filter display-hide">
                                <td></td>
                                <td>
                                    <input type="text" a1="text-center" class="form-control form-filter select-filter input-md"  name="log_us_id" tipe="6" placeholder="User">
                                </td>
                                <td>
                                    <select multiple a1="text-center" class="form-control form-filter select-filter input-md select2_biasa" name="log_role_id" tipe="6" placeholder="Login As"  >
                                        <?php foreach (h_role_name('array') as $key => $val) { ?>
                                            <option value="<?=$key;?>"><?=$val;?></option>
                                        <?php  } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" a1="text-center" class="form-control form-filter select-filter input-md"  name="log_other_user" tipe="7" placeholder="Other User" >
                                </td>
                                <td>
                                    <input type="text" a1="text-center" class="form-control form-filter select-filter input-md"  name="log_ip_address" tipe="1" placeholder="IP" >
                                </td>
                                <td>
                                    <select a1="text-center" a2="false" class="form-control form-filter select-filter input-md select2_biasa"  name="log_type" tipe="2" placeholder="Type" >
                                        <option value=""></option>
                                        <option value="login">login</option>
                                        <option value="update">update</option>
                                        <option value="delete">delete</option>
                                        <option value="insert">insert</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" a1="text-center" class="form-control form-filter input-md tanggal" name="log_created_date" tipe="3" placeholder="From">
                                    <input type="text" a1="text-center" class="form-control form-filter input-md tanggal" name="log_created_date" tipe="4" placeholder="To">
                                </td>
                                <td>
                                    <input type="text" a1="text-center" class="form-control form-filter select-filter input-md"  name="log_activity" tipe="1" placeholder="Activity" >
                                </td>
                                <td>
                                    <input type="text" a1="text-center" class="form-control form-filter select-filter input-md"  name="log_param_id" tipe="6" placeholder="Param ID" >
                                </td>
                                <td class="text-center">
                                    <a href="javascript:;" data-original-title="Search" class="tooltips btn btn-sm yellow-crusta filter-submit margin-bottom btn_search"><i class="fa fa-search"></i></a>
                                    <a href="javascript:;" data-original-title="Reset" class="tooltips btn btn-sm red-sunglo filter-cancel"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    
                </div>
            </div>
        </div>


    </div>
</div>
</form>


<!-- modal view file-->
<div id="popup_view_file" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document" style="width:70%">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><strong>Close</strong></span></button> -->
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>View Sales Plan</b></h3>
      </div>
      <div class="modal-body" id="load_view_file"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- modal history-->
<div id="popup_history_sales" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document" style="width:70%">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><strong>Close</strong></span></button> -->
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>History PICA Sales Plan</b></h3>
      </div>
      <div class="modal-body" id="load_history_sales"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="popup_export" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#3c8dbc;color:white;">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><strong>Close</strong></span></button> -->
                <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
                <h3 class="modal-title" style="text-align:center;"><b>FILTER To EXPORT</b></h3>
            </div>
            <div class="modal-body">
                <form id='export_excel' name='export_excel'  action="javascript:;" target="_blank" method="post" class="form-horizontal">

                    <input type="hidden" name="cek_val" id="cek_val" />
                    <input type="hidden" name="cus_code" id="cus_code" />

                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-2"><b>Format&nbsp;Number&nbsp;: </b></label>
                                    <div class="col-md-3">
                                        <select name="tipe_format" class="required form-control"  placeholder="Type">
                                            <option value="INDONESIA">INDONESIA ( . , )</option>
                                            <option value="ENGLISH" selected="">ENGLISH ( , . )</option>
                                        </select>
                                  </div>
                              </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-2 control-label"><b>Column For Show :</b></label>
                                    <div class="col-md-10">
                                        <div class="checkbox-list">
                                            <label class="checkbox-inline">
                                                <div class="checker">
                                                    <span><input type="checkbox" name="cek_all"></span>
                                                </div> 
                                                Select All : 
                                            </label>
                                        </div>
                                        <div class="table-scrollable" style="border:1px solid #eee;">
                                            <table class="">
                                                <tbody>
                                                    <tr>
                                                        <td style="padding: 0px 0px;text-align: left;"><label class="checkbox-inline">
                                                                <div class="checker"><span><input type="checkbox" name="cek[]" value="1"></span></div> 
                                                               User
                                                            </label>
                                                        </td>
                                                        <td style="padding: 0px 0px;text-align: left;"><label class="checkbox-inline">
                                                                <div class="checker"><span><input type="checkbox" name="cek[]" value="2"></span></div> 
                                                               Login As
                                                            </label>
                                                        </td>
                                                        <td style="padding: 0px 0px;text-align: left;"><label class="checkbox-inline">
                                                                <div class="checker"><span><input type="checkbox" name="cek[]" value="3"></span></div> 
                                                               Other User
                                                            </label>
                                                        </td>
                                                        <td style="padding: 0px 0px;text-align: left;"><label class="checkbox-inline">
                                                                <div class="checker"><span><input type="checkbox" name="cek[]" value="4"></span></div> 
                                                               IP Address
                                                            </label>
                                                        </td>
                                                        <td style="padding: 0px 0px;text-align: left;"><label class="checkbox-inline">
                                                                <div class="checker"><span><input type="checkbox" name="cek[]" value="5"></span></div> 
                                                               Type
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px 0px;text-align: left;"><label class="checkbox-inline">
                                                                <div class="checker"><span><input type="checkbox" name="cek[]" value="6"></span></div> 
                                                               Create Date
                                                            </label>
                                                        </td>
                                                        <td style="padding: 0px 0px;text-align: left;"><label class="checkbox-inline">
                                                                <div class="checker"><span><input type="checkbox" name="cek[]" value="7"></span></div> 
                                                               Activity
                                                            </label>
                                                        </td>
                                                        <td style="padding: 0px 0px;text-align: left;"><label class="checkbox-inline">
                                                                <div class="checker"><span><input type="checkbox" name="cek[]" value="8"></span></div> 
                                                               Param Id
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12" style="text-align:center;">
                                    <button type="button" class="btn btn-primary" id="btn_export_excel">EXPORT</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    
    //================================= Datatable ==========================================
        //variabel
        var table_name  = "table_tpr_log_history";
        var url         = "<?=site_url($setting['url'].'get_table')?>";
        var sort        = [0];
        var order       = [];
        //column order
        var header = [];
        var arr_col = $("#"+table_name).find('.filter').find('td');
        var a = -1;
        $.each(arr_col, function(i, val) {
            a++;
            header[a] = {};
            var name = $(this).find('.form-filter').first().attr('name');   
            var a1 = $(this).find('.form-filter').first().attr('a1');
            var a2 = $(this).find('.form-filter').first().attr('a2');
            if(typeof name === 'undefined'){header[a]['data'] = '';}else{header[a]['data'] = name;}
            if(typeof a1 !== 'undefined'){header[a]['sClass'] = a1;}
            if(typeof a2 !== 'undefined'){header[a]['visible'] = false;}
        }).promise().done(function () { 
            header[0]['data']   = "no";
            header[a]['data']   = "action";
            header[0]['sClass'] = "text-center";
            header[a]['sClass'] = "text-center";
        });

        //table init
        table_tpr_log_history.initDefault(url, header, order, sort);

        //drag column
        new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================




    //=============================== FILERING =========================================
        //select global
        $(".table_tpr_log_history .global-filter").select2({
            minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
        }).on('change', function(event) { 
            $('.table_tpr_log_history .filter-submit').trigger('click');
        });

        //select biasa
        $(".table_tpr_log_history .select2_biasa").select2({
            minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
        });

        //date
        $(".table_tpr_log_history .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //format dolar
        $('.table_tpr_log_history .dolar').inputmask("numeric", {
            radixPoint: ".", groupSeparator: ",", digits: 2, autoGroup: true, prefix: '',
            oncleared: function () { self.Value(''); }
        }).on('change', function(e) {
             $('.table_tpr_log_history .filter-submit').trigger('click');
        });

        //format angka
        $('.table_tpr_log_history .angka').inputmask("numeric", {
            radixPoint: ".", groupSeparator: "", digits: 0, autoGroup: true, prefix: '',
            oncleared: function () { self.Value(''); }
        }).on('change', function(e) {
             $('.table_tpr_log_history .filter-submit').trigger('click');
        });

        //View Data 
        $('.btn_view_history_tmb').die().live('click', function() {
            $('#popup_history_sales').modal();
            var ams_id  = $(this).attr('ams_id');
            var url     = '<?=site_url($setting['url'])?>/load_history_sales';
            var param   = {ams_id:ams_id};
            Metronic.blockUI({ target: '#load_history_sales',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_view_history_tmb').html(msg);
                Metronic.unblockUI('#load_view_history_tmb');
            });
        });




        //tpm_send_to
        window.table_tpr_log_history_log_us_id = function(){
            $(".table_tpr_log_history input[name='log_us_id']").select2({
                minimumInputLength: -1, dropdownAutoWidth : true, allowClear: true, multiple : true,
                ajax: {
                    url: "<?php echo site_url($setting['url'].'select_user'); ?>",
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (term, page) { return { q: term }; },
                    results: function (data, page) { return { results: data.item }; },
                    cache: true
                },
                formatResult: function(item){return item.name;},
                formatSelection: function(item){return item.name;},
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        var id = id.replace(/,/g , "-");
                        $.ajax("<?=site_url($setting['url'].'select_user')?>" +"/"+ id, {
                        dataType: "json" }).done( function(data) { callback(data); });
                    }
                },
            });
        }
        window.table_tpr_log_history_log_us_id();

        //loc_id
        window.table_tpr_log_history_loc_id = function(){
            $(".table_tpr_log_history input[name='loc_id']").select2({
                  minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true, multiple : true,
                  ajax:{
                      url: "<?php echo site_url($setting['url'].'select_location'); ?>",
                      dataType: 'json',
                      quietMillis: 250,
                      cache: true,
                      data: function (term, page) { return { q: term }; },
                      results: function (data, page) { return { results: data.item }; },
                 },
                formatResult: function (item){return item.name;},
                formatSelection: function (item){return item.name;},
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        var id = id.replace(/,/g , "-");
                        $.ajax("<?=site_url($setting['url'].'select_location')?>" +"/"+ id, {
                        dataType: "json" }).done( function(data) { callback(data); });
                    }
                },
            });
        }
        window.table_tpr_log_history_loc_id();

        //cou_id
        window.table_tpr_log_history_cou_id = function(){
            $(".table_tpr_log_history input[name='cou_id']").select2({
                  minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true, multiple : true,
                  ajax:{
                      url: "<?php echo site_url($setting['url'].'select_country'); ?>",
                      dataType: 'json',
                      quietMillis: 250,
                      cache: true,
                      data: function (term, page) { return { q: term }; },
                      results: function (data, page) { return { results: data.item }; },
                 },
                formatResult: function (item){return item.name;},
                formatSelection: function (item){return item.name;},
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        var id = id.replace(/,/g , "-");
                        $.ajax("<?=site_url($setting['url'].'select_country')?>" +"/"+ id, {
                        dataType: "json" }).done( function(data) { callback(data); });
                    }
                },
            });
        }
        window.table_tpr_log_history_cou_id();


        //reg_id
        window.table_tpr_log_history_reg_id = function(){
            $(".table_tpr_log_history input[name='reg_id']").select2({
                  minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true, multiple : true,
                  ajax:{
                      url: "<?php echo site_url($setting['url'].'select_region'); ?>",
                      dataType: 'json',
                      quietMillis: 250,
                      cache: true,
                      data: function (term, page) { return { q: term }; },
                      results: function (data, page) { return { results: data.item }; },
                 },
                formatResult: function (item){return item.name;},
                formatSelection: function (item){return item.name;},
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        var id = id.replace(/,/g , "-");
                        $.ajax("<?=site_url($setting['url'].'select_region')?>" +"/"+ id, {
                        dataType: "json" }).done( function(data) { callback(data); });
                    }
                },
            });
        }
        window.table_tpr_log_history_reg_id();

        //area_id
        window.table_tpr_log_history_area_id = function(){
            $(".table_tpr_log_history input[name='area_id']").select2({
                  minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true, multiple : true,
                  ajax:{
                      url: "<?php echo site_url($setting['url'].'select_area'); ?>",
                      dataType: 'json',
                      quietMillis: 250,
                      cache: true,
                      data: function (term, page) { return { q: term }; },
                      results: function (data, page) { return { results: data.item }; },
                 },
                formatResult: function (item){return item.name;},
                formatSelection: function (item){return item.name;},
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        var id = id.replace(/,/g , "-");
                        $.ajax("<?=site_url($setting['url'].'select_area')?>" +"/"+ id, {
                        dataType: "json" }).done( function(data) { callback(data); });
                    }
                },
            });
        }
        window.table_tpr_log_history_area_id();


        //at_id
        window.table_tpr_log_history_at_id = function(){
            $(".table_tpr_log_history input[name='at_id']").select2({
                minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true, multiple : true,
                ajax:{
                      url: "<?php echo site_url($setting['url'].'select_at'); ?>",
                      dataType: 'json',
                      quietMillis: 250,
                      cache: true,
                      data: function (term, page) {return { q: term };},
                      results: function (data, page) {return { results: data.item };},
                },
                formatResult: function (item){return item.name;},
                formatSelection: function (item){return item.name;},
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        var id = id.replace(/,/g , "-");
                        $.ajax("<?=site_url($setting['url'].'select_at')?>" +"/"+ id, {
                        dataType: "json" }).done( function(data) { callback(data); });
                    }
                },
            });
        }
        window.table_tpr_log_history_at_id();

        //ams_wt_id
        window.table_tpr_log_history_ams_wt_id = function(){
            $(".table_tpr_log_history input[name='ams_wt_id']").select2({
                minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true, multiple : true,
                ajax:{
                      url: "<?php echo site_url($setting['url'].'select_maintenance'); ?>",
                      dataType: 'json',
                      quietMillis: 250,
                      cache: true,
                      data: function (term, page) {return { q: term };},
                      results: function (data, page) {return { results: data.item };},
                },
                formatResult: function (item){return item.name;},
                formatSelection: function (item){return item.name;},
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        var id = id.replace(/,/g , "-");
                        $.ajax("<?=site_url($setting['url'].'select_maintenance')?>" +"/"+ id, {
                        dataType: "json" }).done( function(data) { callback(data); });
                    }
                },
            });
        }
        window.table_tpr_log_history_ams_wt_id();
        //================================================================================================


        //history sales 
        $('.btn_history_sales').die().live('click', function() {
            $('#popup_history_sales').modal();
            var ams_id = $(this).attr('ams_id');
            var url = '<?=site_url($url)?>/load_history_sales';
            var param = {ams_id:ams_id};
            Metronic.blockUI({ target: '#load_history_sales',  animate: true});
            $.post(url, param, function(msg){
                $('#load_history_sales').html(msg);
                Metronic.unblockUI('#load_history_sales');
            });
        });

        //view file 
        $('.btn_view_file').die().live('click', function() {
            $('#popup_view_file').modal();
            var ams_id = $(this).attr('ams_id');
            var url = '<?=site_url($url)?>/load_view_file';
            var param = {ams_id:ams_id};
            Metronic.blockUI({ target: '#load_view_file',  animate: true});
            $.post(url, param, function(msg){
                $('#load_view_file').html(msg);
                Metronic.unblockUI('#load_view_file');
            });
        });

        



        // //zoom in out
        // $('#zoom-in').click(function() { updateZoom(0.1); });
        // $('#zoom-out').click(function() { updateZoom(-0.1); });
        // zoomLevel = 1;
        // var updateZoom = function(zoom) {
        //    zoomLevel += zoom;
        //    $('body').css({ zoom: zoomLevel, '-moz-transform': 'scale(' + zoomLevel + ')' });
        // }

        //export excel
        $('#btn_export_excel').die().live('click',function(){

            var action = "<?php echo site_url($setting['url'].'export_to_excel'); ?>";
            $('#export_excel').attr('action',action);

            var year        = $("#year").val();
            var log_us_id        = $(".table_tpr_log_history input[name='log_us_id']").val();
            var log_role_id        = $(".table_tpr_log_history input[name='log_role_id']").val();
            var log_created_date     = $(".table_tpr_log_history input[name='log_created_date']").val();
            var log_ip_address          = $(".table_tpr_log_history input[name='log_ip_address']").val();
            var log_other_user          = $(".table_tpr_log_history input[name='log_other_user']").val();
            var log_type           = $(".table_tpr_log_history select[name='log_type']").val();
            var log_activity     = $(".table_tpr_log_history input[name='log_activity']").val();
            var log_param_id = $(".table_tpr_log_history input[name='log_param_id']").val();

            $('#year2').val(year);
            $('#log_ip_address2').val(log_ip_address);
            $('#log_other_user2').val(log_other_user);
            $('#log_type2').val(log_type);
            $('#log_created_date2').val(log_created_date);
            $('#log_us_id2').val(log_us_id);
            $('#log_role_id2').val(log_role_id);
            $('#log_activity2').val(log_activity);
            $('#log_param_id2').val(log_param_id);



            $('#export_excel').submit();
            $('#export_excel').attr('action','javascript:;');
        });

});
</script>