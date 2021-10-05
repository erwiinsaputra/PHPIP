<style>
    #load_table_budget_ic>tbody>tr>td{
        padding: 1px;
    }
</style>
<table class="table table-bordered" id="load_table_budget_ic" style="width: 94%;">
    <!-- <thead>
        <tr>
            <th style="text-align:center;background:darkblue;color:white;">&nbsp;</th>
            <th style="text-align:center;background:darkblue;color:white;">Budget</th>
        </tr>
    </thead> -->
    <tbody>
        <?php for($y=$start_year;$y<=$end_year;$y++){ ?>
        <tr>
            <td style="width:25%;text-align:center;vertical-align:middle;background:darkgreen;color:white;"><b>Budget <?=$y?></b></td>
            <td style="text-align:center;">
                <input <?=$readonly?> value="<?=@$budget[$y]?>" name="budget_<?=$y?>" type="text" class="form-control angka" placeholder="0" data-bvalidator="" autocomplete="off" style="text-align:center !important;">
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>