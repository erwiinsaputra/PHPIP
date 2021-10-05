<!-- Filtering -->
<div class="filtering" style="text-align:left !important;">
    <form id="form_filtering" action="javascript:" method="POST" class="form-horizontal" role="form">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-2" style="text-align:center !important;">
                             <img style="width:30px;height:30px;" src="<?=base_url();?>public/assets/app/img/icon/calender.png">
                            <span style="font-size:1.3em;"><b>Periode</b</span>
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
                            <input type="hidden" name="global_table" id="global_table" class="form-control global-filter">
                        </div>
                        <div class="col-md-2 filter" style="text-align:center !important;">
                            <img style="width:25px;height:30px;" src="<?=base_url();?>public/assets/app/img/icon/flag.png">
                            <span style="font-size:1.3em;"><b>Year</b</span>
                            <select class="form-control global-filter"  name="global_year" id="global_year" placeholder="ALL">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-4" style="text-align:center !important;">
                            <img style="width:27px;height:30px;" src="<?=base_url();?>public/assets/app/img/icon/building.png">
                            <span style="font-size:1.3em;"><b>BSC</b</span>
                            <select class="form-control global-filter"  name="global_id_bsc" id="global_id_bsc" placeholder="ALL">
                                <!-- <option value=""></option> -->
                                <?php foreach($bsc as $row){ ?>
                                    <option <?=($row->id == '1'?'selected':'')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2 filter" style="text-align:center !important;">
                            <img style="width:23px;height:30px;" src="<?=base_url();?>public/assets/app/img/icon/time.png">
                            <span style="font-size:1.3em;"><b>Quarter</b</span>
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
                        <div class="col-md-2 filter" style="text-align:center !important;">
                            <img style="width:23px;height:30px;" src="<?=base_url();?>public/assets/app/img/icon/time.png">
                            <span style="font-size:1.3em;"><b>Month</b</span>
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

<hr style="margin-top:0px;"/>


<style>
    .panel-body{padding-left:7em; text-align: center;}
</style>
<!-- Element -->
<div class="row">
    <div class="col-md-12 load_data">

        <div class="panel-group accordion" id="accordion3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title" table="table_st_mydashboard" active="<?=($tab_st==''?'':'active')?>" style="<?=($tab_st==''?'':'background:#FFC000;')?>">
                    <a class="accordion-toggle collapsed" href="#tab_st" style="text-align:left;font-weight:bold;" data-toggle="collapse" data-parent="#accordion3" >
                        Strategic Themes (ST) 
                        <div style="float:right;margin-right:0.7em;"><i class="fa fa-arrow-up"></i></div>
                    </a>
                    </h4>
                </div>
                <div id="tab_st" class="panel-collapse collapse">
                    <div class="panel-body load_table_st_mydashboard"><?=(@$html_table_st_mydashboard==''?'No Data':'');?></div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title" table="table_sr_mydashboard" active="<?=($tab_sr==''?'':'active')?>" style="<?=($tab_sr==''?'':'background:#FFC000;')?>">
                    <a class="accordion-toggle collapsed" href="#tab_sr" style="text-align:left;font-weight:bold;" data-toggle="collapse" data-parent="#accordion3" >
                        Strategic Results (SR) 
                        <div style="float:right;margin-right:0.7em;"><i class="fa fa-arrow-up"></i></div>
                    </a>
                    </h4>
                </div>
                <div id="tab_sr" class="panel-collapse collapse">
                    <div class="panel-body load_table_sr_mydashboard"><?=(@$html_table_sr_mydashboard==''?'No Data':'');?></div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title" table="table_so_mydashboard" active="<?=($tab_so==''?'':'active')?>" style="<?=($tab_so==''?'':'background:#FFC000;')?>">
                    <a class="accordion-toggle collapsed" href="#tab_so" style="text-align:left;font-weight:bold;" data-toggle="collapse" data-parent="#accordion3" >
                        Strategic Objectives (SO)
                        <div style="float:right;margin-right:0.7em;"><i class="fa fa-arrow-up"></i></div>
                    </a>
                    </h4>
                </div>
                <div id="tab_so" class="panel-collapse collapse">
                    <div class="panel-body load_table_so_mydashboard"><?=(@$html_table_so_mydashboard==''?'No Data':'');?></div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title" table="table_kpi_so_mydashboard" active="<?=($tab_kpi_so==''?'':'active')?>" style="<?=($tab_kpi_so==''?'':'background:#FFC000;')?>">
                    <a class="accordion-toggle collapsed" href="#tab_kpi_so" style="text-align:left;font-weight:bold;" data-toggle="collapse" data-parent="#accordion3" >
                        KPI-Strategic Objectives (KPI-SO)  
                        <div style="float:right;margin-right:0.7em;"><i class="fa fa-arrow-up"></i></div>
                    </a>
                    </h4>
                </div>
                <div id="tab_kpi_so" class="panel-collapse collapse">
                    <div class="panel-body load_table_kpi_so_mydashboard"><?=(@$html_table_kpi_so_mydashboard==''?'No Data':'');?></div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title" table="table_si_mydashboard" active="<?=($tab_si==''?'':'active')?>" style="<?=($tab_si==''?'':'background:#FFC000;')?>">
                    <a class="accordion-toggle collapsed" href="#tab_si" style="text-align:left;font-weight:bold;" data-toggle="collapse" data-parent="#accordion3" >
                        Strategic Initiatives (SI) 
                        <div style="float:right;margin-right:0.7em;"><i class="fa fa-arrow-up"></i></div>
                    </a>
                    </h4>
                </div>
                <div id="tab_si" class="panel-collapse collapse">
                    <div class="panel-body load_table_si_mydashboard"><?=(@$html_table_si_mydashboard==''?'No Data':'');?></div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title" table="table_action_plan_mydashboard" active="<?=($tab_action_plan==''?'':'active')?>" style="<?=($tab_action_plan==''?'':'background:#FFC000;')?>">
                    <a class="accordion-toggle collapsed" href="#tab_action_plan" style="text-align:left;font-weight:bold;" data-toggle="collapse" data-parent="#accordion3" >
                        Action Plan & Sub-Action Plan 
                        <div style="float:right;margin-right:0.7em;"><i class="fa fa-arrow-up"></i></div>
                    </a>
                    </h4>
                </div>
                <div id="tab_action_plan" class="panel-collapse collapse">
                    <div class="panel-body load_table_action_plan_mydashboard"><?=(@$html_table_action_plan_mydashboard==''?'No Data':'');?></div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title" table="table_am_mydashboard" active="<?=($tab_am==''?'':'active')?>" style="<?=($tab_am==''?'':'background:#FFC000;')?>">
                    <a class="accordion-toggle collapsed" href="#tab_am" style="text-align:left;font-weight:bold;" data-toggle="collapse" data-parent="#accordion3" >
                        Action Minutes (AM)
                        <div style="float:right;margin-right:0.7em;"><i class="fa fa-arrow-up"></i></div>
                    </a>
                    </h4>
                </div>
                <div id="tab_am" class="panel-collapse collapse">
                    <div class="panel-body load_table_am_mydashboard"><?=(@$html_table_am_mydashboard==''?'No Data':'');?></div>
                </div>
            </div>
        </div>

    </div>
</div>


<script type="text/javascript">
$(document).ready(function() {

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

    //tipe filter
    window.change_filter = function (){
        
        //load data sesuai tab
        var table = $('#global_table').val();
        if(table != ''){
            var url = '<?=site_url($url)?>/load_'+table;
            var param = $('#form_filtering').serializeArray();
            Metronic.blockUI({ target: '.load_'+table,  boxed: true});
            $.post(url, param, function(msg){
                $('.load_'+msg.table).html(msg.html);
                Metronic.unblockUI('.load_'+msg.table);
            },'json');
        }

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

    //ganti panah drowpdown
    $('.panel-title').on('click', function(){

        //change arrow
        if($(this).find('.fa').hasClass('fa-arrow-down')){

            $(this).find('.fa-arrow-down').removeClass('fa-arrow-down').addClass('fa-arrow-up');
            
        }else{

            //load data sesuai tab
            var active = $(this).attr('active');
            if(active == 'active'){

                var table = $(this).attr('table');
                $('#global_table').val(table);
                var url = '<?=site_url($url)?>/load_'+table;
                var param = $('#form_filtering').serializeArray();
                Metronic.blockUI({ target: '.load_'+table,  boxed: true});
                $.post(url, param, function(msg){
                    $('.load_'+msg.table).html(msg.html);
                    Metronic.unblockUI('.load_'+msg.table);
                },'json');

            }

            $('.panel-title').find('.fa-arrow-down').removeClass('fa-arrow-down').addClass('fa-arrow-up');
            $(this).find('.fa-arrow-up').removeClass('fa-arrow-up').addClass('fa-arrow-down');
        }
        
    });
    

});
</script>