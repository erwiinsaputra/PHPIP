<?php  if($tipe == 'review') { ?>

    <!-- <div style="text-align:center;margin-top:0.5em;margin-bottom:1em;">
        <div class="load_btn_approval status_approval_<?=@$id_si;?>_<?=@$year;?>" style="text-align:center;" >
            <?php if(@$status_approval_month =='1' || @$status_approval_month ==''){ ?>
                <b>Status Approval:<b> <button title="DRAFT"class="btn btn-sm btn-default" style="cursor:text;">DRAFT</button>
            <?php } ?>
            <?php if(@$status_approval_month =='2'){ ?>
                <b>Status Approval:<b> <button class="btn btn-sm btn-primary" month="<?=$month?>" style="cursor:text;color:#fff;background-color:#5cb85c;">Waiting Approval</button>
            <?php } ?>
            <?php if(@$status_approval_month =='3'){ ?>
                <b>Status Approval:<b> <button class="btn btn-sm btn-primary" month="<?=$month?>" style="cursor:text;color:#fff;background-color:#5cb85c;">Approved</button>
            <?php } ?>
            <?php if(@$status_approval_month =='4'){ ?>
                <b>Status Approval:<b> <button class="btn btn-sm btn-danger"  month="<?=$month?>" style="cursor:text;">Rejected</button>
            <?php } ?>
        </div>
    </div> -->

<?php  }else{ ?>

    <div style="text-align:center;margin-top:0.5em;margin-bottom:1em;">

        <!-- ========================== Button Update =====================-->
        <div style="margin-bottom:2em;">
            <button class="btn btn-sm btn-success btn_update_si_month" style="border-radius:5px !important;"><i class="fa fa-refresh"></i> &nbsp;Update</button>
            <input type="hidden" value="<?=$status_approval_month?>" id="si_status_approval_<?=@$id_si;?>_<?=@$year;?>"/>
        </div>

        <!-- ========================== Request approval =====================-->
        <div class="status_approval_<?=@$id_si;?>_<?=@$year;?>" style="text-align:center;" >

            <?php if(in_array( h_session('ROLE_ID'), h_role_admin())){ ?>
                <?php if(@$status_approval_month =='1' || @$status_approval_month ==''){ ?>
                    <button title="Request Approval" val="2" month="<?=$month?>" class="btn btn-sm btn_change_status" style="background:#428bca;color:white;"><i class="fa fa-send"></i>&nbsp; Request Approval</button>
                <?php } ?>
                <?php if(@$status_approval_month =='2'){ ?>
                    <button title="Cancel Approval" val="1" month="<?=$month?>" class="btn btn-sm btn_change_status" style="background:red;color:white;"><i class="fa fa-mail-reply"></i>&nbsp; Cancel Approval</button>
                    <button title="Approve" val="3" month="<?=$month?>" class="btn btn-sm btn-primary btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="background:#428bca;color:white;"><i class="fa fa-check"></i> Approve</button>
                    <button title="Reject" val="4"  month="<?=$month?>" class="btn btn-sm btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="cursor:pointer;color:#fff;background-color:red;"><i class="fa fa-mail-reply"></i> Reject</button>
                <?php } ?>
                <?php if(@$status_approval_month =='3'){ ?>
                    <button class="btn btn-sm btn-primary btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="cursor:pointer;color:#fff;background-color:#5cb85c;">Approved</button>
                    <button title="Reject" val="4" month="<?=$month?>" class="btn btn-sm btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="cursor:pointer;color:#fff;background-color:red;"><i class="fa fa-mail-reply"></i> Reject</button>
                <?php } ?>
                <?php if(@$status_approval_month =='4'){ ?>
                    <button title="Approve" val="3" month="<?=$month?>" class="btn btn-sm btn-primary btn_keterangan_approval" style="background:#428bca;color:white;"><i class="fa fa-check"></i> Approve</button>
                    <button class="btn btn-sm btn-danger btn_keterangan_approval" keterangan="<?=@$keterangan?>"  month="<?=$month?>" style="cursor:pointer;">Rejected</button>
                <?php } ?>
            <?php } ?>

            <?php if(h_session('ROLE_ID') == '9'){ ?>
                <?php if(@$status_approval_month =='1' || @$status_approval_month ==''){ ?>
                    <button title="Request Approval" val="2" month="<?=$month?>" class="btn btn-sm btn_change_status" keterangan="<?=@$keterangan?>" style="background:#428bca;color:white;"><i class="fa fa-send"></i>&nbsp; Request Approval</button>
                <?php } ?>
                <?php if(@$status_approval_month =='2'){ ?>
                    <button title="Cancel Approval" val="1" month="<?=$month?>" class="btn btn-sm btn_change_status" keterangan="<?=@$keterangan?>" style="background:red;color:white;"><i class="fa fa-mail-reply"></i>&nbsp; Cancel Approval</button>
                <?php } ?>
                <?php if(@$status_approval_month =='3'){ ?>
                    <button class="btn btn-sm btn-primary btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="cursor:pointer;color:#fff;background-color:#5cb85c;">Approved</button>
                <?php } ?>
                <?php if(@$status_approval_month =='4'){ ?>
                    <button title="Request Approval" val="2" month="<?=$month?>" class="btn btn-sm btn_change_status" style="background:#428bca;color:white;"><i class="fa fa-send"></i>&nbsp; Request Approval</button>
                    <button class="btn btn-sm btn-danger btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="cursor:pointer;">Rejected</button>
                <?php } ?>
            <?php } ?>

            <?php if(h_session('ROLE_ID') == '5'){ ?>
                <?php if(@$status_approval_month =='1' || @$status_approval_month ==''){ ?>
                    <button title="DRAFT"class="btn btn-sm btn-default" style="cursor:pointer;">DRAFT</button>
                <?php } ?>
                <?php if(@$status_approval_month =='2'){ ?>
                    <button title="Approve" val="3" month="<?=$month?>" class="btn btn-sm btn-primary btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="background:#428bca;color:white;"><i class="fa fa-check"></i> Approve</button>
                    <button title="Reject" val="4"  month="<?=$month?>" class="btn btn-sm btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="cursor:pointer;color:#fff;background-color:red;"><i class="fa fa-mail-reply"></i> Reject</button>
                <?php } ?>
                <?php if(@$status_approval_month =='3'){ ?>
                    <button class="btn btn-sm btn-primary btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="cursor:pointer;color:#fff;background-color:#5cb85c;">Approved</button>
                    <button title="Reject" val="4" month="<?=$month?>" class="btn btn-sm btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="cursor:pointer;color:#fff;background-color:red;"><i class="fa fa-mail-reply"></i> Reject</button>
                <?php } ?>
                <?php if(@$status_approval_month =='4'){ ?>
                    <button title="Approve" val="3" month="<?=$month?>" class="btn btn-sm btn-primary btn_keterangan_approval" keterangan="<?=@$keterangan?>" style="background:#428bca;color:white;"><i class="fa fa-check"></i> Approve</button>
                    <button class="btn btn-sm btn-danger btn_keterangan_approval" keterangan="<?=@$keterangan?>"  month="<?=$month?>" style="cursor:pointer;">Rejected</button>
                <?php } ?>
            <?php } ?>

        </div>

    </div>


<?php } ?>
