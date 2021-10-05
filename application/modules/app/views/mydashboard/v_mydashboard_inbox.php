<div class="row table_inbox">
    <div class="col-md-12">

        <!-- <div class="" style="border:1px solid lightgrey; border-radius:10px !important;padding:10px;"> -->
        <div class="" style="border:0px solid lightgrey; border-radius:0px !important;padding:0px;">
            <style>
                .btn_search_status{ cursor:pointer; }
            </style>
            <div style="text-align:center;">
                <span class="label label-warning btn_search_status" val="18">
                    New 
                </span>
                <span class="badge badge-danger tot_inbox_new" style="margin-top:-2em;margin-left:-0.5em;">0</span>
                &nbsp;&nbsp;
                <span class="label label-info btn_search_status" val="19">
                    Reviewed
                </span>
                <span class="badge badge-danger tot_inbox_review" style="margin-top:-2em;margin-left:-0.5em;">0</span>
                &nbsp;&nbsp;
                <span class="label label-success btn_search_status" val="20">
                    Done
                </span>
                <span class="badge badge-danger tot_inbox_done" style="margin-top:-2em;margin-left:-0.5em;">0</span>
                &nbsp;&nbsp;
                <span class="label label-primary btn_search_status" val="">
                    All Status 
                </span>
                <span class="badge badge-danger tot_inbox_all" style="margin-top:-2em;margin-left:-0.5em;">0</span>
                &nbsp;&nbsp;
            </div>
            
            <h4 class="caption-subject font-green-sharp bold" style="text-align:left; margin-top:-1em;"> 
                <i class="fa fa-table"></i> &nbsp; 
                Inbox / Assignment
            </h4>

            <input type="hidden" value="" name="global_review_status" id="global_review_status" tipe="2" class="form-control global-filter" />

                
            <style type="text/css">
                .table-wrap thead tr th, .table-wrap thead tr td, .table-wrap tbody tr td { white-space: nowrap;} 
                .dataTables_scrollHeadInner{ padding-left:0px !important;}
                .DTFC_RightHeadBlocker { background: #eee; border: 1px silid #eee;}
                .DTFC_RightBodyLiner { overflow-y: none;}
                .filter td{ padding: 0px !important; white-space: nowrap;}
            </style>
        
            <div class="table-container">

                <div class="table-actions-wrapper">
                    <div style="float:right; padding-left:10px;">
                        <div class="showhide_column" style="display:none;"></div>
                    </div>
                    <div style="float:right; padding-left:10px;margin-top:-2em;">
                        <a href="javascript:" class="btn btn-sm yellow-crusta btn_show_filter"  data-placement="top" data-container="body"><i class="fa fa-search"></i> Search</a>
                    </div>
                </div>

                <table class="table table-striped table-bordered table-hover table-wrap" id="table_inbox">
                    <thead>
                        <tr role="row" class="heading">
                            <th style="background-color:#006A96 !important;color:white;" width="40px">No</th>
                            <th style="background-color:#006A96 !important;color:white;">ID</th>
                            <th style="background-color:#006A96 !important;color:white;">Element</th>
                            <th style="background-color:#006A96 !important;color:white;text-align:center !important;;">Description</th>
                            <th style="background-color:#006A96 !important;color:white;">Request Date</th>
                            <th style="background-color:#006A96 !important;color:white;">Requested By</th>
                            <th style="background-color:#006A96 !important;color:white;">Status</th>
                            <th style="background-color:#006A96 !important;color:white;">Reviewed Date</th>
                            <?php  if(in_array(h_session('ROLE_ID'), h_role_admin())){ ?>
                                <th style="background-color:#006A96 !important;color:white;">Requested To</th>
                            <?php } ?>
                            <th style="background-color:#006A96 !important;color:white;">Action</th>
                        </tr>
                        <tr role="row" class="filter display-hide">
                            <td></td>
                            <td><input name="id" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                            <td><input name="element" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                            <td><input name="description" tipe="1" a1="text-left" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                            <td>
                                <input type="text" a1="text-center" class="form-control form-filter input-md tanggal" name="request_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                <input type="text" a1="text-center" class="form-control form-filter input-md tanggal" name="request_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                            </td>
                            <td><input type="text" a1="text-center" class="form-control form-filter input-md"  name="name_request_by" tipe="1" placeholder="Search"></td>
                            <td><input readonly="readonly" type="text" a1="text-center" class="form-control form-filter input-md"  name="name_review_status" tipe="1" placeholder="Search">
                            </td>
                            <td>
                                <input type="text" a1="text-center" class="form-control form-filter input-md tanggal" name="review_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                <input type="text" a1="text-center" class="form-control form-filter input-md tanggal" name="review_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                            </td>
                            <?php  if(in_array(h_session('ROLE_ID'), h_role_admin())){ ?>
                                <td><input type="text" a1="text-center" class="form-control form-filter input-md"  name="name_request_to" tipe="1" placeholder="Search"></td>
                            <?php } ?>
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



<!-- modal keterangan approval-->
<div id="popup_keterangan_approval" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Keterangan Approval</b></h3>
      </div>
      <div class="modal-body" id="load_keterangan_approval">
        <textarea id="keterangan_approval" class="form-control" rows="5" disabled="disabled"></textarea>
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
        var table_name  = "table_inbox";
        var url         = "<?=site_url($url);?>/table_inbox";
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
        table_inbox.initDefault(url, header, order, sirt);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_inbox .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //select2 biasa
        $(".table_inbox .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });

        //select2 global_year
        $(".table_inbox #global_year").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        }).on('change', function(event) { 
            var tipe = $('#tipe').val();
            if(tipe == 'table_inbox'){
                $('.table_inbox .filter-submit').first().click();
            }else{
                $('.btn_load_initiative_mapping').click();
            }
        });

        //select2 global_month
        $(".table_inbox #global_month").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_inbox .filter-submit').first().click();
        });

        //select2 global_id_bsc
        $(".table_inbox #global_id_bsc").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            var tipe = $('#tipe').val();
            if(tipe == 'table_inbox'){
                $('.table_inbox .filter-submit').first().click();
            }else{
                $('.btn_load_initiative_mapping').click();
            }
        });

    //====================================================================================


    //=============================== Action =========================================

        //btn done
        $('.table_inbox').on('click', '.btn_done', function(e) {
            var id = $(this).attr('id');
            var val = $(this).attr('val');
            var mes = "Are you sure to Done Data?";
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
                    var url    = '<?=site_url($url)?>/change_status';
                    var param  = {id:id, val:val, token:token};
                    $.post(url, param, function(msg){
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_inbox();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    }, 'json');
            });
        });

        //btn done
        $('.table_inbox').on('click', '.btn_review', function(e) {
            var id = $(this).attr('id');
            var val = $(this).attr('val');
            var token  = $('#ex_csrf_token').val();
            var url    = '<?=site_url($url)?>/change_status';
            var param  = {id:id, val:val, token:token};
            $.post(url, param, function(msg){
                toastr.options = call_toastr('4000');
                if(msg.status == '1'){
                    window.reload_table_inbox();
                    toastr['success'](msg.message, "Success");
                }else{
                    toastr['error'](msg.message, "Error");
                }
            }, 'json');
        });


        //btn done
        $('.table_inbox').on('click', '.btn_search_status', function(e) {
            var val = $(this).attr('val');
            $('#global_review_status').val(val);
            window.reload_table_inbox();
        });

        //btn done
        $('.table_inbox').on('click', '.btn_keterangan_approval', function(e) {
            var keterangan = $(this).attr('keterangan');
            $('#keterangan_approval').val(keterangan);
            $('#popup_keterangan_approval').modal();
        });


});
</script>