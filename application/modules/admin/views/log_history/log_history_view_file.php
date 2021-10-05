<div class="col-md-12 panel panel-body panel-info">
    <div class="col-md-12">
        <div class="col-md-12">
            <div style="font-size:15px;"><b > <?=$action ?> :</b></div>
        </div>
    </div>
    <br>

    <?php $i=0; $prio_name = ''; foreach ($priority as $row) { $i++;?>

        <?php if($row->prio_name != $prio_name){ ?>
        
        <?php if($prio_name != ''){ ?>
        </div>
        <?php }?>

        <div class="col-md-12 panel panel-body panel-info" style="padding-bottom: 0px !important;">
        <?php }  ?>

        <?php if($row->prio_name != $prio_name){ ?>
                <div class="col-md-12">
                    <div class="col-md-12" >
                        <span style="font-size:15px;"><b>LEVEL <?=$row->prio_level;?></b></span>
                        <span style="font-size:13px;"><b>[ <?=$row->prio_name;?> ] :</b></span>
                    </div>
                </div>
        <?php } $prio_name = $row->prio_name;?>
   
                <div class="col-md-6">         
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div style="margin-left:25px;">
                                    
                                    <div>
        								<?php if(@in_array($row->priopo_id, $cek['id'])){ ?>
                                            <a data-toggle="collapse"  data-container="body" data-placement="top" href="#form_file_upload_<?=$row->priopo_id;?>">-&nbsp;<?=$row->priopo_name;?> 
                                                ( <?php echo @$cek['point'][$row->priopo_id];?> ) %
                                            </a> 
                                            <div class="collapse" id="form_file_upload_<?=$row->priopo_id;?>">
                                                 <div style="font-size:10px;">
                                                      <?php foreach ($file as $row2) { 
                                                                $filenya = base64_encode($row2->attc_file);
                                                                $filenya = str_replace('=', '99999', $filenya);
                                                        ?>
                                                            <?php if( $row->priopo_id == $row2->attc_id_prio ){ ?>
                                                                <a href="<?php echo site_url('global/mypica/download_file/'.$row2->attc_created_by.'/'.$filenya); ?>" target="blank"><?=$row2->attc_file;?></a>
                                                                <br>
                                                            <?php } ?>
                                                      <?php } ?>
                                                 </div>
                                            </div>
        							    <?php } else { 
        									echo '-&nbsp;'.$row->priopo_name;
        								} ?>
                                    </div>
                                        
                                </div>                       
                            </div>
                        </div>
                    </div>
                <br>
                </div>

        <?php if($i == count($priority)){ ?>
        </div>
        <?php }?>
           
    <?php } ?>
    
</div>
