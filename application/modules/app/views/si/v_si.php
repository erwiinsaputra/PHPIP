<div class="row table_si">
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
                        <a style="background-color:rgb(119 202 220) !important;color:white;" href="javascript:"  tipe="table_si" class="btn btn-sm btn-danger btn_load_table_si"><i class="fa fa-table"></i> Table SI</a>
                        <a style="background-color:rgb(0 138 172) !important;color:white;" href="javascript:" tipe="initiative_mapping" class="btn btn-sm btn-primary btn_load_initiative_mapping"><i class="fa fa-bar-chart-o"></i> Initiative Mapping</a>
                        <a class="btn btn-primary btn-circle btn-sm " href="<?=site_url($url)?>" data-original-title="Refresh" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a> <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
                        <input type="hidden" id="tipe" value="table_si">
                    </div>
                </div>
            </div>


            <div class="portlet-body ">
                
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
                                                <option value="<?=$y?>"><?=$y?></option>
                                            <?php } ?>
                                        </select>
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
                    .DTFC_RightHeadBlocker { background: #eee; border: 1px silid #eee;}
                    .DTFC_RightBodyLiner { overflow-y: none;}
                    .filter td{ padding: 0px !important; white-space: nowrap;}
                </style>
                
                <div class="table-container load_data">

                    <div class="table-actions-wrapper">
                        <div style="float:right; padding-left:10px;">
                            <div class="showhide_column"></div>
                        </div>
                        <div style="float:right; padding-left:10px;">
                            <a href="javascript:" class="btn btn-sm yellow-crusta btn_show_filter"  data-placement="top" data-container="body"><i class="fa fa-search"></i> Search</a>
                        </div>
                            
                        <?php if(in_array( h_session('ROLE_ID'), h_role_admin())){ ?>
                            <div style="float:left; padding-left:10px;">
                                <a href="javascript:" class="btn btn-sm btn-primary btn_add" data-placement="top" data-container="body"><i class="fa fa-plus"></i> Add SI</a>
                            </div>
                        <?php } ?>
                        
                    </div>

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_si">
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
                                <td><input name="name" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
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


<!-- modal add-->
<div id="popup_add" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>ADD SI</b></h3>
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
        <h3 class="modal-title" style="text-align:center;"><b>EDIT <?=$title;?></b></h3>
      </div>
      <div class="modal-body" id="load_edit"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal view-->
<div id="popup_view" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>View <?=$title;?></b></h3>
      </div>
      <div class="modal-body" id="load_view"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal copy-->
<div id="popup_copy" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>COPY <?=$title;?></b></h3>
      </div>
      <div class="modal-body" id="load_copy"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal upload-->
<div id="popup_upload_si" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Upload File</b></h3>
      </div>
      <div class="modal-body" id="load_upload_si"></div>
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
        var table_name  = "table_si";
        var url         = "<?=site_url($url);?>/table_si";
        var sirt        = [-1,0];
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
        table_si.initDefault(url, header, order, sirt);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_si .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //select2 biasa
        $(".table_si .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });

        //select2 global_year
        $(".table_si #global_year").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        }).on('change', function(event) { 
            var tipe = $('#tipe').val();
            if(tipe == 'table_si'){
                $('.table_si .filter-submit').first().click();
            }else{
                $('.btn_load_initiative_mapping').click();
            }
        });

        //select2 global_month
        $(".table_si #global_month").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_si .filter-submit').first().click();
        });

        //select2 global_id_bsc
        $(".table_si #global_id_bsc").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            var tipe = $('#tipe').val();
            if(tipe == 'table_si'){
                $('.table_si .filter-submit').first().click();
            }else{
                $('.btn_load_initiative_mapping').click();
            }
        });

    //====================================================================================


    //=============================== Action =========================================

        //load add
        $('.table_si').on('click', '.btn_add', function(e) {
            $('#popup_add').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_add";
            var param = {id:id};
            $('#load_add').html('');
            $('#load_edit').html('');
            Metronic.blockUI({ target: '#load_add',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_add').html(msg);
                Metronic.unblockUI('#load_add');
            });
        });

        //load edit
        $('.table_si').on('click', '.btn_edit', function(e) {
            $('#popup_edit').modal();
            var id = $(this).attr('id');
            var type = 'edit';
            var url = "<?=site_url($url);?>/load_edit";
            var param = {id:id, type:type};
            $('#load_edit').html('');
            $('#load_view').html('');
            Metronic.blockUI({ target: '#load_edit',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_edit').html(msg);
                Metronic.unblockUI('#load_edit');
            });
        });

        //load view
        $('.table_si').on('click', '.btn_view', function(e) {
            $('#popup_view').modal();
            var id = $(this).attr('id');
            var type = 'view';
            var url = "<?=site_url($url);?>/load_edit";
            var param = {id:id, type:type};
            $('#load_edit').html('');
            $('#load_view').html('');
            Metronic.blockUI({ target: '#load_view',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_view').html(msg);
                Metronic.unblockUI('#load_view');
            });
        });

        //load copy
        $('.table_si').on('click', '.btn_copy', function(e) {
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

        //load initiative maping
        $('.btn_load_initiative_mapping').on('click',function(){
            var tipe  = $(this).attr('tipe');
            $('#tipe').val(tipe);
            $('.load_data').html('');
            var url = '<?=site_url($url)?>/load_initiative_mapping';
            var id_bsc  = $('.table_si #global_id_bsc').val();
            var year    = $('.table_si #global_year').val();
            var param   = {year:year, id_bsc:id_bsc};
            Metronic.blockUI({ target: '.portlet',  boxed: true});
            $.post(url, param, function(msg){
                $('.load_data').html(msg);
                Metronic.unblockUI('.portlet');
            });
        });
        // $('.btn_load_initiative_mapping').click();

        //load table si
        $('.btn_load_table_si').on('click',function(){
            window.location.href = '<?=site_url($url)?>';
        });

        //btn delete
        $('.table_si').on('click', '.btn_delete', function(e) {
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
                            window.reload_table_si();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    }, 'json');
            });
        });

});
</script>