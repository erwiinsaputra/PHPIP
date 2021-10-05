<div class="row table_perspective">
    <div class="col-md-12">
        <div class="portlet light ">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-table font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Table &nbsp;<?=$title;?></span>
                </div>
                <div class="actions">
                    <div style="float:right;">
                        <a href="javascript:" class="btn btn-sm btn-primary btn_add" data-placement="top" data-container="body"><i class="fa fa-plus"></i> Add</a>
                        <a class="btn btn-primary btn-circle btn-sm " href="<?=site_url($url)?>" data-original-title="Refresh" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a> <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
                    </div>
                </div>
            </div>


            <div class="portlet-body">
               
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
                        <div style="float:right;">
                            <a href="javascript:" class="btn btn-sm yellow-crusta btn_show_filter"  data-placement="top" data-container="body"><i class="fa fa-search"></i> Search</a>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_perspective">
                        <thead>
                            <tr role="row" class="heading">
                                <th width="40px">No</th>
                                <th>ID</th>
                                <th>Role</th>
                                <th>Description</th>
                                <th width="130px">Action</th>
                            </tr>
                            <tr role="row" class="filter display-hide">
                                <td></td>
                                <td><input type="text" a1="text-center" class="form-control form-filter input-md" name="id" tipe="1" placeholder="id"></td>
                                <td><input type="text" a1="text-center" class="form-control form-filter input-md" name="perspective" tipe="1" placeholder="Perspective Name"></td>
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
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>ADD DATA</b></h3>
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
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>EDIT DATA</b></h3>
      </div>
      <div class="modal-body" id="load_edit"></div>
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
        var table_name  = "table_perspective";
        var url         = "<?=site_url($url);?>/table_perspective";
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
        table_perspective.initDefault(url, header, order, sort);

        //drag column
        new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_perspective .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //us_id
        window.table_perspective_note_created_by = function(){
            $(".table_perspective input[name='note_created_by']").select2({
                  minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true, multiple : true,
                  ajax:{
                      url: "<?=site_url($url);?>/select_user",
                      dataType: 'json',
                      quietMillis: 250,
                      cache: true,
                      data: function (term, page) { return { q: term }; },
                      results: function (data, page) { return { results: data.item }; },
                 },
                formatResult: function (item){return item.name;},
                formatSelection: function (item){return item.name;},
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        var id = id.replace(/,/g , "-");
                        $.ajax("<?=site_url($url);?>/select_user/"+ id, {
                        dataType: "json" }).done( function(data) { callback(data); });
                    }
                },
            }).on('change', function(e) {  $('.table_perspective .filter-submit').first().trigger('click'); });
        }
        window.table_perspective_note_created_by();


    //====================================================================================


    //=============================== Action =========================================


        //load add
        $('.btn_add').die().live('click',function(){
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
        $('.btn_edit').die().live('click',function(){
            $('#popup_edit').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_edit";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_edit',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_edit').html(msg);
                Metronic.unblockUI('#load_edit');
            });
        });

        //btn delete
        $('.btn_delete').die().live('click',function(){
            var id = $(this).attr('id');
            var title = "Are You Sure ?";
            var mes = "Are you sure to DELETE Data?";
            var sus = "Successfully DELETE Data!";
            var err = "Failed DELETE Data!";
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
                    var url    = '<?=site_url($url)?>/delete_data';
                    var param   = {id:id};
                    $.post(url, param, function(msg){
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_perspective();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    }, 'json');
            });
            
        });
       
});
</script>