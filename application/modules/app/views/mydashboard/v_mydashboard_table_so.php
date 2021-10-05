<style type="text/css">
    .table-wrap thead tr th, .table-wrap thead tr td, .table-wrap tbody tr td { white-space: nowrap;} 
    .dataTables_scrollHeadInner{ padding-left:0px !important;}
    .DTFC_RightHeadBlocker { background: #eee; border: 1px solid #eee;}
    .DTFC_RightBodyLiner { overflow-y: none;}
    .filter td{ padding: 0px !important; white-space: nowrap;}
</style>

<div class="table-container table_so_mydashboard">

    <input type="hidden" class="form-control global-filter" value="<?=@$year?>" tipe="2" name="global_year" id="global_year">
    <input type="hidden" class="form-control global-filter" value="<?=@$month?>" tipe="2" name="global_month" id="global_month">
    <input type="hidden" class="form-control global-filter" value="<?=@$id_bsc?>" tipe="2" name="global_id_bsc" id="global_id_bsc">

    <div class="table-actions-wrapper">
        <div style="float:right; padding-left:10px;">
            <div class="showhide_column"></div>
        </div>
        <div style="float:right; padding-left:10px;">
            <a href="javascript:" class="btn btn-sm yellow-crusta btn_show_filter"  data-placement="top" data-container="body"><i class="fa fa-search"></i> Search</a>
        </div>
    </div>

    <table class="table table-striped table-bordered table-hover table-wrap" id="table_so_mydashboard">
        <thead>
            <tr role="row" class="heading">
                <th style="background-color:rgb(0 186 230) !important;color:white;" width="40px">No</th>

                <th style="background-color:rgb(0 186 230) !important;color:white;">SO Number</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">SO Title</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">Perspective</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">PIC</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">Start Date</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">End Date</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">Description</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">Action</th>
            </tr>
            <tr role="row" class="filter display-hide">
                <td></td>
                <td><input name="code" tipe="2" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="name" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                <td><input readonly="readonly" name="id_perspective" tipe="2" a1="text-center" class="form-control form-filter select-filter input-md" placeholder="search" type="text" ></td>
                <td><input name="name_pic_so" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                <td>
                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="start_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="start_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                </td>
                <td>
                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="end_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="end_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                </td>
                <td><input name="description" tipe="1" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td class="text-center">
                    <a href="javascript:;" data-original-title="Search" class="tooltips btn btn-sm yellow-crusta filter-submit margin-bottom btn_search"><i class="fa fa-search"></i></a>
                    <a href="javascript:;" data-original-title="Reset" class="tooltips btn btn-sm red-sunglo filter-cancel"><i class="fa fa-times"></i></a>
                </td>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- modal review_so-->
    <div id="popup_review_so" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background:#3c8dbc;color:white;">
                <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
                <h3 class="modal-title" style="text-align:center;"><b>Review SO</b></h3>
            </div>
            <div class="modal-body" id="load_review_so"></div>
            <div class="modal-footer">
                <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    
</div>






<script type="text/javascript">
$(document).ready(function() {

//================================= Datatable ==========================================
        //variabel
        var table_name  = "table_so_mydashboard";
        var url         = "<?=site_url($url);?>/table_so_mydashboard";
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
        table_so_mydashboard.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_so_mydashboard .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //select2 biasa
        $(".table_so_mydashboard .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });
    //====================================================================================


    //load review_so
    $('.table_so_mydashboard').on('click', '.btn_review_so', function(e) {
            $('#popup_review_so').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url();?>app/review_so/load_review_so";
            var param = {id:id, view:'mydashboard'};
            Metronic.blockUI({ target: '#load_review_so',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_review_so').html(msg);
                Metronic.unblockUI('#load_review_so');
            });
        });
});
</script>