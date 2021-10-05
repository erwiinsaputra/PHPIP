<div class="table_year_<?=@$year;?>">
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
                    <th class="thead_tw">Quarter</th>
                    <th class="thead_tw">Target <br>(<?=@$data->ukuran;?>)</th>
                    <th class="thead_tw">Realisasi <br>(<?=@$data->ukuran;?>)</th>
                    <th class="thead_tw">Progress<br>(%)</th>
                    <th class="thead_tw">Keterangan</th>
                    <th class="thead_tw">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php for($m=1;$m<=12;$m++){ ?>
                    <?php if(@$target_month[$year][$m] != ''){ ?>
                    <tr>
                        <td class="tbody_td">Q-<?=h_quarter_name($m)?></td>
                        <td class="tbody_td"><?=h_format_angka(@$target_month[$year][$m])?></td>
                        <td class="tbody_td"><?=h_format_angka(@$realisasi[$year][$m])?></td>
                        <td class="tbody_td" style="<?=(@$color_name[$year][$m]==''?'background:lightgrey;color:black;':@$color_name[$year][$m])?>">
                            <?=(@$pencapaian[$year][$m]==''?'N':h_format_angka(@$pencapaian[$year][$m]).'%')?> 
                        </td>
                        <td class="">
                            <b>Keterangan :</b> <?=h_read_more(@$keterangan[$year][$m],30)?>
                            <b>Recommendations :</b> <?=h_read_more(@$recommendations[$year][$m],30)?>
                        </td>
                        <td class="tbody_td">
                            <a title="Edit" id_month="<?=@$id_month[$year][$m]?>" class="btn btn-sm btn-primary btn_edit_month" href="javascript:;">Edit</a>
                            <a title="Import" id_month="<?=@$id_month[$year][$m]?>" class="btn btn-sm btn-warning btn_import_month" href="javascript:;">Import</a>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>  
    <div class="row">
        <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                <?php if(in_array( h_session('ROLE_ID'), h_role_admin())){ ?>
                    <?php if(@$status_year =='1' || @$status_year ==''){ ?>
                        <button year="<?=@$year?>" id_sr="<?=@$data->id?>" title="Request Approval" val="2" class="btn btn_change_status" style="background:darkblue;color:white;">Request Approval</button>
                    <?php } ?>
                    <?php if(@$status_year =='2'){ ?>
                        <button year="<?=@$year?>" id_sr="<?=@$data->id?>" title="Approve" val="3" class="btn btn-primary btn_change_status" style="background:darkblue;color:white;">Approve</button>
                        <button year="<?=@$year?>" id_sr="<?=@$data->id?>" title="Reject" val="4" class="btn btn_change_status" style="cursor:pointer;color:#fff;background-color:red;">Reject</button>
                    <?php } ?>
                    <?php if(@$status_year =='3'){ ?>
                        <button class="btn btn-primary" style="cursor:text;color:#fff;background-color:#5cb85c;">Approved</button>
                        <button year="<?=@$year?>" id_sr="<?=@$data->id?>" title="Reject" val="4" class="btn btn_change_status" style="cursor:pointer;color:#fff;background-color:red;">Reject</button>
                    <?php } ?>
                    <?php if(@$status_year =='4'){ ?>
                        <button year="<?=@$year?>" id_sr="<?=@$data->id?>" title="Approve" val="3" class="btn btn-primary btn_change_status" style="background:darkblue;color:white;">Approve</button>
                        <button class="btn btn-danger" style="cursor:text;">Rejected</button>
                    <?php } ?>
                <?php } ?>

                <?php if(h_session('ROLE_ID') == '8'){ ?>
                    <?php if(@$status_year =='2'){ ?>
                        <button year="<?=@$year?>" id_sr="<?=@$data->id?>" title="Approve" val="3" class="btn btn-primary btn_change_status" style="background:darkblue;color:white;">Approve</button>
                        <button year="<?=@$year?>" id_sr="<?=@$data->id?>" title="Reject" val="4" class="btn btn_change_status" style="cursor:text;color:#fff;background-color:red;">Reject</button>
                    <?php } ?>
                    <?php if(@$status_year =='3'){ ?>
                        <button class="btn btn-primary" style="cursor:text;color:#fff;background-color:#5cb85c;">Approved</button>
                    <?php } ?>
                    <?php if(@$status_year =='4'){ ?>
                        <button class="btn btn-danger" style="cursor:text;">Rejected</button>
                    <?php } ?>
                <?php } ?>
        </div>
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

    
    //btn change status
    $('.table_year_<?=$year?>').on('click', '.btn_change_status', function(e) {
        var id_sr = $(this).attr('id_sr');
        var year = $(this).attr('year');
        var val = $(this).attr('val');
        var title = $(this).attr('title');
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
                var param  = {id_sr:id_sr, year:year, val:val, title:title, token:token};
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