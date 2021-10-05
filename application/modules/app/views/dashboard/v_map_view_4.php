<!--
<div style="text-align:center;">
    <h2 style="text-align:center;"><b>Strategic Map View</b></h2>
</div>
-->

<style>
#load_svg {
  transform: scale(0.65);
  margin-left: -13.7em;
  margin-top: -9.7em;
  margin-bottom: -9em;
}
.green_box{ height: 1.7em; width: 1.7em; border: 1px solid black; font-size: 1.5em; text-align: center; padding: 3px; color: white; background: green;}
.yellow_box{ height: 1.7em; width: 1.7em; border: 1px solid black; font-size: 1.5em; text-align: center; padding: 3px; color: black; background: yellow;}
.red_box{ height: 1.7em; width: 1.7em; border: 1px solid black; font-size: 1.5em; text-align: center; padding: 3px; color: white; background: red;}
.black_box{ height: 1.7em; width: 1.7em; border: 1px solid black; font-size: 1.5em; text-align: center; padding: 3px; color: white; background: black;}
.grey_box{ height: 1.7em; width: 1.7em; border: 1px solid black; font-size: 1.5em; text-align: center; padding: 3px; color: black; background: lightgrey;}

.title_warna{ font-size:1em; font-weight:bold; margin-bottom:1em;}
.ket_warna{ padding:5px 0px 5px 5px; }

