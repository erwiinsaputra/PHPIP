<?php if(count($file_si) > 0){?>
    <table class="">
        <tbody>
            <?php $i=0; foreach ($file_si as $rows) { $i++; ?>
                <?php  
                    $file_name = $rows->file_name;
                    $id = $rows->id;
                ?>
                <tr role="row" class="heading">
                    <td style="text-align:center">&nbsp; <?=$i?>. &nbsp;</td>
                    <td >
                        <a href="<?php echo base_url('public/files/si/'.$file_name); ?>" target="blank"><?=$file_name?></a>
                    </td>
                </tr> 
            <?php } ?>
        </tbody>
    </table>
<?php } ?>

<input type="hidden" id="jum_file" value="<?=count($file_si)?>">