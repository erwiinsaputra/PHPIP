<div class="table_year_<?=$id;?>_<?=$year?>">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group" style="text-align:center;margin-right:10px;">
                <label class="control-label"><b>Target Year : <?=h_format_angka(@$target_year);?> (<?=@$data->ukuran;?>)</b></label>
            </div>
        </div>
    </div>

    <div style="margin-top:10px;overflow-x:auto;">
        <style type="text/css">
            .table-wrap thead tr th, .table-wrap thead tr td, .table-wrap tbody tr td { white-space: nowrap;} 
            .filter td{ padding: 0px !important; white-space: nowrap;}
            .thead_tw{text-align:center;background:darkblue;color:white;}
            .tbody_td{text-align:center;}
        </style>
        <table class="table table-bordered table-hover table-wrap" id="table_month">
            <thead>
                <tr>
                    <th class="thead_tw">Month</th>
                    <th class="thead_tw">Target <br>(<?=@$data->ukuran;?>)</th>
                    <th class="thead_tw">Realisasi <br>(<?=@$data->ukuran;?>)</th>
                    <th class="thead_tw">Progress <br>(%)</th>
                    <th class="thead_tw">Performance Analysis/<br>Recommendations</th>
                    <th class="thead_tw">Prognosa</th>
                    <th class="thead_tw">Performance Analysis & <br>Quick Win</th>
                    <?php if($view != 'mydashboard'){ ?>
                        <th class="thead_tw">Action</th>
                        <th class="thead_tw">Approval</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php for($m=1;$m<=12;$m++){ ?>
                    <?php if(@$target_month[$year][$m] != ''){ ?>

                    <?php if($month == $m){ $month_select = 'style="background:yellow !important;"';}else{ $month_select = '';} ?>
                    <tr <?=$month_select;?> >
                        <td class="tbody_td"><?=h_month_name($m)?></td>
                        <td class="tbody_td"><?=h_format_angka(@$target_month[$year][$m])?></td>
                        <td class="tbody_td"><?=h_format_angka(@$realisasi[$year][$m])?></td>
                        <td class="tbody_td" style="<?=(@$color_name[$year][$m]==''?'background:lightgrey;color:black;':@$color_name[$year][$m])?>">
                            <?=(@$pencapaian[$year][$m]==''?'N':h_format_angka(@$pencapaian[$year][$m]).'%')?> 
                        </td>
                        <td class="">
                            <b>Keterangan :</b> <?=h_read_more(@$penyebab1[$year][$m],30)?>
                            <b>Recommendations :</b> <?=h_read_more(@$recommendations[$year][$m],30)?>
                        </td>
                        <td class="tbody_td">
                                <?=h_format_angka(@$prognosa[$year][$m])?>
                                <br>
                                <label style="<?=(@$color_name2[$year][$m]==''?'background:lightgrey;color:black;':@$color_name2[$year][$m])?>">
                                    &nbsp;<?=(@$prognosa_pencapaian[$year][$m]==''?'N':h_format_angka(@$prognosa_pencapaian[$year][$m]).'%')?>&nbsp;
                                </label>
                        </td>
                        <td class="">
                            <b>Keterangan :</b> <?=h_read_more(@$penyebab2[$year][$m],30);?>
                            <b>Quick Win :</b> <?=h_read_more(@$quick_win[$year][$m],30);?>
                        </td>

                        <!-- view mydashboard -->
                        <?php if($view != 'mydashboard'){ ?>

                            <td class="tbody_td">
                                <a title="Edit" id_month="<?=@$id_month[$year][$m]?>" class="btn btn-sm btn-primary btn_edit_month" href="javascript:;">Edit</a>
                                <a title="Import" id_month="<?=@$id_month[$year][$m]?>" class="btn btn-sm btn-warning btn_import_month" href="javascript:;">Import</a>
                            </td>

                            <td class="tbody_td">
                                
                                <div style="text-align:center;">

                                    <?php if(in_array( h_session('ROLE_ID'), h_role_admin())){ ?>
                                        <?php if(@$status_month[$year][$m] =='1' || @$status_month[$year][$m] ==''){ ?>
                                            <button month="<?=@$m?>" title="Request Approval" val="2" class="btn btn-sm btn_change_status" style="background:darkblue;color:white;"><i class="fa fa-send"></i>&nbsp; Request Approval</button>
                                        <?php } ?>
                                        <?php if(@$status_month[$year][$m] =='2'){ ?>
                                            <button month="<?=@$m?>" title="Cancel Approval" val="1" class="btn btn-sm btn_change_status" style="background:darkblue;color:white;"><i class="fa fa-mail-reply"></i>&nbsp; Cancel Approval</button>
                                            <div style="margin-top:5px;"></div>
                                            <button month="<?=@$m?>" title="Approve" val="3" class="btn btn-sm btn-primary btn_keterangan_approval" style="background:darkblue;color:white;"><i class="fa fa-check"></i> Approve</button>
                                            <div style="margin-top:5px;"></div>
                                            <button month="<?=@$m?>" title="Reject" val="4" class="btn btn-sm btn_keterangan_approval" style="cursor:pointer;color:#fff;background-color:red;"><i class="fa fa-mail-reply"></i> Reject</button>
                                        <?php } ?>
                                        <?php if(@$status_month[$year][$m] =='3'){ ?>
                                            <button class="btn btn-sm btn-primary btn_keterangan_approval" keterangan="<?=@$data->request_approval_keterangan?>" style="cursor:pointer;color:#fff;background-color:#5cb85c;">Approved</button>
                                            <div style="margin-top:5px;"></div>
                                            <button month="<?=@$m?>"  keterangan="<?=@$data->request_approval_keterangan?>" title="Reject" val="4" class="btn btn-sm btn_keterangan_approval" style="cursor:pointer;color:#fff;background-color:red;"><i class="fa fa-mail-reply"></i> Reject</button>
                                        <?php } ?>
                                        <?php if(@$status_month[$year][$m] =='4'){ ?>
                                            <button month="<?=@$m?>" keterangan="<?=@$data->request_approval_keterangan?>" title="Approve" val="3" class="btn btn-sm btn-primary btn_keterangan_approval" style="background:darkblue;color:white;"><i class="fa fa-check"></i> Approve</button>
                                            <div style="margin-top:5px;"></div>
                                            <button class="btn btn-sm btn-danger btn_keterangan_approval" keterangan="<?=@$data->request_approval_keterangan?>" style="cursor:pointer;">Rejected</button>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if(h_session('ROLE_ID') == '8'){ ?>
                                        <?php if(@$status_month[$year][$m] =='2'){ ?>
                                            <button month="<?=@$m?>" keterangan="<?=@$data->request_approval_keterangan?>" title="Approve" val="3" class="btn btn-sm btn-primary btn_keterangan_approval" style="background:darkblue;color:white;"><i class="fa fa-check"></i> Approve</button>
                                            <div style="margin-top:5px;"></div>
                                            <button month="<?=@$m?>" keterangan="<?=@$data->request_approval_keterangan?>" title="Reject" val="4" class="btn btn-sm btn_keterangan_approval" style="cursor:text;color:#fff;background-color:red;"><i class="fa fa-mail-reply"></i> Reject</button>
                                        <?php } ?>
                                        <?php if(@$status_month[$year][$m] =='3'){ ?>
                                            <button class="btn btn-sm btn-primary btn_keterangan_approval" keterangan="<?=@$data->request_approval_keterangan?>" style="cursor:pointer;color:#fff;background-color:#5cb85c;">Approved</button>
                                            <div style="margin-top:5px;"></div>
                                            <button month="<?=@$m?>" keterangan="<?=@$data->request_approval_keterangan?>" title="Reject" val="4" class="btn btn-sm btn_keterangan_approval" style="cursor:pointer;color:#fff;background-color:red;"><i class="fa fa-mail-reply"></i> Reject</button>
                                        <?php } ?>
                                        <?php if(@$status_month[$year][$m] =='4'){ ?>
                                            <button month="<?=@$m?>" keterangan="<?=@$data->request_approval_keterangan?>" title="Approve" val="3" class="btn btn-sm btn-primary btn_keterangan_approval" style="background:darkblue;color:white;"><i class="fa fa-check"></i> Approve</button>
                                            <div style="margin-top:5px;"></div>
                                            <button class="btn btn-sm btn-danger btn_keterangan_approval" keterangan="<?=@$data->request_approval_keterangan?>" style="cursor:pointer;">Rejected</button>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if(h_session('ROLE_ID') == '10'){ ?>
                                        <?php if(@$status_month[$year][$m] =='1' || @$status_month[$year][$m] ==''){ ?>
                                            <button month="<?=@$m?>" title="Request Approval" val="2" class="btn btn-sm btn_change_status" style="background:darkblue;color:white;"><i class="fa fa-send"></i>&nbsp; Request Approval</button>
                                        <?php } ?>
                                        <?php if(@$status_month[$year][$m] =='2'){ ?>
                                            <button month="<?=@$m?>" title="Cancel Approval" val="1" class="btn btn-sm btn_change_status" style="background:darkblue;color:white;"><i class="fa fa-mail-reply"></i>&nbsp; Cancel Approval</button>
                                            <div style="margin-top:5px;"></div>
                                            <button class="btn btn-sm btn-primary btn_keterangan_approval" style="cursor:pointer;color:#fff;background-color:#5cb85c;">Waiting Approval</button>
                                        <?php } ?>
                                        <?php if(@$status_month[$year][$m] =='3'){ ?>
                                            <button class="btn btn-sm btn-primary btn_keterangan_approval" keterangan="<?=@$data->request_approval_keterangan?>" style="cursor:pointer;color:#fff;background-color:#5cb85c;">Approved</button>
                                        <?php } ?>
                                        <?php if(@$status_month[$year][$m] =='4'){ ?>
                                            <button month="<?=@$m?>" title="Request Approval" val="2" class="btn btn-sm btn_change_status" style="background:darkblue;color:white;"><i class="fa fa-send"></i>&nbsp; Request Approval</button>
                                            <div style="margin-top:5px;"></div>
                                            <button class="btn btn-sm btn-danger btn_keterangan_approval" keterangan="<?=@$data->request_approval_keterangan?>" style="cursor:pointer;">Rejected</button>
                                        <?php } ?>
                                    <?php } ?>


                                </div>
                            </td>

                        <?php } ?>

                    </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>  

    
</div>

<script type="text/javascript">
$(document).ready(function () {

    //load edit month
    $('.btn_edit_month').off().on('click', function(e) {
        $('#popup_edit_month').modal();
        var id  = $(this).attr('id_month');
        var url = "<?=site_url($url);?>/load_edit_month";
        var param = {id:id};
        Metronic.blockUI({ target: '#load_edit_month',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_edit_month').html(msg);
            Metronic.unblockUI('#load_edit_month');
        });
    });

    //import month
    $('.btn_import_month').off().on('click', function(e) {
       alert('Fitur Import, Masih Dalam Pengembangan.');
    });

    
    //btn keterangan approval
    $('.table_year_<?=$id?>_<?=$year?>').on('click', '.btn_keterangan_approval', function(e) {
        var id_kpi_so = '<?=$id?>';
        var year = '<?=$year?>';
        var month = $(this).attr('month');
        var val = $(this).attr('val');
        var title = $(this).attr('title');
        var keterangan = $(this).attr('keterangan');
        //tampilkan popup
        $('#popup_keterangan_approval').modal();
        //cek hanya view keterangan
        if (typeof val == typeof undefined) {
            $('#btn_approval_approve').hide();
            $('#btn_approval_reject').hide();
            $('#keterangan_approval').val(keterangan);
            $('#keterangan_approval').attr('disabled','disabled');
            return true;
        }else{
            //tampilkan tombol reject/approve
            if(val == '3'){
                $('#btn_approval_approve').show();
                $('#btn_approval_reject').hide();
            }
            if(val == '4'){
                $('#btn_approval_approve').hide();
                $('#btn_approval_reject').show();
            }
            $('#keterangan_approval').removeAttr('disabled');
        }
        //pindahkan parameter ke tombol di popup
        $('#keterangan_approval').val(keterangan);
        $('#btn_approval_approve, #btn_approval_reject').attr('month',month);
        $('#btn_approval_approve, #btn_approval_reject').attr('val',val);
        $('#btn_approval_approve, #btn_approval_reject').attr('title',title);
    });
    
    //btn change status
    $('.table_year_<?=$id?>_<?=$year?>').on('click', '.btn_change_status', function(e) {
        var id_kpi_so = '<?=$id?>';
        var year = '<?=$year?>';
        var month = $(this).attr('month');
        var val = $(this).attr('val');
        var title = $(this).attr('title');
        var keterangan = $('#keterangan_approval').val();
        var mes = "Are you sure to "+title+" ?";
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
                var url    = '<?=site_url($url)?>/change_status';
                var param  = {id_kpi_so:id_kpi_so, year:year, month:month, val:val, title:title, keterangan:keterangan, token:token};
                $.post(url, param, function(msg){
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        window.reload_month();
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                }, 'json');
        });
    });

    //btn change status
    $('.btn_change_status_approval').on('click', function(e) {
        var id_kpi_so = '<?=$id?>';
        var year = '<?=$year?>';
        var month = $(this).attr('month');
        var val = $(this).attr('val');
        var title = $(this).attr('title');
        var keterangan = $('#keterangan_approval').val();
        var mes = "Are you sure to "+title+" ?";
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
                var url    = '<?=site_url($url)?>/change_status';
                var param  = {id_kpi_so:id_kpi_so, year:year, month:month, val:val, title:title, keterangan:keterangan, token:token};
                $.post(url, param, function(msg){
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        window.reload_month();
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                }, 'json');
        });
    });

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>