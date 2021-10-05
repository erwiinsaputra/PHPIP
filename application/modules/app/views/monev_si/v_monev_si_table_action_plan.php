<!-- Title -->
<div class="" style="margin-top:1.5em !important;">
    <div class="" style="text-align:center;">
        <div style="font-size:1.3em;"><b>ACTION PLAN</b></div>
        <div  style="font-size:1em;">(Milestone, Task, & KPI-SI) </div>
    </div>
</div>


<!-- Table monitoring -->
<div>
    <form method="post" id="form_action_plan_<?=@$id_si;?>_<?=@$year;?>" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
        <!-- inputan update pencapaian -->
        <input name="id_action_plan" id="id_action_plan_<?=@$id_si;?>_<?=@$year;?>" type="hidden" class="form-control">
        <input name="month" id="month_<?=@$id_si;?>_<?=@$year;?>" type="hidden" class="form-control">
        <input name="pencapaian" id="pencapaian_<?=@$id_si;?>_<?=@$year;?>" type="hidden" class="form-control">
        <!-- inputan update pencapaian -->
        <input name="id_action_plan_status" id="id_action_plan_status_<?=@$id_si;?>_<?=@$year;?>" type="hidden" class="form-control">
        <input name="status_year" id="status_year_<?=@$id_si;?>_<?=@$year;?>" type="hidden" class="form-control">
    </form>
    <div class="row" >
        <div class="col-md-12" style="margin-top:1em !important;">

            <div class="table_action_plan_<?=@$id_si;?>_<?=@$year;?>" style="font-size:0.8em !important;">
                <style type="text/css">
                    .table_action_plan thead tr th, .table_action_plan tbody tr td { white-space: nowrap;} 
                    .table_action_plan .thead_month{text-align:center;background:darkblue;color:white;font-size:1em;}
                    .table_action_plan .tbody_td{text-align:center;font-size:1em;background:lightgrey;color:black;} 
                    .table_action_plan .tbody_td2{text-align:center;font-size:1em;} 
                    .btn_issue, .btn_import{ font-size:1em !important; border-radius:7px !important;}
                </style>

                <table class="table table-bordered table-hover table_action_plan" id="table_action_plan_<?=@$id_si;?>_<?=@$year;?>">
                    <thead>
                        <tr>
                            <th class="thead_month">No</th>
                            <th class="thead_month">Action Plan</th>
                            <th class="thead_month">Deliverable</th>
                            <th class="thead_month">PIC<br>Action Plan</th>
                            <th class="thead_month">Start</th>
                            <th class="thead_month">End</th>
                            <th class="thead_month">Weighting<br>Factor&nbsp;(%)</th>
                            <?php for($m=1;$m<=12;$m++){?>
                                <th class="thead_month"><?=substr(h_month_name($m),0,3)?><br>(%)</th>
                            <?php } ?>
                            <th class="thead_month">Status</th>
                            <th class="thead_month">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $no=0; foreach($arr_action_plan as $row){ $no++;?>
                        <?php $id_sub = $row->id; $parent = $row->parent;?>
                        <?php $parent_color = ($parent == '0' ? "tbody_td" : "tbody_td2");?>
                        <?php $btn_action = ($parent == '0' ? "tbody_td" : "tbody_td2");?>
                            <tr>
                                <td class="<?=$parent_color?>"><?=str_replace('.0','.',$row->code);?></td>
                                <td class="<?=$parent_color?>"><?=h_text_br($row->name,50);?></td>
                                <td class="<?=$parent_color?>"><?=h_read_more($row->deliverable,20)?></td>
                                <td class="<?=$parent_color?>"><?=h_read_more($row->name_pic_action_plan.', '.$row->name_pic_action_plan2,20)?></td>
                                <td class="<?=$parent_color?>"><?=$row->start_date?></td>
                                <td class="<?=$parent_color?>"><?=$row->end_date?></td>
                                <td class="<?=$parent_color?>"><?=round($row->weighting_factor, 2, PHP_ROUND_HALF_DOWN)?></td>

                                <?php for($m=1;$m<=12;$m++){?>
                                    <?php if(@$parent == '0'){?>
                                    <td class="<?=$parent_color?>" style="padding:2px 0px 2px 0px !Important; background-color:lightgrey !important; border:0px;">
                                        <input <?=($tipe=='review'?'readonly="readonly"':'')?> readonly="readonly" value="<?=round(@$arr_month[$id_sub][$m]['pencapaian'],2)?>" idnya="<?=$id_sub?>" month="<?=$m?>" type="text" class="form-control percent" placeholder="0" style="text-align:center;padding:0px;font-size:1.2em;">
                                    </td>
                                    <?php }else{ ?>
                                        <?php
                                            for($y=substr($row->start_date,0,4); $y<=substr($row->end_date,0,4); $y++){
                                                $arr_year[] = $y;
                                            } 
                                            if(in_array($year,$arr_year)){
                                                $bgcolor_pencapaian ='white';
                                                $readonly_pencapaian = '';
                                            }else{
                                                $bgcolor_pencapaian = 'white';
                                                $readonly_pencapaian = '';
                                                // $readonly_pencapaian = 'readonly="readonly';
                                            }
                                        ?>
                                        <td class="<?=$parent_color?>" style="padding:2px 0px 2px 0px !Important; background-color:<?=$bgcolor_pencapaian?> !important; border:0px">
                                            <input <?=($tipe=='review'?'readonly="readonly"':'')?> <?=$readonly_pencapaian?> name="pencapaian[]" value="<?=round(@$arr_month[$id_sub][$m]['pencapaian'],2)?>" idnya="<?=$id_sub?>" month="<?=$m?>" type="text" class="form-control percent update_pencapaian" placeholder="0" style="text-align:center;padding:0px;font-size:1.2em;background:lightyellow;" autocomplete="off">
                                        </td>
                                    <?php } ?>
                                <?php } ?>


                                <?php if(@$parent == '0'){?>
                                    <td class="<?=$parent_color?>" style="font-size:1.2em;">
                                        <?php if(@$arr_year[$id_sub][$year]['status_complete'] == ''){ ?>
                                                Not Yet Started
                                        <?php }else{ ?>
                                            <?php foreach($status_complete as $row){ ?>
                                                <?=(@$arr_year[$id_sub][$year]['status_complete'] == $row->id ? $row->name : '')?>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                <?php }else{ ?>
                                    <?php
                                        for($y=substr($row->start_date,0,4); $y<=substr($row->end_date,0,4); $y++){
                                            $arr_year_periode[] = $y;
                                        } 
                                        if(in_array($year,$arr_year_periode)){
                                            $bgcolor_complete = 'white';
                                            $disabled_complete = '';
                                        }else{
                                            $bgcolor_complete = 'white';
                                            $disabled_complete = '';
                                            // $disabled_complete = 'disabled="disabled';
                                        }
                                    ?>
                                    <td class="<?=$parent_color?>" style="padding:0px !Important; background-color:<?=$bgcolor_complete?> !important; ">
                                        <select <?=($tipe=='review'?'disabled="disabled"':'')?> <?=$disabled_complete?> name="status_complete" idnya="<?=$id_sub?>" class="form-control select2_biasa update_status_complete" style="font-size:1.2em !important;">
                                            <?php foreach($status_complete as $row){ ?>
                                                <option <?=(@$arr_year[$id_sub][$year]['status_complete'] == $row->id ? 'selected' : '')?> value="<?=$row->id?>"><?=$row->name?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                <?php } ?>

                                <td class="<?=$parent_color?>" style="padding-top:5px !Important; padding-bottom:0px !Important; ">
                                    <?php if(@$parent != '0'){?>
                                        <button title="Issue" id_sub="<?=@$id_sub?>" class="btn btn-sm btn-primary btn_issue">Issue</button>
                                        <?php if($tipe=='monitoring'){?>
                                            <button title="Import" id_sub="<?=@$id_sub?>"class="btn btn-sm btn-warning btn_import">Import</button>
                                        <?php } ?>
                                    <?php } ?>
                                </td>

                            </tr>
                        <?php } ?>

                        <?php if(count($arr_action_plan) < 1){?>
                            <tr>
                                <td colspan="<?=($tipe=='monitoring'?'21':'20')?>" style="text-align:center;">Empty Data</td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>  
    </div>
</div>



<!-- Button calculation  -->
<div style="margin-top:1em !important;text-align:center;">
    <?php if($tipe=='monitoring'){?>
        <!-- <div style="text-align:right;margin-right:9em;margin-bottom:-2.15em;"> -->
            <button class="btn btn-sm btn-success btn_update_calculation" style="border-radius:5px !important;"><i class="fa fa-refresh"></i> &nbsp;Update Calculation </button>
        <!-- </div> -->
    <?php } ?>
    <!-- <div style="text-align:right;margin-right:1em;"> -->
        <button class="btn btn-sm btn-primary btn_all_issue"><i class="fa fa-book"></i> &nbsp;All Issue </button>
    <!-- </div> -->
</div>


<!-- Complete Overall -->
<div class="row" style="margin-top:3em !important;">
    <div class="col-md-12">
        <div class="table_complete_year_<?=@$id_si;?>_<?=@$year;?>">
            <style type="text/css">
                #table_complete_year_<?=@$id_si;?>_<?=@$year;?> thead tr th, #table_complete_year_<?=@$id_si;?>_<?=@$year;?> tbody tr td { white-space: nowrap;} 
                #table_complete_year_<?=@$id_si;?>_<?=@$year;?> .tbody_td{text-align:center;font-size:1em;background:lightgrey;color:black;} 
            </style>
            <table class="table table-bordered table-hover" id="table_complete_year_<?=@$id_si;?>_<?=@$year;?>">
                <tbody>
                    <tr>
                        <td class="tbody_td" style="width:30% !important;text-align:center;background:none;color:white;"></td>
                        <?php for($m=1;$m<=12;$m++){?>
                            <td class="tbody_td" style="background:darkblue;color:white;"><?=substr(h_month_name($m),0,3)?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td class="tbody_td" style="width:30% !important;text-align:center;background:#5B9BD5;color:white;">(%) Complete on Year</td>
                        <?php for($m=1;$m<=12;$m++){?>
                            <td class="tbody_td" style="background:#FFF2CC;color:black;"><?=(@$arr_complete_on_year[$m] == '' ? '0' : @$arr_complete_on_year[$m])?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td class="tbody_td" style="text-align:center;background:#68e060;color:white;">(%) Overall Complete</td>
                        <?php for($m=1;$m<=12;$m++){?>
                            <td class="tbody_td" style="background:#FFD966;color:black;"><?=(@$arr_overall_complete[$m] == '' ? '0' : @$arr_overall_complete[$m])?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td class="tbody_td" style="text-align:center;background:yellow;color:black;"><b>SI Status Color</b></td>
                        <?php for($m=1;$m<=12;$m++){?>
                            <td class="tbody_td" id="si_color_month_<?=@$id_si;?>_<?=@$year;?>_<?=$m?>" style="background:orange;color:black;padding:10px 0px 10px 0px !important;">
                                <?php if(@$arr_color[$m] == '4' || @$arr_color[$m] == ''){ ?> <span class="grey_box" style="font-size:1.2em;border:0px;">&nbsp;N&nbsp;</span> <?php } ?>
                                <?php if(@$arr_color[$m] == '5'){ ?> <span class="red_box" style="font-size:1.2em;border:0px;">&nbsp;R&nbsp;</span> <?php } ?>
                                <?php if(@$arr_color[$m] == '6'){ ?> <span class="yellow_box" style="font-size:1.2em;border:0px;">&nbsp;Y&nbsp;</span> <?php } ?>
                                <?php if(@$arr_color[$m] == '7'){ ?> <span class="green_box" style="font-size:1.2em;border:0px;">&nbsp;G&nbsp;</span> <?php } ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <!-- <tr>
                        <td class="tbody_td" style="text-align:center;background:yellow;color:black;">Cek Perhitungan</td>
                        <td class="tbody_td" colspan="12" style="background:yellow;color:black;padding:5px 0px 5px 0px !important;">
                            <div class="cek_perhitungan"></div>
                        </td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Table view si month -->
<div class="row" >
    <div class="col-md-12"  style="margin-top:0em !important;">

        <style>
            .tooltip2 { position: relative; display: inline-block;}
            .tooltip2 .tooltip2text { margin-top:-15em; visibility: hidden; width: 600px; background-color: black; color: #fff; border-radius: 6px;padding: 10px;position: absolute;z-index: 99999;}
            .tooltip2:hover .tooltip2text { visibility: visible;}

            .grey_box { height: 1.7em; width: 1.7em; border: 1px solid black; font-size: 1.5em; text-align: center; padding: 3px; color: white; background: grey;}
            .red_box { height: 1.7em; width: 1.7em; border: 1px solid black; font-size: 1.5em; text-align: center; padding: 3px; color: white; background: red;}
            .yellow_box { height: 1.7em; width: 1.7em; border: 1px solid black; font-size: 1.5em; text-align: center; padding: 3px; color: black; background: yellow;}
            .green_box { height: 1.7em; width: 1.7em; border: 1px solid black; font-size: 1.5em; text-align: center; padding: 3px; color: white; background: green;}
        </style>

        <table class="table table-bordered table-hover" id="table_view_si_month_<?=@$id_si;?>_<?=@$year;?>">
            <tbody>
                <tr style="background: darkblue;color:white;">
                    <td class="tbody" colspan="4" style="text-align:center;">
                            
                            <!-- ========================== Select Month =====================-->
                            <div style="display: flex; align-items: center; justify-content: center;">
                                <b style="font-size:1.3em;">Month : </b>
                                &nbsp; &nbsp;
                                <select id="si_month_<?=@$id_si;?>_<?=@$year;?>" name="si_month" class="form-control input-sm" style="font-size:1.2em !important;width:10em;">
                                    <?php for($m=1;$m<=12;$m++){?>
                                        <option <?=($month == $m ? 'selected' : '')?> 
                                                value="<?=$m?>" 
                                                month="<?=$m?>"
                                                color="<?=(@$arr_color[$m]==''?'4':@$arr_color[$m])?>" 
                                                keterangan="<?=@$arr_keterangan[$m]?>"
                                                status_complete="<?=@$arr_status_complete[$m]?>"
                                                status_approval="<?=@$arr_status_approval[$m]?>"
                                                keterangan_approval="<?=@$arr_keterangan_approval[$m]?>"
                                                
                                        ><?=h_month_name($m)?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            

                    </td>
                </tr>
                <tr style="background: #f5f5f5;">
                    <td class="tbody" style="width:10% !important;"><b>SI Status</b></td>
                    <td class="tbody" style="width:20% !important;">  
                        <div style="float:left;">
                            <input <?=($tipe=='review'?'readonly="readonly"':'')?> id="si_color_<?=@$id_si;?>_<?=@$year;?>" type="text" value="" class=""/>
                        </div>
                        <div style="float:left;margin-left:1em;">
                            <div class="tooltip2">
                                <button class="btn btn-sm btn-info" style="border-radius:100% !important;"><i class="fa fa-question"></i> </button>
                                <span class="tooltip2text">
                                    <span class="grey_box"> N </span> 
                                        &nbsp; Not Yet Defined:<br>
                                        <div style="margin-left:3em;">
                                            Status SI belum di definisikan.
                                        </div>
                                        <br>
                                    <span class="red_box"> R </span> 
                                        &nbsp; Red: <br>
                                        <div style="margin-left:3em;">
                                            <table>
                                                <tr>
                                                    <td style="vertical-align:top;">1.</td> 
                                                    <td>Terdapat Isu Strategis yang memerlukan pembahasan khusus bersama Direksi ketika SRM (Strategy Review Meeting) terkait progres pelaksanaan dari SI ini.</td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">2.</td> 
                                                    <td>Setidaknya terdapat satu Action Plan/Sub Action Plan  (Milestone) yang "missed" dan tidak terdapat rencana perbaikan.</td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">3.</td> 
                                                    <td>Pelaksanaan SI terhenti atau membutuhkan kordinasi di level Korporasi</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <br>
                                    <span class="yellow_box"> Y </span> 
                                        &nbsp; Yellow: <br>
                                        <div style="margin-left:3em;">
                                            Terdapat beberapa Action Plan / Sub Action Plan (Milestones) yang terancam pelaksanaannya dan berpotensi mundur dari rencana.
                                        </div>
                                        <br>
                                    <span class="green_box"> G </span> 
                                        &nbsp; Green: <br>
                                        <div style="margin-left:3em;">
                                            Semua Action Plan / Sub Action Plan (Milestones) masih On Track. Status SI masih On Time, Under Budget, dan semua Risiko terkait SI telah termitigasi. 
                                        </div>
                                        <br>
                                </span>
                            </div>
                        </div>
                        <div>
                            <select <?=($tipe=='review'?'disabled="disabled"':'')?> id="si_status_complete_<?=@$id_si;?>_<?=@$year;?>" class="form-control">
                                <?php   if(@$arr_status_complete[$month] == ''){ 
                                            $si_status_complete = '13';
                                        }else{ 
                                            $si_status_complete = @$arr_status_complete[$month]; 
                                        }?>
                                <?php foreach($status_complete as $row){ ?>
                                    <option <?=($row->id == $si_status_complete ? 'selected' : '')?> value="<?=$row->id?>"><?=$row->name?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td class="tbody" style="width:15% !important;">
                        <b>Performance Analysis & <br>Recommendation</b>
                    </td>
                    <td class="tbody" style="width:55% !important;">
                        <textarea <?=($tipe=='review'?'readonly="readonly"':'')?> id="si_keterangan_<?=@$id_si;?>_<?=@$year;?>" class="form-control" rows="3"><?=@$arr_keterangan[$month]?></textarea>
                    </td>
                </tr>
                
                <!-- cek review, apakah menu monev si atau menu lain dashboard -->

                <tr>
                    <td class="tbody load_btn_approval" colspan="4" style="background: #f5f5f5;"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



                        






<script src="<?= plugin_url('freeze_column/tableHeadFixer.js')?>" type="text/javascript" ></script>

<script type="text/javascript">
$(document).ready(function () {

    //scroll kebawah
    $('#popup_add').animate( { scrollTop: '+=4000' }, 1000);

    //freeze kolom
    $(".table_action_plan_<?=@$id_si;?>_<?=@$year;?>").find(".table_action_plan").tableHeadFixer({"top" : 1, "right" :<?=($tipe=='monitoring'?'14':'13')?>, "left" :2}); 
    $('.table_action_plan_<?=@$id_si;?>_<?=@$year;?>').animate( { scrollLeft: '+=400' }, 1000);
    // $('.table_action_plan_<?=@$id_si;?>_<?=@$year;?>').animate( { scrollLeft: '-=400' }, 1000);
    
    //format angka
    $('.table_action_plan_<?=@$id_si;?>_<?=@$year;?> .angka').inputmask('decimal', {
        alias: 'numeric',
        autoGroup: true,
        radixPoint:".", 
        groupSeparator: ",", 
        digits: 5,
        rightAlign : false,
        prefix: ''
    });

    //format percent
    $('.table_action_plan_<?=@$id_si;?>_<?=@$year;?> .percent').keyup(function () { 
        this.value = this.value.replace(/[^0-9\.]/g,''); 
        if(this.value > 100){ this.value = 100; }
    });


    //update status complete year
    $('.table_action_plan_<?=@$id_si;?>_<?=@$year;?>').on('change', '.update_status_complete', function(e) {

        //simpan data
        var idnya = $(this).attr('idnya');
        var val = $(this).val();

        var idnya_up = $('#id_action_plan_status_<?=@$id_si;?>_<?=@$year;?>').val();
        var val_up = $('#status_year_<?=@$id_si;?>_<?=@$year;?>').val();
        if(idnya_up == ''){
            var idnya_new = idnya;
            var val_new = val;
        }else{
            var idnya_new = idnya+'^'+idnya_up;
            var val_new = val+'^'+val_up;
        }
        $('#id_action_plan_status_<?=@$id_si;?>_<?=@$year;?>').val(idnya_new);
        $('#status_year_<?=@$id_si;?>_<?=@$year;?>').val(val_new);

    });

    //update status pencapaian month
    $('.table_action_plan_<?=@$id_si;?>_<?=@$year;?>').on('change', '.update_pencapaian', function(e) {

        //simpan data
        var idnya = $(this).attr('idnya');
        var month = $(this).attr('month');
        var val = $(this).val();

        var idnya_up = $('#id_action_plan_<?=@$id_si;?>_<?=@$year;?>').val();
        var month_up = $('#month_<?=@$id_si;?>_<?=@$year;?>').val();
        var val_up = $('#pencapaian_<?=@$id_si;?>_<?=@$year;?>').val();
        if(idnya_up == ''){
            var idnya_new = idnya;
            var month_new = month;
            var val_new = val;
        }else{
            var idnya_new = idnya+'^'+idnya_up;
            var month_new = month+'^'+month_up;
            var val_new = val+'^'+val_up;
        }
        $('#id_action_plan_<?=@$id_si;?>_<?=@$year;?>').val(idnya_new);
        $('#month_<?=@$id_si;?>_<?=@$year;?>').val(month_new);
        $('#pencapaian_<?=@$id_si;?>_<?=@$year;?>').val(val_new);
    });


    //btn_issue
    $('.table_action_plan_<?=@$id_si;?>_<?=@$year;?>').on('click', '.btn_issue', function(e) {
        $('#popup_issue').modal();
        var token  = $('#ex_csrf_token').val();
        var tipe = '<?=$tipe?>';
        var id_action_plan = $(this).attr('id_sub');
        var id_si = '<?=@$id_si;?>';
        var year = '<?=@$year;?>';
        var url = "<?=site_url($url);?>/load_issue";
        var param = {token:token, id_si:id_si, id_action_plan:id_action_plan, year:year, tipe:tipe};
        Metronic.blockUI({ target: '#load_issue',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_issue').html(msg);
            Metronic.unblockUI('#load_issue');
        });
    });

    //btn_all_issue
    $('.btn_all_issue').off().on('click', function(e) {
        $('#popup_issue').modal();
        var token  = $('#ex_csrf_token').val();
        var id_action_plan = '';
        var id_si = '<?=@$id_si;?>';
        var year = '<?=@$year;?>';
        var tipe = '<?=$tipe?>';
        var url = "<?=site_url($url);?>/load_issue";
        var param = {token:token, id_si:id_si, tipe:tipe, id_action_plan:id_action_plan, year:year};
        Metronic.blockUI({ target: '#load_issue',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_issue').html(msg);
            Metronic.unblockUI('#load_issue');
        });
    });


    //===============================================================================
    //btn_view_si_month
    //select2 biasa
    $(".table_action_plan_<?=@$id_si;?>_<?=@$year;?> .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //select view month
    $("#si_month_<?=@$id_si;?>_<?=@$year;?>").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) {
        
        //update view month
        Metronic.blockUI({ target: '#table_view_si_month_<?=@$id_si;?>_<?=@$year;?>',  boxed: true});
        var token  = $('#ex_csrf_token').val();
        var month = $('option:selected', this).attr('month');
        var color = $('option:selected', this).attr('color');
        var keterangan = $('option:selected', this).attr('keterangan');
        var status_complete = $('option:selected', this).attr('status_complete');
        var status_approval = $('option:selected', this).attr('status_approval');
        var keterangan_approval = $('option:selected', this).attr('keterangan_approval');

        if(color == ''){ color=1; }
        if(status_complete == ''){ status_complete=1; }
        
        $("#si_color_<?=@$id_si;?>_<?=@$year;?>").select2("val", color);
        $("#si_status_complete_<?=@$id_si;?>_<?=@$year;?>").select2("val", status_complete);
        $("#si_keterangan_<?=@$id_si;?>_<?=@$year;?>").val(keterangan);
        $("#si_status_approval_<?=@$id_si;?>_<?=@$year;?>").val(status_approval);
        $("#si_keterangan_approval_<?=@$id_si;?>_<?=@$year;?>").val(keterangan_approval);

        Metronic.unblockUI('#table_view_si_month_<?=@$id_si;?>_<?=@$year;?>');

        //load btn status approval sesuai month
        window.load_btn_approval(month,status_approval,keterangan_approval);

    });

    //si_status_complete
    $("#si_status_complete_<?=@$id_si;?>_<?=@$year;?>").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) { 
        // kosong
    });

    //si_color
    var data_html = [
        { id: 4, text: '<span class="grey_box">&nbsp;N&nbsp;</span>' },
        { id: 5, text: '<span class="red_box">&nbsp;R&nbsp;</span>' },
        { id: 6, text: '<span class="yellow_box">&nbsp;Y&nbsp;</span>' },
        { id: 7, text: '<span class="green_box">&nbsp;G&nbsp;</span>'},
    ];
    $("#si_color_<?=@$id_si;?>_<?=@$year;?>").select2({
      data: data_html,
      escapeMarkup: function(markup) { return markup; },
      dropdownAutoWidth : true,
      minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
      templateResult: function (d) { return $(d.text); },
      templateSelection: function (d) { return $(d.text); },
    }).on('change', function(event) { 
        // kosong
    });
    $("#si_color_<?=@$id_si;?>_<?=@$year;?>").select2("val", "<?=(@$arr_color[$month] == '' ? '4' : @$arr_color[$month])?>");


    //si_keterangan
    $('#si_keterangan_<?=@$id_si;?>_<?=@$year;?>').on('change', function(e) {
        // kosong
    });

    //btn update si month
    $('#table_view_si_month_<?=@$id_si;?>_<?=@$year;?>').on('click', '.btn_update_si_month', function(e) {
        var title = "Update Data Month !";
        var mes = "Are you sure to Update data ?";
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
            //update si month
            window.update_si_month();
        });
    });

    //update si
    window.update_si_month = function (){
        var token  = $('#ex_csrf_token').val();
        var month   = $("#si_month_<?=@$id_si;?>_<?=@$year;?>").val();
        var id_si   = "<?=@$id_si;?>";
        var year    = <?=@$year;?>;

        //ambil data
        var status_complete  = $("#si_status_complete_<?=@$id_si;?>_<?=@$year;?>").val();
        var color            = $("#si_color_<?=@$id_si;?>_<?=@$year;?>").val();
        var keterangan       = $("#si_keterangan_<?=@$id_si;?>_<?=@$year;?>").val();

        //change atribut
        $("option:selected","#si_month_<?=@$id_si;?>_<?=@$year;?>").attr('color',color);
        $("option:selected","#si_month_<?=@$id_si;?>_<?=@$year;?>").attr('keterangan',keterangan);
        $("option:selected","#si_month_<?=@$id_si;?>_<?=@$year;?>").attr('status_complete',status_complete);

        //color di si status color
        if(color == '4' || color == ''){ var color_span = '<span class="grey_box" style="font-size:1.2em;border:0px;">&nbsp;N&nbsp;</span>'; } 
        if(color == '5'){ var color_span = '<span class="red_box" style="font-size:1.2em;border:0px;">&nbsp;R&nbsp;</span>'; } 
        if(color == '6'){ var color_span = '<span class="yellow_box" style="font-size:1.2em;border:0px;">&nbsp;Y&nbsp;</span>'; } 
        if(color == '7'){ var color_span = '<span class="green_box" style="font-size:1.2em;border:0px;">&nbsp;G&nbsp;</span>'; } 
        $("#si_color_month_<?=@$id_si;?>_<?=@$year;?>_"+month).html(color_span);

        //update data
        var url              = "<?=site_url($url);?>/update_si_month";
        var param            = {token:token, id_si:id_si, year:year, month:month, 
                                    status_complete:status_complete, color:color, keterangan:keterangan
                                };
        Metronic.blockUI({ target: '.table_action_plan_<?=@$id_si;?>_<?=@$year;?>',  boxed: true});
        $.post(url, param, function(msg){
            toastr.options = call_toastr('4000');
            toastr['success']("Update Data Success", "Success");
            Metronic.unblockUI('.table_action_plan_<?=@$id_si;?>_<?=@$year;?>');
        });
    }
    
    //si_keterangan
    $('.btn_update_calculation').off().on('click', function(e) {
        var token  = $('#ex_csrf_token').val();
        var id_si = '<?=@$id_si;?>';
        var year = '<?=@$year;?>';
        //data pencapaian
        var id_action_plan = $('#id_action_plan_<?=@$id_si;?>_<?=@$year;?>').val();
        var month = $('#month_<?=@$id_si;?>_<?=@$year;?>').val();
        var pencapaian = $('#pencapaian_<?=@$id_si;?>_<?=@$year;?>').val();
        //data ststus
        var id_action_plan_status = $('#id_action_plan_status_<?=@$id_si;?>_<?=@$year;?>').val();
        var status_year = $('#status_year_<?=@$id_si;?>_<?=@$year;?>').val();
        //update data
        var url = "<?=site_url($url);?>/update_calculation";
        var param = {id_si:id_si, year:year, 
                        id_action_plan:id_action_plan, month:month, pencapaian:pencapaian, 
                        id_action_plan_status:id_action_plan_status, status_year:status_year, 
                    token:token};
        Metronic.blockUI({ target: '.table_action_plan_<?=@$id_si;?>_<?=@$year;?>',  boxed: true});
        $.post(url, param, function(msg){
            // $('.cek_perhitungan').html(msg);
            window.load_table_action_plan();
            Metronic.unblockUI('.table_action_plan_<?=@$id_si;?>_<?=@$year;?>');
        });
    });


    //btn change status
    $('#table_view_si_month_<?=@$id_si;?>_<?=@$year;?>').on('click', '.btn_change_status', function(e) {
        var id_si = '<?=$id_si?>';
        var year = '<?=$year?>';
        var month = $(this).attr('month');
        var val = $(this).attr('val');
        var title = $(this).attr('title');
        var keterangan = $('#keterangan_approval').val();
        var mes = "Are you sure to "+title+" ?";
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
                var token  = $('#ex_csrf_token').val();
                var url    = '<?=site_url($url)?>/change_status';
                var param  = {id_si:id_si, month:month, year:year, val:val, title:title, keterangan:keterangan, token:token};
                Metronic.blockUI({ target: '.load_btn_approval',  boxed: true});
                $.post(url, param, function(msg){
                    $('.load_btn_approval').html(msg);
                    toastr.options = call_toastr('4000');
                    toastr['success']("Update Success", "Success");
                    Metronic.unblockUI('.load_btn_approval');
                }, 'html');
        });
    });

    //btn change status
    $('.btn_change_status_approval').on('click', function(e) {
        var id_si = '<?=$id_si?>';
        var year = '<?=$year?>';
        var month = $(this).attr('month');
        var val = $(this).attr('val');
        var title = $(this).attr('title');
        var keterangan = $('#keterangan_approval').val();
        var mes = "Are you sure to "+title+" ?";
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
                var token  = $('#ex_csrf_token').val();
                var url    = '<?=site_url($url)?>/change_status';
                var param  = {id_si:id_si, month:month, year:year, val:val, title:title, keterangan:keterangan, token:token};
                Metronic.blockUI({ target: '#load_keterangan_approval',  boxed: true});
                $.post(url, param, function(msg){
                    $('.load_btn_approval').html(msg);
                    Metronic.unblockUI('#load_keterangan_approval');
                    toastr.options = call_toastr('4000');
                    toastr['success']("Update Success", "Success");
                    $('#popup_keterangan_approval').modal('hide');
                }, 'html');
        });
    });


    //btn keterangan approval
    $('#table_view_si_month_<?=@$id_si;?>_<?=@$year;?>').on('click', '.btn_keterangan_approval', function(e) {
        var id_si = '<?=$id_si?>';
        var year = '<?=$year?>';
        var month = $(this).attr('month');
        var val = $(this).attr('val');
        var title = $(this).attr('title');
        var keterangan = $(this).attr('keterangan');
        //tampilkan popup
        $('#popup_keterangan_approval').modal();
        //cek hanya view keterangan
        if (typeof val == typeof undefined) {
            $('#btn_approval_approve').hide();
            $('#btn_approval_reject').hide();
            $('#keterangan_approval').val(keterangan);
            $('#keterangan_approval').attr('disabled','disabled');
            return true;
        }else{
            //tampilkan tombol reject/approve
            if(val == '3'){
                $('#btn_approval_approve').show();
                $('#btn_approval_reject').hide();
            }
            if(val == '4'){
                $('#btn_approval_approve').hide();
                $('#btn_approval_reject').show();
            }
            $('#keterangan_approval').removeAttr('disabled');
        }
        //pindahkan parameter ke tombol di popup
        $('#keterangan_approval').val(keterangan);
        $('#btn_approval_approve, #btn_approval_reject').attr('month',month);
        $('#btn_approval_approve, #btn_approval_reject').attr('val',val);
        $('#btn_approval_approve, #btn_approval_reject').attr('title',title);
    });
   
    //scroll top
    // $(".scroll_top").scroll(function(){
    //     $(".table_action_plan_<?=@$id_si;?>_<?=@$year;?>")
    //         .scrollLeft($(".scroll_top").scrollLeft());
    // });
    // $(".table_action_plan_<?=@$id_si;?>_<?=@$year;?>").scroll(function(){
    //     $(".scroll_top").scrollLeft($(".table_action_plan_<?=@$id_si;?>_<?=@$year;?>").scrollLeft());
    // });

    // $(".table_action_plan_<?=@$id_si;?>_<?=@$year;?>").clone().html('.scroll_top');


    //load btn status approval sesuai month
    window.load_btn_approval = function(month,val,keterangan){
        var id_si   = '<?=$id_si?>';
        var year    = '<?=$year?>';
        var tipe    = '<?=$tipe?>';
        var token   = $('#ex_csrf_token').val();
        var url     = '<?=site_url($url)?>/change_status_month';
        var param   = {id_si:id_si, month:month, year:year, tipe:tipe, val:val, keterangan:keterangan, token:token};
        $.post(url, param, function(msg){
            $('.load_btn_approval').html(msg);
        }, 'html');
    }

    //load awal btn approval
    var month = $('option:selected', '#si_month_<?=@$id_si;?>_<?=@$year;?>').attr('month');
    var val = $('option:selected', '#si_month_<?=@$id_si;?>_<?=@$year;?>').attr('status_approval');
    var keterangan = $('option:selected', '#si_month_<?=@$id_si;?>_<?=@$year;?>').attr('keterangan_approval');
    window.load_btn_approval(month,val,keterangan);
    

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
    
});
</script>
