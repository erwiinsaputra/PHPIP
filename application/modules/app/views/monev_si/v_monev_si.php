<div class="row table_monev_si">
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
                        <a href="javascript:" id="btn_download" class="btn btn-danger btn-sm"  data-placement="top" data-container="body"><i class="fa fa-download"></i> Download</a>
                        <?php if(in_array(h_session('ROLE_ID'), h_role_admin())){ ?>
                            <a href="javascript:" id="btn_upload" class="btn btn-primary btn-sm"  data-placement="top" data-container="body"><i class="fa fa-upload"></i> Upload</a>
                        <?php } ?>
                        <a class="btn btn-primary btn-circle btn-sm " href="<?=site_url($url)?>" data-original-title="Refresh" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a> <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
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
                                        <select class="form-control global-filter"  name="global_id_bsc" id="global_id_bsc" tipe="2" placeholder="ALL">
                                            <option value=""></option>
                                            <?php foreach($bsc as $row){ ?>
                                                <option <?=($row->id == '1' ? 'selected="selected"' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1"><b>Year&nbsp;:</b></label>
                                    <div class="col-md-4">
                                        <select class="form-control global-filter"  tipe="2" name="global_year" id="global_year" placeholder="ALL">
                                            <option value=""></option>
                                            <?php for($y=$start_year; $y <= $end_year; $y++){ ?>
                                                <option <?=($y == @$year ? 'selected' : '')?> value="<?=$y?>"><?=$y?></option>
                                            <?php } ?>
                                        </select>
                                        <input value="<?=@$month?>" type="hidden" name="global_month" id="global_month" class="form-control global-filter">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-1"><b>SI&nbsp;Title:</b></label>
                                    <div class="col-md-9">
                                        <input value="<?=@$id_si;?>" name="global_id_si" id="global_id_si" tipe="2" class="form-control global-filter" placeholder="ALL"  type="text" />
                                    </div>
                                    <div class="col-md-2">
                                        <a href="javascript:" id="btn_detail_si" class="btn btn-warning btn-sm"><i class="fa fa-list"></i> Detail SI</a>
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

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_monev_si">
                        <thead>
                            <tr role="row" class="heading">
                            <th style="background-color:rgb(0 186 230) !important;color:white;" width="40px">No</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">ID</th>
            
                                <th style="background-color:rgb(0 186 230) !important;color:white;">SI Number</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">SI Title</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">PIC</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">BSC</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Start Date</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">End Date</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Status</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Background & Goal</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Objective & key Result</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Cek Objective & key Result</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Is Active</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Created Date</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Created By</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Updated Date</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Updated By</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;" width="150px">Action</th>
                            </tr>
                            <tr role="row" class="filter display-hide">
                                <td></td>
                                <td><input name="id" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="code" tipe="2" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="name" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="name_pic_si" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input readonly="readonly" name="id_bsc" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="start_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="start_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="end_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="end_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td><select name="status_si" tipe="2" a1="text-center" class="form-control form-filter select-filter input-md select2_biasa"   placeholder="Search" >
                                        <option value=""></option>
                                        <?php foreach($status_si as $row){ ?>
                                            <option value="<?=$row->id;?>"><?=str_replace(' ','&nbsp;',$row->name);?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input name="background_goal" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="objective_key_result" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="cek_objective_key_result" tipe="1" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><select value="t" name="is_active" tipe="2" a1="text-center" a2="false"  class="form-control form-filter select-filter input-md select2_biasa"   placeholder="Search" >
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



<!-- download excel -->
<div id="popup_download" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Download Data</b></h3>
      </div>
      <div class="modal-body">

            <!-- form download -->
            <form id='form_download' name='form_download' action="javascript:;" target="_blank" method="post">
               
                <div style="text-align:center;">
                    <!-- <div><b>Select Periode</b></div>
                    <div style="text-align:center;margin-top:1em;margin-bottom:3em;">
                        <select name="periode" class="form-control input-md select2_biasa" style="margin-left:10%;margin-right:10%;width:80%;">
                            <?php foreach($periode as $row){ ?>
                                <?php 
                                    $periode_year = $row->start_year.' - '.$row->end_year;
                                    if( strpos( $periode_year, date('Y') ) !== false ) {
                                        $selected = "selected='selected'";
                                    }
                                ?>
                                <option <?=@$selected;?> value="<?=$row->id;?>"><?=$periode_year?></option>
                            <?php } ?>
                        </select>
                    </div> -->
                    <div style="text-align:center;"><b>Select Year</b></div>
                    <div style="text-align:center;margin-top:1em;margin-bottom:3em;">
                        <select name="year" class="form-control input-md select2_biasa" placeholder="ALL Year" style="margin-left:10%;margin-right:10%;width:80%;" >
                            <option value=""></option>
                            <?php for($y=$start_year; $y <= $end_year; $y++){ ?>
                                <option <?=(date('Y') == $y ? 'selected' : '')?> value="<?=$y?>"><?=$y?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div style="text-align:center;">
                        <button type="button" class="btn btn-danger" id="btn_download_data"><i class="fa fa-download"></i> Download Data</button>
                    </div>
                    <div style="display:none;">
                        <input type="hidden" name="input_form" id="input_form"/>
                        <button type="button" class="btn btn-primary" id="btn_submit_download">Download</button>
                    </div>
                </div>

            </form>
            
      </div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- upload excel -->
<div id="popup_upload" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Upload Data Excel</b></h3>
      </div>
      <div class="modal-body">

        <div style="text-align:center;">
            <form id="form_upload" action="javascript:;" method="post" enctype="multipart/form-data">
                <div><b>Select Year:</b></div>
                <div style="text-align:center;margin-top:1em;margin-bottom:2em;">
                <select name="year" id="year_upload" class="form-control input-md select2_biasa" placeholder="ALL Year" style="margin-left:10%;margin-right:10%;width:80%;">
                    <option value=""></option>
                    <?php for($y=$start_year; $y <= $end_year; $y++){ ?>
                        <option <?=(date('Y') == $y ? 'selected' : '')?> value="<?=$y?>"><?=$y?></option>
                    <?php } ?>
                </select>
                </div>
                <div><b>File Upload:</b></div>
                <div style="margin-left:30%;margin-right:30%;width:40%;margin-top:1em;">
                    <input type="file" name="file" id="file_upload" />
                </div>
                <hr style="margin-top:2em;">
                <div style="margin-top:1em;">
                    <button type="button" class="btn btn-primary" id="btn_upload_data"><i class="fa fa-upload"></i>  Upload</button> 
                </div>
            </form>
        </div>
        
      </div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal add-->
<div id="popup_add" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static" style="margin-top: -1% !important;">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>SI Monitoring & EVALUATION</b></h3>
      </div>
      <div class="modal-body">
            <div class="panel-group accordion" id="accordion3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title" style="font-size:2em;text-align:center;">
                            <a id="title_detail_si" style="font-size:0.6em !important;" class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#tab_detail_si">
                                SI Title
                            </a>
                        </h4>
                    </div>
                    <br><br>
                    <div id="tab_detail_si" class="panel-collapse collapse">
                        <div id="load_detail_si"></div>
                    </div>
                </div>
            </div>
            <div id="load_add"></div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal Issue -->
<div id="popup_issue" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Issue</b></h3>
      </div>
      <div class="modal-body" id="load_issue"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- modal Add Issue -->
<div id="popup_add_issue" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Add Issue</b></h3>
      </div>
      <div class="modal-body" id="load_add_issue"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal edit Issue -->
<div id="popup_edit_issue" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Edit Issue</b></h3>
      </div>
      <div class="modal-body" id="load_edit_issue"></div>
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
            <button id="btn_approval_approve" month="" title="" val="3" class="btn btn-sm btn-primary btn_change_status_approval" style="background:darkblue;color:white;"> Approve</button>
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
        var table_name  = "table_monev_si";
        var url         = "<?=site_url($url);?>/table_monev_si";
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
        table_monev_si.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_monev_si .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //select2 biasa
        $(".table_monev_si .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });

        //select2 biasa
        $(".table_monev_si #global_year").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $(".table_monev_si #global_id_bsc").change();
        });

        //select2 biasa
        $(".table_monev_si #global_id_bsc").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_monev_si #global_id_perspective').val('');
            $(".table_monev_si #global_id_perspective").change();
        });

        //select si
        window.table_monev_si_global_id_si = function(){
            $(".table_monev_si input[name='global_id_si']").select2({
                minimumInputLength: -1,
                dropdownAutoWidth : true,
                allowClear:true,
                ajax: {
                url: "<?php echo site_url($url.'/select_si')?>",
                dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
                data: function (term, page) {
                    var id_bsc = $("#global_id_bsc").val();
                    return { q: term, id_bsc:id_bsc};
                },
                results: function (data, page) {  return { results: data.item }; },
                },
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                    $.ajax({
                        url:"<?php echo site_url($url.'/select_si')?>",
                        dataType: "json", type:"POST", 
                        data:{ id: id}
                    }).done(function(res) { callback(res[0]); });
                    }
                },
                formatResult: function(item){ return item.name },
                formatSelection: function(item){ return item.name;  }
            }).on('change', function(event) { 
                $('.table_monev_si #global_year').val('');
                $('.table_monev_si #global_month').val('');
                $('.table_monev_si .filter-submit').first().click();
            });
        }
        window.table_monev_si_global_id_si();
    //====================================================================================


    //=============================== Action =========================================

        //load add
        $('.table_monev_si').on('click', '.btn_add_progress', function(e) {
            $('#popup_add').modal();

            //load monitoring
            var tipe = 'monitoring';
            var id_si = $(this).attr('id');
            var id_bsc = $(".table_monev_si #global_id_bsc").val();
            var year = $(".table_monev_si #global_year").val();
            var month = $(".table_monev_si #global_month").val();
            var url = "<?=site_url($url);?>/load_add";
            var param = {tipe:tipe, id_si:id_si, id_bsc:id_bsc, year:year, month:month};
            Metronic.blockUI({ target: '#load_add',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_add').html(msg);
                Metronic.unblockUI('#load_add');
            });

            //load detail si
            var title = $(this).attr('title_popup');
            $('#popup_add').find('#title_detail_si').html(title);
            var url = "<?=site_url('app/ic');?>/load_detail_si";
            var param = {id:id_si};
            Metronic.blockUI({ target: '#load_detail_si',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_detail_si').html(msg);
                Metronic.unblockUI('#load_detail_si');
            });

        });

        //change year
        window.change_year_table = function (){
            var periode  =  $(".table_monev_si select[name='global_id_periode'] option[selected='selected']").text();
            var pecah = periode.split(" - ");
            var start_year = pecah[0];
            $(".table_monev_si #global_start_year").val(start_year);
            for(i=0;i<5;i++){
                var year = parseFloat(start_year)+i;
                var a = $(".table_monev_si .col_target_"+i).text()+' '+year;
                $(".table_monev_si .col_target_"+i).text(a);
            }
        }
        window.change_year_table();
    //====================================================================================




   //====================================================================================

    //select2 biasa
    $("#popup_download .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    });
    //select2 biasa
    $("#popup_upload .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
    });

    //btn download 
    $('#btn_download').on('click',function(){
        $('#popup_download').modal();
    });

    //btn download data
    $('#btn_download_data').on('click',function(){
        var arr = {};
        $('.table_monev_si .global-filter').each(function(index, el) {
            var name = $(el).attr('name');
            var val = $(el).val();
            if($(this).val() != ''){
                if(val != null){ arr[name] = val; }
            }
        });
        var input = JSON.stringify(arr);
        $('#form_download').find('#input_form').val(input);
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
        $('#popup_upload').modal();
    });
    
    //btn upload data
    $('#btn_upload_data').on('click',function(){
        if ($('#file_upload').get(0).files.length === 0) {
            alert('File belum dipilih'); return true;
        }else{
            $('#form_upload').submit();
        }
    });

    //submit upload excel
    $('#form_upload').on('submit', function(e) {
        
        e.preventDefault();
        var param = new FormData(this);
        var id_bsc = $(".table_monev_si #global_id_bsc").val();
        var year = $("#form_upload #year_upload").val();
        param.append('id_bsc', id_bsc);
        param.append('year', year);

        //konfirmasi
        var title = "Upload Data!";
        var mes = "Mohon menunggu ! \n Sampai Upload data selesai, \n\n Waktu Proses upload, \n Tergantung dari banyak data yang diupload.";
        swal({
                title: title,
                text: mes,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Upload',
                closeOnConfirm: true
        },
        function(){

              Metronic.blockUI({ target:'#form_upload',  animate: true});     
              $.ajax({
                  url: "<?=site_url($url);?>/upload_excel",
                  type: "POST",
                  data: param,
                  contentType: false, cache: false, processData: false,
                  dataType: 'json',
                  success: function(msg){

                    //alert
                    toastr.options = call_toastr('3000');
                    //result
                    if(msg.status == 1){ 
                        //refresh table
                        window.reload_table_monev_si();
                        //hide popup
                        $('#popup_upload').modal('hide');
                        var $toast = toastr['success'](msg.message, "Success");
                    }else{
                        var $toast = toastr['error'](msg.message, "Success");
                    }
                    
                    //kosongkan file
                    $("#file_upload").val('');

                    Metronic.unblockUI('#form_upload');

                  },
                  error : function(msg){
                      Metronic.unblockUI('#form_upload');
                  }
              });
        });
        
        //kosongkan file
        $("#file_upload").val('');
        
    });

});
</script>