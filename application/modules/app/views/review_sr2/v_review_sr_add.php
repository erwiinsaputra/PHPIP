<form method="post" id="form_review_sr" action="javascript:;" class="form-horizontal">
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
  </div>
  
</form>


<script type="text/javascript">
$(document).ready(function () {

    //change year
    $("#form_review_sr .btn_change_year").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
    }).on('change', function(event) { 
        var year = $(this).val();
        window.reload_table_kpi_so();
    });

    //reload month
    window.reload_table_kpi_so = function (){
        var id_so   = "<?=@$id_so;?>";
        var year    = $('#form_review_sr #year').val();
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
    
    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');
});
</script>