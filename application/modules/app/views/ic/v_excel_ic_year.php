<style type="text/css">
   .tbl_header, .table{
    width:100%;
    border-collapse: collapse;
  }
  .tbl_detail{
    width:100%;
    border:0.5px solid black;
    border-collapse: collapse;
  }
  .tbl_detail tr, .tbl_detail tr td{
    padding:0px 2px 0px 2px;
  }
  .border{
    border:1px solid black;
    border-collapse: collapse;
  }
  .border_lf{
    border-left:1px solid black;
    border-right:1px solid black;
    border-collapse: collapse;
  }
  .border_lft{
    border-left:1px solid black;
    border-right:1px solid black;
    border-top:1px solid black;
    border-collapse: collapse;
  }
  .rata_tengah{
    text-align:center;
    vertical-align:middle;
  }
  .rata_tengah2{
    text-align:center;
    vertical-align:middle;
    color:blue;
  }

  .color-header{
    background-color: #2c5da3;
    color:white;
  }
  .color-header-sales{
    background-color: #ee9600;
  }
  .color-header-total{
    background-color: #123057;
    color:white;
  }  
  .color-header-total-sub{
    background-color: #ffbb00;
  }  

  .hide2{
    display:none;
  }

  .head_col{
    font-size: 12px; background-color: #5b9bd1; color: #fff;
  }  

  .vertical-text {
    text-transform:capitalize;
    transform: rotate(90deg);
    /* transform-origin: left bottom 20px;  */
  }

  .middle{
      vertical-align:middle;
  }
  .top-text{
      vertical-align: top; 
  }

</style>

  <h2 align="center">Summary KPI-SO</h2>

   <table class="table">
      <tbody>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td style="width:100px;"><b>Periode : <?=@$periode;?></b></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td style="width:100px;"><b>Balance Scorecard : <?=@$bsc;?></b></td>
            </tr>
      </tbody>
  </table>

  <br>

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


  <table class="tbl_detail tbl_header" border="1">
    <thead>
      <tr>
        <th rowspan="2" class="head_col">Perspective</th>
        <th rowspan="2" class="head_col">SO No</th>
        <th rowspan="2" class="head_col">Strategic Objective (SO)</th>
        <th rowspan="2" class="head_col">No.</th>
        <th rowspan="2" class="head_col">Indikator</th>
        <th rowspan="2" class="head_col">Polarisasi</th>
        <th rowspan="2" class="head_col">PIC</th>
        <th rowspan="2" class="head_col">Ukuran</th>
        <th colspan="5" class="head_col">KPI-SO</th>
      </tr>
      <tr>

        <?php $pecah = explode(' - ',$periode); ?>
        <?php for($year=$pecah[0];$year<=$pecah[1];$year++){ ?>
            <th class="head_col"><?=$year?></th>
        <?php } ?>

      </tr>
    </thead>
    <tbody>

        <?php $no='0'; foreach ($data as $row){ $no++; ?>
        <tr>
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

          <!-- <td rowspan=""><?=$perscpective;?></td>
          <td rowspan=""><?=$code_so;?></td>
          <td rowspan=""><?=$name_so;?></td> -->
          <td class="" ><?=$row->code_kpi_so;?></td>
          <td class="" ><?=$row->name_kpi_so;?></td>
          <td class="" ><?=$row->name_polarisasi;?></td>
          <td class="" ><?=$row->name_pic_kpi_so;?></td>
          <td class="" ><?=$row->ukuran;?></td>

          <?php if($row->polarisasi == '10'){ ?>
            <?php $arr_target_from = explode(', ',$row->arr_target_from); ?>
            <?php $arr_target_to = explode(', ',$row->arr_target_to); ?>
            <?php $z=-1; foreach($arr_target_from as $val){ $z++;?>
              <td class="" ><?=$val.' - '.@$arr_target_to[$z];?></td>
            <?php } ?>
          <?php }else{ ?>
            <?php $arr_target = explode(', ',$row->arr_target); ?>
            <?php foreach($arr_target as $val){ ?>
              <td class="" ><?=$val;?></td>
            <?php } ?>
          <?php } ?>

        </tr>
        <?php } ?>
    </tbody>
  </table>