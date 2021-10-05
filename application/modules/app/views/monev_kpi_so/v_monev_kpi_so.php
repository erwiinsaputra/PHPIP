<div class="row table_monev_kpi_so">
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
                                    <option <?=($y==@$year?'selected="selected"':'')?> value="<?=$y?>"><?=$y?></option>
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
                                    <?php if(in_array($m,array('3','6','9','12'))){ ?>
                                        <option <?=($m==@$month?'selected="selected"':'')?> value="<?=$m?>"><?=h_month_name($m)?></option>
                                    <?php } ?>
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
                                    <label class="control-label col-md-1"><b>BSC:</b></label>
                                    <div class="col-md-4">
                                        <select class="form-control global-filter"  name="global_id_bsc" id="global_id_bsc" tipe="2" placeholder="ALL">
                                            <option value=""></option>
                                            <?php foreach($bsc as $row){ ?>
                                                <option <?=($row->id==@$id_bsc?'selected':'')?>  value="<?=$row->id;?>"><?=$row->name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2"><b>Perspective:</b></label>
                                    <div class="col-md-5">
                                        <input value="<?=@$id_perspective;?>" name="global_id_perspective" id="global_id_perspective" tipe="2" class="form-control global-filter" placeholder="ALL"  type="text" />
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label class="control-label col-md-1"><b>SO&nbsp;Title:</b></label>
                                    <div class="col-md-11">
                                        <input value="<?=@$id_so;?>" name="global_id_so" id="global_id_so" tipe="2" class="form-control global-filter" placeholder="ALL"  type="text" />
                                        <input value="<?=@$id_kpi_so;?>" name="global_id_kpi_so" id="global_id_kpi_so" tipe="2" class="form-control global-filter"  type="hidden" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin-top:0px;">

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
                    </div>

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_monev_kpi_so">
                        <thead>
                            <tr role="row" class="heading">
                                <th style="background-color:rgb(52 215 255) !important;color:white;" width="40px">No</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">ID</th>
                                
                                <th style="background-color:rgb(52 215 255) !important;color:white;">SO Number</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">SO Title</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Number</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">KPI-SO</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">PIC KPI-SO</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Polarisasi</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Ukuran</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Frekuensi Pengukuran</th>

                                
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Perspective</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">BSC</th>

                                <th style="background-color:rgb(52 215 255) !important;color:white;">Description</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Is Deleted</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Created Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Created By</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Updated Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Updated By</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;" width="130px">Action</th>

                            </tr>
                            <tr role="row" class="filter display-hide">
                                <td></td>
                                <td><input name="id" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="code_so" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="id_so" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="code" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="name_kpi_so" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="pic_kpi_so" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="name_polarisasi" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="ukuran" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="frekuensi_pengukuran" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                              
                                <td><input readonly="readonly" name="id_perspective" tipe="2" a1="text-center"  a2="false" class="form-control form-filter select-filter input-md" placeholder="search" type="text" ></td>
                                <td><input readonly="readonly" name="id_bsc" tipe="2" a1="text-center"  a2="false" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                
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
<div id="popup_add" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static" style="margin-top: -1% !important;">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>KPI-SO Progress</b></h3>
      </div>
      <div class="modal-body" id="load_add"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal add-->
<div id="popup_edit_month" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Edit month</b></h3>
      </div>
      <div class="modal-body" id="load_edit_month"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal keterangan approval-->
<div id="popup_keterangan_approval" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Keterangan Approval</b></h3>
      </div>
      <div class="modal-body" id="load_keterangan_approval">
        <textarea id="keterangan_approval" class="form-control" rows="5"></textarea>
        <br>
        <div style="text-align: center;">
            <button id="btn_approval_approve"month="" title="" val="3" class="btn btn-sm btn-primary btn_change_status_approval" style="background:darkblue;color:white;"> Approve</button>
            <button id="btn_approval_reject" month="" title="" val="4" class="btn btn-sm btn_change_status_approval" style="cursor:pointer;color:#fff;background-color:red;"> Reject</button>
        </div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
$(document).ready(function() {
    
    //================================= Datatable ==========================================
        //variabel
        var table_name  = "table_monev_kpi_so";
        var url         = "<?=site_url($url);?>/table_monev_kpi_so";
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
        table_monev_kpi_so.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_monev_kpi_so .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //select2 biasa
        $(".table_monev_kpi_so .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });

        //select2 biasa
        $(".table_monev_kpi_so #global_year").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $(".table_monev_kpi_so #global_id_bsc").change();
        });

        //select2 biasa
        $(".table_monev_kpi_so #global_month").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $(".table_monev_kpi_so #global_id_bsc").change();
        });

        //select2 biasa
        $(".table_monev_kpi_so #global_id_bsc").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_monev_kpi_so #global_id_perspective').val('');
            $(".table_monev_kpi_so #global_id_perspective").change();
        });


        //select perspective
        window.table_monev_kpi_so_global_id_perspective = function(){
            $(".table_monev_kpi_so input[name='global_id_perspective']").select2({
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
                $('.table_monev_kpi_so #global_id_so').val('');
                $(".table_monev_kpi_so #global_id_so").change();
            });
        }
        window.table_monev_kpi_so_global_id_perspective();

         //select so
         window.table_monev_kpi_so_global_id_so = function(){
            $(".table_monev_kpi_so input[name='global_id_so']").select2({
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
                $('.table_monev_kpi_so #global_id_kpi_so').val('');
                $('.table_monev_kpi_so .filter-submit').first().click();
            });
        }
        window.table_monev_kpi_so_global_id_so();
    //====================================================================================


    //=============================== Action =========================================

        //load add
        $('.table_monev_kpi_so').on('click', '.btn_add_progress', function(e) {
            $('#popup_add').modal();
            var id = $(this).attr('id');
            var year = $('#global_year').val();
            var month = $('#global_month').val();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_add";
            var param = {id:id, year:year, month:month};
            Metronic.blockUI({ target: '#load_add',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_add').html(msg);
                Metronic.unblockUI('#load_add');
            });
        });

        //change year
        window.change_year_table = function (){
            var periode  =  $(".table_monev_kpi_so select[name='global_id_periode'] option[selected='selected']").text();
            var pecah = periode.split(" - ");
            var start_year = pecah[0];
            $(".table_monev_kpi_so #global_start_year").val(start_year);
            for(i=0;i<5;i++){
                var year = parseFloat(start_year)+i;
                var a = $(".table_monev_kpi_so .col_target_"+i).text()+' '+year;
                $(".table_monev_kpi_so .col_target_"+i).text(a);
            }
        }
        window.change_year_table();
    //====================================================================================




    //popup export 
    $('.btn_export').die().live('click',function(){

        var tipe = $(this).attr('tipe');
        $('#tipe_export').val(tipe);

        var arr = {};
        $('.table_monev_kpi_so .form-filter').each(function(index, el) {
            var name = $(el).attr('name');
            var val = $(el).val();
            if($(this).val() != ''){
                if(val != null){ arr[name] = val; }
            }
        });
        $('.table_monev_kpi_so .global-filter').each(function(index, el) {
            var name = $(el).attr('name');
            var val = $(el).val();
            if($(this).val() != ''){
                if(val != null){ arr[name] = val; }
            }
        });
        var input = JSON.stringify(arr);
        $('#input_form_export').val(input);

        $('#btn_submit_export').click();    
       
    });


    //export excel
    $('#btn_submit_export').die().live('click',function(){
        //cek tipe
        var tipe = $('#tipe_export').val();
        if(tipe == 'pdf'){
            var action = "<?=site_url($url);?>/export_pdf";
        }else{
            var action = "<?=site_url($url);?>/export_excel";
        }
        //export excelnya
        $('#form_export').attr('action',action);
        $('#form_export').submit();
        $('#form_export').attr('action','javascript:;');
        toastr.options = call_toastr('3000');
        var $toast = toastr['success']("Success For Export", "Success");
            
    });



    //btn download 
    $('#btn_download').on('click',function(){
        var arr = {};
        $('.table_monev_kpi_so .global-filter').each(function(index, el) {
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
                      $('.table_monev_kpi_so .filter-submit').first().click();

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