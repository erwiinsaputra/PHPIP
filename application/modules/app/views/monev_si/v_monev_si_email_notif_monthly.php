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
                            <u><b><?=@$subject?></b></u><br><br>
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
                              <br>
                              Saat ini sudah memasuki bulan baru (<b><?=date('F');?></b>),<br><br>
                              Mohon untuk bapak/ibu, <br><br>
                              Mengisi Data Monitoring & Evaluation Strategic Initiative (SI)<br><br>
                              <br>
                              Bulan : <b><?=date('F', strtotime("-1 month"))?><b>
                              <br><br><br><br>
                              Klik Link Dibawah untuk pengisian data:<br><br><br>
                              <a href="<?=$link;?>" target="_blank"> <button>Isi Data </button></a>
                              <br><br><br><br>
                          </td>
                        </tr>

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