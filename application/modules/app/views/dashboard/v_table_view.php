<!--
  <div style="text-align:center;">
    <h2 style="text-align:center;"><b>Strategic Table View</b></h2>
</div>
-->

<style>
.btn_so{
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
<div class="row" style="margin-top:0em;">
  <div class="col-md-12">

        <div class="row">
            <div class="col-md-12">
                <hr style="margin-bottom:-35px;"/>

                <script type="text/javascript">
                $(document).ready(function () {
                  //cek prognosa
                  $('.cek_prognosa').die().live('click',function(){
                      if($(this).attr('checked') == 'checked'){
                          var new_colspan = parseFloat($('.colspan_month').attr('colspan')) + 2;
                          $('.colspan_month').attr('colspan', new_colspan);
                          var month = "<?=@$month?>";
                          if(month != ''){
                              $('.prognosa').hide();
                              $('.prognosa_'+month).show();
                          }else{
                              $('.prognosa').show();
                          }
                      }else{
                          var new_colspan = parseFloat($('.colspan_month').attr('colspan')) - 2;
                          $('.colspan_month').attr('colspan', new_colspan);
                          $('.prognosa').hide();
                      }
                  });

                  //cek triwulan
                  $('.cek_triwulan').die().live('click',function(){
                      if($(this).attr('checked') == 'checked'){
                          $('.month').hide();
                          $('.month_3').show();
                          $('.month_6').show();
                          $('.month_9').show();
                          $('.month_12').show();
                      }else{
                          $('.month').show();
                      }
                      $('.prognosa').hide();
                  });
                });
                </script>
            </div>
        </div>

        <div style="height:35em;">
            <?php 
                //rowspan 
                $rowspan_perspective = $rowspan_name_so = $rowspan_code_so = [];
                $no=0; foreach ($data as $row){ $no++;
                    $rowspan_perspective[$row->name_perspective][] = 1;
                    $rowspan_code_so[$row->code_so][] = 1;
                    $rowspan_name_so[$row->name_so][] = 1;
                } 
                // echo '<pre>';print_r($rowspan_code_so);exit;
            ?>
            <style type="text/css">
                .thead_month{text-align:center;background:darkblue;color:white; font-size:0.9em !important;}
                .thead_month2{text-align:center;background:blue;color:white; font-size:0.9em !important;}
                .tbody_td{text-align:center;font-size:0.9em !important;}
            </style>
            <table class="table table-bordered table-hover table-wrap" id="table_view">
              <thead class="bg-primary" style="color: white;">
                <tr>
                  <th class="thead_month" rowspan="2">Perspective</th>
                  <th class="thead_month" rowspan="2">SO<br>Code</th>
                  <th class="thead_month" rowspan="2">Strategic Objectives (SO)</th>
                  <th class="thead_month" rowspan="2">No.</th>
                  <th class="thead_month" rowspan="2"><div style="width:15em;">Indikator</div></th>
                  <th class="thead_month" rowspan="2">Pol</th>
                  <th class="thead_month" rowspan="2"><div style="width:7em;">PIC</div></th>
                  <th class="thead_month" rowspan="2"><div style="width:8em;">Ukuran</div></th>
                  <th class="thead_month" rowspan="2">Target <?=$year?></th>
                  <?php for($m=1;$m<=12;$m++){ ?>
                    <th class="thead_month colspan_month month month_<?=$m?>" colspan="3"><?=h_month_name($m)?></th>
                  <?php } ?>
                </tr>
                <tr>
                    <?php for($m=1;$m<=12;$m++){ ?>
                      <th class="thead_month2 month month_<?=$m?>">Target</th>
                      <th class="thead_month2 month month_<?=$m?>">Realisasi</th>
                      <th class="thead_month2 month month_<?=$m?>">Pencapaian</th>
                      <th class="thead_month2 month month_<?=$m?> prognosa prognosa_<?=$m?>" style="display:none;">Prognosa</th>
                      <th class="thead_month2 month month_<?=$m?> prognosa prognosa_<?=$m?>" style="display:none;">Prognosa&nbsp;%</th>
                    <?php } ?>
                </tr>
              </thead>

              <tbody>
                <?php $no='0'; foreach ($data as $row){ $no++; ?>
                <tr>
                  <?php 
                        //id so, untuk detail
                        $id = $row->id;
                        $id_so = $row->id_so;

                        //rowspan 
                        if(@$perscpective == $row->name_perspective ){ $perscpective = ''; }else{ $perscpective = $row->name_perspective; }
                        if(@$code_so == $row->code_so ){ $code_so = ''; }else{ $code_so = $row->code_so; }
                        if(@$name_so == $row->name_so ){ $name_so = ''; }else{ $name_so = $row->name_so; }
                        if($no == 1){
                            $perscpective = $row->name_perspective; 
                            $code_so = $row->code_so;
                            $name_so = $row->name_so;
                        }
                  ?>

                  <?php if($perscpective == $row->name_perspective){ ?>
                  <td class="tbody_td vertical" rowspan="<?=count(@$rowspan_perspective[$row->name_perspective]);?>" style="background:grey;color:white;">
                      <div class="rotate"><?=$perscpective;?></div>
                  </td>
                  <?php } ?>
                  <?php if($code_so == $row->code_so){ ?>
                  <td class="tbody_td btn_so" so="<?=$id_so;?>"
                      rowspan="<?=count(@$rowspan_code_so[$row->code_so]);?>" 
                      style="<?=@$so_color[$id_so];?>">
                      <?=$code_so;?>
                  </td>
                  <?php } ?>
                  <?php if($name_so == $row->name_so){ ?>
                  <td class="tbody_td btn_so" so="<?=$id_so;?>" rowspan="<?=count(@$rowspan_name_so[$row->name_so]);?>">
                      <?=$name_so;?>
                  </td>
                  <?php } ?>

                  <?php 
                        //rowspan 
                        $perscpective = $row->name_perspective; 
                        $code_so = $row->code_so;
                        $name_so = $row->name_so;
                  ?>

                  <td class="tbody_td btn_so" so="<?=$id_so;?>"><?=$row->code_kpi_so;?></td>
                  <td class="tbody_td btn_so" so="<?=$id_so;?>"><?=$row->name_kpi_so;?></td>
                  <td class="tbody_td btn_so" so="<?=$id_so;?>" style="white-space: nowrap;text-align:center !important;">
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
                  <td class="tbody_td btn_so" so="<?=$id_so;?>"><?=$row->name_pic_kpi_so;?></td>
                  <td class="tbody_td btn_so" so="<?=$id_so;?>"><?=$row->ukuran;?></td>

                  <?php 
                      if($row->polarisasi == '10'){
                        echo '<td class="tbody_td btn_so" so="'.$id_so.'" >'.@$arr_target_from[$id][$year].' - '.@$arr_target_to[$id][$year].'</td>';
                      }else{
                        echo '<td class="tbody_td  btn_so" so="'.$id_so.'" >'.h_format_angka(@$arr_target[$id][$year]).'</td>';
                      }
                  ?>

                  <?php for($m=1;$m<=12;$m++){?>
                      <td class="tbody_td month month_<?=$m?> btn_so" so="<?=$id_so;?>">
                          <?php 
                              if($row->polarisasi == '10'){
                                 echo @$arr_month[$row->id][$m]['target'];
                              }else{
                                echo h_format_angka(@$arr_month[$row->id][$m]['target']);
                              } 
                          ?>
                      </td>
                      <td class="tbody_td month month_<?=$m?> btn_so" so="<?=$id_so;?>">
                          <?=h_format_angka(@$arr_month[$row->id][$m]['realisasi'])?>
                      </td>
                      <td class="tbody_td month month_<?=$m?> btn_so" so="<?=$id_so;?>">
                            <?php 
                                $huruf = (@$arr_month[$row->id][$m]['pencapaian'] == '' ? 'N' : h_format_angka(@$arr_month[$row->id][$m]['pencapaian']).' %');
                                $warna = (@$arr_month[$row->id][$m]['color'] =='' ? 'background:grey;color:white;' : @$arr_month[$row->id][$m]['color']); 
                                if(@$arr_month[$row->id][$m]['target'] == ''){
                                  $warna = 'background:black;color:white;';
                                  $huruf = 'B';
                                }
                            ?>
                          <span class="label" style="<?=$warna?>">
                            <?=$huruf?> 
                          </span>
                      </td>
                      <td class="tbody_td prognosa prognosa_<?=$m?> month month_<?=$m?> btn_so" so="<?=$id_so;?>" 
                          style="display:none;">
                          <span>
                            <?=h_format_angka(@$arr_month[$row->id][$m]['prognosa'])?>
                          </span>
                      </td>
                      <td class="tbody_td prognosa prognosa_<?=$m?> month month_<?=$m?> btn_so" so="<?=$id_so;?>" 
                          style="display:none; background:lightblue;">
                          <?php 
                                $huruf = (@$arr_month[$row->id][$m]['prognosa_pencapaian'] == '' ? 'N' : h_format_angka(@$arr_month[$row->id][$m]['prognosa_pencapaian']).' %');
                                $warna = (@$arr_month[$row->id][$m]['prognosa_color'] =='' ? 'background:grey;color:white;' : @$arr_month[$row->id][$m]['prognosa_color']); 
                                if(@$arr_month[$row->id][$m]['target'] == ''){
                                  $warna = 'background:black;color:white;';
                                  $huruf = 'B';
                                }
                          ?>
                          <span class="label" style="<?=$warna?>">
                            <?=$huruf?> 
                          </span>
                      </td>
                  <?php } ?>

                </tr>
                <?php } ?>


                
              </tbody>
            </table>


        </div>

        <br><br><br>
  </div>
</div>


<script src="<?= plugin_url('freeze_column/tableHeadFixer.js')?>" type="text/javascript" ></script>

<script type="text/javascript">
$(document).ready(function () {
  
  $("#table_view").tableHeadFixer({"top" : 2,"left" : 5});

  //cek month
  var month = $(".filtering #global_month").val();
  if(month != ''){
      $('.month').hide();
      $('.month_'+month).show();
      $('.prognosa_'+month).hide();
  }
  
  //cek triwulan
  var tw = $(".filtering #global_triwulan").val();
  var m = $(".filtering #global_month").val();
  if(tw == '' && m == ''){
    $('.cek_triwulan').trigger('click');
  }

});
</script>