<div class="row table_review_si">
    <div class="col-md-12">
        <div class="portlet light ">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-table font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?=$title;?></span>
                    <input type="hidden" id="ex_csrf_token" value="<?=csrf_get_token();?>">
                </div>
                <div class="actions">
                
                    <div style="float:right;">
                        <a class="btn btn-primary btn-circle btn-sm " href="<?=site_url($url)?>" data-original-title="Refresh" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a> <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
                    </div>

                    <div style="float:left;">
                        <div style="float:left;font-size:1.3em;"><span>BSC : </span>&nbsp;&nbsp;</div>
                        <div style="float:left;">
                            <select class="form-control global-filter"  name="global_id_bsc" id="global_id_bsc" tipe="2" placeholder="ALL">
                                <option value=""></option>
                                <?php foreach($bsc as $row){ ?>
                                    <option <?=($row->id == '1' ? 'selected="selected"' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
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
                            <div class="col-md-5 loading_grafik">
                                <div style="display: flex;justify-content:center;margin-top:-0.5em;margin-bottom:0.5em;margin-left:5em;">
                                    <div id="load_grafik"></div>
                                </div>
                            </div>
                            <div class="col-md-7" style="margin-top:3em;">
                                <div class="col-md-12">
                                    <div class="form-group" >
                                        <label class="control-label col-md-2" ><b>Status&nbsp;:</b></label>
                                        <div class="col-md-4">
                                            <select class="form-control global-filter" name="global_status_complete" id="global_status_complete" tipe="2" placeholder="ALL">
                                                <option value=""></option>
                                                <?php foreach(@$status_complete as $row){ ?>
                                                    <option value="<?=$row->id;?>"><?=$row->name;?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2"><b>Quarter&nbsp;:</b></label>
                                        <div class="col-md-4">
                                            <select class="form-control global-filter"  tipe="2" name="global_quarter" id="global_quarter" placeholder="ALL" style="">
                                                <option value=""></option>
                                                <?php for($q=1; $q <= 4; $q++){ ?>
                                                    <option value="<?=$q?>"><?=$q?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-2"><b>Year&nbsp;:</b></label>
                                        <div class="col-md-4">
                                            <select class="form-control global-filter"  tipe="2" name="global_year" id="global_year" placeholder="ALL">
                                                <option value=""></option>
                                                <?php for($y=$start_year; $y <= $end_year; $y++){ ?>
                                                    <option <?=($y == date('Y') ? 'selected="selected"' : '')?> value="<?=$y?>"><?=$y?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2"><b>Month&nbsp;:</b></label>
                                        <div class="col-md-4">
                                            <select class="form-control global-filter" tipe="2" name="global_month" id="global_month" placeholder="ALL" style="">
                                                <option value=""></option>
                                                <?php for($m=1; $m <= 12; $m++){ ?>
                                                    <option <?=($m == (int)date('m') ? 'selected' : '') ?>  value="<?=$m?>"><?=$m?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
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

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_review_si">
                        <thead>
                            <tr role="row" class="heading">
                                <th style="background-color:rgb(0 186 230) !important;color:white;" width="40px">No</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">ID</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">SI</th>
                                <th style="background-color:rgb(0 186 230) !important;color:white;">SI Title</th>
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
            </div>
        </div>


    </div>
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



<!-- grafik pie -->
<script src="https://code.highcharts.com/highcharts.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    //================================= Load Grafik ==========================================
        var options = {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                height: '200',
                width:400
            },
            colors: ['#d3d3d3', '#ff0000', '#ffff00', '#008000'],
            title: {
                text: 'Total Color Status SI',
                style: { 'display': 'none', 'font-size':'1em'}
            },
            tooltip: {
                pointFormat: '{series.name} = {point.y}'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.name} = {point.y}',
                        connectorColor: 'silver'
                    }
                }
            },
            series: [{
                name: 'Total',
                colorByPoint: true,
                data: [
                    { name: 'Not Yet Defined', y: 61.41 },
                    { name: 'Red', y: 11.84 },
                    { name: 'Yellow', y: 10.85 },
                    { name: 'Green', y: 4.67 },
                ]
            }],
            exporting: { enabled: false },
            credits: { enabled: false }
        };

        var chart = Highcharts.chart('load_grafik', options);

        //reload grafik
        window.reload_grafik = function (data_grafik){
            Metronic.blockUI({ target: '#loading_grafik',  boxed: true});
            chart.series[0].setData(data_grafik);
            Metronic.unblockUI('#loading_grafik');
        }

        
    //================================= Datatable ==========================================
        //variabel
        var table_name  = "table_review_si";
        var url         = "<?=site_url($url);?>/table_review_si";
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
        table_review_si.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_review_si .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

        //select2 biasa
        $(".table_review_si .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });

        //select2 global_status_complete
        $(".table_review_si #global_status_complete").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_review_si .filter-submit').first().click();
        });

        //select2 global_year
        $(".table_review_si #global_year").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_review_si .filter-submit').first().click();
        });

        //select2 global_quarter
        $(".table_review_si #global_quarter").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        }).on('change', function(event) { 
            var q = $(this).val();
            if(q == '1'){
                $(".table_review_si #global_month").select2('val','1');
            }else if(q == '2'){
                $(".table_review_si #global_month").select2('val','4');
            }else if(q == '3'){
                $(".table_review_si #global_month").select2('val','8');
            }else if(q == '4'){
                $(".table_review_si #global_month").select2('val','12');
            }else{
                $(".table_review_si #global_month").select2('val','');
            }
            $('.table_review_si .filter-submit').first().click();
        });

        //select2 global_month
        $(".table_review_si #global_month").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_review_si .filter-submit').first().click();
        });

        //select2 biasa
        $(".table_review_si #global_id_bsc").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_review_si #global_id_perspective').val('');
            $(".table_review_si #global_id_perspective").change();
        });

    //====================================================================================


    //=============================== Action =========================================

        //load issue
        $('.table_review_si').on('click','.btn_issue', function(e) {
            $('#popup_issue').modal();
            var id_si  = $(this).attr('id_si');
            var year  = '<?=date('Y')?>';
            var url = "<?=site_url($url);?>/load_issue";
            var param = {id_si:id_si, year:year};
            Metronic.blockUI({ target: '#load_issue',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_issue').html(msg);
                Metronic.unblockUI('#load_issue');
            });
        });

        //load ic
        $('.table_review_si').on('click', '.btn_ic', function(e) {
            $('#popup_ic').modal();

            //load monitoring
            var tipe = 'review';
            var id_si = $(this).attr('id_si');
            var year  = $(".table_review_si #global_year").val();
            var month = $(".table_review_si #global_month").val();
            var url = "<?=site_url('app/monev_si');?>/load_add";
            var param = {tipe:tipe, id_si:id_si, year:year, month:month};
            Metronic.blockUI({ target: '#load_ic',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_ic').html(msg);
                Metronic.unblockUI('#load_ic');
            });

            //load detail si
            var title = $(this).attr('title_popup');
            $('#popup_ic').find('#title_detail_si').html(title);
            var url = "<?=site_url('app/ic');?>/load_detail_si";
            var param = {id:id_si};
            Metronic.blockUI({ target: '#load_detail_si',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_detail_si').html(msg);
                Metronic.unblockUI('#load_detail_si');
            });

        });



});
</script>


