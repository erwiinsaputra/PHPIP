<div class="table_sub_action_plan<?=@$id;?>">
    
    <div style="margin-top:1em;">
        <style type="text/css">
            .table_sub_action_plan thead tr th, .table_sub_action_plan thead tr td, .table_sub_action_plan tbody tr td { white-space: nowrap;} 
            .table_sub_action_plan .filter td{ padding: 0px !important; white-space: nowrap;}
            .table_sub_action_plan .thead_month{text-align:center;background:darkblue;color:white;font-size:1em;}
            .table_sub_action_plan .thead_month2{text-align:center;background:blue;color:white;font-size:1em;}
            .table_sub_action_plan .tbody_td{text-align:center;font-size:1em;background:lightgrey;color:black;} 
            .table_sub_action_plan .tbody_td2{text-align:center;font-size:1em;} 
            .table_sub_action_plan .btn_detail_month{cursor:pointer;} 
        </style>
        
        <table class="table table-bordered table-hover table_sub_action_plan" id="table_sub_action_plan<?=@$id;?>">
            <thead>
                <tr>
                    <th class="thead_month">No</th>
                    <th class="thead_month">Action Plan</th>
                    <th class="thead_month">Deliverable</th>
                    <th class="thead_month">PIC<br>Action Plan</th>
                    <th class="thead_month">Start</th>
                    <th class="thead_month">End</th>
                    <th class="thead_month">Weighting<br>Factor&nbsp;(%)</th>
                    <th class="thead_month">Budget<br><?=$year?></th>
                </tr>
            </thead>
            <tbody>
                    <?php $no=0; foreach($arr_action_plan as $row){ $no++;?>
                    <?php $id = $row->id;?>
                    <?php $parent_color = ($row->parent == '0' ? "tbody_td" : "tbody_td2");?>
                    <?php $btn_action = ($row->parent == '0' ? "tbody_td" : "tbody_td2");?>

                    <?php 
                        //cek data parent dan sub sama
                        if(@$name == @$row->name && @$deliverable == @$row->deliverable){ continue;}
                        $name = @$row->name;
                        $deliverable = @$row->deliverable;
                    ?>
                        <tr>
                            <td class="<?=$parent_color?>"><?=str_replace('.0','.',$row->code);?></td>
                            <td class="<?=$parent_color?>"><?=h_text_br($row->name,50);?></td>
                            <td class="<?=$parent_color?>"><?=h_text_br($row->deliverable,50)?></td>
                            <td class="<?=$parent_color?>"><?=h_text_br($row->name_pic_action_plan.', '.$row->name_pic_action_plan2,20)?></td>
                            <td class="<?=$parent_color?>"><?=$row->start_date?></td>
                            <td class="<?=$parent_color?>"><?=$row->end_date?></td>
                            <td class="<?=$parent_color?>"><?=round($row->weighting_factor, 2, PHP_ROUND_HALF_DOWN)?></td>
                            <td class="<?=$parent_color?>"><?=number_format(round($row->budget_year,2),0,',','.');?></td>
                        </tr>
                    <?php } ?>
                    <?php if(count($arr_action_plan) < 1){?>
                        <tr>
                            <td class="tbody_td2" colspan="8">No Data</td>
                        </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>  
</div>

<script src="<?= plugin_url('freeze_column/tableHeadFixer.js')?>" type="text/javascript" ></script>

<script type="text/javascript">
$(document).ready(function () {

    //freeze kolom
    $(".table_sub_action_plan<?=@$id;?>").tableHeadFixer({"top" : 1,"left" : 2}); 

});
</script>