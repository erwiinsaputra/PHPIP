<link href="<?= plugin_url('grid_view_scroll/css/web.css')?>" rel="stylesheet" />
<script src="<?= plugin_url('grid_view_scroll/js/gridviewscroll.js')?>" type="text/javascript" ></script>
<script type="text/javascript">
    window.onload = function () {
        var options = new GridViewScrollOptions();
        options.elementID = "gvMain";
        options.width = 850;
        options.height = 350;
        options.freezeColumn = true;
        options.freezeFooter = true;
        options.freezeColumnCssClass = "GridViewScrollItemFreeze";
        options.freezeFooterCssClass = "GridViewScrollFooterFreeze";
        options.freezeHeaderRowCount = 3;
        options.freezeColumnCount = 3;

        gridViewScroll = new GridViewScroll(options);
    }
    function enhance() {
        gridViewScroll.enhance();
    }
    function undo() {
        gridViewScroll.undo();
    }
</script>

<div style="text-align:center;">
    <h2 style="text-align:center;"><b>Strategic Table View</b></h2>
</div>

<input type="button" value="Enhance" onclick="enhance();" />
    <input type="button" value="Undo" onclick="undo();" />

<!-- List View -->
<div class="row" [hidden]="viewListDashboard">
  <div class="col-lg-12">
    <nb-card>
      <nb-card-body>

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
                .thead_tw2{text-align:center;background:darkblue;color:white;}
                .tbody_td2{text-align:center;}
            </style>
            <table cellspacing="0" id="gvMain" style="width:100%;border-collapse:collapse;">
                <tr class="GridViewScrollHeader">
                  <th scope="col" class="thead_tw" rowspan="3" colspan="2">SO Number</th>
                  <th scope="col" class="thead_tw" rowspan="3">Strategic Objectives (SO)</th>
                  <th scope="col" class="thead_tw bug_no" rowspan="3">No.</th>
                  <th scope="col" class="thead_tw" rowspan="3" colspan="2">Indikator</th>
                  <th scope="col" class="thead_tw" rowspan="3">PIC</th>
                  <th scope="col" class="thead_tw" rowspan="3">Ukuran</th>
                  <th scope="col" class="thead_tw" rowspan="3">Target <?=$year?></th>
                  <th class="thead_tw" colspan="20">KPI</th>
                </tr>
                <tr class="GridViewScrollHeader">
                  <?php for($i=1;$i<=4;$i++){ ?>
                    <th scope="col" class="thead_tw col_tw col_tw_<?=$i?> col_<?=$i?>" colspan="5">TW&nbsp;<?=$i?></th>
                  <?php } ?>
                </tr>
                <tr class="GridViewScrollHeader">
                    <?php for($i=1;$i<=4;$i++){ ?>
                      <th scope="col" class="thead_tw col_tw col_<?=$i?>">Target</th>
                      <th scope="col" class="thead_tw col_tw col_<?=$i?>">Realisasi</th>
                      <th scope="col" class="thead_tw col_tw col_<?=$i?>">Pencapaian</th>
                      <th scope="col" class="thead_tw col_tw col_<?=$i?> col_<?=$i?>_5">Prognosa</th>
                      <th scope="col" class="thead_tw col_tw col_<?=$i?> col_<?=$i?>_5">Prognosa&nbsp;%</th>
                    <?php } ?>
                </tr>

                <?php $no='0'; foreach ($data as $row){ $no++; ?>
                <tr class="GridViewScrollItem">
                  <?php 
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
                  <td class="top-text" rowspan="<?=count(@$rowspan_perspective[$row->name_perspective]);?>"><?=$perscpective;?></td>
                  <?php } ?>
                  <?php if($code_so == $row->code_so){ ?>
                  <td class="top-text" rowspan="<?=count(@$rowspan_code_so[$row->code_so]);?>"><?=$code_so;?></td>
                  <?php } ?>
                  <?php if($name_so == $row->name_so){ ?>
                  <td class="top-text" rowspan="<?=count(@$rowspan_name_so[$row->name_so]);?>"><?=$name_so;?></td>
                  <?php } ?>

                  <?php 
                        //rowspan 
                        $perscpective = $row->name_perspective; 
                        $code_so = $row->code_so;
                        $name_so = $row->name_so;
                  ?>

                  <td class="" ><?=$row->code_kpi_so;?></td>
                  <td class="" ><?=$row->name_kpi_so;?></td>
                  <td class="" style="white-space: nowrap;">
                        <?php 
                            $name_polarisasi = ''; 
                            if(@$row->polarisasi == '10' ){ 
                              $name_polarisasi = '<i class="fa fa-arrow-right"></i><i class="fa fa-arrow-left"></i>'; 
                            }elseif(@$row->polarisasi == '8'){ 
                              $name_polarisasi = '<i class="fa fa-arrow-up"></i>'; 
                            }elseif(@$row->polarisasi == '9'){ 
                              $name_polarisasi = '<i class="fa fa-arrow-down"></i>'; 
                            }
                        ?>
                        <?=$name_polarisasi;?>
                  </td>
                  <td class="" ><?=$row->name_pic_kpi_so;?></td>
                  <td class="" ><?=$row->ukuran;?></td>

                  <?php 
                      if($row->polarisasi == '10'){
                        $arr_target_from = explode(', ',$row->arr_target_from);
                        $arr_target_to  = explode(', ',$row->arr_target_to); 
                        $z=-1;foreach($arr_target_from as $val){ $z++;
                          echo '<td class="" >'.$val.' - '.@$arr_target_to[$z].'</td>';
                        }
                      }else{
                        $arr_target = explode(', ',$row->arr_target);
                        foreach($arr_target as $val){
                          echo '<td class="" >'.h_format_angka($val).'</td>';
                        } 
                      } 
                  ?>

                  <?php for($i=1;$i<=4;$i++){?>
                      <td class="col_tw col_<?=$i?>">
                          <?php 
                              if($row->polarisasi == '10'){
                                 echo @$arr_triwulan[$row->id][$i]['target'];
                              }else{
                                echo h_format_angka(@$arr_triwulan[$row->id][$i]['target']);
                              } 
                          ?>
                      </td>
                      <td class="col_tw col_<?=$i?>"><?=h_format_angka(@$arr_triwulan[$row->id][$i]['realisasi'])?></td>
                      <td class="col_tw col_<?=$i?>">
                          <span class="label" style="<?=(@$arr_triwulan[$row->id][$i]['color'] =='' ? 'background:grey;color:white;' : @$arr_triwulan[$row->id][$i]['color'])?>">
                            <?=h_format_angka(@$arr_triwulan[$row->id][$i]['pencapaian'])?> %
                          </span>
                      </td>
                      <td class="col_tw col_<?=$i?> col_<?=$i?>_5" >
                          <span>
                            <?=h_format_angka(@$arr_triwulan[$row->id][$i]['prognosa'])?>
                          </span>
                      </td>
                      <td class="col_tw col_<?=$i?> col_<?=$i?>_5" >
                          <span class="label" style="<?=(@$arr_triwulan[$row->id][$i]['prognosa_color'] =='' ? 'background:grey;color:white;' : @$arr_triwulan[$row->id][$i]['prognosa_color'])?>">
                            <?=h_format_angka(@$arr_triwulan[$row->id][$i]['prognosa_pencapaian'])?> %
                          </span>
                      </td>
                  <?php } ?>

                </tr>
                <?php } ?>

                
            </table>


        </div>
      </nb-card-body>
    </nb-card>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function () {
  // $("#gvMain").tableHeadFixer({"top" : 2,"left" : 5});
});
</script>