.item_strategic_theme:hover { cursor:pointer;} 
.title_strategic_theme { 
  background: radial-gradient(#2476C0 0%, #1D62A1 50%, #20476A 100%);
  width:11.5em; height:4.5em;
  float:left;
  color:white;
  margin-right:3em;
}
.item_strategic_theme { 
  background: radial-gradient(#00B8FF 0%, #009AD9 50%, #006A96 100%);
  width:10.1em; height:4.5em;
  float:left;
  color:white;
  margin-right:3em;
}
.img_strategic_theme{
  margin-top: 2em;
  margin-left: 0.7em;
  float:left;
}
.img_strategic_theme2{
  width: 6em;
  margin-top: -0.4em;
  margin-left: -2.2em;
  float:left;
}
.text_strategic_theme{
  color: white;
  font-size: 1em;
  float: left;
  width: 7.5em;
  margin-top: 1em;
  text-align: right;
}
.text_strategic_theme2{
  color:white;
  font-size:0.9em;
  float:left;
  width: 7.5em;
  margin-left: -0.8em;
  text-align: center;
  padding: 0.3em;
}

.middle_center {
  height: 4.5em;
  line-height: 4.5em;
  text-align: center;
}
.middle_center2 {
  display: inline-block;
  vertical-align: middle;
  line-height: normal;
}


</style>

<?php 
  $file_name = "public/files/strategy_map/".$id_bsc."_".$id_periode.".svg";
  if (file_exists($file_name)) {
    $hidden = "";
    $isi_kosong = "";
    $file =  file_get_contents($file_name);
  }else{
    $file = "";
    $isi_kosong = "<h1 style='text-align:center;margin-left:1em;margin-top:4em;margin-bottom:2em;'><b>Data Tidak Ditemukan !</b></h1>";
    $hidden = "display:none;";
  }
?>

<!-- Strategi Maps -->
<div class="row" style="margin-top:0em;">


  <?php 
    $file_name = "public/files/strategy_map/".$id_bsc."_".$id_periode.".svg";
    if (!file_exists($file_name)) {
      echo "<h1 style='text-align:center;margin-left:1em;margin-top:2em;margin-bottom:2em;'><b>Data Tidak Ditemukan !</b></h1>";
    }
  ?>
  



  <?php if (file_exists($file_name)) { ?>

      <div class="" style="<?=$hidden?> margin-left:1.2em; margin-right:-1em;float:left;">

        <div class="row">
          <div class="col-md-12" style="margin-left:0.1em;margin-bottom:1em;">
            <div class="title_strategic_theme">
              <div class="img_strategic_theme">
                <img style="width:2.5em;" src="<?php echo base_url(); ?>public/assets/app/img/icon/grafik.png">
              </div>
              <div class="text_strategic_theme">Strategic Result Overview</div>
            </div>

            <?php foreach($strategic_theme as $row){ ?>
            <div class="item_strategic_theme" id_nya="<?=$row->id?>" title="<?=$row->name?>">
              <div class="img_strategic_theme2">
                <img style="width:5.5em;" src="<?php echo base_url(); ?>public/files/icon_strategic_theme/<?=$row->icon?>">
              </div>
              <div class="middle_center">
                <span class="text_strategic_theme2 middle_center2"><?=h_text_space_to_br($row->name)?></span>
              </div>
            </div>
            <?php } ?>

          </div>
        </div>

        <div class="scroll_map" style="overflow-x:scroll;overflow-y:auto; height:39em;direction:ltr;width:65em;">
          <div id="load_svg" >
            <?=file_get_contents($file_name);?>
          </div>
        </div>
      </div>
      
      <div class="" style="<?=$hidden?> float:left;padding-left:0px;width:13em;">

        <div class="row">
          <div class="col-md-12" style="margin-left:0.1em;margin-bottom:1em;">
            <div style="width:11.5em; height:5em;"></div>
          </div>
        </div>


        <div class="keterangan_warna" >
          <div class="title_warna">Keterangan Warna :</div>
          <table class="table_warna" width="100%">
            <tbody>
              <tr>
                <td class="text_warna"><div class="green_box">G</div></td>
                <td class="ket_warna">Semua Indikator Tercapai</td>
              </tr>
              <tr>
                <td class="text_warna"><div class="yellow_box">Y</div></td>
                <td class="ket_warna">Sebagian Besar Indikator Tercapai</td>
              </tr>
              <tr>
                <td class="text_warna"><div class="red_box">R</div></td>
                <td class="ket_warna">Sebagian Kecil Indikator Tercapai</td>
              </tr>
              <tr>
                <td class="text_warna"><div class="grey_box">N</div></td>
                <td class="ket_warna">Belum Masuk Periode Pengukuran</td>
              </tr>
              <tr>
                <td class="text_warna"><div class="black_box">B</div></td>
                <td class="ket_warna">Tidak Aktif Pada Periode Tertentu</td>
              </tr>
            </tbody>
          </table>
          
        </div>
      </div>
  <?php } ?>

</div>


<script type="text/javascript">
$(document).ready(function () {

    var text = $('#load_svg').find('text');
    $.each(text,function(){
      var str = $(this).text();
      var cek = str.indexOf("Id");
      if(cek != '-1'){
        var id = str.substring(2).replace(" ", "");
        // console.log(id);
        var so_color = <?=$so_color?>;
        // console.log(so_color);
        var txt = so_color[id];
        
        var txt_bg = "";
        var txt_color = "";
        
        if(txt == 'N'){
          txt_bg = "grey";
          txt_color = "white";
        }
        if(txt == 'R'){
          txt_bg = "red";
          txt_color = "white";
        }
        if(txt == 'Y'){
          txt_bg = "yellow";
          txt_color = "black";
        }
        if(txt == 'G'){
          txt_bg = "darkgreen";
          txt_color = "white";
        }
        if(txt == '' || txt === null ){
          txt = 'B';
          txt_bg = "black";
          txt_color = "white";
        }

        //warna
        $(this).prev('path').css({"cursor": "pointer"});
        $(this).prev('path').attr('fill',txt_bg);
        $(this).prev('path').attr('class','btn_so');
        $(this).prev('path').attr('so',id);
        //text
        $(this).text(txt);
        $(this).css({"cursor": "pointer", "font-size":"2.7em"});
        $(this).attr('fill',txt_color);
        $(this).attr('class','btn_so');
        $(this).attr('so',id);
        //position
        var pos = $(this).attr('transform').replace("translate(", "").replace(")", "").split(' ');
        var x = (parseFloat(pos[0])-7).toFixed();
        var y = (parseFloat(pos[1])+15).toFixed();
        // console.log(x+' '+y);
        $(this).attr('transform','translate('+x+' '+y+')');

        // <tspan font-size="25" x="1.66669" y="13">N</tspan>
        // $(this).attr('transform','translate(553.951 130)');
       
      }
    });
    $('#load_svg').show();


    //btn detail view
    $('.item_strategic_theme').on('click',function(){
        $('#popup_detail_strategic_theme').modal();
        var id = $(this).attr('id_nya');
        var title = $(this).attr('title');
        $('#popup_detail_strategic_theme').find('.modal-title').text(title);
        var url = '<?=site_url($url)?>/load_detail_strategic_theme';
        var filter = $('#form_filtering').serializeArray();
        var param = {id:id, filter:filter}
        Metronic.blockUI({ target: '#load_detail_strategic_theme',  boxed: true});
        $.post(url, param, function(msg){
            $('#load_detail_strategic_theme').html(msg);
            Metronic.unblockUI('#load_detail_strategic_theme');
        });
    });
  
});
</script>






