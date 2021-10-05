    

<div class="row table_user">
    <div class="col-md-12">
        <div class="portlet light datatable">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-table font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Table <?php echo $setting['pagetitle']?></span>
                </div>
                <div class="actions">
                    <!-- <span>Show Data : </span>
                    <select class="bs-select form-control input-small-x input-sm input-inline" data-style="btn-danger" name="custom_status">
                        <option value="all">All</option>
                        <option value="1">Active</option>
                        <option value="0">Non Active</option>
                    </select> -->
                    <a href="<?=site_url($setting['url'].'show_add')?>" class="ajaxify btn green-meadow tooltips" data-original-title="Tambah Data" data-placement="top" data-container="body"><i class="fa fa-plus"></i></a>
                    <a href="javascript:;" onclick="reloadTable()" class="btn purple-plum tooltips" data-original-title="Reload" data-placement="top" data-container="body"><i class="fa fa-refresh"></i></a>
                    <a href="javascript:;" class="btn btn-sm yellow-crusta btn_show_filter tooltips" data-original-title="Cari" data-placement="top" data-container="body"><i class="fa fa-search"></i></a>

                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <div class="table-actions-wrapper">
                        <span></span>
                    </div>

                    <style type="text/css">
                      .table-wrap thead tr th, .table-wrap thead tr td, .table-wrap tbody tr td { white-space: nowrap; } 
                      .dataTables_scrollHeadInner{ padding-left:0px !important;}
                      .DTFC_RightHeadBlocker { background: #eee; border: 1px solid #eee;}
                      .DTFC_RightBodyLiner { overflow-y: none;}
                      .filter td{ padding: 0px !important;}
                    </style>

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_user">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="40px">No</th>
                            <th>Employee Number</th>
                            <th>Username</th>
                            <th>Initial Name</th>
                            <th>Title</th>
                            <th>E-mail</th> 
                            <th>Role Name</th>
                            <th>User Customer Company</th>
                            <th>User Unit</th>
                            <th>User Is Active</th>
                            <th>User Created Date</th>
                            <th>User Update Date</th>
                            <th>User Customer</th><!-- 
                            <th>User Tipe</th>  -->
                            <th width="130px">Action</th>
                        </tr>
                        <tr role="row" class="filter display-hide">
                            <td></td>
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_USERNAME" placeholder="Employee Number">
                            </td>
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_NAME" placeholder="Username">
                            </td>
                             <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_INITIAL" placeholder="Role Name">
                            </td>
                             <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_REGION_ID" placeholder="Region">
                            </td>
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_CUS_ID" placeholder="Customer">
                            </td>
                             <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_ROLE_ID" placeholder="User Role ID">
                            </td>
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_CUS_COMPANY" placeholder="User Customer Company">
                            </td>
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_UNIT" placeholder="User Unit">
                            </td>
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_IS_ACTIVE" placeholder="User Is Active">
                            </td>
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_CREATED_DATE" placeholder="User Created Date">
                            </td>
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_UPDATED_DATE" placeholder="User Update Date">
                            </td>
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_CUS_ID" placeholder="User Customer ID">
                            </td><!-- 
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="USER_TIPE" placeholder="User Tipe">
                            </td> -->
                            <td class="text-center">
                                <button data-original-title="Search" class="tooltips btn btn-sm yellow-crusta filter-submit margin-bottom"><i class="fa fa-search"></i></button>
                                <button data-original-title="Reset" class="tooltips btn btn-sm red-sunglo filter-cancel"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var urlM = '';

            jQuery(document).ready(function() {
                var url = "<?=site_url($setting['url'].'table_user')?>";
                var header = [
                    { "sClass": "text-center" },
                    { "sClass": "text-center" },
                    { "sClass": "text-left" },
                    { "sClass": "text-center" },
                    { "sClass": "text-center" },
                    { "sClass": "text-center" },
                    { "sClass": "text-center" },
                    { "sClass": "text-center" },
                    { "sClass": "text-center" },
                    { "sClass": "text-center" },
                    { "sClass": "text-center" },
                    { "sClass": "text-center" },
                    { "sClass": "text-center" },/*
                    { "sClass": "text-center" },*/
                    { "sClass": "text-center" }
                ];
                var order = [
                    [1, "desc"]
                ];
                var sort = [-1,0];

                table_user.initDefault(url, header, order, sort);

                new $.fn.dataTable.FixedColumns("#table_user", { leftColumns: 1, rightColumns: 1 } );

            });
        </script>
    </div>
</div>