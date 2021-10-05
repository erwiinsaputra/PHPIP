<style type="text/css">
    .table-wrap thead tr th, .table-wrap thead tr td, .table-wrap tbody tr td { white-space: nowrap;} 
    .dataTables_scrollHeadInner{ padding-left:0px !important;}
    .DTFC_RightHeadBlocker { background: #eee; border: 1px solid #eee;}
    .DTFC_RightBodyLiner { overflow-y: none;}
    .filter td{ padding: 0px !important; white-space: nowrap;}
</style>

<div class="table-container table_sr_mydashboard">

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

    <table class="table table-striped table-bordered table-hover table-wrap" id="table_sr_mydashboard">
        <thead>
            <tr role="row" class="heading">
                <th style="background-color:rgb(61 122 177) !important;color:white;" width="40px">No</th>
                <th style="background-color:rgb(61 122 177) !important;color:white;">ID</th>

                <th style="background-color:rgb(61 122 177) !important;color:white;">Periode</th>
                <th style="background-color:rgb(61 122 177) !important;color:white;">BSC</th>
                <th style="background-color:rgb(61 122 177) !important;color:white;">Strategic Theme</th>
                <th style="background-color:rgb(61 122 177) !important;color:white;">Code</th>
                <th style="background-color:rgb(61 122 177) !important;color:white;">Strategic Result</th>
                
                <th style="background-color:rgb(61 122 177) !important;color:white;">Indikator</th>
                <th style="background-color:rgb(61 122 177) !important;color:white;">Polarisasi</th>
                <th style="background-color:rgb(61 122 177) !important;color:white;">Ukuran</th>
                <th style="background-color:rgb(61 122 177) !important;color:white;">Long Term Target</th>

                <th style="background-color:rgb(61 122 177) !important;color:white;">PIC</th>
                <th style="background-color:rgb(61 122 177) !important;color:white;">Description</th>
            </tr>
            <tr role="row" class="filter display-hide">
                <td></td>
                <td><input name="id" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                    
                <td><input readonly="readonly" name="name_periode" tipe="1" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input readonly="readonly" name="name_bsc" tipe="1" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input readonly="readonly" name="name_strategic_theme" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="code" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="name" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                
                <td><input name="indikator" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                <td><input name="polarisasi" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                <td><input name="ukuran" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                <td><input name="target" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                
                <td><input name="name_pic_sr" tipe="1" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="description" tipe="1" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    
</div>

<script type="text/javascript">
$(document).ready(function() {
//================================= Datatable ==========================================
        //variabel
        var table_name  = "table_sr_mydashboard";
        var url         = "<?=site_url($url);?>/table_sr_mydashboard";
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
        table_sr_mydashboard.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        // new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_sr_mydashboard .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //select2 biasa
        $(".table_sr_mydashboard .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });
    //====================================================================================


    
});
</script>