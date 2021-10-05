<style type="text/css">
    .table-wrap thead tr th, .table-wrap thead tr td, .table-wrap tbody tr td { white-space: nowrap;} 
    .dataTables_scrollHeadInner{ padding-left:0px !important;}
    .DTFC_RightHeadBlocker { background: #eee; border: 1px solid #eee;}
    .DTFC_RightBodyLiner { overflow-y: none;}
    .filter td{ padding: 0px !important; white-space: nowrap;}
</style>

<div class="table-container table_si_mydashboard">

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

    <table class="table table-striped table-bordered table-hover table-wrap" id="table_si_mydashboard">
        <thead>
            <tr role="row" class="heading">
                <th style="background-color:rgb(0 186 230) !important;color:white;" width="40px">No</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">ID</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">SI</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">SO Title</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">PIC</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">Status</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">Color</th>
                <th style="background-color:rgb(0 186 230) !important;color:white; font-size:0.8em !important;">%&nbsp;Complete&nbsp;<br>on&nbsp;Year</th>
                <th style="background-color:rgb(0 186 230) !important;color:white; font-size:0.8em !important;">%&nbsp;Overall<br>Complete</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">Year</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">Month</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;">BSC</th>
                <th style="background-color:rgb(0 186 230) !important;color:white;" width="130px">Action</th>
            </tr>
            <tr role="row" class="filter display-hide">
                <td></td>
                <td><input name="id" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="code" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="name" tipe="1" a1="text-left" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="name_pic_si" tipe="1" a1="text-center"  class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input readonly="readonly" name="status_complete" tipe="1" a1="text-center"  class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="color" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="complete_on_year" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="overall_complete" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input readonly="readonly" name="year" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input readonly="readonly" name="month" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input readonly="readonly" name="id_bsc" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td class="text-center">
                    <a href="javascript:;" data-original-title="Search" class="tooltips btn btn-sm yellow-crusta filter-submit margin-bottom btn_search"><i class="fa fa-search"></i></a>
                    <a href="javascript:;" data-original-title="Reset" class="tooltips btn btn-sm red-sunglo filter-cancel"><i class="fa fa-times"></i></a>
                </td>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    
</div>



<!-- modal add-->
<div id="popup_ic" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static" style="margin-top: -1% !important;">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Review SI</b></h3>
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
            <div id="load_ic"></div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal issue-->
<div id="popup_issue" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Review Issue</b></h3>
      </div>
      <div class="modal-body" id="load_issue"></div>
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
        var table_name  = "table_si_mydashboard";
        var url         = "<?=site_url($url);?>/table_si_mydashboard";
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
        table_si_mydashboard.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_si_mydashboard .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //select2 biasa
        $(".table_si_mydashboard .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });
    
    //==========================================================================================


    //=============================== Action =========================================

    //load issue
    $('.table_si_mydashboard').on('click','.btn_issue', function(e) {
        $('#popup_issue').modal();
        var id_si  = $(this).attr('id_si');
        alert(id_si);
        var year  = '<?=date('Y')?>';
        var url = "<?=site_url();?>app/review_si/load_issue";
        var param = {id_si:id_si, year:year};
        Metronic.blockUI({ target: '#load_issue',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_issue').html(msg);
            Metronic.unblockUI('#load_issue');
        });
    });

    //load ic
    $('.table_si_mydashboard').on('click', '.btn_ic', function(e) {
        $('#popup_ic').modal();

        //load monitoring
        var tipe = 'review';
        var id_si = $(this).attr('id');
        var year  = $(".table_si_mydashboard #global_year").val();
        var month = $(".table_si_mydashboard #global_month").val();
        var url = "<?=site_url();?>app/monev_si/load_add";
        var param = {tipe:tipe, id_si:id_si, year:year, month:month};
        Metronic.blockUI({ target: '#load_ic',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_ic').html(msg);
            Metronic.unblockUI('#load_ic');
        });

        //load detail si
        var title = $(this).attr('title_popup');
        $('#popup_ic').find('#title_detail_si').html(title);
        var url = "<?=site_url();?>app/ic/load_detail_si";
        var param = {id:id_si};
        Metronic.blockUI({ target: '#load_detail_si',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_detail_si').html(msg);
            Metronic.unblockUI('#load_detail_si');
        });

    });
    //==========================================================================================

});
</script>