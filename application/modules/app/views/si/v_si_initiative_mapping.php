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

<?php if(count(@$arr_id_so) < 1){  
  echo "<br><br><h2 style='text-align:center;margin-left:1em;margin-top:1em;margin-bottom:1em;'><b>Data Tidak Ditemukan !</b></h2><br><br>";exit; 
}?>

<!-- List View -->
<div class="row" style="margin-top:0em;">
  <div class="col-md-12">

        <div style="height:35em;">
            <style type="text/css">
                .thead_month{text-align:center;background:darkblue;color:white; font-size:0.8em !important;}
                .thead_month2{text-align:center;background:#5B9BD5;color:white; font-size:0.8em !important;}
                .tbody_td{text-align:center;font-size:0.8em !important;padding: 0.5em !important;}
                .middle{vertical-align:middle !important;}
                .title_mapping{font-size:1em !important;vertical-align:middle !important;}
            </style>
            <table class="table table-bordered table-hover table-wrap" id="table_initiative_mapping">
              <thead class="bg-primary" style="color: white;">
                <tr>
                  <th class="thead_month top_align title_mapping" colspan="2">Initiative Mapping</th>
                  <!-- SO Code-->
                  <?php foreach (@$arr_id_so as $id_so){ 
                          $code_so = str_replace(',','.',@$arr_code_so[$id_so]); 
                          $name_so = @$arr_name_so[$id_so]; 
                          $jum_kpi_so = count(@$arr_jum_kpi_so[$id_so]);
                  ?>
                  <th class="thead_month title_tip" title="<?='('.$code_so.') '.$name_so?>" colspan="<?=$jum_kpi_so?>"><?=$code_so?></th>
                  <?php } ?>
                </tr>  
                <tr>  
                  <th class="thead_month middle" style="width:5%;font-size:0.8em !important;">SI</th>
                  <th class="thead_month middle" style="width:10%;font-size:0.8em !important;">SI Title</th>
                  <!-- KPI-SO Code-->
                  <?php foreach (@$arr_id_so as $id_so){  ?>
                      <?php foreach (array_keys($arr_id_kpi_so[$id_so]) as $id_kpi_so){ 
                          $code_kpi_so = str_replace(',','.',@$arr_code_kpi_so[$id_so][$id_kpi_so]); 
                          $name_kpi_so = @$arr_name_kpi_so[$id_so][$id_kpi_so]; 
                      ?>
                      <th class="thead_month2 title_tip"  title="<?='('.$code_kpi_so.') '.$name_kpi_so?>">KPI- <?=$code_kpi_so?></th>
                      <?php } ?>
                  <?php } ?>
                  <!-- ============== -->
                </tr>
              </thead>
              <tbody>
                <?php $no='0'; foreach (@$arr_si as $row){ $no++; 
                    //warna kolom
                    if ($no % 2 == 0){ //Kondisi
                        $color = "#EAEFF7";
                    }else {
                        $color = "#D2DEEF";
                    }
                ?>
                  <tr>
                    <?php 
                          //param 
                          $id_si = $row->id;
                          $code_si = str_replace(',','.',$row->code);
                          $nama_si = $row->name;
                    ?>
                    <td class="tbody_td" style="background:#9DC3E6;color:black;">
                        <?=$code_si;?>
                    </td>
                    <td class="tbody_td" style="background:#9DC3E6;color:black;">
                        <?=h_read_more($nama_si,60);?>
                    </td>

                    <!-- SO dan KPI-SO -->
                    <?php foreach (@$arr_id_so as $id_so){ ?>
                      <?php foreach (array_keys($arr_id_kpi_so[$id_so]) as $id_kpi_so){ ?>
                        <!-- Direct  Indirect-->
                        <?php if(@$arr_direct[$id_si][$id_kpi_so] == '1'){ ?>
                          <td class="tbody_td" style="background:#BF9000;color:black;">D
                          <?php //echo $id_si.'-'.$id_kpi_so?></td>
                        <?php }elseif(@$arr_direct[$id_si][$id_kpi_so] == '0'){ ?>
                          <td class="tbody_td" style="background:#FFF2CC;color:black;">I
                          <?php //echo $id_si.'-'.$id_kpi_so?></td>
                        <?php }else{?>
                          <td class="tbody_td" style="background:<?=$color?>;color:black;"></td>
                        <?php } ?>
                      <?php } ?>
                    <?php } ?>
                    <!-- ============== -->
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
  
  $("#table_initiative_mapping").tableHeadFixer({"top" : 2,"left" : 2});

  // title_tip 
  $('.title_tip').hover(function(){
          // Hover over code
          var title = $(this).attr('title');
          $(this).data('tipText', title).removeAttr('title');
          $('<p class="title_css"></p>').text(title).appendTo('body').fadeIn('slow');
  }, function() {
          // Hover out code
          $(this).attr('title', $(this).data('tipText'));
          $('.title_css').remove();
  }).mousemove(function(e) {
          var mousex = e.pageX + 20; //Get X coordinates
          var mousey = e.pageY + 10; //Get Y coordinates
          $('.title_css').css({ top: mousey, left: mousex })
  });
});
</script>