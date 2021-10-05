<style type="text/css">
.form-horizontal .control-label{
    padding-top: 6px;
    font-weight: bold;
    font-size: 17px;
}
.form-section{
    text-decoration: underline;
}
</style>


<div class="row form-horizontal" style="font-size:15px;">
    <div class="col-md-12 form-body">
        

        <h3 class="form-section" style="text-align:center;">Data From TPM</h3>

        <div class="form-group">

            <!--  baris 1-->
            <label class="control-label col-md-2">Customer:</label>
            <div class="col-md-10 form-control-static">
                    <?=@$tpm_detail->cus_name;?> &nbsp; 
                    [ <b>Country:</b> <?=@$tpm_detail->cou_name;?> ] &nbsp; 
                    [ <b>Region:</b> <?=@$tpm_detail->reg_name;?> ] &nbsp;
                    [ <b>Area:</b> <?=@$tpm_detail->area_name;?> ]
            </div>

            <!--  baris 2-->
            <label for="group" class="col-md-2 control-label">Product:</label>
            <div class="col-md-2 form-control-static"><?=@$tpm_detail->gr_name;?></div>
            <label for="group" class="col-md-2 control-label">Year:</label>
            <div class="col-md-2 form-control-static"><?=@$tpm_detail->tpm_year;?></div>
            <label for="group" class="col-md-2 control-label">Market&nbsp;Share:</label>
            <div class="col-md-1 form-control-static" style="text-align:right;"><?=number_format(@$tpm_detail->tpm_market,2);?></div>
            
            <?php if(@$tpm_detail->gr_id == '1' || @$tpm_detail->gr_id == '2' || @$tpm_detail->gr_id == '4'){?>
            <label for="group" class="col-md-2 control-label">
                <b>
                <?=(@$tpm_detail->at_name == '' ? '': 'A/C&nbsp;Type') ?>
                <?=(@$tpm_detail->eng_name == '' ? '': 'Engine') ?>
                <?=(@$tpm_detail->apu_name == '' ? '': 'APU') ?> 
                <?=(@$tpm_detail->comp_name == '' ? '': 'Component') ?> 
                :</b>
            </label>
            <div class="col-md-2 form-control-static"><?=@$tpm_detail->at_name;?><?=@$tpm_detail->eng_name;?><?=@$tpm_detail->apu_name;?><?=@$tpm_detail->comp_name;?></div>
            <?php } ?>

            <label for="group" class="col-md-2 control-label">Deviasi:</label>
            <div class="col-md-1 form-control-static"><?=number_format(floor(abs((@$tpm_detail->tpm_market)-(@$tpm_detail->tpm_tot_salesplan))),2);?></div>
        
            <label for="group" class="col-md-3 control-label">Sales&nbsp;Plan:</label>
            <div class="col-md-1 form-control-static" style="text-align:right;">
                <?=number_format(@$tpm_detail->tpm_tot_salesplan);?>
            </div>
            
        </div>


        <h3 class="form-section" style="text-align:center;">Data From AMS</h3>

        <div class="form-group">
            <!--  baris 1-->
            <label for="group" class="col-md-2 control-label">Project&nbsp;Type:</label>
            <div class="col-md-2 form-control-static"><?=h_project_type($arr_reg->tpm_project_type);?></div>
            <label for="group" class="col-md-2 control-label">Maintenance:</label>
            <div class="col-md-2 form-control-static">
                <?php 
                    $arr_wt_name = explode(',', $arr_reg->ams_wt_name);
                    $data_wt_name = '';
                    foreach ($arr_wt_name as $val) {
                        $data_wt_name .= $val.' + ';
                    }
                    $data_wt_name = substr($data_wt_name, 0, -3);
                    echo $data_wt_name;
                ?>
            </div>
            <label for="group" class="col-md-2 control-label">Location:</label>
            <div class="col-md-2 form-control-static"><?=$arr_reg->loc_name;?></div>
            
            

            
            <label for="group" class="col-md-2 control-label">
                <?=($arr_reg->ams_ar_id == '' ?  'Seri Number ' : 'A/C&nbsp;Registration');?>:
            </label>
            <div class="col-md-2 form-control-static">
                <?php  echo @$arr_reg->ams_serinumber.@$arr_reg->ams_ar_id;?>
            </div>

            <label for="group" class="col-md-2 control-label">Sales&nbsp;Plan:</label>
            <div class="col-md-2 form-control-static"><?=number_format($arr_reg->ams_salesplan)?></div>
            <label for="group" class="col-md-2 control-label">Start&nbsp;Date:</label>
            <div class="col-md-2 form-control-static"><?=$arr_reg->ams_start_date;?></div>

            <!--  baris 4-->
            <label for="group" class="col-md-2 control-label">TAT:</label>
            <div class="col-md-2 form-control-static"><?=$arr_reg->ams_tat;?></div>
            <label for="group" class="col-md-2 control-label">End&nbsp;Date:</label>
            <div class="col-md-2 form-control-static"><?=$arr_reg->ams_end_date;?></div>
        </div>

    </div>
</div>