<div class="table_si_year_<?=@$year;?>">
    <div style="margin-top:1em;">
        <style type="text/css">
            .table_si thead tr th, .table_si thead tr td, .table_si tbody tr td { white-space: nowrap;} 
            .table_si .filter td{ padding: 0px !important; white-space: nowrap;}
            .table_si .thead_th{text-align:center;background:#5B9BD5;color:white;font-size:1em;}
            .table_si .tbody_td{text-align:center;font-size:1em;} 
            .table_si .btn_ic, .table_si .btn_issue{cursor:pointer;} 
        </style>
        
        <table class="table table-bordered table-hover table_si" id="table_si">
            <thead>
                <tr>
                    <th class="thead_th">Code SI</th>
                    <th class="thead_th">SI Title</th>
                    <th class="thead_th">PIC</th>
                    <th class="thead_th">Status</th>
                    <th class="thead_th">% Complete on Year</th>
                    <th class="thead_th">% Overall Complete</th>
                    <th class="thead_th">Direct / Indirect</th>
                    <th class="thead_th">IC & Issue</th>
                </tr>
            </thead>
            <tbody>

                <?php $no=0; foreach($arr_si as $row){ $no++;?>
               
                    <tr>
                        <td class="tbody_td"><?=@$row->code_si;?></td>
                        <td class="tbody_td"><?=h_text_br(@$row->name_si,50);?></td>
                        <td class="tbody_td"><?=h_text_br(@$row->name_pic_si,20)?></td>
                        <td class="tbody_td"  style="<?=@$row->warna?>">
                            <span class="label" style="font-size:1em; <?=@$row->warna?>">
                                <?=@$row->huruf?> 
                            </span>
                        </td>
                        <td class="tbody_td"><?=h_format_angka(@$row->complete_on_year)?> %</td>
                        <td class="tbody_td"><?=h_format_angka(@$row->overall_complete)?> %</td>
                        <td class="tbody_td"><?=(@$row->direct == '1' ? 'Direct' : 'Indirect')?></td>
                        <td class="tbody_td">
                             <button title="IC" title_popup="<?='('.@$row->code_si.') '.@$row->name_si?>" id_si="<?=@$row->id_si;?>" class="btn btn-sm btn-primary btn_ic" style="border-radius:0px !important;">IC</button>
                             <button title="Issue" id_si="<?=@$row->id_si;?>" class="btn btn-sm btn-danger btn_issue" style="border-radius:0px !important;" >Issue</button>
                        </td>
                    </tr>
                <?php } ?>
                <?php if(count($arr_si) < 1){?>
                    <td class="tbody_td" colspan="8">No Data</td>
                <?php } ?>
            </tbody>
        </table>
    </div>  
</div>


<script type="text/javascript">
$(document).ready(function () {

    //load ic
    $('.table_si_year_<?=@$year;?>').on('click', '.btn_ic', function(e) {
        $('#popup_ic').modal();

        //load monitoring
        var tipe = 'review';
        var id_si = $(this).attr('id_si');
        var year = '<?=$year?>';
        var month = '<?=$month?>';
        var url = "<?=site_url('app/monev_si');?>/load_add";
        var param = {tipe:tipe, id_si:id_si, year:year, month:month};
        Metronic.blockUI({ target: '#load_ic',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_ic').html(msg);
            Metronic.unblockUI('#load_ic');
        });

        //load detail si
        var title = $(this).attr('title_popup');
        $('#popup_ic').find('#title_detail_si').html(title);
        var url = "<?=site_url('app/ic');?>/load_detail_si";
        var param = {id:id_si};
        Metronic.blockUI({ target: '#load_detail_si',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_detail_si').html(msg);
            Metronic.unblockUI('#load_detail_si');
        });

    });
    

    //load issue
    $('.table_si_year_<?=@$year;?>').on('click','.btn_issue', function(e) {
        $('#popup_issue').modal();
        var id_si  = $(this).attr('id_si');
        var year  = '<?=$year?>';
        var url = "<?=site_url('app/review_si');?>/load_issue";
        var param = {id_si:id_si, year:year};
        Metronic.blockUI({ target: '#load_issue',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_issue').html(msg);
            Metronic.unblockUI('#load_issue');
        });
    });

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>