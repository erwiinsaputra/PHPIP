<form method="post" id="form_edit" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
  <div class="form-body">
      <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-3"><b>BSC</b>
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <select <?=@$disabled?> class="form-control select2_biasa" placeholder="BSC" data-bvalidator="required"  >
                            <option value=""></option>
                            <?php foreach($bsc as $row){ ?>
                                <option <?=(@$data->id_bsc == $row->id ? 'selected' : '')?> value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php } ?>
                        </select>
                        <input name="id_bsc" value="<?=@$data->id_bsc?>"  type="hidden" class="required form-control" placeholder="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3"><b>SI</b>
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input <?=@$readonly?> value="<?=@$data->id_si;?>" name="id_si" type="text" class="required form-control" placeholder="SI"  data-bvalidator="required"  />
                        <input value="<?=@$data->id_pic;?>" name="pic_si" id="pic_si" type="hidden" class="required form-control" placeholder="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3"><b>PIC SI</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input readonly="readonly" id="pic_si_name" type="text" class="required form-control" placeholder="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3"><b>Keterangan <br>Request</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea <?=@$readonly?> name="keterangan" class="form-control" rows="3" data-bvalidator="required"><?=@$data->keterangan;?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3"><b>Keterangan <br>Approval</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea <?=@$readonly?> name="keterangan" class="form-control" rows="3" data-bvalidator="required"><?=@$data->keterangan_approval;?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3"><b>Keterangan <br>Request Assist Admin</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea <?=@$readonly?> id="keterangan_send_to_admin" name="keterangan_send_to_admin" class="form-control" rows="3" data-bvalidator="required"><?=@$data->keterangan_send_to_admin;?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3"><b>Keterangan <br>Done Request Assist Admin</b>
                        <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <textarea <?=@$readonly?> id="keterangan_done_request_admin"name="keterangan_done_request_admin" class="form-control" rows="3" data-bvalidator="required"><?=@$data->keterangan_done_request_admin;?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3"><b>Attachment</b>
                        <span class="required" aria-required="true"></span>
                    </label>
                    <div style="margin-top:0.5em;font-size:1.2em !important;"><?=@$html_list_file_request_ic?></div>
                </div>
                <br><br><br>

              <hr style="margin-top:0px;">

          </div>
         
      </div>
    </div>
  
    <div class="form-actions">
      <div class="row">
          <div class="col-md-12" style="text-align:center;">
                <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                <input type="hidden" name="id" value="<?=@$data->id;?>">
                <input type="hidden" name="type" id="type" value="<?=@$type;?>">
          </div>
      </div>
    </div>

  
</form>

<script type="text/javascript">
$(document).ready(function () {
    
    //format angka
    $('.angka').inputmask('decimal', {
        alias: 'numeric',
        autoGroup: true,
        radixPoint:".", 
        groupSeparator: ",", 
        digits: 5,
        rightAlign : false,
        prefix: ''
    });

    //select2 biasa
    $("#form_edit .select2_biasa").select2({
        minimumResultsForSearch: -1, minimumInputLength: -1, allowClear:false, dropdownAutoWidth : true,
    });
    
    //select si
    $("#form_edit input[name='id_si']").select2({
        minimumInputLength: -1,
        dropdownAutoWidth : true,
        // allowClear:true,
        ajax: {
          url: "<?php echo site_url($url.'/select_si_pic')?>",
          dataType: 'json',  type:"POST", quietMillis: 250, cache: true,
          data: function (term, page) {
            var id_bsc = $("#form_edit select[name='id_bsc']").val();
            return { q: term, id_bsc:id_bsc};
          },
          results: function (data, page) {  return { results: data.item }; },
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            var start_date = $("#form_edit #start_date").val();
            var end_date = $("#form_edit #end_date").val();
            if (id !== "") {
              $.ajax({
                  url:"<?php echo site_url($url.'/select_si_pic')?>",
                  dataType: "json", type:"POST", 
                  data:{ id: id}
              }).done(function(res) { callback(res[0]); });
            }
        },
        formatResult: function(item){ return item.name },
        formatSelection: function(item){ 
            $('#form_edit #pic_si').val(item.pic_si);
            $('#form_edit #pic_si_name').val(item.pic_si_name);
            return item.name; 
        }
    }).on('change', function(event) { 
        //change
    });

    //load awal
    $("#form_edit input[name='id_si']").change();

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');

});
</script>