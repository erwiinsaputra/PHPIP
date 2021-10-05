<div class="table_kpi_so_year_<?=@$year;?>">

    <!-- <div class="row">
        <div class="col-md-12">
            <div class="form-group" style="text-align:center;margin-right:10px;">
                <label class="control-label"><b>Target Year : <?=@$target_year;?> <?=@$data->ukuran;?></b></label>
            </div>
        </div>
    </div> -->

    <div class="row">
        <div class="col-md-12">
            <div style="float:right;padding: 5px 5px 0px 5px; border-radius: 6px !important;;">
                <label style="font-size:1.3em;cursor:pointer;"><input type="checkbox" class="cek_prognosa"> <b>Show Prognosa</b></label>
            </div>
            <div style="float:right;padding: 5px 5px 0px 5px; border-radius: 6px !important;margin-bottom: 0.5em;">
                <label style="font-size:1.3em;cursor:pointer;"><input type="checkbox" class="cek_triwulan"> <b>Show Triwulan</b></label>
            </div>

            <script type="text/javascript">
            $(document).ready(function () {
                //cek show prognosa
                $('.table_kpi_so_year_<?=@$year;?> .cek_prognosa').die().live('click',function(){
                    if($(this).attr('checked') == 'checked'){
                        var new_colspan = parseFloat($('.colspan_month').attr('colspan')) + 2;
                        $('.colspan_month').attr('colspan', new_colspan);
                        if($('.cek_triwulan').attr('checked') == 'checked'){
                            $('.prognosa').hide();
                            $('.prognosa_3').show();
                            $('.prognosa_6').show();
                            $('.prognosa_9').show();
                            $('.prognosa_12').show();
                        }else{
                            $('.prognosa').show();
                        }
                    }else{
                        var new_colspan = parseFloat($('.colspan_month').attr('colspan')) - 2;
                        $('.colspan_month').attr('colspan', new_colspan);
                        $('.prognosa').hide();
                    }

                });
                //cek show triwulan
                $('.cek_triwulan').die().live('click',function(){
                    if($(this).attr('checked') == 'checked'){
                        $('.month').hide();
                        $('.month_3').show();
                        $('.month_6').show();
                        $('.month_9').show();
                        $('.month_12').show();
                        if($('.table_kpi_so_year_<?=@$year;?> .cek_prognosa').attr('checked') == 'checked'){
                            $('.prognosa').hide();
                            $('.prognosa_3').show();
                            $('.prognosa_6').show();
                            $('.prognosa_9').show();
                            $('.prognosa_12').show();
                        }else{
                            $('.prognosa').hide();
                        }
                    }else{
                        $('.month').show();
                        if($('.table_kpi_so_year_<?=@$year;?> .cek_prognosa').attr('checked') == 'checked'){
                            $('.prognosa').show();
                        }else{
                            $('.prognosa').hide();
                        }
                    }
                });

                //cek default show triwulan
                $('.cek_triwulan').trigger('click');

            });
            </script>


        </div>
    </div>
    
    <div style="margin-top:1em;">
        <style type="text/css">
            .table_kpi_so thead tr th, .table_kpi_so thead tr td, .table_kpi_so tbody tr td { white-space: nowrap;} 
            .table_kpi_so .filter td{ padding: 0px !important; white-space: nowrap;}
            .table_kpi_so .thead_month{text-align:center;background:darkblue;color:white;font-size:1em;}
            .table_kpi_so .thead_month2{text-align:center;background:blue;color:white;font-size:1em;}
            .table_kpi_so .tbody_td{text-align:center;font-size:1em;} 
            .table_kpi_so .btn_detail_month{cursor:pointer;} 
        </style>
        
        <table class="table table-bordered table-hover table_kpi_so" id="table_kpi_so">
            <thead>
                <tr>
                    <th rowspan="3" class="thead_month">Code<br>KPI-SO</th>
                    <th rowspan="3" class="thead_month">KPI-SO</th>
                    <th rowspan="3" class="thead_month">Pol</th>
                    <th rowspan="3" class="thead_month">PIC<br>KPI-SO</th>
                    <th rowspan="3" class="thead_month">Ukuran</th>
                    <th rowspan="3" class="thead_month">Annual<br>target</th>
                    <?php for($m=1;$m<=12;$m++){?>
                        <th colspan="3" class="thead_month colspan_month month month_<?=$m?>"><?=h_month_name($m)?></th>
                    <?php } ?>
                </tr>
                <tr>
                    <?php for($m=1;$m<=12;$m++){?>
                        <th class="thead_month2 month month_<?=$m?>">Target</th>
                        <th class="thead_month2 month month_<?=$m?>">Realisasi</th>
                        <th class="thead_month2 month month_<?=$m?>">Pencapaian</th>
                        <th class="thead_month2 month month_<?=$m?> prognosa prognosa_<?=$m?>" style="display:none;">Prognosa </th>
                        <th class="thead_month2 month month_<?=$m?> prognosa prognosa_<?=$m?>" style="display:none;">Prognosa(%)</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php $no=0; foreach($arr_kpi_so as $row){ $no++;?>
                    <tr>
                        <td class="tbody_td"><?=$row->code_kpi_so;?></td>
                        <td class="tbody_td"><?=h_text_br($row->name_kpi_so,50);?></td>
                        <td class="tbody_td">
                            <?php 
                                $name_polarisasi = ''; 
                                if(@$row->polarisasi == '10' ){ 
                                    $name_polarisasi = '<img src="'.img_url('arrow/right.png').'" width="30em;"> <img src="'.img_url('arrow/left.png').'" width="30em;" style="margin-left:-1.5em;">'; 
                                }elseif(@$row->polarisasi == '8'){ 
                                    $name_polarisasi = '<img src="'.img_url('arrow/up.png').'" width="20em;">'; 
                                }elseif(@$row->polarisasi == '9'){ 
                                    $name_polarisasi = '<img src="'.img_url('arrow/down.png').'" width="20em;">'; 
                                }
                            ?>
                            <?=$name_polarisasi;?>
                        </td>
                        <td class="tbody_td"><?=h_text_br($row->name_pic_kpi_so,20)?></td>
                        <td class="tbody_td"><?=$row->ukuran?></td>

                        <?php 
                            if($row->polarisasi == '10'){
                                $arr_target_from = explode(', ',$row->arr_target_from);
                                $arr_target_to  = explode(', ',$row->arr_target_to); 
                                $z=-1;foreach($arr_target_from as $val){ $z++;
                                    echo '<td class="tbody_td">'.$val.' - '.@$arr_target_to[$z].'</td>';
                                }
                            }else{
                                $arr_target = explode(', ',$row->arr_target);
                                foreach($arr_target as $val){
                                    echo '<td class="tbody_td">'.h_format_angka($val).'</td>';
                                } 
                            } 
                        ?>

                        <?php for($m=1;$m<=12;$m++){?>
                            <td class="tbody_td btn_detail_month month month_<?=$m?>" month="<?=$m?>" month_name="<?=h_month_name($m)?>" idnya="<?=$row->id?>">
                                <?=h_format_angka(@$month[$row->id][$m]['target'])?>
                            </td>
                            <td class="tbody_td btn_detail_month month month_<?=$m?>" month="<?=$m?>" month_name="<?=h_month_name($m)?>" idnya="<?=$row->id?>">
                                <?=(@$month[$row->id][$m]['realisasi'] == '' ? 'N' : h_format_angka(@$month[$row->id][$m]['realisasi']))?> 
                            </td>
                            <?php 
                                $huruf = (@$month[$row->id][$m]['pencapaian'] == '' ? 'N' : h_format_angka(@$month[$row->id][$m]['pencapaian']).' %');
                                $warna = (@$month[$row->id][$m]['color'] =='' ? 'background:grey;color:white;' : @$month[$row->id][$m]['color']); 
                                if(@$month[$row->id][$m]['target'] == ''){
                                  $warna = 'background:black;color:white;';
                                  $huruf = 'B';
                                }
                            ?>
                            <td class="tbody_td btn_detail_month month month_<?=$m?>" month="<?=$m?>" month_name="<?=h_month_name($m)?>" idnya="<?=$row->id?>"
                                style="<?=$warna?>">
                                <span class="label" style="font-size:1em; <?=$warna?>">
                                    <?=$huruf?> 
                                </span>
                            </td>
                            <td class="tbody_td btn_detail_month month month_<?=$m?> prognosa prognosa_<?=$m?>" month="<?=$m?>" month_name="<?=h_month_name($m)?>" idnya="<?=$row->id?>"
                                style="display:none;">
                                <?=(@$month[$row->id][$m]['prognosa'] == '' ? 'N' : h_format_angka(@$month[$row->id][$m]['prognosa']))?> 
                            </td>
                            <?php 
                                $huruf = (@$month[$row->id][$m]['prognosa_pencapaian'] == '' ? 'N' : h_format_angka(@$month[$row->id][$m]['prognosa_pencapaian']).' %');
                                $warna = (@$month[$row->id][$m]['prognosa_color'] =='' ? 'background:grey;color:white;' : @$month[$row->id][$m]['prognosa_color']); 
                                if(@$month[$row->id][$m]['target'] == ''){
                                  $warna = 'background:black;color:white;';
                                  $huruf = 'B';
                                }
                            ?>
                            <td class="tbody_td btn_detail_month month month_<?=$m?> prognosa prognosa_<?=$m?>" month="<?=$m?>" month_name="<?=h_month_name($m)?>" idnya="<?=$row->id?>" 
                                style="display:none; <?=$warna?>">
                                <span class="label" style="font-size:1em; <?=$warna?>">
                                    <?=$huruf?> 
                                </span>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>  
</div>

<script src="<?= plugin_url('freeze_column/tableHeadFixer.js')?>" type="text/javascript" ></script>

<script type="text/javascript">
$(document).ready(function () {

    $(".table_kpi_so_year_<?=@$year;?> #table_kpi_so").tableHeadFixer({"top" : 2,"left" : 3}); 

    //load edit triwulan
    $('.btn_edit_triwulan').off().on('click', function(e) {
        $('#popup_edit_triwulan').modal();
        var id  = $(this).attr('id_triwulan');
        var url = "<?=site_url($url);?>/load_edit_triwulan";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_edit_triwulan',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_edit_triwulan').html(msg);
            Metronic.unblockUI('#load_edit_triwulan');
        });
    });

    //load edit Month
    $('.btn_detail_month').off().on('dblclick', function(e) {
        $('#popup_detail_month').modal();
        var id_kpi_so = $(this).attr('idnya');
        var month = $(this).attr('month');
        var month_name = $(this).attr('month_name');
        var year  = <?=@$year;?>;
        var url = "<?=site_url($url);?>/load_detail_month";
        var param = {id_kpi_so:id_kpi_so, month:month, year:year};
        Metronic.blockUI({ target: '#load_detail_month',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_detail_month').html(msg);
            Metronic.unblockUI('#load_detail_month');
        });
        $('#popup_detail_month').find('.modal-title').html('<b>Detail '+month_name+'</b>');
    });
    
    //btn change status
    $('.table_kpi_so_year_<?=$year?>').on('click', '.btn_change_status', function(e) {
        var id = $(this).attr('id');
        var val = $(this).attr('val');
        var status = $(this).attr('title');
        var mes = "Are you sure to "+status+" ?";
        var title = status;
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
                var param  = {id:id, val:val, token:token};
                $.post(url, param, function(msg){
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        window.reload_triwulan();
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                }, 'json');
        });
    });


    //show triwulan
    var triwulan = "<?=@$triwulan?>";
    if(triwulan != ''){
        $('.month').hide();
        $('.month_'+triwulan).show();
        $('.prognosa_'+triwulan).hide();
    }

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>