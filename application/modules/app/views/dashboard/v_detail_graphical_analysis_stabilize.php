<style>
.highcharts-figure, .highcharts-data-table table {
    min-width: 310px; 
    max-width: 800px;
    margin: 1em auto;
}

#container {
    height: 400px;
}

.highcharts-data-table table {
	font-family: Verdana, sans-serif;
	border-collapse: collapse;
	border: 1px solid #EBEBEB;
	margin: 10px auto;
	text-align: center;
	width: 100%;
	max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}
</style>


<div class="row">
    <div class="col-md-12">
        <h3 style="text-align:center;"><b><?=$data->name_kpi_so?></b></h3>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-7">
        <figure class="highcharts-figure">
            <div id="load_grafik_barline"></div>
        </figure>
    </div>
    <div class="col-md-4">
        <style>
            .table_detail_kpi_so tbody tr td{ font-size:1.4em; padding:0.1em; }
            .table_detail_kpi_so{ margin-top:1.5em;}
        </style>
        <table class="table_detail_kpi_so">
            <tbody>
                <tr>
                    <td><b>PIC</b></td>
                    <td>:</td>
                    <td><?=@$data->name_pic_kpi_so;?></td>
                </tr>
                <tr>
                    <td><b>Polarisasi</b></td>
                    <td>:</td>
                    <td><?=@$data->name_polarisasi;?></td>
                </tr>
                <tr>
                    <td><b>Ukuran</b></td>
                    <td>:</td>
                    <td><?=@$data->ukuran;?></td>
                </tr>
                <tr>
                    <td><b>Frekuensi Pengukuran</b></td>
                    <td>:</td>
                    <td><?=@$data->frekuensi_pengukuran;?></td>

                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h3 style="text-align:center;"><b>Keterangan (Performance Analysis)</b></h3>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-9">
        <div style="text-align:center;">
            <style>
                .table_detail_keterangan tbody tr td{ font-size:1.4em; padding:0.1em; vertical-align:top;text-align:left;}
                .table_detail_keterangan{ margin-top:1.5em;}
            </style>
            <table class="table_detail_keterangan">
                <tbody>
                    <?php for($year = $start_year; $year <= $end_year; $year++){ ?>
                    <tr>
                        <td><b><?=@$year;?></b></td>
                        <td>:</td>
                        <td>
                            <?=@$penyebab1[$id_kpi_so][$year];?>
                            <?=@$recommendations[$id_kpi_so][$year];?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>

<!-- grafik graphical analysis -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<!-- <script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script> -->

<script type="text/javascript">
$(document).ready(function () {

    //array pencapaian %
    var start_year = <?=$start_year?>;
    var end_year = <?=$end_year?>;
    var arr_pencapaian = {};
    var pencapaian = [<?=$pencapaian?>];
    var i = 0;
    for(var year = start_year; year <= end_year; year++){
        arr_pencapaian[year] = pencapaian[i];
        i++;
    }

    //chart
    Highcharts.chart('load_grafik_barline', {
        title: {
            text: ''
        },
        xAxis: {
            categories: [<?=$category_year?>]
        },
        credits: { enabled: false},
        series: [
        {
            type: 'column',
            name: 'Realisasi',
            color: '#008aac',
            data: [<?=$realisasi?>]
           
        }, {
            type: 'line',
            name: 'Target Bawah',
            color: '#2c5981',
            data: [<?=$target_from?>]
        }, {
            type: 'line',
            name: 'Target Atas',
            color: '#2c5981',
            data: [<?=$target_to?>]
        },{
            type: 'line',
            name: 'Pencapaian',
            data: [<?=$realisasi?>],
            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            },
            dataLabels: {
                enabled: true,
                // format: '{point.y}'
                y: -30,
                formatter: function(){
                    var year = this.point.category;
                    return arr_pencapaian[year]+' %';
                }
            }
        }]
    });

});
</script>