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

<?php if(count($data) < 1){  echo "<br><br><h2 style='text-align:center;'>Data Tidak Ditemukan !</h2><br><br>";exit; }?>

<!-- List View -->
<div class="row" style="margin-top:1em;">
  <div class="col-md-12">
        <div id="show_hide_col">
            <div style="float:right;">
                <a href="javascript:" class="btn btn-md btn-primary show_hide_col"><i class="fa fa-list"></i>&nbsp;&nbsp;Column</a>
            </div>
            <br><br>
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
                .thead_tw{text-align:center;background:darkblue;color:white;}
                .thead_tw2{text-align:center;background:blue;color:white;}
                .tbody_td{text-align:center;}
            </style>
            <table class="table table-bordered table-hover table-wrap" id="table_view">
              <thead class="bg-primary" style="color: white;">
                <tr>
                  <th class="thead_tw" rowspan="2">Perspective</th>
                  <th class="thead_tw" rowspan="2">SO Number</th>
                  <th class="thead_tw" rowspan="2">Strategic Objectives (SO)</th>
                  <th class="thead_tw" rowspan="2">No.</th>
                  <th class="thead_tw" rowspan="2"><div style="width:15em;">Indikator</div></th>
                  <th class="thead_tw" rowspan="2">Pol</th>
                  <th class="thead_tw" rowspan="2"><div style="width:7em;">PIC</div></th>
                  <th class="thead_tw" rowspan="2"><div style="width:8em;">Ukuran</div></th>
                  <th class="thead_tw" rowspan="2">Target <?=$year?></th>
                  <?php for($i=1;$i<=4;$i++){ ?>
                    <th class="thead_tw col_tw col_tw_<?=$i?> col_<?=$i?>" colspan="<?=(@$triwulan != ''? '5"':'3')?>">TW&nbsp;<?=$i?></th>
                  <?php } ?>
                </tr>
                <tr>
                    <?php for($i=1;$i<=4;$i++){ ?>
                      <th class="thead_tw2 col_tw col_<?=$i?>">Target</th>
                      <th class="thead_tw2 col_tw col_<?=$i?>">Realisasi</th>
                      <th class="thead_tw2 col_tw col_<?=$i?>">Pencapaian</th>
                      <th class="thead_tw2 col_tw col_<?=$i?> col_prog col_<?=$i?>_5" style="display:none;">Prognosa</th>
                      <th class="thead_tw2 col_tw col_<?=$i?> col_prog col_<?=$i?>_5" style="display:none;">Prognosa&nbsp;%</th>
                    <?php } ?>
                </tr>
              </thead>

              <tbody>
                <?php $no='0'; foreach ($data as $row){ $no++; ?>
                <tr>
                  <?php 
                        //id so, untuk detail
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
                      style="<?=$so_color[$id_so];?>">
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
                            if(@$row->name_polarisasi == 'Stabilize' ){ 
                              // $name_polarisasi = '<i class="fa fa-arrow-right"></i><i class="fa fa-arrow-left"></i>'; 
                              $name_polarisasi = '<img src="'.img_url('arrow/right.png').'" width="30em;"> <img src="'.img_url('arrow/left.png').'" width="30em;" style="margin-left:-1.5em;">'; 
                            }elseif(@$row->name_polarisasi == 'Maximum'){ 
                              // $name_polarisasi = '<i class="fa fa-arrow-up"></i>'; 
                              $name_polarisasi = '<img src="'.img_url('arrow/up.png').'" width="20em;">'; 
                            }elseif(@$row->name_polarisasi == 'Minimum'){ 
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
                        $arr_target_from = explode(', ',$row->arr_target_from);
                        $arr_target_to  = explode(', ',$row->arr_target_to); 
                        $z=-1;foreach($arr_target_from as $val){ $z++;
                          echo '<td class="tbody_td btn_so" so="'.$id_so.'" >'.$val.' - '.@$arr_target_to[$z].'</td>';
                        }
                      }else{
                        $arr_target = explode(', ',$row->arr_target);
                        foreach($arr_target as $val){
                          echo '<td class="tbody_td  btn_so" so="'.$id_so.'" >'.h_format_angka($val).'</td>';
                        } 
                      } 
                  ?>

                  <?php for($i=1;$i<=4;$i++){?>
                      <td class="tbody_td col_tw col_<?=$i?> btn_so" so="<?=$id_so;?>">
                          <?php 
                              if($row->polarisasi == '10'){
                                 echo @$arr_triwulan[$row->id][$i]['target'];
                              }else{
                                echo h_format_angka(@$arr_triwulan[$row->id][$i]['target']);
                              } 
                          ?>
                      </td>
                      <td class="tbody_td col_tw col_<?=$i?> btn_so" so="<?=$id_so;?>">
                          <?=h_format_angka(@$arr_triwulan[$row->id][$i]['realisasi'])?>
                      </td>
                      <td class="tbody_td col_tw col_<?=$i?> btn_so" so="<?=$id_so;?>">
                          <span class="label" style="<?=(@$arr_triwulan[$row->id][$i]['color'] =='' ? 'background:grey;color:white;' : @$arr_triwulan[$row->id][$i]['color'])?>">
                            <?=h_format_angka(@$arr_triwulan[$row->id][$i]['pencapaian'])?> %
                          </span>
                      </td>
                      <td class="tbody_td col_tw col_<?=$i?> col_prog col_<?=$i?>_5 btn_so" so="<?=$id_so;?>" 
                          style="display:none;">
                          <span>
                            <?=h_format_angka(@$arr_triwulan[$row->id][$i]['prognosa'])?>
                          </span>
                      </td>
                      <td class="tbody_td col_tw col_<?=$i?> col_prog col_<?=$i?>_5 btn_so" so="<?=$id_so;?>" 
                          style="display:none; background:lightblue;">
                          <span class="label" style="<?=(@$arr_triwulan[$row->id][$i]['prognosa_color'] =='' ? 'background:grey;color:white;' : @$arr_triwulan[$row->id][$i]['prognosa_color'])?>">
                            <?=h_format_angka(@$arr_triwulan[$row->id][$i]['prognosa_pencapaian'])?> %
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




<div id="popup_column" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background:#3c8dbc;color:white;">
            <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
            <h3 class="modal-title" style="text-align:center;"><b>Show Column</b></h3>
          </div>
          <div class="modal-body">
            <div class="row form-group">
                <div class="col-md-12">
                    
                  <div class="row form-group">
                      <div class="col-md-offset-1 col-md-2"> <label><b>Column For&nbsp;Show&nbsp;:</b></label></div>
                        <div class="col-md-8" style="border:1px solid #eee;margin-left: 12px;width: 390px;padding: 10px;">
                            <div class="col-md-12">
                            <label><input type="checkbox" class="cek_col" value="5" <?=(@$triwulan != ''? 'checked="checked"':'')?>>Prognosa</label>
                            </div>
                            <div class="col-md-12">
                            <label><input type="checkbox" class="cek_col cek_tw" id="cek_tw_1" value="1" checked="checked">Triwulan 1</label>
                            </div>
                            <div class="col-md-12">
                            <label><input type="checkbox" class="cek_col cek_tw" id="cek_tw_2" value="2" checked="checked">Triwulan 2</label>
                            </div>
                            <div class="col-md-12">
                            <label><input type="checkbox" class="cek_col cek_tw" id="cek_tw_3" value="3" checked="checked">Triwulan 3</label>
                            </div>
                            <div class="col-md-12">
                            <label><input type="checkbox" class="cek_col cek_tw" id="cek_tw_4" value="4" checked="checked">Triwulan 4</label>
                            </div>
                        </div>
                      </div>
                      <script type="text/javascript">
                      $(document).ready(function () {
                        $('.cek_col').die().live('click',function(){
                            var val = $(this).val();
                            if(val == '5'){
                                if($(this).attr('checked') == 'checked'){
                                    var cek_tw = $(".cek_tw");
                                    $.each(cek_tw,function(){
                                        var val2 = $(this).val();
                                        if($(this).attr('checked') == 'checked'){
                                            $('.col_'+val2+'_5').show();
                                            var new_colspan = parseFloat($('.col_tw_'+val2).attr('colspan')) + 2;
                                            $('.col_tw_'+val2).attr('colspan', new_colspan);
                                        }
                                    });
                                }else{
                                    var cek_tw = $(".cek_tw");
                                    $.each(cek_tw,function(){
                                        var val2 = $(this).val();
                                        if($(this).attr('checked') == 'checked'){
                                            $('.col_'+val2+'_5').hide();
                                            var new_colspan = parseFloat($('.col_tw_'+val2).attr('colspan')) - 2;
                                            $('.col_tw_'+val2).attr('colspan', new_colspan);
                                        }
                                    });
                                }
                            }else{
                                if($(this).attr('checked') == 'checked'){
                                    $('.col_'+val).show();
                                }else{
                                    $('.col_'+val).hide();
                                }
                            }
                           
                        });
                      });
                      </script>

                  </div> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn default">Close</button>
            </div>
          </div>
        </div>
    </div>
</div>


<script src="<?= plugin_url('freeze_column/tableHeadFixer.js')?>" type="text/javascript" ></script>

<script type="text/javascript">
$(document).ready(function () {
  
  $("#table_view").tableHeadFixer({"top" : 2,"left" : 5});
  
  //show triwulan
  var triwulan = "<?=@$triwulan?>";
  if(triwulan != ''){
    $('.col_tw').hide();
    $('.col_'+triwulan).show();
    $('.col_prog').hide();
    $('.col_'+triwulan+'_5').show();
    $('.show_hide_col').hide();
  }

  // btn filter
  $('.show_hide_col').on('click',function(){
      $('#popup_column').modal();
  });

});
</script>