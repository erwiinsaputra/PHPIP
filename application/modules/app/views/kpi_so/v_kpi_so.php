<div class="row table_kpi_so">
    <div class="col-md-12">
        <div class="portlet light ">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-table font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Table &nbsp;<?=$title;?></span>
                    <input type="hidden" id="ex_csrf_token" value="<?=csrf_get_token();?>">
                </div>
                <div class="actions">

                     <div style="float:right;">
                        <a href="javascript:" id="btn_download" class="btn btn-danger btn-sm"  data-placement="top" data-container="body"><i class="fa fa-file"></i> Download</a>
                        <!-- <a href="javascript:" id="btn_upload" class="btn btn-primary btn-sm"  data-placement="top" data-container="body"><i class="fa fa-file"></i> Upload</a> -->
                        <a class="btn btn-primary btn-circle btn-sm " href="<?=site_url($url)?>" data-original-title="Refresh" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a> <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
                    </div> 

                    <div style="float:left;">
                        <div style="float:left;font-size:1.5em;"><span>Year : </span>&nbsp;&nbsp;</div>
                        <div style="float:left;">
                            <select class="form-control global-filter"  tipe="2" name="global_year" id="global_year" placeholder="ALL">
                                <option value=""></option>
                                <?php for($y=$start_year; $y <= $end_year; $y++){ ?>
                                    <option <?=($y == $year ? 'selected="selected"' : '')?> value="<?=$y?>"><?=$y?></option>
                                <?php } ?>
                            </select>
                        </div>
                        &nbsp; &nbsp; &nbsp; &nbsp; 
                    </div>

                    <div style="float:left;">
                        <div style="float:left;font-size:1.5em;"><span>Month : </span>&nbsp;&nbsp;</div>
                        <div style="float:left;">
                            <select class="form-control global-filter" tipe="2" name="global_month" id="global_month" placeholder="ALL">
                                <option value=""></option>
                                <?php for($m=1; $m <= 12; $m++){ ?>
                                    <option value="<?=$m?>"><?=$m?></option>
                                <?php } ?>
                            </select>
                        </div>
                        &nbsp; &nbsp; &nbsp; &nbsp; 
                    </div>

                </div>
            </div>


            <div class="portlet-body">
                
                <div class="form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-1"><b>BSC&nbsp;:</b></label>
                                    <div class="col-md-4">
                                        <select class="form-control global-filter" name="global_id_bsc" id="global_id_bsc" tipe="2" placeholder="ALL">
                                            <option value=""></option>
                                            <?php foreach($bsc as $row){ ?>
                                                <?php if(@$id_bsc == ''){ $id_bsc='1';} ?>
                                                <option <?=($row->id == @$id_bsc ? 'selected':'')?>  value="<?=$row->id;?>"><?=$row->name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2"><b>Perspective&nbsp;:</b></label>
                                    <div class="col-md-5">
                                        <input value="<?=@$id_perspective;?>" name="global_id_perspective" id="global_id_perspective" tipe="2" class="form-control global-filter" placeholder="ALL"  type="text" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-1"><b>SO&nbsp;Title:</b></label>
                                    <div class="col-md-11">
                                        <input value="<?=@$id_so;?>" name="global_id_so" id="global_id_so" tipe="2" class="form-control global-filter" placeholder="ALL"  type="text" />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- <hr style="margin-top:0px;"> -->

                <style type="text/css">
                    .table-wrap thead tr th, .table-wrap thead tr td, .table-wrap tbody tr td { white-space: nowrap;} 
                    .dataTables_scrollHeadInner{ padding-left:0px !important;}
                    .DTFC_RightHeadBlocker { background: #eee; border: 1px solid #eee;}
                    .DTFC_RightBodyLiner { overflow-y: none;}
                    .filter td{ padding: 0px !important; white-space: nowrap;}
                </style>
                
                <div class="table-container">

                    <div class="table-actions-wrapper">
                        <div style="float:right; padding-left:10px;">
                            <div class="showhide_column"></div>
                        </div>
                        <div style="float:right; padding-left:10px;">
                            <a href="javascript:" class="btn btn-sm yellow-crusta btn_show_filter"  data-placement="top" data-container="body"><i class="fa fa-search"></i> Search</a>
                        </div>
                        <div style="float:left; padding-left:10px;">
                            <a href="javascript:" class="btn btn-sm btn-primary btn_add" data-placement="top" data-container="body"><i class="fa fa-plus"></i> Add KPI-SO</a>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_kpi_so">
                        <thead>
                            <tr role="row" class="heading">
                                <th style="background-color:rgb(52 215 255) !important;color:white;" width="40px">No</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">ID</th>
                                
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Number</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">KPI-SO</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">PIC KPI-SO</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Polarisasi</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Ukuran</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Status</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Frekuensi Pengukuran</th>
                                
                                <th style="background-color:rgb(52 215 255) !important;color:white;">SO Title</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Perspective</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">BSC</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Start Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">End Date</th>

                                <th style="background-color:rgb(52 215 255) !important;color:white;">Description</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Is Active</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Created Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Created By</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Updated Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Updated By</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;" width="130px">Action</th>

                            </tr>
                            <tr role="row" class="filter display-hide">
                                <td></td>
                                <td><input name="id" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="code" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="name" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="pic_kpi_so" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="name_polarisasi" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="ukuran" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="name_status_kpi_so" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="frekuensi_pengukuran" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>

                                <td><input readonly="readonly" name="id_so" tipe="2" a1="text-center"  a2="false" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input readonly="readonly" name="id_perspective" tipe="2" a1="text-center"  a2="false" class="form-control form-filter select-filter input-md" placeholder="search" type="text" ></td>
                                <td><input readonly="readonly" name="id_bsc" tipe="2" a1="text-center"  a2="false" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="start_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="start_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="end_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="end_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td><input name="description" tipe="1" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><select value="t" a1="text-center" a2="false"  class="form-control form-filter select-filter input-md select2_biasa"  name="is_active" tipe="2" placeholder="Search" >
                                        <option value=""></option>
                                        <option value="t">Active</option>
                                        <option value="f">Disabled</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="created_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="created_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td><input type="text" a1="text-center" a2="false" class="form-control form-filter input-md"  name="created_by" tipe="1" placeholder="Search"></td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="updated_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="updated_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td><input type="text" a1="text-center" a2="false" class="form-control form-filter input-md"  name="updated_by" tipe="1" placeholder="Search"></td>
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


<!-- download data excel/pdf -->
<div style="display:none;">
    <form id='form_download' name='form_download' action="javascript:;" target="_blank" method="post">
        <input type="hidden" name="input_form" id="input_form" />
        <button type="button" class="btn btn-primary" id="btn_submit_download">Download</button>
    </form>
    <form id="form_upload" action="javascript:;" method="post" enctype="multipart/form-data">
        <input type="file" name="file" id="file_upload" />
    </form>
</div>


<!-- modal add-->
<div id="popup_add" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>ADD KPI-SO</b></h3>
      </div>
      <div class="modal-body" id="load_add"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal edit-->
<div id="popup_edit" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>EDIT KPI-SO</b></h3>
      </div>
      <div class="modal-body" id="load_edit"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal copy-->
<div id="popup_copy" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>COPY KPI-SO</b></h3>
      </div>
      <div class="modal-body" id="load_copy"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal upload-->
<div id="popup_upload_kpi_so" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Upload File</b></h3>
      </div>
      <div class="modal-body" id="load_upload_kpi_so"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy btn_close_upload" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>





<script type="text/javascript">
$(document).ready(function() {
    
    //================================= Datatable ==========================================
        //variabel
        var table_name  = "table_kpi_so";
        var url         = "<?=site_url($url);?>/table_kpi_so";
        var sort        = [-1,0];
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
            header[0]['data']       = "no";
            header[a]['data']       = "action";
            header[0]['sClass']     = "text-center";
            header[a]['sClass']     = "text-center";
        });

        //table init
        table_kpi_so.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_kpi_so .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //select2 biasa
        $(".table_kpi_so .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });

        //select2 global_year
        $(".table_kpi_so #global_year").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_kpi_so .filter-submit').first().click();
        });

        //select2 global_month
        $(".table_kpi_so #global_month").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_kpi_so .filter-submit').first().click();
        });

        //select2 biasa
        $(".table_kpi_so #global_id_bsc").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_kpi_so #global_id_perspective').val('');
            $(".table_kpi_so #global_id_perspective").change();
        });


        //select perspective
        window.table_kpi_so_global_id_perspective = function(){
            $(".table_kpi_so input[name='global_id_perspective']").select2({
                minimumResultsForSearch: -1,
                minimumInputLength: -1,
                dropdownAutoWidth : true,
                allowClear:true,
                ajax: {
                url: "<?php echo site_url($url.'/select_perspective')?>",
                dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
                data: function (term, page) {
                    var id_bsc = $("#global_id_bsc").val();
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
                $('.table_kpi_so #global_id_so').val('');
                $(".table_kpi_so #global_id_so").change();
            });
        }
        window.table_kpi_so_global_id_perspective();

         //select so
         window.table_kpi_so_global_id_so = function(){
            $(".table_kpi_so input[name='global_id_so']").select2({
                minimumInputLength: -1,
                dropdownAutoWidth : true,
                allowClear:true,
                ajax: {
                url: "<?php echo site_url($url.'/select_so')?>",
                dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
                data: function (term, page) {
                    var id_periode = $("#global_id_periode").val();
                    var id_bsc = $("#global_id_bsc").val();
                    var id_perspective = $("#global_id_perspective").val();
                    return { q: term, id_periode:id_periode, id_bsc:id_bsc, id_perspective:id_perspective };
                },
                results: function (data, page) {  return { results: data.item }; },
                },
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                    $.ajax({
                        url:"<?php echo site_url($url.'/select_so')?>",
                        dataType: "json", type:"POST", 
                        data:{ id: id}
                    }).done(function(res) { callback(res[0]); });
                    }
                },
                formatResult: function(item){ return item.name },
                formatSelection: function(item){ return item.name; }
            }).on('change', function(event) { 
                $('.table_kpi_so .filter-submit').first().click();
            });
        }
        window.table_kpi_so_global_id_so();
    //====================================================================================


    //=============================== Action =========================================

        //load add
        $('.table_kpi_so').on('click', '.btn_add', function(e) {
            $('#popup_add').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_add";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_add',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_add').html(msg);
                Metronic.unblockUI('#load_add');
            });
        });

        //load edit
        $('.table_kpi_so').on('click', '.btn_edit', function(e) {
            $('#popup_edit').modal();
            var id = $(this).attr('id');
            var type = 'edit';
            var url = "<?=site_url($url);?>/load_edit";
            var param = {id:id, type:type};
            Metronic.blockUI({ target: '#load_edit',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_edit').html(msg);
                Metronic.unblockUI('#load_edit');
            });
        });

        //load copy
        $('.table_kpi_so').on('click', '.btn_copy', function(e) {
            $('#popup_copy').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_copy";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_copy',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_copy').html(msg);
                Metronic.unblockUI('#load_copy');
            });
        });

        //load view
        $('.table_kpi_so').on('click', '.btn_view', function(e) {
            $('#popup_edit').modal();
            var id = $(this).attr('id');
            var type = 'view';
            var url = "<?=site_url($url);?>/load_edit";
            var param = {id:id, type:type};
            Metronic.blockUI({ target: '#load_edit',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_edit').html(msg);
                Metronic.unblockUI('#load_edit');
            });
        });

        //btn delete
        $('.table_kpi_so').on('click', '.btn_delete', function(e) {
            var id = $(this).attr('id');
            var val = $(this).attr('val');
            if(val == 't'){
                var mes = "Are you sure to Active Data?";
            }else{
                var mes = "Are you sure to DELETE Data?";
            }
            var title = "Are You Sure ?";
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
                    var token  = $('#ex_csrf_token').val();
                    var url    = '<?=site_url($url)?>/delete_data';
                    var param  = {id:id, val:val, token:token};
                    $.post(url, param, function(msg){
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_kpi_so();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    }, 'json');
            });
        });


        //change year
        window.change_year_table = function (){
            var periode  =  $(".table_kpi_so select[name='global_id_periode'] option[selected='selected']").text();
            var pecah = periode.split(" - ");
            var start_year = pecah[0];
            $(".table_kpi_so #global_start_year").val(start_year);
            for(i=0;i<5;i++){
                var year = parseFloat(start_year)+i;
                var a = $(".table_kpi_so .col_target_"+i).text()+' '+year;
                $(".table_kpi_so .col_target_"+i).text(a);
            }
        }
        window.change_year_table();

    //====================================================================================


    //btn download 
    $('#btn_download').on('click',function(){
        var arr = {};
        $('.table_kpi_so .global-filter').each(function(index, el) {
            var name = $(el).attr('name');
            var val = $(el).val();
            if($(this).val() != ''){
                if(val != null){ arr[name] = val; }
            }
        });
        var input = JSON.stringify(arr);
        $('#input_form').val(input);
        $('#btn_submit_download').click();    
    });
    //btn submit download
    $('#btn_submit_download').on('click',function(){
        var action = "<?=site_url($url);?>/download_excel";
        $('#form_download').attr('action',action);
        $('#form_download').submit();
        $('#form_download').attr('action','javascript:;');
        toastr.options = call_toastr('3000');
        var $toast = toastr['success']("Success For Export", "Success");
    });

    //btn upload
    $('#btn_upload').click(function(){
        $('#file_upload').trigger('click');
    });
    //btn submit upload
    $("#file_upload").change(function() {
        //parameter
        var param   = new FormData();
        param.append('file', this.files[0]);

        //konfirmasi
        var title = "Are You Sure ?";
        var mes = "Are you sure to Upload Data?";
        var sus = "Successfully Upload Data!";
        var err = "Failed Upload Data!";
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

              Metronic.blockUI({ target:'.body',  animate: true});     
              $.ajax({
                  url: "<?=site_url($url);?>/upload_excel",
                  type: "POST",
                  data: param,
                  contentType: false, cache: false, processData: false,
                  dataType: 'json',
                  success: function(data){
                      //succes
                      $("#file_upload").val('');
                      toastr.options = { "closeButton": true, "debug": false, "positionClass": "toast-top-right", "onclick": null,
                          "showDuration": "1000", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing",
                          "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut"
                      };
                      if(data.status == 0){ var msg = 'error'; }else if(data.status == 1){ var msg = 'success'; }
                      var toast = toastr[msg](data.message, msg.charAt(0).toUpperCase() + msg.slice(1));

                      //refresh table
                      $('.table_kpi_so .filter-submit').first().click();

                      Metronic.unblockUI('.body');
                  },
                  error : function(msg){
                      Metronic.unblockUI('.body');
                  }
              });
        });
        
        //kosongkan file
        $("#file_upload").val('');
        
    });




});
</script>