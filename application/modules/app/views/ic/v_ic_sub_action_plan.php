<div class="sub_action_plan<?=@$id;?>">
    

    <div style="text-align:center;margin-top:1em;">
        <?php if($view == ''){?>
            <div class="" style="float:right;margin-right:1em;margin-top:1em;">
                <a href="javascript:" id="<?=@$id;?>" class="btn btn-sm btn-primary btn_add_sub"><i class="fa fa-plus"></i> Add Sub Action Plan</a>
            </div>
        <?php } ?>
        &nbsp; &nbsp; 
        <div class="" style="margin-left:10em;">
            <label class="label label-danger" style="font-size:1.5em;color:white;">Total Weighting Factor:</label> 
            <label class="label label-danger" style="font-size:1.5em;background:black;color:white;"><span class="total_sub_weighting_factor"> ---</span> %</label> 
        </div>
    </div>

    

    <div style="margin-top:2em;" class="table_sub_action_plan<?=@$id;?>">
        <style type="text/css">
            .table_sub_action_plan thead tr th, .table_sub_action_plan tbody tr td { white-space: nowrap;} 
            .table_sub_action_plan .thead_month{text-align:center;background:darkblue;color:white;font-size:1em;}
            .table_sub_action_plan .tbody_td{text-align:center;font-size:1em;background:lightgrey;color:black;} 
            .table_sub_action_plan .tbody_td2{text-align:center;font-size:1em;} 
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
                    <?php for($y=$start_year;$y<=$end_year;$y++){?>
                        <th class="thead_month">Budget<br><?=$y?></th>
                    <?php } ?>
                    <?php if($view == ''){?>
                        <th class="thead_month">Action</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tbody_td"><?=$action_plan->code;?></td>
                    <td class="tbody_td"><?=h_text_br($action_plan->name,50);?></td>
                    <td class="tbody_td"><?=h_read_more($action_plan->deliverable,20)?></td>
                    <td class="tbody_td"><?=h_read_more($action_plan->name_pic_action_plan.', '.$action_plan->name_pic_action_plan2,20)?></td>
                    <td class="tbody_td"><?=$action_plan->start_date?></td>
                    <td class="tbody_td"><?=$action_plan->end_date?></td>
                    <td class="tbody_td"><?=round( $action_plan->weighting_factor, 2, PHP_ROUND_HALF_DOWN)?></td>
                    <?php for($y=$start_year;$y<=$end_year;$y++){?>
                        <td class="tbody_td"><?=@$budget[$id][$y]?></td>
                    <?php } ?>
                    <?php if($view == ''){?>
                        <td class="tbody_td">&nbsp;</td>
                    <?php } ?>
                </tr>
                <?php $no=0; foreach($sub_action_plan as $row){ $no++;?>
                <?php $id_sub = $row->id;?>
                    <tr>
                        <td class="tbody_td2"><?=str_replace('.0','.',$row->code);?></td>
                        <td class="tbody_td2"><?=h_text_br($row->name,50);?></td>
                        <td class="tbody_td2"><?=h_read_more($row->deliverable,20)?></td>
                        <td class="tbody_td2"><?=h_read_more($row->name_pic_action_plan.', '.$row->name_pic_action_plan2,20)?></td>
                        <td class="tbody_td2"><?=$row->start_date?></td>
                        <td class="tbody_td2"><?=$row->end_date?></td>
                        <td class="tbody_td2"><?=round($row->weighting_factor, 2, PHP_ROUND_HALF_DOWN)?></td>
                        <?php for($y=$start_year;$y<=$end_year;$y++){?>
                            <td class="tbody_td2"><?=@$budget[$id_sub][$y]?></td>
                        <?php } ?>
                        <?php if($view == ''){?>
                            <td class="tbody_td2">
                                <button title="Edit Sub" id="<?=@$id_sub?>" class="btn btn-sm btn-primary btn_edit_sub"><i class="fa fa-edit"></i></button>
                                <button title="Delete Sub" id="<?=@$id_sub?>" val="f" class="btn btn-sm btn-danger btn_delete_sub"><i class="fa fa-remove"></i></button>
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

    //freeze kolom
    $(".table_sub_action_plan").tableHeadFixer({"top" : 1, "right" :1, "left" :2}); 
    $('.table_sub_action_plan<?=@$id;?>').animate( { scrollLeft: '+=400' }, 1000);

    //load add sub 
    $('.btn_add_sub').off().on('click', function(e) {
        $('#popup_add_sub').modal();
        var id = $(this).attr('id');
        var url = "<?=site_url($url);?>/load_add_sub";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_add_sub',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_add_sub').html(msg);
            Metronic.unblockUI('#load_add_sub');
        });
    });

    //load edit
    $('.table_sub_action_plan').on('click', '.btn_edit_sub', function(e) {
        $('#popup_edit_sub').modal();
        var id = $(this).attr('id');
        var url = "<?=site_url($url);?>/load_edit_sub";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_edit_sub',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_edit_sub').html(msg);
            Metronic.unblockUI('#load_edit_sub');
        });
    });

     //btn delete
     $('.table_sub_action_plan').on('click', '.btn_delete_sub', function(e) {
        var id = $(this).attr('id');
        var val = $(this).attr('val');
        if(val == 't'){
            var mes = "Are you sure to Active Data?";
        }else{
            var mes = "Are you sure to DELETE Data?";
        }
        var title = "Are You Sure ?";
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
                var url    = '<?=site_url($url)?>/delete_sub_action_plan';
                var param  = {id:id, val:val, token:token};
                $.post(url, param, function(msg){
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        window.reload_table_sub_action_plan(msg.id);
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                }, 'json');
        });
    });

    //get total sub weight factor
    window.get_total_sub_weighting_factor = function(){
        var url = "<?=site_url($url);?>/get_total_weighting_factor";
        var id_sub_si = '<?=@$id;?>';
        if(id_sub_si == ''){
            $('.total_sub_weighting_factor').html(' 0');
        }else{
            var url = "<?=site_url($url);?>/get_total_sub_weighting_factor";
            var param = {id_sub_si:id_sub_si};
            $.post(url, param, function(msg){
                $('.total_sub_weighting_factor').html(msg.val);
            },'json');
        }
    }
    window.get_total_sub_weighting_factor();

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>