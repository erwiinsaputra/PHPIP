<?php if(count($file_request_ic) > 0){?>
    <table class="">
        <tbody>
            <?php $i=0; foreach ($file_request_ic as $rows) { $i++; ?>
                <?php  
                    $file_name = $rows->file_name;
                    $id = $rows->id;
                ?>
                <tr role="row" class="heading">
                    <td style="text-align:center">&nbsp; <?=$i?>. &nbsp;</td>
                    <td >
                        <a href="<?php echo base_url('public/files/request_ic/'.$file_name); ?>" target="blank"><?=$file_name?></a>
                    </td>
                </tr> 
            <?php } ?>
        </tbody>
    </table>
<?php } ?>

<input type="hidden" id="jum_file" value="<?=count($file_request_ic)?>">