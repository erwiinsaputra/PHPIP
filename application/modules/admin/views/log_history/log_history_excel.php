<style type="text/css">
  .tbl_header{
    width:100%;
  }
  .tbl_detail{
    width:100%;
    border:0.5px solid black;
    border-collapse: collapse;
  }
  .tbl_detail tr, .tbl_detail tr td{
    padding:0px 2px 0px 2px; white-space: nowrap;
    white-space: nowrap;
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

</style>

<!-- Target Yearly -->
<h1 align="center">Report Log History <?=$year;?></h1>
  <table class="tbl_detail tbl_header " border="1">
    <thead>
      <tr>
        <th width="40px">No</th>
        <th>USER</th>
        <th>Login AS</th>
        <th>Other User</th>
        <th>IP Address</th>
        <th>Type</th>
        <th>Create Date</th>
        <th>Activity</th>
      </tr>
    </thead>
    <tbody>
      <?php $no='0'; foreach ($isi as $rows){ $no++; ?>
      <tr>
        <td><?=$no;?></td>
        <td><?=$rows->USER_NAME;?>&nbsp;<?=$rows->USER_INITIAL==''?'': '['.$rows->USER_INITIAL.']';?></td>
        <td><?=h_role_name($rows->log_role_id);?></td>
        <td><?=$rows->log_other_user;?></td>
        <td><?=$rows->log_ip_address;?></td>
        <td><?=$rows->log_type;?></td>
        <td><?=date('d-F-Y H:i:s',strtotime($rows->log_created_date));?></td>
        <td><?=$rows->log_activity;?></td>
      </tr>
      <?php } ?>
    </tbody>
</table>