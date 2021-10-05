<div class="table_issue_<?=@$year?>_<?=@$id_si?>" style="font-size:0.8em !important;">
    <style type="text/css">
        .table_issue_<?=@$year?>_<?=@$id_si?> thead tr th, .table_issue_<?=@$year?>_<?=@$id_si?> tbody tr td { white-space: nowrap;} 
        .table_issue_<?=@$year?>_<?=@$id_si?> .thead_month{text-align:center;background:darkblue;color:white;font-size:1em;}
        .table_issue_<?=@$year?>_<?=@$id_si?> .tbody_td{text-align:center;font-size:1em;} 
        .btn_issue, .btn_import{ font-size:1em !important; border-radius:7px !important;}
    </style>

    <table class="table table-bordered table-hover" id="table_issue_<?=@$year?>_<?=@$id_si?>">
        <thead>
            <tr>
                <th class="thead_month">No</th>
                <th class="thead_month">Action Plan</th>
                <th class="thead_month">Issue</th>
                <th class="thead_month">Category</th>
                <th class="thead_month">Issue Date</th>
                <th class="thead_month">Follow Up</th>
                <th class="thead_month">Executor</th>
                <th class="thead_month">Email/No HP</th>
                <th class="thead_month">Due Date</th>
                <th class="thead_month">Status</th>
                <?php if($tipe=='monitoring'){?>
                    <th class="thead_month">Action</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
           
            <?php $no=0; foreach($arr_issue as $row){ $no++;?>
                <tr>
                    <?php
                    //executor
                    if($row->executor > 0){
                        $name_executor = $row->name_executor;
                    }else{
                        $name_executor = $row->executor;
                    }
                    ?>
                    <td class="tbody_td" style="text-align:left;"><?=$no?></td>
                    <td class="tbody_td" style="text-align:left;"><?=h_text_br(str_replace('.0','.',$row->name_action_plan),30)?></td>
                    <td class="tbody_td" style="text-align:left;"><?=h_text_br($row->issue,30)?></td>
                    <td class="tbody_td"><?=($row->category=='0'?'Internal':'External')?></td>
                    <td class="tbody_td"><?=str_replace(' ','<br>',$row->date_issue)?></td>
                    <td class="tbody_td" style="text-align:left;"><?=h_text_br($row->followup,30)?></td>
                    <td class="tbody_td"><?=h_text_br($name_executor,20)?></td>
                    <td class="tbody_td"><?=h_read_more($row->email,20)?><br><?=($row->no_hp == '0' ? '-' : $row->no_hp)?></td>
                    <td class="tbody_td"><?=str_replace(' 00:00:00','',$row->due_date)?></td>
                    <td class="tbody_td"><?=($row->status_issue=='0'?'Open':'Close')?></td>
                    <?php if($tipe=='monitoring'){?>
                    <td class="tbody_td">
                        <button title="Edit" id="<?=@$row->id?>" id_action_plan="<?=@$row->id_action_plan?>" class="btn btn-sm btn-primary btn_edit_issue"><i class="fa fa-edit"></i></button>
                        <button title="Delete" id="<?=@$row->id?>" class="btn btn-sm  btn-danger btn_delete_issue"><i class="fa fa-remove"></i></button>
                    </td>
                    <?php } ?>
                </tr>
                <?php } ?>

                <?php if(count(@$arr_issue) == 0){?>
                <tr>
                    <td style="text-align:center;" colspan="11">Empty Data</td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
</div>



<script type="text/javascript">
$(document).ready(function () {
    //freeze kolom
    $("#table_issue_<?=@$year?>_<?=@$id_si?>").tableHeadFixer({"top" : 1, "right" :1, "left" :1}); 
    $('.table_issue_<?=@$year?>_<?=@$id_si?>').animate( { scrollLeft: '+=300' }, 300);
    $('.table_issue_<?=@$year?>_<?=@$id_si?>').animate( { scrollLeft: '-=300' }, 10);
});
</script>

