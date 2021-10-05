<div class="table-container">
    <table class="table table-striped table-bordered table-hover" id="table_pica">            
        <thead>
             <tr role="row" class="heading">
                <th style="text-align:center">No</th>
                <th style="text-align:center">Date&nbsp;Create</th>
                <th style="text-align:center">Activity&nbsp;Plan</th>
                <th style="text-align:center">Output</th>                
                <th style="text-align:center">Start&nbsp;Date</th>
                <th style="text-align:center">End&nbsp;Date&nbsp;&nbsp;</th>
                <th style="text-align:center">Send&nbsp;To</th>
                <th style="text-align:center">Pic</th>
                <th style="text-align:center" width="500px" >Followup</th>
                <th style="text-align:center">Status</th>
                <!-- <th style="text-align:center">Action</th> -->
            </tr> 
            <!-- <tr role="row" class="filter display-hide">
                <td></td>
                <td></td>
                <td><input id="cus_id" type="text" class="form-control form-filter input-sm" name="ams_customer" placeholder="Customer"></td>
                <td><input id="gr_id" type="text" class="form-control form-filter input-sm" name="ams_group" placeholder="Product"></td>
                <td><input type="text" class="form-control form-filter input-sm" name="tpm_project_type" placeholder="Project Type"></td>
                <td><input type="text" class="form-control form-filter input-sm" name="ams_serinumber" placeholder="Seri Number"></td>
                <td><input type="text" class="form-control form-filter input-sm" name="ams_salesplan" placeholder="Seri Number"></td>
                <td><input type="text" class="form-control form-filter input-sm" name="loc_id" placeholder="Location"></td>
                <td><input type="text" class="form-control form-filter input-sm" name="ams_tat" placeholder="TAT"></td>
                <td><input id="start_date" type="text" class="form-control form-filter input-sm" name="ams_start_date" placeholder="Start Date"></td>
               <td><input id="end_date" type="text" class="form-control form-filter input-sm" name="ams_end_date" placeholder="End Date"></td>
                <td><input type="text" class="form-control form-filter input-sm" name="ams_created_date" placeholder="Create Date"></td>
                <td class="text-center">
                    <button data-original-title="Search" class="tooltips btn btn-sm yellow-crusta filter-submit margin-bottom btn_search"><i class="fa fa-search"></i></button>
                    <button data-original-title="Reset" class="tooltips btn btn-sm red-sunglo filter-cancel"><i class="fa fa-times"></i></button>
                </td>
            </tr> -->
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        //datatable

        var param  = "<?php echo $ams_id;?>";
        var url = "<?=site_url($url);?>/table_pica";
        var header = [
            { "sClass": "text-center" },
            { "sClass": "text-center" },
            { "sClass": "text-center" },
            { },
            { },
            { "sClass": "text-center" },
            { "sClass": "text-center" },
            { "sClass": "text-center" },
            { "sClass": "text-center" },
            { },
            // { "sClass": "text-center" }
        ];

        var order = [];
        var sort = [-1];
        table_pica.initDefault(url, header, order, sort, param);

    });
</script>