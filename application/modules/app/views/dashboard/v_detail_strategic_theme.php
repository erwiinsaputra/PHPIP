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

<!-- grafik graphical analysis -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<!-- <script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script> -->


<?php foreach($strategic_result as $row){ $id_sr = $row->id; ?>

<div class="panel-group accordion" id="accordion3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title" style="font-size:2em;text-align:center;">
                <a style="font-size:0.8em !important;" class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion3" href="#tab_<?=$row->id?>">
                    (<?=$row->code_sr?>). <?=$row->name_sr?> 
                </a>
            </h4>
        </div>
        <div id="tab_<?=$row->id?>" class="panel-collapse in">
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                        <style>
                            .table_detail_sr tbody tr td{ font-size:1.4em; padding:0.1em; }
                            .table_detail_sr{ margin-top:1.5em;}
                        </style>
                        <table class="table_detail_sr">
                            <tbody>
                                <tr>
                                    <td style="width:100%;" colspan="3"><u><b>(<?=$row->code_sr?>). <?=$row->name_sr?> </b></u></td>
                                </tr>
                            </tbody>
                        </table>        
                        <table class="table_detail_sr">
                            <tbody>
                                <tr>
                                    <td style="width:45%;"><b>Indikator</b></td>
                                    <td style="width:10%;">:</td>
                                    <td style="width:45%;"><?=@$row->indikator;?></td>
                                </tr>
                                <tr>
                                    <td style="width:45%;"><b>Polarisasi</b></td>
                                    <td style="width:10%;">:</td>
                                    <td style="width:45%;"><?=@$row->name_polarisasi;?></td>
                                </tr>
                                <tr>
                                    <td style="width:45%;"><b>Ukuran</b></td>
                                    <td style="width:10%;">:</td>
                                    <td style="width:45%;"><?=@$row->ukuran;?></td>
                                </tr>
                                <tr>
                                    <td style="width:45%;"><b>Long Term Target</b></td>
                                    <td style="width:10%;">:</td>
                                    <td style="width:45%;"><?=@$row->target;?></td>
                                </tr>
                                <tr>
                                    <td style="width:100%;" colspan="3" >
                                        <div style="display: block;margin-left: auto;margin-right: auto;width: 50%;margin-top:2em;">
                                            <div style="background:lightblue; border:1px solid lightblue; border-radius: 100px !important;width:5em;height:5em;">
                                                <?php 
                                                    $name_polarisasi = ''; 
                                                    if(@$row->polarisasi == '10' ){ 
                                                        $name_polarisasi = '<img src="'.img_url('arrow/right.png').'" style="width:5em;margin-top:1em;margin-left:-1.1em;"> <img src="'.img_url('arrow/left.png').'" style="width:5em;margin-top:-4.4em; margin-left:1em;">'; 
                                                    }elseif(@$row->polarisasi == '8'){ 
                                                        $name_polarisasi = '<img src="'.img_url('arrow/up.png').'" style="width:5em;margin-top:-2em;">'; 
                                                    }elseif(@$row->polarisasi == '9'){ 
                                                        $name_polarisasi = '<img src="'.img_url('arrow/down.png').'" style="width:5em;margin-top:-2em;">'; 
                                                    }
                                                ?>
                                                <?=$name_polarisasi?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-7 btn_detail_strategic_result" 
                        title="(<?=$row->code_sr?>). <?=$row->name_sr?>" 
                        id_nya="<?=$id_sr?>" 
                        category_year="<?=$data_sr[$id_sr]['category_year']?>"
                        target="<?=$data_sr[$id_sr]['target']?>"
                        pencapaian="<?=$data_sr[$id_sr]['pencapaian']?>"
                        realisasi="<?=$data_sr[$id_sr]['realisasi']?>"
                        deviasi="<?=$data_sr[$id_sr]['deviasi']?>"
                        keterangan="<?=$data_sr[$id_sr]['keterangan']?>"
                        recommendations="<?=$data_sr[$id_sr]['recommendations']?>"
                    >
                        <figure class="highcharts-figure">
                            <div id="load_grafik_barline_<?=$id_sr?>"></div>
                        </figure>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
$(document).ready(function () {

    //array pencapaian %
    var start_year = <?=$start_year?>;
    var end_year = <?=$end_year?>;
    var arr_pencapaian = {};
    var pencapaian = [<?=$data_sr[$id_sr]['pencapaian']?>];
    var i = 0;
    for(var year = start_year; year <= end_year; year++){
        arr_pencapaian['Q2-'+String(year).substr(2)] = pencapaian[i];
        arr_pencapaian['Q4-'+String(year).substr(2)] = pencapaian[i+1];
        i++;
    }
    // console.log(arr_pencapaian);

    //chart
    Highcharts.chart('load_grafik_barline_<?=$id_sr?>', {
        title: {
            text: 'Realisasi Strategic Result'
        },
        xAxis: {
            categories: [<?=$data_sr[$id_sr]['category_year']?>]
        },
        yAxis: {
            title: {
                text: 'Jumlah Expert (Person)'
            }
        },
        plotOptions: {
            series: { pointWidth: 25 }
        },
        credits: { enabled: false},
        series: [
        {
            type: 'column',
            name: 'Realisasi',
            color: '#ED7D31',
            data: [<?=$data_sr[$id_sr]['realisasi']?>],
            dataLabels: {
                enabled: true,
                format: '{y} ',
                y: 20,
            },
        }, {
            type: 'line',
            name: 'Target',
            color: '#2c5981',
            data: [<?=$data_sr[$id_sr]['target']?>],
            dataLabels: {
                enabled: true,
                format: '{y} ',
                y: 20,
            },
        }
        // ,{
        //     type: 'line',
        //     name: 'Pencapaian',
        //     data: [<?=$data_sr[$id_sr]['pencapaian']?>],
        //     marker: {
        //         lineWidth: 4,
        //         // lineColor: Highcharts.getOptions().colors[3],
        //         lineColor: '#5B9BD5',
        //         fillColor: 'white'
        //     },
        //     dataLabels: {
        //         enabled: true,
        //         // format: '{point.y}'
        //         // y: -30,
        //         formatter: function(){
        //             var cat = this.point.category;
        //             return arr_pencapaian[cat]+' %';
        //         }
        //     }
        // }
        ]
    });


    $('.btn_detail_strategic_result').off().on('dblclick',function(){
        var id = $(this).attr('id_nya');
        var category_year = $(this).attr('category_year');
        var target = $(this).attr('target');
        var pencapaian = $(this).attr('pencapaian');
        var realisasi = $(this).attr('realisasi');
        var deviasi = $(this).attr('deviasi');
        var keterangan = $(this).attr('keterangan');
        var recommendations = $(this).attr('recommendations');
        var title = $(this).attr('title');
        $('#popup_detail_strategic_result').modal();
        $('#popup_detail_strategic_result').find('.modal-title').text(title);
        var url = '<?=site_url($url)?>/load_detail_strategic_result';
        var filter = $('#form_filtering').serializeArray();
        var param = {id:id, category_year:category_year, target:target, pencapaian:pencapaian, realisasi:realisasi, deviasi:deviasi, keterangan:keterangan, recommendations:recommendations}
        Metronic.blockUI({ target: '#load_detail_strategic_result',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_detail_strategic_result').html(msg);
            Metronic.unblockUI('#load_detail_strategic_result');
        });
    });

});
</script>

<?php } ?>