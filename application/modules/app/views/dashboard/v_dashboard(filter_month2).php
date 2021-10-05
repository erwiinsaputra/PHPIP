<div class="portlet light ">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-table font-green-sharp"></i>
            <span class="caption-subject font-green-sharp bold uppercase">Strategic Map</span>
        </div>
        <div class="actions">
        
            <div style="float:right;">
                <a class="btn btn-primary btn-circle btn-sm " href="<?=site_url($url)?>" data-original-title="Refresh" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a> <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
            </div>
            <div style="float:right;">
                <a href="javascript:" tipe="map_view" class="btn btn-sm btn-primary btn_view"><i class="fa fa-bar-chart-o"></i> Strategic Map View</a>
                <a href="javascript:" tipe="table_view" class="btn btn-sm btn-danger btn_view"><i class="fa fa-table"></i> Table View</a>
                <input type="hidden" id="tipe" value="map_view">
                <input type="hidden" id="load_awal" value="0">
                &nbsp; &nbsp;
            </div>
            
        </div>
    </div>

    <div class="portlet-body">

        <div class="filtering" style="text-align:center !important;margin-bottom: 3em;">
            <form id="form_filtering" action="javascript:" method="POST" class="form-horizontal" role="form">
                <div class="row">
                    <div class="col-md-12">                   
                        <div class="form-group">
                            <label class="control-label col-md-1"><b>Periode:</b></label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <select class="form-control global-filter"  name="global_id_periode" id="global_id_periode"  placeholder="Periode">
                                        <!-- <option value=""></option> -->
                                        <?php foreach($periode as $row){ ?>
                                            <?php 
                                                $periode_year = $row->start_year.' - '.$row->end_year;
                                                if( strpos( $periode_year, date('Y') ) !== false ) {
                                                    $selected = "selected='selected'";
                                                }
                                            ?>
                                            <option <?=@$selected;?> value="<?=$row->id;?>"><?=$periode_year?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <label class="control-label col-md-1" style="margin-left:-2em;"><b>BSC:</b></label>
                            <div class="col-md-2" style="margin-left:-2em;">
                                <div class="input-group">
                                    <select class="form-control global-filter"  name="global_id_bsc" id="global_id_bsc" placeholder="ALL">
                                    <!-- <option value=""></option> -->
                                    <?php foreach($bsc as $row){ ?>
                                        <option <?=($row->id == '1'?'selected':'')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <label class="control-label col-md-1"><b>Year:</b></label>
                            <div class="col-md-1">
                                <div class="input-group">
                                    <select class="form-control global-filter"  name="global_year" id="global_year" placeholder="ALL">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <label class="control-label col-md-1"><b>Triwulan:</b></label>
                            <div class="col-md-1">
                                <div class="input-group">
                                    <select class="form-control global-filter"  name="global_triwulan" id="global_triwulan" placeholder="ALL">
                                        <option value=""></option>
                                        <?php for($tw=1; $tw <= 4; $tw++){ ?>
                                            <option value="<?=$tw?>"><?=$tw?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <label class="control-label col-md-1"><b>Month:</b></label>
                            <div class="col-md-1">
                                <div class="input-group">
                                    <select class="form-control global-filter"  name="global_month" id="global_month" placeholder="ALL">
                                        <option value=""></option>
                                        <?php for($m=1; $m <= 12; $m++){ ?>
                                            <option <?=($m == date('m') ? 'selected="selected"' : '')?> value="<?=$m?>"><?=$m?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        <div id="load_view" style="text-align:center">
            <?=@$html_map_view;?>
        </div>
    </div>

</div>



<!-- modal review_so-->
<div id="popup_review_so" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Review SO</b></h3>
      </div>
      <div class="modal-body" id="load_review_so"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal detail_tw-->
<div id="popup_detail_tw" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;font-size:2em;margin-left:6em;"><b>Detail Month</b></h3>
      </div>
      <div class="modal-body" id="load_detail_tw"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function () {

    //periode
    $(".filtering #global_id_periode").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) { 
        $('.filtering #global_year').val('').change();
        window.change_periode();
    });

    //BSC
    $(".filtering #global_id_bsc").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) { 
        window.change_filter();
    });

    //Year
    $(".filtering #global_year").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
    }).on('change', function(event) { 
        var load_awal = $('#load_awal').val();
        if(load_awal == 0){
            $('#load_awal').val(1);
        }else{
            window.change_filter();
        }
    });

    //Month
    $(".filtering #global_month").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) { 
        window.change_filter();
    });

    //Triwulan
    $(".filtering #global_triwulan").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
    }).on('change', function(event) { 
        var tw = $(".filtering #global_triwulan").val();
        if(tw == 1){ var m = 3;}
        if(tw == 2){ var m = 6;}
        if(tw == 3){ var m = 9;}
        if(tw == 4){ var m = 12;}
        $(".filtering #global_month").val(tw);
        $(".filtering #global_month").trigger('change');
    });

    

    //tipy view
    $('.btn_view').on('click',function(){
        var tipe = $(this).attr('tipe');
        $('#tipe').val(tipe);
        var url = '<?=site_url($url)?>/load_'+tipe;
        var param = $('#form_filtering').serializeArray();
        Metronic.blockUI({ target: '#load_view',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_view').html(msg);
            Metronic.unblockUI('#load_view');
        });
    });

    //tipe filter
    window.change_filter = function (){
        var tipe = $('#tipe').val();
        var url = '<?=site_url($url)?>/load_'+tipe;
        var param = $('#form_filtering').serializeArray();
        Metronic.blockUI({ target: '#load_view',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_view').html(msg);
            Metronic.unblockUI('#load_view');
        });
    }

    //change year
    window.change_periode = function (){
        $(".filtering select[name='global_year']").text("<option selected='selected' value=''></option>");
        $(".filtering select[name='global_year']").val("");
        var val =  $(".filtering #global_id_periode").val();
        var periode =  $(".filtering select[name='global_id_periode'] option[value='"+val+"']").text();
        var pecah = periode.split(" - ");
        var start_year = pecah[0];
        var end_year = pecah[1];
        for(var year=start_year;year<=end_year;year++){
            if(year == start_year){
                var newOption = new Option('', '', true, true);
                $(".filtering select[name='global_year']").append(newOption);
                var newOption = new Option(year, year, true, true);
                $(".filtering select[name='global_year']").append(newOption);
            }else{
                var newOption = new Option(year, year, true, true);
                $(".filtering select[name='global_year']").append(newOption);
            }
            if(year == start_year){ var year_now = start_year; }
            if(year == "<?=date('Y')?>"){ var year_now = "<?=date('Y')?>"; }
        }
        $(".filtering select[name='global_year']").val(year_now).change();
    }
    window.change_periode();

     //tipe view
     $('#load_view').on('click', '.btn_so', function(e) {
        $('#popup_review_so').modal();
        var id = $(this).attr('so');
        var year = $('#global_year').val();
        var month = $('#global_month').val();
        var url = "<?=site_url('app/review_so');?>/load_review_so";
        var param = {id:id, year:year, month:month};
        Metronic.blockUI({ target: '#load_review_so',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_review_so').html(msg);
            Metronic.unblockUI('#load_review_so');
        });
    });   


});
</script>






