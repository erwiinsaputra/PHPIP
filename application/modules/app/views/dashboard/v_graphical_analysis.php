<!--
  <div style="text-align:center;">
    <h2 style="text-align:center;"><b>Strategic Table View</b></h2>
</div>
-->

<style>
{
  cursor: pointer;
}
/* .rotate {
  // FF3.5+ 
  -moz-transform: rotate(-90.0deg);
  // Opera 10.5
  -o-transform: rotate(-90.0deg);
  // Saf3.1+, Chrome 
  -webkit-transform: rotate(-90.0deg);
  // IE6,IE7 
  filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=0.083);
  // IE8
  -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
  // Standard
  transform: rotate(-90.0deg);
  margin-left: -85px;
  position: absolute;
  width: 58px;
  height:58px;
}
.vertical{
  max-width: 50px;
  height: 85px;
  line-height: 14px;
  padding-bottom: 20px;
  text-align: inherit;
} */
</style>

<?php if(count($data) < 1){  
    echo "<br><br><h1 style='text-align:center;margin-left:1em;margin-top:1em;margin-bottom:1em;'><b>Data Tidak Ditemukan !</b></h1><br><br>";exit; 
  }?>

<!-- List View -->
<div class="row" style="margin-top:1em;">
  <div class="col-md-12">

        <div style="height:35em;">
            <style type="text/css">
                .thead_periode{text-align:center;background:darkblue;color:white;}
                .thead_periode2{text-align:center;background:blue;color:white;}
                .tbody_td{text-align:center;}
            </style>
            <table class="table table-bordered table-hover table-wrap" id="table_graphical_analysis">
              <thead class="bg-primary" style="color: white;">
                <tr>
                  <th class="thead_periode" rowspan="2">No<br>KPI</th>
                  <th class="thead_periode" rowspan="2"><div style="width:15em;">Indikator</div></th>
                  <th class="thead_periode" rowspan="2">Pol</th>
                  <th class="thead_periode" rowspan="2"><div style="width:7em;">PIC</div></th>
                  <th class="thead_periode" rowspan="2"><div style="width:8em;">Ukuran</div></th>
                  <th class="thead_periode" colspan="<?=($end_year-$start_year+1)?>">Target</th>
                  <th class="thead_periode" rowspan="2">Action</th>
                </tr>
                <tr>
                    <?php for($year=$start_year;$year<=$end_year;$year++){ ?>
                      <th class="thead_periode2"><?=$year?></th>
                    <?php } ?>
                </tr>
              </thead>

              <tbody>
                <?php $no='0'; foreach ($data as $row){ $no++; ?>
                <?php   
                        //id 
                        $id = $row->id; 
                        $id_so = $row->id_so;
                ?>
                <tr>
                  <td class="tbody_td" so="<?=$row->id;?>"><?=$row->code_kpi_so;?></td>
                  <td class="tbody_td" so="<?=$row->id;?>"><?=$row->name_kpi_so;?></td>
                  <td class="tbody_td" so="<?=$row->id;?>" style="white-space: nowrap;text-align:center !important;">
                        <?php 
                            $name_polarisasi = ''; 
                            if(@$row->polarisasi == '10' ){ 
                              // $name_polarisasi = '<i class="fa fa-arrow-right"></i><i class="fa fa-arrow-left"></i>'; 
                              $name_polarisasi = '<img src="'.img_url('arrow/right.png').'" width="30em;"> <img src="'.img_url('arrow/left.png').'" width="30em;" style="margin-left:-1.5em;">'; 
                            }elseif(@$row->polarisasi == '8'){ 
                              // $name_polarisasi = '<i class="fa fa-arrow-up"></i>'; 
                              $name_polarisasi = '<img src="'.img_url('arrow/up.png').'" width="20em;">'; 
                            }elseif(@$row->polarisasi == '9'){ 
                              // $name_polarisasi = '<i class="fa fa-arrow-down"></i>'; 
                              $name_polarisasi = '<img src="'.img_url('arrow/down.png').'" width="20em;">'; 
                            }
                        ?>
                        <?=$name_polarisasi;?>
                  </td>
                  <td class="tbody_td" so="<?=$row->id;?>"><?=$row->name_pic_kpi_so;?></td>
                  <td class="tbody_td" so="<?=$row->id;?>"><?=$row->ukuran;?></td>

                  <?php for($year=$start_year;$year<=$end_year;$year++){ 
                            if($row->polarisasi == '10'){
                                echo '<td class="tbody_td" so="'.$id_so.'" >'.@$arr_target_from[$id][$year].' - '.@$arr_target_to[$id][$year].'</td>';
                            }else{
                                echo '<td class="tbody_td " so="'.$id_so.'" >'.h_format_angka(@$arr_target[$id][$year]).'</td>';
                            }
                        } 
                   ?>

                  <td class="tbody_td" so="<?=$row->id;?>">
                        <button title="Detail" polarisasi="<?=$row->polarisasi?>" id_kpi_so="<?=$row->id?>" class="btn btn-sm btn-warning btn_detail_graphical_analysis"><i class="fa fa-graph"></i> View</button>
                  </td>

                </tr>
                <?php } ?>


              </tbody>
            </table>


        </div>

        <br><br><br>
  </div>
</div>


<!-- freeze column -->
<script src="<?= plugin_url('freeze_column/tableHeadFixer.js')?>" type="text/javascript" ></script>


<script type="text/javascript">
$(document).ready(function () {
  
  $("#table_graphical_analysis").tableHeadFixer({"top" : 2,"left" : 5});

   //btn detail view
   $('#table_graphical_analysis .btn_detail_graphical_analysis').on('click',function(){
        $('#popup_detail_graphical_analysis').modal();
        var id = $(this).attr('id_kpi_so')
        var polarisasi = $(this).attr('polarisasi')
        if(polarisasi == '10'){
          var url = '<?=site_url($url)?>/load_detail_graphical_analysis_stabilize';
        }else{
          var url = '<?=site_url($url)?>/load_detail_graphical_analysis';
        }
        var filter = $('#form_filtering').serializeArray();
        var param = {id:id, filter:filter}
        Metronic.blockUI({ target: '#load_detail_graphical_analysis',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_detail_graphical_analysis').html(msg);
            Metronic.unblockUI('#load_detail_graphical_analysis');
        });
    });

});
</script>