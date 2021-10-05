<div class="row table_review_so">
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
                        <a class="btn btn-primary btn-circle btn-sm " href="<?=site_url($url)?>" data-original-title="Refresh" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a> <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
                    </div>

                    <div style="float:left;">
                        <div style="float:left;font-size:1.5em;"><span>Year : </span>&nbsp;&nbsp;</div>
                        <div style="float:left;">
                            <select class="form-control global-filter"  tipe="2" name="global_year" id="global_year" placeholder="ALL">
                                <option value=""></option>
                                <?php for($y=$start_year; $y <= $end_year; $y++){ ?>
                                    <option <?=($y == date('Y') ? 'selected="selected"' : '')?> value="<?=$y?>"><?=$y?></option>
                                <?php } ?>
                            </select>
                        </div>
                        &nbsp; &nbsp; &nbsp; &nbsp; 
                    </div>

                    <div style="float:left;">
                        <div style="float:left;font-size:1.5em;"><span>Month : </span>&nbsp;&nbsp;</div>
                        <div style="float:left;">
                            <select class="form-control global-filter" tipe="2" name="global_month" id="global_month" placeholder="ALL">
                                <option value=""></option>
                                <?php for($m=1; $m <= 12; $m++){ ?>
                                    <option value="<?=$m?>"><?=$m?></option>
                                <?php } ?>
                            </select>
                        </div>
                        &nbsp; &nbsp; &nbsp; &nbsp; 
                    </div>

                </div>
            </div>


            <div class="portlet-body">
                
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
                                    <label class="control-label col-md-2"><b>Perspective&nbsp;:</b></label>
                                    <div class="col-md-4">
                                        <input name="global_id_perspective" id="global_id_perspective" tipe="2" class="form-control global-filter" placeholder="ALL"  type="text" />
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
                    .DTFC_RightHeadBlocker { background: #eee; border: 1px solid #eee;}
                    .DTFC_RightBodyLiner { overflow-y: none;}
                    .filter td{ padding: 0px !important; white-space: nowrap;}
                </style>
                
                <div class="table-container">

                    <div class="table-actions-wrapper">
                        <div style="float:right; padding-left:10px;">
                            <div class="showhide_column"></div>
                        </div>
                        <div style="float:right; padding-left:10px;">
                            <a href="javascript:" class="btn btn-sm yellow-crusta btn_show_filter"  data-placement="top" data-container="body"><i class="fa fa-search"></i> Search</a>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_review_so">
                        <thead>
                            <tr role="row" class="heading">
                                <th style="background-color:rgb(0 186 230) !important;color:white;" width="40px">No</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">ID</th>
            
                                <th style="background-color:rgb(0 186 230) !important;color:white;">SO Number</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;" style="text-align:center !important;">SO Title</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">PIC</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Perspective</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">BSC</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Status</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Description</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Is Active</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Created Date</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Created By</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Updated Date</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">Updated By</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;" width="130px">Action</th>
                            </tr>
                            <tr role="row" class="filter display-hide">
                                <td></td>
                                <td><input name="id" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="code" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="name" tipe="1" a1="text-left" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="name_pic_so" tipe="1" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input readonly="readonly" name="id_perspective" tipe="2" a2="false" a1="text-center" class="form-control form-filter select-filter input-md" placeholder="search" type="text" ></td>
                                <td><input readonly="readonly" name="id_bsc" tipe="1" a2="false" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><select name="status_so" tipe="2" a2="false" a1="text-center" class="form-control form-filter select-filter input-md select2_biasa"   placeholder="Search" >
                                        <option value=""></option>
                                        <?php foreach($status_so as $row){ ?>
                                            <option value="<?=$row->id;?>"><?=str_replace(' ','&nbsp;',$row->name);?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input name="description" tipe="1" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
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


<!-- modal detail_month-->
<div id="popup_detail_month" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Detail </b></h3>
      </div>
      <div class="modal-body" id="load_detail_month"></div>
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
        var table_name  = "table_review_so";
        var url         = "<?=site_url($url);?>/table_review_so";
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
        table_review_so.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_review_so .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //select2 biasa
        $(".table_review_so .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });

        //select2 global_year
        $(".table_review_so #global_year").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_review_so .filter-submit').first().click();
        });

        //select2 global_month
        $(".table_review_so #global_month").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_review_so .filter-submit').first().click();
        });

        //select2 biasa
        $(".table_review_so #global_id_bsc").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_review_so #global_id_perspective').val('');
            $(".table_review_so #global_id_perspective").change();
        });


        //select perspective
        window.table_review_so_global_id_perspective = function(){
            $(".table_review_so input[name='global_id_perspective']").select2({
                minimumResultsForSearch: -1,
                minimumInputLength: -1,
                dropdownAutoWidth : true,
                allowClear:true,
                ajax: {
                url: "<?php echo site_url($url.'/select_perspective')?>",
                dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
                data: function (term, page) {
                    var id_bsc = $("#global_id_bsc").val();
                    return { q: term, id_bsc:id_bsc };
                },
                results: function (data, page) {  return { results: data.item }; },
                },
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                    $.ajax({
                        url:"<?php echo site_url($url.'/select_perspective')?>",
                        dataType: "json", type:"POST", 
                        data:{ id: id}
                    }).done(function(res) { callback(res[0]); });
                    }
                },
                formatResult: function(item){ return item.name },
                formatSelection: function(item){ return item.name; }
            }).on('change', function(event) { 
                $('.table_review_so .filter-submit').first().click();
            });
        }
        window.table_review_so_global_id_perspective();

    //====================================================================================


    //=============================== Action =========================================

        //load review_so
        $('.table_review_so').on('click', '.btn_review_so', function(e) {
            $('#popup_review_so').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_review_so";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_review_so',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_review_so').html(msg);
                Metronic.unblockUI('#load_review_so');
            });
        });

});
</script>