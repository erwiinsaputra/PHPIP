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
        <?=$data->name_kpi_so?>
    </div>
    <div class="col-md-7">
        <figure class="highcharts-figure">
            <div id="load_grafik_barline"></div>
        </figure>
    </div>
    <div class="col-md-5">
        <div class="row">
                <div class="col-md-4"><b>PIC</b></div>
                <div class="col-md-6">
                    <?=@$data->name_pic_kpi_so;?>
                </div>
                <div class="col-md-4"><b>Polarisasi</b></div>
                <div class="col-md-6">
                    <?=@$data->name_polarisasi;?>
                </div>
                <div class="col-md-4"><b>Ukuran</b></div>
                <div class="col-md-6">
                    <?=@$data->ukuran;?>
                </div>
                <div class="col-md-4"><b>Frekuensi Pengukuran</b></div>
                <div class="col-md-6">
                    <?=@$data->frekuensi_pengukuran;?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        Grafik
    </div>
    <div class="col-md-12">
        <?php for($year = $start_year; $year <= $end_year; $year++){ ?>
        <div class="row">
            <div class="col-md-3">
                <?=@$year;?>
            </div>
            <div class="col-md-9">
                asdasd
                <?=@$penyebab1[$id_kpi_so][$year];?>
                <?=@$recommendations[$id_kpi_so][$year];?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>



<script type="text/javascript">
$(document).ready(function () {

    bar = {
        options: {
            chart: { type: 'column' },
            title: { text: '' },
            credits: { enabled: true},
            subtitle: { text: '' },
            xAxis: {
                categories: [],
                crosshair: true
            },
            yAxis: [{
                className: 'highcharts-color-0',
                title: { text: '' }
            }, {
                className: 'highcharts-color-1',
                opposite: true,
                title: {  text: 'Accumulation' }
            }],
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:,.1f} $</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    point: {
                        events: {
                        click: function() {
                            var param = this.category;
                            // console.log(param);
                            bar.getDetail(param);
                            bar.detailTitle = param;
                            // location.reload(true);
                        }
                        }
                    }
                }
            },
            series: [{
                type: 'column',
                name: 'Target',
                data: [3, 2, 1, 3, 4],
                color: '#2c5981',
                dataLabels: {
                    enabled: true,
                    // format: '{point.y:,.0f}'
                    formatter: function(){
                        // console.log(this.point.category+""+month_now);
                        var a = '';
                        if(this.point.category.includes(month_now)){
                            a =  this.point.y/1000000;
                            var b = a.toFixed(1);
                        }
                        return b;
                    }
                }
            },{
                type: 'column',
                name: 'Realisasi',
                data: [2, 3, 5, 7, 6],
                color: '#008aac',
                dataLabels: {
                    enabled: true,
                    // format: '{point.y:,.0f}'
                    formatter: function(){
                        // console.log(this.point.category+""+month_now);
                        var c = '';
                        if(this.point.category.includes(month_now)){
                            c =  this.point.y/1000000;
                            var d = c.toFixed(1);
                        }
                        return d;
                    }

                }
            },{
                type: 'line',
                name: 'Pencapaian',
                data: [2, 3, 5, 7, 6],
                marker: {
                    lineWidth: 2,
                    lineColor: Highcharts.getOptions().colors[3],
                    fillColor: 'white'
                }
                dataLabels: {
                    enabled: true,
                    // format: '{point.y:,.0f}'
                    formatter: function(){
                        // console.log(this.point.category+""+month_now);
                        var c = '';
                        if(this.point.category.includes(month_now)){
                            c =  this.point.y/1000000;
                            var d = c.toFixed(1);
                        }
                        return d;
                    }

                }
            }]
        },
        width: '',
        detailTitle: '',
        getDetail: function(month) {
            // alert('a');
            $('span[name="group_title"]').text(bar.detailTitle);
            $('#modal_group_detail').modal('show');
            //ambil datanya
            var year = $('#year').val();

            // var month = data.options.name.substring(6);
            var filternya = $('.filternya');
            var arr = {};
            $.each(filternya, function(i, val) {
                    var isi = $(this).val();
                    var name = $(this).attr('name');
                    if(typeof name != "undefined"){
                    arr[name] = isi;
                    }
            });
            arr['month'] = month;
            //get data
            var url = myBaseUrl+'/load_table_detail_bar';
            var param = arr
            Metronic.blockUI({ target: '#load_table_detail_bar',  animate: true});
            $.post(url, param, function(msg){
                $('#load_table_detail_bar').html(msg);
                    Metronic.unblockUI('#load_table_detail_bar');
            });
        },
        sendRequest: function(param, url') {
            Metronic.blockUI({ target: '.load_grafik_barline',  animate: true});
            $.ajax({
                url: myBaseUrl + url,
                type: 'POST',
                dataType: 'JSON',
                data: JSON.stringify(param)
            }).done(function(result) {
                bar.generateChart(result.data);
            }); 
        },
        generateChart: function(data) {
            //console.log(data.target);
            // bar.options.xAxis.categories = data.kategori;
            bar.options.series[0].data = data.target;
            bar.options.series[1].data = data.realisasi;
            bar.options.series[2].data = data.pencapaian;

            Highcharts.chart('load_grafik_barline', bar.options);
            Metronic.unblockUI('#load_grafik_barline');

        },
        init: function() {
            var url =  = '/load_grafik_barline';
            // var param = window.param_filter();
            bar.sendRequest(param,url);
        }
    };
    bar.init();

});
</script>