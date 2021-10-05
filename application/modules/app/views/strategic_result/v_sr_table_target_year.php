<style>
    #load_table_target_kpi_so>tbody>tr>td{
        padding: 1px;
    }
</style>
<table class="table table-bordered" id="load_table_target_kpi_so">
    <thead>
        <tr>
            <th rowspan="2" style="text-align:center;background:darkblue;color:white;">Year</th>
            <th rowspan="2" style="text-align:center;background:darkblue;color:white;">Target<br>Year</th>
            <th colspan="2" style="text-align:center;background:darkblue;color:white;">Target&nbsp;Quarter</th>
        </tr>
        <tr>
            <?php for($m=1;$m<=12;$m++){ ?>
                <?php if($m == '6' || $m == '12'){ ?>
                <?php $q = ($m == '6' ? '2' : '4'); ?>
                <th style="text-align:center;background:darkblue;color:white;"><?=$q?></th>
                <?php } ?>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php for($y=$start_year;$y<=$end_year;$y++){ ?>
        <tr>
            <td style="padding: 8px;"><b><?=$y?></b></td>
            <td>
                <div class="input_single" style="<?=($polarisasi == '10' ? 'display:none;':'')?>">
                    <input <?=$readonly?> value="<?=@$target[$y]?>" name="target_<?=$y?>" type="text" class="form-control angka" placeholder="0" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                </div>
                <div class="input_range" style="<?=($polarisasi == '10' ? '' : 'display:none;')?>">
                    <input <?=@$readonly?>value="<?=@$target_from[$y]?>" name="target_from_<?=$y?>" type="text" class="form-control angka" placeholder="From" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                    <input <?=@$readonly?> value="<?=@$target_to[$y]?>" name="target_to_<?=$y?>" type="text" class="form-control angka" placeholder="To" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                </div>
            </td>
            <?php for($m=1;$m<=12;$m++){ ?>
            <?php if($m == '6' || $m == '12'){ ?>
                <td>
                    <div class="input_single" style="<?=($polarisasi == '10' ? 'display:none;':'')?>">
                        <input <?=@$readonly?> value="<?=@$target_month[$y][$m]?>" name="target_month_<?=$y?>_<?=$m?>" type="text" class="form-control angka" placeholder="0" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                    </div>
                    <div class="input_range" style="<?=($polarisasi == '10' ? '' : 'display:none;')?>">
                        <input <?=@$readonly?> value="<?=@$target_month_from[$y][$m]?>" name="target_month_from_<?=$y?>_<?=$m?>" type="text" class="form-control angka" placeholder="From" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                        <input <?=@$readonly?> value="<?=@$target_month_to[$y][$m]?>" name="target_month_to_<?=$y?>_<?=$m?>" type="text" class="form-control angka" placeholder="To" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
                    </div>
                </td>
            <?php } ?>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>