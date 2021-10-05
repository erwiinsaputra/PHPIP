<div style="height:200px; overflow-y:auto;">

    <div class="table_issue<?=@$id_si;?>">
        
        <div style="margin-top:1em;">
            <style type="text/css">
                .table_issue thead tr th, .table_issue thead tr td, .table_issue tbody tr td { white-space: nowrap;} 
                .table_issue .filter td{ padding: 0px !important; white-space: nowrap;}
                .table_issue .thead_month{text-align:center;background:darkblue;color:white;font-size:1em;}
                .table_issue .thead_month2{text-align:center;background:blue;color:white;font-size:1em;}
                .table_issue .tbody_td{text-align:center;font-size:1em;background:white;color:black;} 
                .table_issue .btn_detail_month{cursor:pointer;} 
            </style>
            
            <table class="table table-bordered table-hover table_issue" id="table_issue<?=@$id_si;?>">
                <thead>
                    <tr>
                        <th class="thead_month">No</th>
                        <th class="thead_month">Action Plan</th>
                        <th class="thead_month">Issue</th>
                        <th class="thead_month">Category</th>
                        <th class="thead_month">Issue Date</th>
                        <th class="thead_month">Follow Up</th>
                        <th class="thead_month">Executor</th>
                        <th class="thead_month">Email / HP</th>
                        <th class="thead_month">Due Date</th>
                        <th class="thead_month">Status</th>
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
                        <td class="tbody_td" style="text-align:left;background:lightgrey;"><?=$no?></td>
                        <td class="tbody_td" style="text-align:left;background:lightgrey;"><?=h_text_br(str_replace('.0','.',$row->name_action_plan),30)?></td>
                        <td class="tbody_td" style="text-align:left;background:lightgrey;"><?=h_text_br($row->issue,30)?></td>
                        <td class="tbody_td"><?=($row->category=='0'?'Internal':'External')?></td>
                        <td class="tbody_td"><?=str_replace(' ','<br>',$row->date_issue)?></td>
                        <td class="tbody_td" style="text-align:left;background:lightgrey;"><?=h_text_br($row->followup,30)?></td>
                        <td class="tbody_td"><?=h_text_br($name_executor,20)?></td>
                        <td class="tbody_td"><?=h_read_more($row->email,20)?><br><?=$row->no_hp?></td>
                        <td class="tbody_td"><?=str_replace(' 00:00:00','',$row->due_date)?></td>
                        <td class="tbody_td"><?=($row->status_issue=='0'?'Open':'Close')?></td>
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
    </div>

</div>

<script src="<?= plugin_url('freeze_column/tableHeadFixer.js')?>" type="text/javascript" ></script>

<script type="text/javascript">
$(document).ready(function () {

    //freeze kolom
    // $(".table_issue<?=@$id_si;?>").tableHeadFixer({"top" : 1,"left" : 1}); 

});
</script>