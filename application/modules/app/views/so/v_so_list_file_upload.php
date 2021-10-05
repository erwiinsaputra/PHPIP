<?php $i=0; foreach ($file_so as $rows) { $i++; ?>
    
    <?php  
        $file_name = $rows->file_name;
        $id = $rows->id;
    ?>
    <tr role="row" class="heading">
        <td style="text-align:center"><?=$i?></td>
        <td >
            <a href="<?php echo base_url('public/files/so/'.$file_name); ?>" target="blank"><?=$file_name?></a>
        </td>
        <td style="text-align:center">
            <button type="button" file_name="<?=@$file_name?>" idnya="<?=@$id?>" class="btn btn-sm btn-danger btn_delete">Delete</button>                                               
        </td>
    </tr> 
<?php } ?>


<script type="text/javascript">
    $(document).ready(function() {
        $('.list_file_upload_so').on('click', '.btn_delete', function(e) {
            //param
            var file_name   = $(this).attr('file_name');
            var id = $(this).attr('idnya');
            //confirm
            var title = "Are You Sure ?";
            var mes = "Are you sure to DELETE Data?";
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
                    var url    = "<?=site_url($url);?>/delete_file_so";
                    var param  = {id:id, file_name:file_name};
                    $.post(url, param, function(msg){
                        toastr.options = call_toastr('3000');
                        if(msg.status == '1'){
                            window.list_file_upload_so();
                            window.list_file_so();
                            toastr['success']("Successfully DELETE Data!", "Success");
                        }else{
                            toastr['error']("Failed DELETE Data!", "Error");
                        }
                    },'json');
            });
        });
    
    });

</script>