<style type="text/css">
    .table-wrap thead tr th, .table-wrap thead tr td, .table-wrap tbody tr td { white-space: nowrap;} 
    .dataTables_scrollHeadInner{ padding-left:0px !important;}
    .DTFC_RightHeadBlocker { background: #eee; border: 1px solid #eee;}
    .DTFC_RightBodyLiner { overflow-y: none;}
    .filter td{ padding: 0px !important; white-space: nowrap;}
</style>

<div class="table-container table_st_mydashboard">

    <table class="table table-striped table-bordered table-hover table-wrap" id="table_st_mydashboard">
        <thead>
            <tr role="row" class="heading">
                <th style="background-color:rgb(104 157 204) !important;color:white;" width="40px">No</th>

                <th style="background-color:rgb(104 157 204) !important;color:white;">Code</th>
                <th style="background-color:rgb(104 157 204) !important;color:white;">Strategic Theme</th>
                <th style="background-color:rgb(104 157 204) !important;color:white;">Description</th>
            </tr>
            <tr role="row" class="filter display-hide">
                <td></td>
                <td><input name="code" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                <td><input name="name" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                <td><input name="description" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    
</div>


<script type="text/javascript">
$(document).ready(function() {
//================================= Datatable ==========================================
        //variabel
        var table_name  = "table_st_mydashboard";
        var url         = "<?=site_url($url);?>/table_st_mydashboard";
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
            header[0]['data']       = "no";
            header[0]['sClass']     = "text-center";
        });

        //table init
        table_st_mydashboard.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        // new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================
    
});
</script>