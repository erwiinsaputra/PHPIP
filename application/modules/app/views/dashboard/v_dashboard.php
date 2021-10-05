<div class="portlet light ">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-table font-green-sharp"></i>
            <span class="caption-subject font-green-sharp bold uppercase">Strategic Map</span>
        </div>
        <div class="actions">
        
            <div style="float:right;">
                <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen btn_fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
            </div>
            <div style="float:right;">
                <a style="background-color:rgb(0 138 172) !important;color:white;" href="javascript:" tipe="map_view" class="btn btn-sm btn-primary btn_view"><i class="fa fa-bar-chart-o"></i> Strategic Map View</a>
                <a style="background-color:rgb(119 202 220) !important;color:white;" href="javascript:" tipe="table_view" class="btn btn-sm btn-danger btn_view"><i class="fa fa-table"></i> Table View</a>
                <a style="background-color:rgb(120 228 255) !important;color:white;" href="javascript:" tipe="graphical_analysis" class="btn btn-sm btn-info btn_view"><i class="fa fa-table"></i> Graphical Analysis</a>
                <input type="hidden" id="tipe" value="map_view">
                <input type="hidden" id="load_awal" value="0">
                &nbsp; &nbsp;
            </div>
            
        </div>
    </div>

    <div class="portlet-body">
        <style>
            .t_filter{
                font-size: 1.3em;
                font-weight:bold;
            }
        </style>

        <div class="filtering" style="text-align:center !important;">
            <form id="form_filtering" action="javascript:" method="POST" class="form-horizontal" role="form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <img style="width:30px;height:30px;" src="<?=base_url();?>public/assets/app/img/icon/calender.png">
                                    <span class="t_filter">Periode</span>
                                    <select class="form-control global-filter"  name="global_id_periode" id="global_id_periode"  placeholder="Periode">
                                        <?php foreach($periode as $row){ ?>
                                            <?php 
                                                $periode_year = $row->start_year.' - '.$row->end_year;
                                                if( strpos( $periode_year, date('Y') ) !== false ) {
                                                    $selected = "selected='selected'";
                                                }
                                            ?>
                                            <option <?=@$selected;?> value="<?=$row->id;?>"><?=$periode_year?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 filter">
                                    <img style="width:25px;height:30px;" src="<?=base_url();?>public/assets/app/img/icon/flag.png">
                                    <span class="t_filter">Year</span>
                                    <select class="form-control global-filter"  name="global_year" id="global_year" placeholder="ALL">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <img style="width:27px;height:30px;" src="<?=base_url();?>public/assets/app/img/icon/building.png">
                                    <span class="t_filter">BSC</span>
                                    <select class="form-control global-filter"  name="global_id_bsc" id="global_id_bsc" placeholder="ALL">
                                        <!-- <option value=""></option> -->
                                        <?php foreach($bsc as $row){ ?>
                                            <option <?=($row->id == '1'?'selected':'')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 filter">
                                    <img style="width:23px;height:30px;" src="<?=base_url();?>public/assets/app/img/icon/time.png">
                                    <span class="t_filter">Quarter</span>
                                    <select class="form-control global-filter"  name="global_triwulan" id="global_triwulan" placeholder="ALL">
                                        <option value=""></option>
                                        <?php 
                                            if(h_triwulan_now() == '1'){ $tw_now = '4';}
                                            if(h_triwulan_now() == '2'){ $tw_now = '1';}
                                            if(h_triwulan_now() == '3'){ $tw_now = '2';}
                                            if(h_triwulan_now() == '4'){ $tw_now = '3';}
                                        ?>
                                        <?php for($tw=1; $tw <= 4; $tw++){ ?>
                                            <option <?=(@$tw_now == $tw ?'selected':'')?> value="<?=$tw?>"><?=$tw?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 filter">
                                    <img style="width:23px;height:30px;" src="<?=base_url();?>public/assets/app/img/icon/time.png">
                                    <span class="t_filter">Month</span>
                                    <select class="form-control global-filter"  name="global_month" id="global_month" placeholder="ALL">
                                        <option value=""></option>
                                        <?php 
                                            if(h_triwulan_now() == '1'){ $month_now = '12';}
                                            if(h_triwulan_now() == '2'){ $month_now = '3';}
                                            if(h_triwulan_now() == '3'){ $month_now = '6';}
                                            if(h_triwulan_now() == '4'){ $month_now = '9';}
                                        ?>
                                        <?php for($m=1; $m <= 12; $m++){ ?>
                                            <option <?=(@$month_now == $m ?'selected':'')?> value="<?=$m?>"><?=$m?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        
        <div id="load_view">
            <?=@$html_map_view;?>
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
        <h3 class="modal-title" style="text-align:center;font-size:2em;margin-left:6em;"><b>Detail Month</b></h3>
      </div>
      <div class="modal-body" id="load_detail_month"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal detail grafik-->
<div id="popup_detail_graphical_analysis" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Graphical Analysis</b></h3>
      </div>
      <div class="modal-body" id="load_detail_graphical_analysis"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal detail strategic theme-->
<div id="popup_detail_strategic_theme" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Detail Strategic Theme</b></h3>
      </div>
      <div class="modal-body" id="load_detail_strategic_theme"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal detail strategic result-->
<div id="popup_detail_strategic_result" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Detail Strategic Result</b></h3>
      </div>
      <div class="modal-body" id="load_detail_strategic_result"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== REVIEW SI ========================================================== -->
<!-- modal ic-->
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
  <div class="modal-dialog modal-full" role="document">
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

<!-- ========================================== END REVIEW SI ========================================================== -->



<script type="text/javascript">
$(document).ready(function () {
   
    //periode
    $(".filtering #global_id_periode").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) { 
        $('.filtering #global_year').val('').change();
        window.change_periode();
    });

    //BSC
    $(".filtering #global_id_bsc").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) { 
        window.change_filter();
    });

    //Year
    $(".filtering #global_year").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
    }).on('change', function(event) { 
        var load_awal = $('#load_awal').val();
        if(load_awal == 0){
            $('#load_awal').val(1);
        }else{
            window.change_filter();
        }
    });

    //Triwulan
    $(".filtering #global_triwulan").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) { 
        var tw = $(".filtering #global_triwulan").val();
        if(tw == 1){ var m = 3;}
        if(tw == 2){ var m = 6;}
        if(tw == 3){ var m = 9;}
        if(tw == 4){ var m = 12;}
        if(tw == ''){ var m = '';}
        $(".filtering #global_month").val(m);
        $(".filtering #global_month").trigger('change');
    });

    //Month
    $(".filtering #global_month").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) { 
        var m = $(".filtering #global_month").val();
        if(m == ''){
            $(".filtering #global_triwulan").val('');
        }
        window.change_filter();
    });

    //tipe view
    $('.btn_view').on('click',function(){
        var tipe = $(this).attr('tipe');
        $('#tipe').val(tipe);
        if(tipe == 'graphical_analysis'){
            $('.filter').hide();
        }else{
            $('.filter').show();
        }
        $('#load_view').html('');
        var url = '<?=site_url($url)?>/load_'+tipe;
        var param = $('#form_filtering').serializeArray();
        Metronic.blockUI({ target: '.portlet',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_view').html(msg);
            Metronic.unblockUI('.portlet');
        });
    });

    //tipe filter
    window.change_filter = function (){
        var tipe = $('#tipe').val();
        var url = '<?=site_url($url)?>/load_'+tipe;
        var param = $('#form_filtering').serializeArray();
        Metronic.blockUI({ target: '#load_view',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_view').html(msg);
            Metronic.unblockUI('#load_view');
        });
    }

    //change year
    window.change_periode = function (){
        $(".filtering select[name='global_year']").text("<option selected='selected' value=''></option>");
        $(".filtering select[name='global_year']").val("");
        var val =  $(".filtering #global_id_periode").val();
        var periode =  $(".filtering select[name='global_id_periode'] option[value='"+val+"']").text();
        var pecah = periode.split(" - ");
        var start_year = pecah[0];
        var end_year = pecah[1];
        for(var year=start_year;year<=end_year;year++){
            if(year == start_year){
                var newOption = new Option('', '', true, true);
                $(".filtering select[name='global_year']").append(newOption);
                var newOption = new Option(year, year, true, true);
                $(".filtering select[name='global_year']").append(newOption);
            }else{
                var newOption = new Option(year, year, true, true);
                $(".filtering select[name='global_year']").append(newOption);
            }
            if(year == start_year){ var year_now = start_year; }
            if(year == "<?=date('Y')?>"){ 
                var tw_now = "<?=h_triwulan_now()?>";
                if(tw_now == '1'){
                    var year_now = "<?=date('Y')-1?>"; 
                }else{
                    var year_now = "<?=date('Y')?>"; 
                }
            }
        }
        $(".filtering select[name='global_year']").val(year_now).change();
    }
    window.change_periode();

    //tipe view
    $('#load_view').on('click', '.btn_so', function(e) {
        $('#popup_review_so').modal();
        var id = $(this).attr('so');
        var year = $('#global_year').val();
        var month = $('#global_month').val();
        var url = "<?=site_url('app/review_so');?>/load_review_so";
        var param = {id:id, year:year, month:month};
        Metronic.blockUI({ target: '#load_review_so',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_review_so').html(msg);
            Metronic.unblockUI('#load_review_so');
        });
    });

    //btn fullscreen
    // $('.btn_fullscreen').on('click',function(e) {
    //     if($(this).hasClass('on')){
    //         $("#load_svg").css({'transform':'scale(0.65)','margin-left':'-13.7em','margin-top':'-10em'});
    //     }else{
    //         $("#load_svg").css({'transform':'scale(0.82)','margin-left':'-8em','margin-top':'-5em'});
    //     }
    // });

});
</script>






