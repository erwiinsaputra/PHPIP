<div class="row">
    <div class="col-md-12">
        <div class="portlet light datatable">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-table font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Table <?=$url?></span>
                </div>
                <div class="actions">
                    <!-- <select class="bs-select form-control input-small-x input-sm input-inline" data-style="btn-danger" name="custom_status">
                        <option value="all">Semua</option>
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                        <option value="99">Hapus</option>
                    </select> -->
                    <a href="<?=site_url($url)?>/show_add" class="ajaxify btn green-meadow tooltips" data-original-title="Tambah Data" data-placement="top" data-container="body"><i class="fa fa-plus"></i></a>
                    <a href="javascript:" onclick="reloadTable()" class="btn purple-plum tooltips" data-original-title="Reload" data-placement="top" data-container="body"><i class="fa fa-refresh"></i></a>
                    <a href="javascript:" class="btn btn-sm yellow-crusta btn_show_filter tooltips" data-original-title="Cari" data-placement="top" data-container="body"><i class="fa fa-search"></i></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <div class="table-actions-wrapper"></div>
                    
                    <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="40px">No</th>
                            <th>Role</th>
                            <th width="130px">Aksi</th>
                        </tr>
                        <tr role="row" class="filter display-hide">
                            <td></td>
                            <td>
                                <input type="text" class="form-control form-filter input-sm" name="role" placeholder="Role">
                            </td>
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
                var url = "<?=site_url($url.'get_table')?>";
                var header = [
                    { "sClass": "text-center" },
                    null,
                    { "sClass": "text-center" }
                ];
                var order = [];
                var sort = [-1, 0];

                TableAjax.initDefault(url, header, order, sort);
            });
        </script>
    </div>
</div>