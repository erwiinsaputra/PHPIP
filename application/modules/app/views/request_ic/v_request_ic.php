<div class="row table_request_ic">
    <div class="col-md-12">
        <div class="portlet light ">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-table font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Table &nbsp;<?=$title;?></span>
                    <input type="hidden" id="ex_csrf_token" value="<?=csrf_get_token();?>">
                </div>
                <div class="actions">

                    <div style="float:right;" >
                        <a class="btn btn-primary btn-circle btn-sm " href="<?=site_url($url)?>" data-original-title="Refresh" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a> <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
                    </div> 

                    <div style="float:left;">
                        <div style="float:left;font-size:1.5em;margin-top:0.2em;"><span><b>BSC :</b> </span>&nbsp;&nbsp;</div>
                        <div style="float:left;">
                                <select class="form-control global-filter" name="global_id_bsc" id="global_id_bsc" tipe="2" placeholder="ALL" style="width:20em !important;">
                                    <option value=""></option>
                                    <?php foreach($bsc as $row){ ?>
                                        <?php if(@$id_bsc == ''){ $id_bsc='1';} ?>
                                        <option <?=($row->id == @$id_bsc ? 'selected':'')?>  value="<?=$row->id;?>"><?=$row->name;?></option>
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
                                <!-- <div class="form-group">
                                    <label class="control-label col-md-1"><b>BSC&nbsp;:</b></label>
                                    <div class="col-md-9">
                                        <select class="form-control global-filter" name="global_id_bsc" id="global_id_bsc" tipe="2" placeholder="ALL">
                                            <option value=""></option>
                                            <?php foreach($bsc as $row){ ?>
                                                <?php if(@$id_bsc == ''){ $id_bsc='1';} ?>
                                                <option <?=($row->id == @$id_bsc ? 'selected':'')?>  value="<?=$row->id;?>"><?=$row->name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div> -->

                                <div class="form-group">
                                    <label class="control-label col-md-1"><b>SI&nbsp;Title:</b></label>
                                    <div class="col-md-9">
                                        <input value="<?=@$id_si;?>" name="global_id_si" id="global_id_si" tipe="2" class="form-control global-filter" placeholder="ALL"  type="text" />
                                        <input value="<?=@$id;?>" name="global_id" id="global_id" tipe="2" class="form-control global-filter" type="hidden" />
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
                            <?php if(h_session('ROLE_ID') != '5'){ ?>
                                <a href="javascript:" class="btn btn-sm btn-primary btn_add" data-placement="top" data-container="body"><i class="fa fa-plus"></i> Add Request</a>
                            <?php } ?>
                            <a href="javascript:" class="btn btn-sm yellow-crusta btn_show_filter"  data-placement="top" data-container="body"><i class="fa fa-search"></i> Search</a>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_request_ic">
                        <thead>
                            <tr role="row" class="heading">
                                <th style="background-color:rgb(52 215 255) !important;color:white;" width="40px">No</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">ID</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">BSC</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">SI</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">PIC</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Request Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Request By</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Keterangan</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">File</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Status</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Keterangan Approval</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Request Assist Admin</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Finished</th>

                                <th style="background-color:rgb(52 215 255) !important;color:white;">Approve Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Finished Date</th>

                                <th style="background-color:rgb(52 215 255) !important;color:white;">Is Active</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Created Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Created By</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Updated Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Updated By</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;" width="150px">Action</th>

                            </tr>
                            <tr role="row" class="filter display-hide">
                                <td></td>
                                <td><input name="id" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input readonly="readonly" name="id_bsc" tipe="2" a1="text-center"  a2="false" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input readonly="readonly" name="id_si" tipe="2" a1="text-center"  class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input readonly="readonly" name="id_pic" tipe="2" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td>
                                    <input type="text" a1="text-center" class="form-control form-filter input-md tanggal" name="request_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" class="form-control form-filter input-md tanggal" name="request_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td><input name="name_request_by" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="keterangan" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input readonly="readonly"  name="file_request" tipe="1" a2="false" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><select name="status_request" tipe="2" value="t" a1="text-center" class="form-control form-filter select-filter input-md select2_biasa"  placeholder="Search" >
                                        <option value=""></option>
                                        <?php foreach($status_request as $row){ ?>
                                            <option value="<?=$row->id;?>"><?=str_replace(' ','&nbsp;',$row->name);?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input name="keterangan_approval" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><select name="status_send_to_admin" tipe="2" value="t" a1="text-center" class="form-control form-filter select-filter input-md select2_biasa"  placeholder="Search" >
                                        <option value=""></option>
                                        <option value="1">No</option>
                                        <option value="1">Send</option>
                                        <option value="3">Done</option>
                                    </select>
                                </td>
                                <td><select name="status_finished" tipe="2" value="t" a1="text-center" class="form-control form-filter select-filter input-md select2_biasa"  placeholder="Search" >
                                        <option value=""></option>
                                        <option value="0">No</option>
                                        <option value="1">Done</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="approve_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="approve_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="finished_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="finished_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
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





<!-- modal add-->
<div id="popup_add" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Add Request IC</b></h3>
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
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Edit Request IC</b></h3>
      </div>
      <div class="modal-body" id="load_edit"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- modal detail-->
<div id="popup_detail" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Detail Request IC</b></h3>
      </div>
      <div class="modal-body" id="load_detail"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal approval -->
<div id="popup_approval" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Approval</b></h3>
      </div>
      <div class="modal-body" id="load_approval"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal upload-->
<div id="popup_upload_request_ic" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Upload File</b></h3>
      </div>
      <div class="modal-body" id="load_upload_request_ic"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy btn_close_upload" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal View File Request-->
<div id="popup_view_file_request" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>File Request</b></h3>
      </div>
      <div class="modal-body" id="load_view_file_request"></div>
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
        var table_name  = "table_request_ic";
        var url         = "<?=site_url($url);?>/table_request_ic";
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
        table_request_ic.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_request_ic .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

         //select2 biasa
         $("#popup_download .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });

        //select2 biasa
        $(".table_request_ic .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });


        //select2 biasa
        $(".table_request_ic #global_id_bsc").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_request_ic #global_id_perspective').val('');
            $(".table_request_ic #global_id_perspective").change();
        });


         //select si
         window.table_request_ic_global_id_si = function(){
            $(".table_request_ic input[name='global_id_si']").select2({
                minimumInputLength: -1,
                dropdownAutoWidth : true,
                allowClear:true,
                ajax: {
                url: "<?php echo site_url('app/ic/select_si')?>",
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
                        url:"<?php echo site_url('app/ic/select_si')?>",
                        dataType: "json", type:"POST",
                        data:{ id: id}
                    }).done(function(res) { callback(res[0]); });
                    }
                },
                formatResult: function(item){ return item.name },
                formatSelection: function(item){ return item.name;  }
            }).on('change', function(event) {
                $('.table_request_ic #global_id').val('');
                window.reload_table_request_ic();
                // $('.table_request_ic .filter-submit').first().click();
            });
        }
        window.table_request_ic_global_id_si();
    //====================================================================================


    //=============================== Action =========================================


        //btn detail si
        $('.table_request_ic').on('click', '#btn_detail_si', function(e) {
            var id = $("#global_id_si").val();
            if(id == ''){
                alert('SI belum dipilih'); return true;
            }
            $('#popup_detail').modal();
            var url = "<?=site_url($url);?>/load_detail_si";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_detail_si',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_detail_si').html(msg);
                Metronic.unblockUI('#load_detail_si');
            });
        });

        //load add
        $('.table_request_ic').on('click', '.btn_add', function(e) {
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
        $('.table_request_ic').on('click', '.btn_edit', function(e) {
            $('#popup_edit').modal();
            $('#popup_edit').find('.modal-title').html('Edit Request IC');
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

        //load view
        $('.table_request_ic').on('click', '.btn_view', function(e) {
            $('#popup_edit').modal();
            $('#popup_edit').find('.modal-title').html('View Request IC');
            var id = $(this).attr('id');
            var type = 'view';
            var url = "<?=site_url($url);?>/load_view";
            var param = {id:id, type:type};
            Metronic.blockUI({ target: '#load_edit',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_edit').html(msg);
                Metronic.unblockUI('#load_edit');
            });
        });

        //load view, done request assist admin
        $('.table_request_ic').on('click', '.btn_done_request_admin', function(e) {
            $('#popup_edit').modal();
            $('#popup_edit').find('.modal-title').html('Done Request Assist Admin');
            var id = $(this).attr('id');
            var type = 'view';
            var url = "<?=site_url($url);?>/load_done_request_admin";
            var param = {id:id, type:type};
            Metronic.blockUI({ target: '#load_edit',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_edit').html(msg);
                Metronic.unblockUI('#load_edit');
            });
        });


        //load approval
        $('.table_request_ic').on('click', '.btn_approval', function(e) {
            $('#popup_approval').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_approval";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_approval',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_approval').html(msg);
                Metronic.unblockUI('#load_approval');
            });
        });

        //load view file request
        $('.table_request_ic').on('click', '.btn_view_file_request', function(e) {
            $('#popup_view_file_request').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_view_file_request";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_view_file_request',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_view_file_request').html(msg);
                Metronic.unblockUI('#load_view_file_request');
            });
        });

        //btn delete
        $('.table_request_ic').on('click', '.btn_delete', function(e) {
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
                            window.reload_table_request_ic();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    }, 'json');
            });
        });

        //btn send_approval
        $('.table_request_ic').on('click', '.btn_send_approval', function(e) {
            var id = $(this).attr('id');
            var val = $(this).attr('val');
            var id_si = $(this).attr('id_si');
            var mes = "Are you sure to Send Approval ?";
            var title = "Send Approval";
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
                    var url    = '<?=site_url($url)?>/send_approval';
                    var param  = {id:id, id_si:id_si, val:val, token:token};
                    $.post(url, param, function(msg){
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_request_ic();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    }, 'json');
            });
        });

        //btn reject_send
        $('.table_request_ic').on('click', '.btn_reject_send', function(e) {
            var id = $(this).attr('id');
            var id_si = $(this).attr('id_si');
            var val = $(this).attr('val');
            var mes = "Are you sure to Reject Send Approval?";
            var title = "Reject Send";
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
                    var url    = '<?=site_url($url)?>/reject_send';
                    var param  = {id:id, id_si:id_si, val:val, token:token};
                    $.post(url, param, function(msg){
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_request_ic();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    }, 'json');
            });
        });

        //btn send_to_admin
        $('.table_request_ic').on('click', '.btn_send_to_admin', function(e) {
            $('#popup_edit').modal();
            $('#popup_edit').find('.modal-title').html('Request Assist Admin');
            var id = $(this).attr('id');
            var id_si = $(this).attr('id_si');
            var type = 'view';
            var url = "<?=site_url($url);?>/load_send_to_admin";
            var param = {id:id, id_si:id_si, type:type};
            Metronic.blockUI({ target: '#load_edit',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_edit').html(msg);
                Metronic.unblockUI('#load_edit');
            });
        });

        //btn finished
        $('.table_request_ic').on('click', '.btn_finished', function(e) {
            var id = $(this).attr('id');
            var mes = "Are you sure Finished Request?";
            var title = "Finished Request";
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
                    var url    = '<?=site_url($url)?>/finished_request';
                    var param  = {id:id, token:token};
                    $.post(url, param, function(msg){
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_request_ic();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    }, 'json');
            });
        });

        //btn go to master IC
        $('.table_request_ic').on('click', '.btn_go_to_master', function(e) {
            var id = $(this).attr('id');
            window.open('<?=site_url('app/ic/index/')?>'+id,'_blank'); 
        });

    //====================================================================================

});
</script>