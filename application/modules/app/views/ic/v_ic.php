<div class="row table_action_plan">
    <div class="col-md-12">
        <div class="portlet light ">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-table font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Table &nbsp;<?=$title;?></span>
                    <input type="hidden" id="ex_csrf_token" value="<?=csrf_get_token();?>">
                </div>
                <div class="actions">

                     <div style="float:right;">
                        <a href="javascript:" id="btn_download" class="btn btn-danger btn-sm"  data-placement="top" data-container="body"><i class="fa fa-download"></i> Download</a>
                        
                        <?php if(in_array(h_session('ROLE_ID'), h_role_admin())){ ?>
                            <a href="javascript:" id="btn_upload" class="btn btn-primary btn-sm"  data-placement="top" data-container="body"><i class="fa fa-upload"></i> Upload</a>
                        <?php } ?>
                        <a class="btn btn-primary btn-circle btn-sm " href="<?=site_url($url)?>" data-original-title="Refresh" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a> <a class="btn btn-circle btn-icon-only btn-default tooltips fullscreen" href="javascript:;" data-original-title="Fullscreen" title="Fullscreen"></a>
                    </div> 

                </div>
            </div>


            <div class="portlet-body">
                
                <div class="form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-1"><b>BSC&nbsp;:</b></label>
                                    <div class="col-md-4">
                                        <select class="form-control global-filter" name="global_id_bsc" id="global_id_bsc" tipe="2" placeholder="ALL">
                                            <option value=""></option>
                                            <?php foreach($bsc as $row){ ?>
                                                <?php if(@$id_bsc == ''){ $id_bsc='1';} ?>
                                                <option <?=($row->id == @$id_bsc ? 'selected':'')?>  value="<?=$row->id;?>"><?=$row->name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1"><b>Year&nbsp;:</b></label>
                                    <div class="col-md-4">
                                        <select class="form-control global-filter"  tipe="2" name="global_year" id="global_year" placeholder="ALL">
                                            <option value=""></option>
                                            <?php for($y=$start_year; $y <= $end_year; $y++){ ?>
                                                <option <?=($y == $year ? 'selected="selected"' : '')?> value="<?=$y?>"><?=$y?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-1"><b>SI&nbsp;Title:</b></label>
                                    <div class="col-md-9">
                                        <input value="<?=@$id_si;?>" name="global_id_si" id="global_id_si" tipe="2" class="form-control global-filter" placeholder="ALL"  type="text" />
                                    </div>
                                    <div class="col-md-2">
                                        <a href="javascript:" id="btn_detail_si" class="btn btn-warning btn-sm"><i class="fa fa-list"></i> Detail SI</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <hr style="margin-top:0px;margin-bottom:0px;">

                <div class="row">
                    <div class="col-md-12">
                        <div id="message_empty_data_ic" style="display:none; padding:10px; border-radius:50px; border:1px solid white; background:#ffab005e;; color:black;">
                            <div style="text-align:center;">
                                <b><u>Langkah-Langkah Pengisian Data IC:</u></b>
                                <br>
                                <br>
                                Untuk Pengisian Data IC, mohon Download Template Excel Default yang sudah disediakan:<br><br>
                                <button class="btn btn-primary btn-sm" id="btn_download_template2">Download Template Excel</button>
                                <br>
                                <br>
                            </div>
                            1. Setelah download template, harap mengisi data sesuai contoh data pada template excel.
                            <br><br>
                            2. Setelah pengisian data, Mohon untuk melakukan Request IC pada menu Request IC dengan menyertakan file excel yang sudah diedit,
                            <br>&nbsp; &nbsp; kemudian Request IC dikirimkan kepada PIC SI terkait untuk direview dan diapprove.
                            <br><br>
                            3. Setelah request IC di approve oleh PIC SI, PIC IC mengirimkan Request Assist ke pihak Admin untuk dibantu upload data excel.
                            <br>&nbsp; &nbsp; Atau PIC IC melakukan penginputan data secara manual.
                            <br><br>
                        </div>
                    </div>
                </div>

                <hr style="margin-top:0px;">

                <style type="text/css">
                    .table-wrap thead tr th, .table-wrap thead tr td, .table-wrap tbody tr td { white-space: nowrap;} 
                    .dataTables_scrollHeadInner{ padding-left:0px !important;}
                    .DTFC_RightHeadBlocker { background: #eee; border: 1px solid #eee;}
                    .DTFC_RightBodyLiner { overflow-y: none;}
                    .filter td{ padding: 0px !important; white-space: nowrap;}
                </style>
                
                <div class="table-container">

                    <div class="table-actions-wrapper">
                        <div style="float:right; padding-left:10px;">
                            <div class="showhide_column"></div>
                        </div>
                        <div style="float:right; padding-left:10px;">
                            <a href="javascript:" class="btn btn-sm yellow-crusta btn_show_filter"  data-placement="top" data-container="body"><i class="fa fa-search"></i> Search</a>
                        </div>
                        <div style="float:left; margin-left:-10em; margin-top:0.3em;">
                            <label class="label label-danger" style="font-size:1em;color:white;">Total Weighting Factor:</label> 
                            <label class="label label-danger" style="font-size:1.3em;background:black;color:white;"><span class="total_weighting_factor"> ---</span> %</label> 
                        </div>
                        <?php if(in_array(h_session('ROLE_ID'), h_role_admin())){ ?>
                            <div style="float:left; padding-left:10px;">
                                <a href="javascript:" class="btn btn-sm btn-primary btn_add" data-placement="top" data-container="body"><i class="fa fa-plus"></i> Add Action Plan</a>
                            </div>
                        <?php } ?>
                        <?php if(h_session('ROLE_ID') == '5'){ ?>
                            <div style="float:left; padding-left:10px;">
                                <a href="javascript:" class="btn btn-sm btn-primary btn_add" data-placement="top" data-container="body"><i class="fa fa-plus"></i> Add Action Plan</a>
                            </div>
                        <?php } ?>
                        <?php if(h_session('ROLE_ID') == '9'){ ?>
                            <div style="float:left; padding-left:10px;">
                                <a href="javascript:" class="btn btn-sm btn-primary btn_add" style="display:none;" data-placement="top" data-container="body"><i class="fa fa-plus"></i> Add Action Plan</a>
                            </div>
                        <?php } ?>
                    </div>

                    <table class="table table-striped table-bordered table-hover table-wrap" id="table_action_plan">
                        <thead>
                            <tr role="row" class="heading">
                                <th style="background-color:rgb(52 215 255) !important;color:white;" width="40px">No</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">ID</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">BSC</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">SI Title</th>
                                
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Number</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Action Plan</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Deliverable</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">PIC Action Plan</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Weighting Factor (%)</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Budget Currency</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Status</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Start Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">End Date</th>

                                <th style="background-color:rgb(52 215 255) !important;color:white;">Is Active</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Created Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Created By</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Updated Date</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;">Updated By</th>
                                <th style="background-color:rgb(52 215 255) !important;color:white;" width="130px">Action</th>

                            </tr>
                            <tr role="row" class="filter display-hide">
                                <td></td>
                                <td><input name="id" tipe="2" a1="text-center" a2="false" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input readonly="readonly" name="id_bsc" tipe="2" a1="text-center"  a2="false" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input readonly="readonly" name="id_si" tipe="2" a1="text-center"  a2="false" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                
                                <td><input name="code" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="name" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="deliverable" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" autocomplete="off"></td>
                                <td><input name="pic_action_plan" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="weighting_factor" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><input name="budget_currency" tipe="1" a1="text-center" class="form-control form-filter input-md" placeholder="search" type="text" ></td>
                                <td><select name="status_action_plan" tipe="2" value="t" a1="text-center" a2="false"  class="form-control form-filter select-filter input-md select2_biasa"  placeholder="Search" >
                                        <option value=""></option>
                                        <?php foreach($status_action_plan as $row){ ?>
                                            <option value="<?=$row->id;?>"><?=str_replace(' ','&nbsp;',$row->name);?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="start_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="start_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="end_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="end_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td><select value="t" a1="text-center" a2="false"  class="form-control form-filter select-filter input-md select2_biasa"  name="is_active" tipe="2" placeholder="Search" >
                                        <option value=""></option>
                                        <option value="t">Active</option>
                                        <option value="f">Disabled</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="created_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="created_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td><input type="text" a1="text-center" a2="false" class="form-control form-filter input-md"  name="created_by" tipe="1" placeholder="Search"></td>
                                <td>
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="updated_date" tipe="3" placeholder="From" style="width:60px !important;display:inline;" autocomplete="off">
                                    <input type="text" a1="text-center" a2="false" class="form-control form-filter input-md tanggal" name="updated_date" tipe="4" placeholder="To" style="width:60px !important;display:inline;" autocomplete="off">
                                </td>
                                <td><input type="text" a1="text-center" a2="false" class="form-control form-filter input-md"  name="updated_by" tipe="1" placeholder="Search"></td>
                                <td class="text-center">
                                    <a href="javascript:;" data-original-title="Search" class="tooltips btn btn-sm yellow-crusta filter-submit margin-bottom btn_search"><i class="fa fa-search"></i></a>
                                    <a href="javascript:;" data-original-title="Reset" class="tooltips btn btn-sm red-sunglo filter-cancel"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    
                </div>
            </div>
        </div>


    </div>
</div>



<!-- download excel -->
<div id="popup_download" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Download Data Excel / Template</b></h3>
      </div>
      <div class="modal-body">

        <div style="text-align:center;">
            <div><b>Select Template</b></div>
            <div style="text-align:center;margin-top:1em;margin-bottom:3em;">
                <select class="form-control input-md select2_biasa" id="template_excel" style="margin-left:10%;margin-right:10%;width:80%;">
                    <?php foreach($template_excel_ic as $row){ ?>
                        <option value="<?=$row->id;?>" file_name="<?=$row->file_name;?>"><?=$row->name;?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <button type="button" class="btn btn-primary" id="btn_download_template"><i class="fa fa-download"></i> Download Template</button>
                <button type="button" class="btn btn-danger" id="btn_download_data"><i class="fa fa-download"></i> Download Data</button>
            </div>
        </div>

        <!-- form download -->
        <div style="display:none;">
            <form id='form_download' name='form_download' action="javascript:;" target="_blank" method="post">
                <input type="hidden" name="input_form" id="input_form" />
                <button type="button" class="btn btn-primary" id="btn_submit_download">Download</button>
            </form>
        </div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- upload excel -->
<div id="popup_upload" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Upload Data Excel</b></h3>
      </div>
      <div class="modal-body">

        <div style="text-align:center;">
            <form id="form_upload" action="javascript:;" method="post" enctype="multipart/form-data">
                <div><b>Format Template:</b></div>
                <div style="text-align:center;margin-top:1em;margin-bottom:2em;">
                    <select class="form-control input-md select2_biasa" id="template_upload"  style="margin-left:10%;margin-right:10%;width:80%;">
                        <option value="template_ic_default">Template IC default (action plan and sub action plan)</option>
                        <option value="template_ic_JVC">Template IC project pengembangan pembangkit JVC & Non JVC</option>
                    </select>
                </div>
                <div><b>File Upload:</b></div>
                <div style="margin-left:30%;margin-right:30%;width:40%;margin-top:1em;">
                    <input type="file" name="file" id="file_upload" />
                </div>
                <hr style="margin-top:2em;">
                <div style="margin-top:1em;">
                    <button type="button" class="btn btn-primary" id="btn_upload_data"><i class="fa fa-upload"></i>  Upload</button> 
                </div>
                <div style="margin-top:3em;">
                    <button type="button" class="btn btn-warning" id="btn_backup_data"><i class="fa fa-download"></i> Backup</button>
                    &nbsp; &nbsp; 
                    <button type="button" class="btn btn-info" id="btn_restore_data"><i class="fa fa-refresh"></i> Restore</button>
                </div>
            </form>
        </div>
        
      </div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- modal detail si-->
<div id="popup_detail_si" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Detail SI</b></h3>
      </div>
      <div class="modal-body" id="load_detail_si"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal add-->
<div id="popup_add" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Add Action Plan</b></h3>
      </div>
      <div class="modal-body" id="load_add"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal sub action plan-->
<div id="popup_sub_action_plan" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Sub Action Plan</b></h3>
      </div>
      <div class="modal-body" id="load_sub_action_plan"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal add sub-->
<div id="popup_add_sub" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>ADD Sub Action Plan</b></h3>
      </div>
      <div class="modal-body" id="load_add_sub"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal edit-->
<div id="popup_edit" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>EDIT Action Plan</b></h3>
      </div>
      <div class="modal-body" id="load_edit"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal edit sub-->
<div id="popup_edit_sub" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>EDIT Sub Action Plan</b></h3>
      </div>
      <div class="modal-body" id="load_edit_sub"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal copy-->
<div id="popup_copy" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-full" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>COPY Action Plan</b></h3>
      </div>
      <div class="modal-body" id="load_copy"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- modal upload-->
<div id="popup_upload_ic" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc;color:white;">
        <button type="button" class="btn bg-navy" data-dismiss="modal" style="float:right;color:black;border-radius: 4px !important;"><strong> X </strong></button>
        <h3 class="modal-title" style="text-align:center;"><b>Upload File</b></h3>
      </div>
      <div class="modal-body" id="load_upload_ic"></div>
      <div class="modal-footer">
         <button type="button" class="btn bg-navy btn_close_upload" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>





<script type="text/javascript">
$(document).ready(function() {
    
    //================================= Datatable ==========================================
        //variabel
        var table_name  = "table_action_plan";
        var url         = "<?=site_url($url);?>/table_action_plan";
        var sort        = [-1,0];
        var order       = [];
        //column order
        var header = [];
        var arr_col = $("#"+table_name).find('.filter').find('td');
        var a = -1;
        $.each(arr_col, function(i, val) {
            a++;
            header[a] = {};
            var name = $(this).find('.form-filter').first().attr('name');   
            var a1 = $(this).find('.form-filter').first().attr('a1');
            var a2 = $(this).find('.form-filter').first().attr('a2');
            if(typeof name === 'undefined'){header[a]['data'] = '';}else{header[a]['data'] = name;}
            if(typeof a1 !== 'undefined'){header[a]['sClass'] = a1;}
            if(typeof a2 !== 'undefined'){header[a]['visible'] = false;}
        }).promise().done(function () { 
            header[0]['data']       = "no";
            header[a]['data']       = "action";
            header[0]['sClass']     = "text-center";
            header[a]['sClass']     = "text-center";
        });

        //table init
        table_action_plan.initDefault(url, header, order, sort);

        //drag column
        // new $.fn.dataTable.ColReorder("#"+table_name);

        //fixcolumn
        new $.fn.dataTable.FixedColumns("#"+table_name, { leftColumns: 1, rightColumns: 1 } );
    //==========================================================================================


    //=============================== FILERING =========================================
        //date
        $(".table_action_plan .tanggal").datepicker({
             rtl: Metronic.isRTL(), orientation: "left", autoclose: true, format : 'yyyy-mm-dd',
        });

         //select2 biasa
         $("#popup_download .select2_biasa, #popup_upload .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });

        //select2 biasa
        $(".table_action_plan .select2_biasa").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        });

        //select2 global_year
        $(".table_action_plan #global_year").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_action_plan .filter-submit').first().click();
        });


        //select2 biasa
        $(".table_action_plan #global_id_bsc").select2({
            minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true
        }).on('change', function(event) { 
            $('.table_action_plan #global_id_perspective').val('');
            $(".table_action_plan #global_id_perspective").change();
        });


         //select si
         window.table_action_plan_global_id_si = function(){
            $(".table_action_plan input[name='global_id_si']").select2({
                minimumInputLength: -1,
                dropdownAutoWidth : true,
                allowClear:true,
                ajax: {
                url: "<?php echo site_url($url.'/select_si')?>",
                dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
                data: function (term, page) {
                    var id_bsc = $("#global_id_bsc").val();
                    return { q: term, id_bsc:id_bsc};
                },
                results: function (data, page) {  return { results: data.item }; },
                },
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                    $.ajax({
                        url:"<?php echo site_url($url.'/select_si')?>",
                        dataType: "json", type:"POST", 
                        data:{ id: id}
                    }).done(function(res) { callback(res[0]); });
                    }
                },
                formatResult: function(item){ return item.name },
                formatSelection: function(item){ return item.name;  }
            }).on('change', function(event) { 
                $('.table_action_plan .filter-submit').first().click();

                //get total weighting fighter
                window.get_total_weighting_factor();

            });
        }
        window.table_action_plan_global_id_si();
    //====================================================================================


    //=============================== Action =========================================


        //btn detail si
        $('.table_action_plan').on('click', '#btn_detail_si', function(e) {
            var id = $("#global_id_si").val();
            if(id == ''){
                alert('SI belum dipilih'); return true;
            }
            $('#popup_detail_si').modal();
            var url = "<?=site_url($url);?>/load_detail_si";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_detail_si',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_detail_si').html(msg);
                Metronic.unblockUI('#load_detail_si');
            });
        });

        //load add
        $('.table_action_plan').on('click', '.btn_add', function(e) {
            $('#popup_add').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_add";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_add',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_add').html(msg);
                Metronic.unblockUI('#load_add');
            });
        });

        //load edit
        $('.table_action_plan').on('click', '.btn_edit', function(e) {
            $('#popup_edit').modal();
            var id = $(this).attr('id');
            var type = 'edit';
            var url = "<?=site_url($url);?>/load_edit";
            var param = {id:id, type:type};
            Metronic.blockUI({ target: '#load_edit',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_edit').html(msg);
                Metronic.unblockUI('#load_edit');
            });
        });

        //load sub action plan
        $('.table_action_plan').on('click', '.btn_sub_action_plan', function(e) {
            $('#popup_sub_action_plan').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_sub_action_plan";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_sub_action_plan',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_sub_action_plan').html(msg);
                Metronic.unblockUI('#load_sub_action_plan');
            });
        });

        //reload table sub action plan
        window.reload_table_sub_action_plan = function(id){
            var url = "<?=site_url($url);?>/load_sub_action_plan";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_sub_action_plan',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_sub_action_plan').html(msg);
                Metronic.unblockUI('#load_sub_action_plan');
            });
        }
        

        //load copy
        $('.table_action_plan').on('click', '.btn_copy', function(e) {
            $('#popup_copy').modal();
            var id = $(this).attr('id');
            var url = "<?=site_url($url);?>/load_copy";
            var param = {id:id};
            Metronic.blockUI({ target: '#load_copy',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_copy').html(msg);
                Metronic.unblockUI('#load_copy');
            });
        });

        //load view
        $('.table_action_plan').on('click', '.btn_view', function(e) {
            $('#popup_edit').modal();
            var id = $(this).attr('id');
            var type = 'view';
            var url = "<?=site_url($url);?>/load_edit";
            var param = {id:id, type:type};
            Metronic.blockUI({ target: '#load_edit',  boxed: true});
            $.post(url, param, function(msg){
                $('#load_edit').html(msg);
                Metronic.unblockUI('#load_edit');
            });
        });

        //btn delete
        $('.table_action_plan').on('click', '.btn_delete', function(e) {
            var id = $(this).attr('id');
            var val = $(this).attr('val');
            if(val == 't'){
                var mes = "Are you sure to Active Data?";
            }else{
                var mes = "Are you sure to DELETE Data?";
            }
            var title = "Are You Sure ?";
            swal({
                    title: title,
                    text: mes,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    closeOnConfirm: true
            },
            function(){
                    var token  = $('#ex_csrf_token').val();
                    var url    = '<?=site_url($url)?>/delete_data';
                    var param  = {id:id, val:val, token:token};
                    $.post(url, param, function(msg){
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_action_plan();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    }, 'json');
            });
        });


    //====================================================================================


    //btn download 
    $('#btn_download').on('click',function(){
        $('#popup_download').modal();
    });

    //btn download template
    $('#btn_download_template2').on('click',function(){
        $('#btn_download_template').click();
    });

    //btn download template
    $('#btn_download_template').on('click',function(){
        var template = $('#template_excel').find('option:selected').attr('file_name');
        window.open("<?=site_url()?>public/files/template_excel_ic/"+template, "_blank");
    });

    //btn download data
    $('#btn_download_data').on('click',function(){
        var arr = {};
        $('.table_action_plan .global-filter').each(function(index, el) {
            var name = $(el).attr('name');
            var val = $(el).val();
            if($(this).val() != ''){
                if(val != null){ arr[name] = val; }
            }
        });
        var input = JSON.stringify(arr);
        $('#form_download').find('#input_form').val(input);
        $('#btn_submit_download').click();    
    });
    
    //btn submit download
    $('#btn_submit_download').on('click',function(){
        var action = "<?=site_url($url);?>/download_excel";
        $('#form_download').attr('action',action);
        $('#form_download').submit();
        $('#form_download').attr('action','javascript:;');
        toastr.options = call_toastr('3000');
        var $toast = toastr['success']("Success For Export", "Success");
    });


    //btn restore data
    $('#btn_backup_data').on('click',function(){
        var mes = "Are you sure to Backup Data?";
        var title = "Backup Data";
        swal({
                title: title,
                text: mes,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes',
                closeOnConfirm: true
        },
        function(){
                var token  = $('#ex_csrf_token').val();
                var url    = '<?=site_url($url)?>/backup_data';
                var param  = {token:token};
                $.post(url, param, function(msg){
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                }, 'json');
        });
    });
        
    
    //btn restore data
    $('#btn_restore_data').on('click',function(){
        var mes = "Are you sure to Restore Data?";
        var title = "Restore Data";
        swal({
                title: title,
                text: mes,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes',
                closeOnConfirm: true
        },
        function(){
                var token  = $('#ex_csrf_token').val();
                var url    = '<?=site_url($url)?>/restore_data';
                var param  = {token:token};
                $.post(url, param, function(msg){
                    toastr.options = call_toastr('4000');
                    if(msg.status == '1'){
                        window.reload_table_action_plan();
                        toastr['success'](msg.message, "Success");
                    }else{
                        toastr['error'](msg.message, "Error");
                    }
                }, 'json');
        });
    });
        


    //btn upload
    $('#btn_upload').click(function(){
        $('#popup_upload').modal();
    });
    
    //btn upload data
    $('#btn_upload_data').on('click',function(){
        if ($('#file_upload').get(0).files.length === 0) {
            alert('File belum dipilih'); return true;
        }else{
            $('#form_upload').submit();
        }
    });

   

    //submit upload excel
    $('#form_upload').on('submit', function(e) {
        
        e.preventDefault();
        var param = new FormData(this);
        var id_bsc = $(".table_action_plan #global_id_bsc").val();
        var format_template = $("#template_upload").val();
        param.append('id_bsc', id_bsc);
        param.append('format_template', format_template);

        //konfirmasi
        var title = "Upload Data!";
        var mes = "Mohon menunggu ! \n Sampai Upload data selesai, \n\n Waktu Proses upload, \n Tergantung dari banyak data yang diupload.";
        swal({
                title: title,
                text: mes,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Upload',
                closeOnConfirm: true
        },
        function(){

              Metronic.blockUI({ target:'#form_upload',  animate: true});     
              $.ajax({
                  url: "<?=site_url($url);?>/upload_excel",
                  type: "POST",
                  data: param,
                  contentType: false, cache: false, processData: false,
                  dataType: 'json',
                  success: function(data){

                    //succes
                    toastr.options = { "closeButton": true, "debug": false, "positionClass": "toast-top-right", "onclick": null,
                        "showDuration": "1000", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing",
                        "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut"
                    };
                    if(data.status == 0){ var msg = 'error'; }else if(data.status == 1){ var msg = 'success'; }
                    var toast = toastr[msg](data.message, msg.charAt(0).toUpperCase() + msg.slice(1));

                    //kosongkan file
                    $("#file_upload").val('');

                    //refresh table
                    window.reload_table_action_plan();

                    //hide popup
                    $('#popup_upload').modal('hide');

                    Metronic.unblockUI('#form_upload');

                  },
                  error : function(msg){
                      Metronic.unblockUI('#form_upload');
                  }
              });
        });
        
        //kosongkan file
        $("#file_upload").val('');
        
    });

    //get total weight factor
    window.get_total_weighting_factor = function(){
        var url = "<?=site_url($url);?>/get_total_weighting_factor";
        var id_si = $(".table_action_plan #global_id_si").val();
        if(id_si == ''){
            $('.table_action_plan .total_weighting_factor').html(' ---');
        }else{
            var param = {id_si:id_si};
            $.post(url, param, function(msg){
                $('.table_action_plan .total_weighting_factor').html(msg.val);
            },'json');
        }
    }

});
</script>