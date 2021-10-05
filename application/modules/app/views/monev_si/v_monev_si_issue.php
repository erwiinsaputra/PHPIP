<?php if($tipe=='monitoring'){?>
<div style="text-align:center;margin-top:1em;">
    <div class="" style="margin-center:1em;margin-top:1em;margin-bottom:1em;">
        <a href="javascript:" class="btn btn-sm btn-primary btn_add_issue_<?=@$year?>_<?=@$id_si?>"><i class="fa fa-plus"></i> Add Issue</a>
    </div>
</div>
<?php } ?>
<div class="load_table_issue">
    <?=@$html_load_table_issue?>
</div>
<script type="text/javascript">
$(document).ready(function () {

    
    //load add issue 
   $('.btn_add_issue_<?=@$year?>_<?=@$id_si?>').off().on('click', function(e) {
        $('#popup_add_issue').modal();
        var id_action_plan = '<?=@$id_action_plan?>';
        var id_si   = '<?=@$id_si?>';
        var year    = '<?=@$year?>';
        var url     = "<?=site_url($url);?>/load_add_issue";
        var param   = {id_action_plan:id_action_plan, id_si:id_si, year:year};
        Metronic.blockUI({ target: '#load_add_issue',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_add_issue').html(msg);
            Metronic.unblockUI('#load_add_issue');
        });
    });

   //load edit issue
   $('.table_issue_<?=@$year?>_<?=@$id_si?>').on('click', '.btn_edit_issue', function(e) {
        $('#popup_edit_issue').modal();
        var id = $(this).attr('id');
        var id_action_plan = '<?=@$id_action_plan?>';
        var year = '<?=@$year?>';
        var id_si = '<?=@$id_si?>';
        var url = "<?=site_url($url);?>/load_edit_issue";
        var param = {id:id, id_action_plan:id_action_plan, id_si:id_si, year:year};
        Metronic.blockUI({ target: '#load_edit_issue',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_edit_issue').html(msg);
            Metronic.unblockUI('#load_edit_issue');
        });
    });

    //btn delete
    $('.table_issue_<?=@$year?>_<?=@$id_si?>').on('click', '.btn_delete_issue', function(e) {
        var id = $(this).attr('id');
        var mes = "Are you sure to DELETE Data?";
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
                var url    = '<?=site_url($url)?>/delete_issue';
                var param  = {id:id, token:token};
                $.post(url, param, function(msg){
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        window.reload_table_issue('<?=@$id_si?>','<?=@$id_action_plan?>','<?=@$year?>');
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                }, 'json');
        });
    });
    
    
    //==============================================================================


    //cek total total_weighting_factor
    window.reload_table_issue = function(id_si,id_action_plan,year){
        var url = "<?=site_url($url);?>/load_table_issue";
        var param = {id_si:id_si,id_action_plan:id_action_plan,year:year};
        Metronic.blockUI({ target: '.load_table_issue',  boxed: true});
        $.post(url, param, function(msg){
            $('.load_table_issue').html(msg);
            Metronic.unblockUI('.load_table_issue');
        });
    }


    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');

});
</script>