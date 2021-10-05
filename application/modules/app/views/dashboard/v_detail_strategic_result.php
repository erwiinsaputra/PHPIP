<div class="row">
    <div class="col-md-12" style="padding:0 45px 0 45px;">
        <div class="">
            <table class="table table-bordered" id="table_detail_strategic_result">
                <thead>
                    <tr>
                        <th style="text-align:center;background-color: rgb(61, 122, 177);color:white;">&nbsp;</th>
                        <?php foreach($category_year as $val){?>
                            <th style="text-align:center;background-color: rgb(61, 122, 177);color:white;"><?=$val?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:center;background:lightblue;"><b>Target</b></td>
                        <?php foreach($target as $val){?>
                            <td style="text-align:center;"><?=$val?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="text-align:center;background:lightblue;"><b>Realisasi</b></td>
                        <?php foreach($realisasi as $val){?>
                            <td style="text-align:center;"><?=$val?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="text-align:center;background:lightblue;"><b>Pencapaian</b></td>
                        <?php foreach($pencapaian as $val){?>
                            <td style="text-align:center;background:lightgrey;"><?=str_replace('0.00','0',$val)?> %</td>
                        <?php } ?>
                    </tr> 
                    <tr>
                        <td style="text-align:center;background:lightblue;"><b>Gap / Deviasi</b></td>
                        <?php foreach($deviasi as $val){?>
                            <td style="text-align:center;"><?=$val-100?> %</b></td>
                        <?php } ?>
                    </tr>     
                </tbody>
            </table>
        </div>

        <hr style="margin-top:0px;">

        <div class="">
            <table class="table table-bordered" id="table_performance_analys">
                <thead>
                    <tr>
                        <th style="text-align:center;background-color: rgb(61, 122, 177);color:white;">&nbsp;</th>
                        <th style="text-align:center;background-color: rgb(61, 122, 177);color:white;">Keterangan</th>
                        <th style="text-align:center;background-color: rgb(61, 122, 177);color:white;">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=-1; foreach($category_year as $val){ $i++; ?>
                    <tr>
                        <td style="text-align:center;background:lightblue;"><b><?=$val?></b></td>
                        <td style="text-align:center;"><?=@$keterangan[$i]?></td>
                        <td style="text-align:center;"><?=@$rekomendasi[$i]?></td>
                    </tr>
                    <?php } ?>  
                </tbody>
            </table>
        </div>
    </div>
</div>