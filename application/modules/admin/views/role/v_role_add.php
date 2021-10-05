<!-- nestable -->
<link rel="stylesheet" type="text/css" href="<?=base_url()?>/public/assets/global/plugins/jquery-nestable/jquery.nestable.css"/>

<form method="post" id="form_add" action="javascript:;" class="form-horizontal" enctype="multipart/form-data">
    <div class="form-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-3">Role
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input name="name" type="text" class="form-control" placeholder="Role" data-bvalidator="required" autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Description
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input name="description" type="text" class="form-control" placeholder="Description" data-bvalidator="required" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>


        <div class="form-actions">
            <div class="row">
                <div class="col-md-12" style="text-align:center;">
                        <input type="hidden" name="ex_csrf_token" value="<?=csrf_get_token();?>">
                        <button id="btn_save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                        
                <div class="row">
                    <div class="col-md-12" >
                        <h3 style="text-align:center;">To Add Menu<br>Drag Menu From "List of Menu" To "Menu Selected"</h3>
                        <textarea style="display:none;" name="menu_json" id="nestable_list_1_output" class="form-control col-md-12 margin-bottom-10"></textarea>
                        <textarea style="display:none;" id="nestable_list_2_output" class="form-control col-md-12 margin-bottom-10"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-comments"></i>List of Menu
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="dd" id="nestable_list_2">
                                    <ol class="dd-list">
                                        <?php foreach($list_menu as $row){ ?>
                                        <li class="dd-item" data-id="13">
                                            <div class="dd-handle">
                                                <i class="icon-<?=$row->icon;?>"></i> &nbsp; <?=$row->name;?>
                                                <div style="float:right;">
                                                    <?php if(@$row->link == ''){ ?>
                                                        [<?=@$row->folder;?>/<?=@$row->controler;?>]
                                                    <?php }else{ ?>
                                                        [<?=@$row->link;?>]
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </li>
                                        <?php }  ?>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-comments"></i>Menu Selected
                                </div>
                            </div>
                            <div class="portlet-body ">
                                <div class="dd" id="nestable_list_1">
                                    <ol class="dd-list">
                                        <li class="dd-item" data-id="1">
                                            <div class="dd-handle">
                                            <i class="icon-logout"></i> &nbsp; Logout
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>
        </div>

    </div>
  </div>
  
</form>


<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?=base_url()?>/public/assets/global/plugins/jquery-nestable/jquery.nestable.js"></script>


<script type="text/javascript">
$(document).ready(function () {
    //cek user
    // $("#form_add input[name='name']").change(function () {
    //     var url = '<?=site_url($url)?>/check_role';
    //     checkData(this, url);
    // });

    //save data
    $('#form_add #btn_save').on('click',function(){
        $('#form_add').bValidator();
        $('#form_add').submit();
        if($('#form_add').data('bValidator').isValid()){

            var title = "Save Data!";
            var mes = "Are You Sure ?";
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
                    var url   = "<?=site_url($url);?>/save_add";
                    var param = $('#form_add').serializeArray();
                    Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('.modal-dialog');
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            $('#popup_add').modal('hide');
                            window.reload_table_role();
                            toastr['success'](msg.message, "Success");
                        }else{
                            toastr['error'](msg.message, "Error");
                        }
                    },'json');
            });
        }else{
        //   alert('Data Harus Lengkap! \nCoba Cek Inputan');
        }
    });

    //update token csrf
    $('#ex_csrf_token').val('<?=csrf_get_token();?>');


    //===================================== nestable ===============================
    //update nestable
    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target), output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // activate Nestable for list 1
    $('#nestable_list_1').nestable({ group: 1 }).on('change', updateOutput);
    $('#nestable_list_2').nestable({ group: 1 }).on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable_list_1').data('output', $('#nestable_list_1_output')));
    updateOutput($('#nestable_list_2').data('output', $('#nestable_list_2_output')));

    $('#nestable_list_menu').on('click', function (e) {
        var target = $(e.target), action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

    //mousedown
    $(document).on('mousemove', function(e) {
        var y = e.clientY;
        var h = $(window).height();
        var n = h - y;  
        if (n < 60) {
            var t = parseFloat($(window).scrollTop());
            $('html,body').animate({scrollTop:t + 60 + 'px'},200);
        } else {
            $('html,body').stop();
        }
    });
    //===========================================================================

function checkData(ele, url){
    var input = $(ele);
    if (input.val() === "") {
        input.closest('.form-group').removeClass('has-error').removeClass('has-success');
        $('.fa-check, fa-warning', input.closest('.form-group')).remove();
        return;
    }
    input.attr("readonly", true). attr("disabled", true).addClass("spinner");
    var param = { val: input.val() };
    $.post(url, param, function (res) {
        input.attr("readonly", false).attr("disabled", false).removeClass("spinner");
        // change popover font color based on the result
        if (res.status == 1) {
            input.closest('.form-group').removeClass('has-error').addClass('has-success');
            $('.fa-warning', input.closest('.form-group')).remove();
            input.before('<i class="fa fa-check"></i>');
        } else {
            input.closest('.form-group').removeClass('has-success').addClass('has-error');
            $('.fa-check', input.closest('.form-group')).remove();
            input.before('<i class="fa fa-warning"></i>');
        }
    }, 'json');
}

});
</script>