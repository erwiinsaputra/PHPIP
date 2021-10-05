<form method="post" id="form_review_so" action="javascript:;" class="form-horizontal">
  <div class="form-body">
      <div class="row">

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-1" style="font-size:1.3em;"><b>Year</b></label>
                <div class="col-md-2">
                    <select class="form-control btn_change_year"  name="year" id="year" placeholder="Periode">
                        <?php for($y=$start_year;$y<=$end_year;$y++){ ?>
                            <?php 
                                $selected = '';
                                if( strpos( $y, $year ) !== false ) {
                                    $selected = 'selected="selected"';
                                }
                            ?>
                            <option <?=@$selected;?> value="<?=$y;?>"><?=$y?></option>
                        <?php } ?>
                    </select>
                </div>
                <label class="control-label col-md-12" style="font-size:1.3em;text-align: center !important;"><b><?=@$so_title?></b></label>
            </div>
            <hr style="margin-top:0px;">
        </div>

        <div class="col-md-12">
            <div id="load_table_kpi_so"></div>
        </div>
        
    </div>
    
 <?php if(@$view != 'mydashboard'){ ?>
    
    <!-- filtering si -->
    <div class="row" style="margin-top:2em;">
        <div class="col-md-3">
            <div style="float:left;font-size:1.3em; margin-top: 0.3em;margin-right: 1em;">
                <b>Select&nbsp;KPI-SO&nbsp;to&nbsp;show&nbsp;associated&nbsp;SI&nbsp;:</b>
            </div>
        </div>
        <div class="col-md-4">
            <select id="id_kpi_so" class="form-control input-md select2_biasa" placeholder="Select KPI SO" >
                <option value=""></option>
                <?php foreach($arr_kpi_so as $row){ ?>
                    <option value="<?=$row->id;?>"><?='('.$row->code_kpi_so.') ';?><?=str_replace(' ','&nbsp;',$row->name_kpi_so);?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-2">
            <select id="direct" class="form-control input-md select2_biasa" placeholder="Select Direct/Indirect" >
                <option value="">Direct / Indirect</option>
                <option value="1">Direct</option>
                <option value="0">Indirect</option>
            </select>
        </div>
        <div class="col-md-2">
            <select id="month" class="form-control input-md select_month_si" placeholder="Select Month" >
                <option value=""></option>
                <?php for($m=1;$m<=12;$m++){?>
                    <option <?=($m == (int)date('m') ? 'selected' : '')?> value="<?=$m?>"><?=h_month_name($m)?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <!-- table si -->
    <div class="row">
        <div class="col-md-12">
            <div id="load_table_si"></div>
        </div>
    </div>

<?php } ?>

</form>


<script type="text/javascript">
$(document).ready(function () {
    
    //default month from dashboard
    var month = $('#form_filtering #global_month').val();
    if(typeof month === "undefined" || month == ''){
        month = '<?=(int)date('m')?>'; 
        $('#form_review_so #month').val('<?=(int)date('m')?>');
    }else{
        $('#form_review_so #month').val(month);
    }

    //change year
    $("#form_review_so .btn_change_year").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
    }).on('change', function(event) { 
        var year = $(this).val();
        window.reload_table_kpi_so();
        window.reload_table_si();
    });

    //reload table kpi so
    window.reload_table_kpi_so = function (){
        var id_so   = "<?=@$id_so;?>";
        var year    = $('#form_review_so #year').val();
        var month   = "<?=@$month?>";
        var url     = "<?=site_url($url);?>/load_table_kpi_so";
        var param   = {id_so:id_so, year:year, month:month};
        Metronic.blockUI({ target: '#load_table_kpi_so',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_table_kpi_so').html(msg);
            Metronic.unblockUI('#load_table_kpi_so');
        });
    }
    window.reload_table_kpi_so();

    //reload table si
    window.reload_table_si = function (){
        var id_so     = "<?=@$id_so;?>";
        var id_kpi_so = $('#form_review_so #id_kpi_so').val();
        var month     = $('#form_review_so #month').val();
        var direct    = $('#form_review_so #direct').val();
        var year      = $('#form_review_so #year').val();
        var url       = "<?=site_url($url);?>/load_table_si";
        var param     = {id_so:id_so, id_kpi_so:id_kpi_so, year:year, month:month, direct:direct};
        Metronic.blockUI({ target: '#load_table_si',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_table_si').html(msg);
            Metronic.unblockUI('#load_table_si');
        });
    }
    window.reload_table_si();

    //select2 biasa
    $("#form_review_so .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) {
        window.reload_table_si();
    });;

    //select2 biasa
    $("#form_review_so .select_month_si").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
    }).on('change', function(event) {
        window.reload_table_si();
    });;
    
    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>