<style type="text/css">   
  table { border-collapse: collapse !important; } 
  .container { margin: 0 !important; padding: 0 !important; height: 100% !important; width: 100% !important;  background-color: #c0ddff;}  
</style>

<div class="container">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;"> 
      <tr> 
        <td align="center" style="background-color: #c0ddff;" bgcolor="#c0ddff">
          <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
            <tr>
              <td align="center" valign="top" style="font-size:0; padding-bottom: 25px; padding-top: 25px;padding-right: 25px;padding-left: 25px;" bgcolor="#003b6d">
                <div style="display:inline-block; max-width:100%; min-width:100px; vertical-align:top; width:100%;">
                  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                    <tr>
                      <td align="center" style="height:40px; font-size:20px;">
                        <i><b>
                              <div style="color:white; font-weight:800;">
                                  INDONESIA POWER
                              </div>
                          </b>
                        </i>
                      </td>
                    </tr>
                  </table>
                </div> 
              </td>
            </tr>
            <tr>
              <td align="center" style="background-color: #ffffff;" bgcolor="#ffffff">

                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;"> 
                  <tr>
                    <td align="left">

                      <table cellspacing="0" cellpadding="0" border="0" width="100%" style="width:100%;">
                        <tr>
                          <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; color:black; font-size: 15px; font-weight:800;">
                            <b><?=@$subject;?></b>
                          </td>
                        </tr>
                        <tr>
                          <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; color:black; font-size: 15px; font-weight:800;">
                              Dear Mr/Mrs  :<br>
                              <b><?=@$fullname;?></b> <br>
                              (<?=@$title;?>)
                          </td>
                        </tr>
                        <!-- <tr>  
                          <td align="center">
                            <table cellspacing="0" cellpadding="0" border="0" width="60%" style="width:60%;">
                              <tr>
                                <td width="40%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 10px; padding: 15px 10px 5px 10px;">
                                 Request From :
                                </td>
                                <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 400; line-height: 10px; padding: 15px 10px 5px 10px;">
                                  <?=@$from;?>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr> -->
                        <tr>  
                          <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 400; line-height: 10px; padding: 15px 10px 5px 10px;">
                            <?php if($status == '1'){ ?>
                              <br>
                              Pembatalan Permohonan Review dan Approval <br>
                              Monitoring & Evaluation KPI-SO,<br><br>
                              From : <?=@$request_by?> <br>
                              Date : <?=date('d F Y',strtotime(@$request_date))?><br><br>
                              KPI-SO : <?='('.@$kpi_so_code.') '.@$kpi_so_name;?><br><br>
                              Klik Link Dibawah untuk melihat data:<br><br>
                              <a href="<?=$link;?>" target="_blank"> <button>Lihat Data</button> </a>
                              <br><br><br>

                              <br><br>
                            <?php }?>  
                          
                            <?php if($status == '2'){ ?>
                              <br>
                              Permohonan Review dan Approval <br>
                              Monitoring & Evaluation KPI-SO,<br><br>
                              Charter Manager : <?=@$request_by?> <br>
                              Date : <?=date('d F Y',strtotime(@$request_date))?><br><br>
                              KPI-SO : <?='('.@$kpi_so_code.') '.@$kpi_so_name;?><br><br>
                              Klik Link Dibawah untuk melihat data:<br><br>
                              <a href="<?=$link;?>" target="_blank"> <button>Lihat Data</button> </a>
                              <br><br><br>
                            <?php }?>

                            <?php if($status == '3'){ ?>
                              <br>
                              Permohonan Review dan Approval <br>
                              Monitoring & Evaluation KPI-SO,<br><br>
                              <b>Telah disetujui (APPROVED)</b><br><br>
                              By : <?=@$request_by?> <br>
                              Date : <?=date('d F Y',strtotime(@$request_date))?><br><br>
                              KPI-SO : <?='('.@$kpi_so_code.') '.@$kpi_so_name;?><br><br>
                              Klik Link Dibawah untuk melihat data:<br><br>
                              <a href="<?=$link;?>" target="_blank"> <button>Lihat Data</button> </a>
                              <br><br><br>
                            <?php }?>

                            <?php if($status == '4'){ ?>
                              <br>
                              Permohonan Review dan Approval <br>
                              Monitoring & Evaluation KPI-SO,<br><br>
                              <b>Ditolak (REJECTED)</b><br><br>
                              By : <?=@$request_by?> <br>
                              Date : <?=date('d F Y',strtotime(@$request_date))?><br><br>
                              KPI-SO : <?='('.@$kpi_so_code.') '.@$kpi_so_name;?><br><br>
                              Klik Link Dibawah untuk melihat data:<br><br>
                              <a href="<?=$link;?>" target="_blank"> <button>Lihat Data</button></a>
                              <br><br><br>
                            <?php }?>

                          </td>
                        </tr>

                        <!-- redirect link -->
                        <!-- <tr>
                          <td align="center" style="padding: 25px 0 15px 0;">
                            <b>Klik Link Dibawah ini untuk Review Data:</b><br>
                          </td>
                          <td align="center" style="padding: 25px 0 15px 0;">
                            <table border="0" cellspacing="0" cellpadding="0" width="30%" style="width:30%;">
                              <tr>
                                <td align="center" bgcolor="#fff" style="border-radius:5px;" >
                                  <a href="<?=$link;?>" target="_blank"> Review Data </a>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr> -->

                      </table>

                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td align="center" style=" padding: 10px; background-color: #003b6d;" bgcolor="#1b9ba3">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
                  <tr>
                    <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 0px; padding-top: 0px;"> 
                    </td>
                  </tr>
                  <tr>
                    <td align="center" style="padding: 0px 0px 0px 0px;">
                      <table border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                        <tr>
                          <td align="center" style="border-radius: 5px; color:#fff;" >
                            Copyright &copy;<?php echo date("Y"); ?> Indonesia Power
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
</div>