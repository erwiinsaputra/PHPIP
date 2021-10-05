<!-- nestable -->
<link rel="stylesheet" type="text/css" href="<?=base_url()?>/public/assets/global/plugins/jquery-nestable/jquery.nestable.css"/>

<form method="post" id="form_edit" action="javascript:;" class="form-horizontal">
    <div class="form-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-3">Role
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input value="<?=$data->name?>" name="name" type="text" class="form-control" placeholder="Role" data-bvalidator="required" autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Description
                    <span class="required" aria-required="true">*</span>
                    </label>
                    <div class="col-md-8">
                        <input value="<?=$data->description?>" name="description" type="text" class="form-control" placeholder="Description" data-bvalidator="required" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-12" style="text-align:center;">
                    <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
                    <input type="hidden" name="id" value="<?=@$data->id;?>"  >
                    <button id="btn_update" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
                    
            <div class="row">
                <div class="col-md-12" >
                    <h3 style="text-align:center;">To Add Menu<br>Drag Menu From "List of Menu" To "Menu Selected"</h3>
                    <textarea style="display:none;" name="menu_json" id="nestable_list_11_output" class="form-control col-md-12 margin-bottom-10"></textarea>
                    <textarea style="display:none;" id="nestable_list_22_output" class="form-control col-md-12 margin-bottom-10"></textarea>
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
                            <div class="dd" id="nestable_list_22">
                                <li class="dd-item" data-id="<?=@$row->id;?>">
                                    <div class="dd-handle">
                                        <i class="icon-setting"></i> &nbsp; -
                                        <div style="float:right;"></div>
                                    </div>
                                </li>
                                <ol class="dd-list">
                                    <?php foreach($list_menu as $row){ ?>
                                    <li class="dd-item" data-id="<?=@$row->id;?>">
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
                            <div class="dd" id="nestable_list_11">

                                <ol class="dd-list">

                                    <?php foreach($menu_selected as $row){ ?>
                                    <li class="dd-item" data-id="<?=@$row['id'];?>">

                                        <?php if(count(@$row['sub']) == 0){ ?>
                                            <div class="dd-handle">
                                                <i class="icon-<?=@$row['icon'];?>"></i> &nbsp; <?=@$row['name'];?>
                                                <div style="float:right;">
                                                    <?php if(@$row['link'] == ''){ ?>
                                                        [<?=@$row['folder'];?>/<?=@$row['controler'];?>]
                                                    <?php }else{ ?>
                                                        [<?=@$row['link'];?>]
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php }else{  ?>
                                            <div class="dd-handle">
                                                <i class="icon-<?=@$row['icon'];?>"></i> &nbsp; <?=@$row['name'];?>
                                                <div style="float:right;">
                                                    <?php if(@$row['link'] == ''){ ?>
                                                        [<?=@$row['folder'];?>/<?=@$row['controler'];?>]
                                                    <?php }else{ ?>
                                                        [<?=@$row['link'];?>]
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <ol class="dd-list">

                                                <?php foreach(@$row['sub'] as $row2){ ?>
                                                <li class="dd-item" data-id="<?=@$row2['id'];?>">

                                                    <?php if(count($row2['sub']) == 0 ){ ?>
                                                        <div class="dd-handle">
                                                            <i class="icon-<?=@$row2['icon'];?>"></i> &nbsp; <?=@$row2['name'];?>
                                                            <div style="float:right;">
                                                                <?php if(@$row2['link'] == ''){ ?>
                                                                    [<?=@$row2['folder'];?>/<?=@$row2['controler'];?>]
                                                                <?php }else{ ?>
                                                                    [<?=@$row2['link'];?>]
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php }else{  ?>
                                                        <div class="dd-handle">
                                                            <i class="icon-<?=@$row2['icon'];?>"></i> &nbsp; <?=@$row2['name'];?>
                                                            <div style="float:right;">
                                                                <?php if(@$row2['link'] == ''){ ?>
                                                                    [<?=@$row2['folder'];?>/<?=@$row2['controler'];?>]
                                                                <?php }else{ ?>
                                                                    [<?=@$row2['link'];?>]
                                                                <?php } ?>
                                                            </div>
                                                        </div>

                                                        <ol class="dd-list">

                                                            <?php foreach(@$row2['sub'] as $row3){ ?>
                                                            <li class="dd-item" data-id="<?=@$row3['id'];?>">

                                                                <div class="dd-handle">
                                                                    <i class="icon-<?=@$row3['icon'];?>"></i> &nbsp; <?=@$row3['name'];?>
                                                                    <div style="float:right;">
                                                                        <?php if(@$row3['link'] == ''){ ?>
                                                                            [<?=@$row3['folder'];?>/<?=@$row3['controler'];?>]
                                                                        <?php }else{ ?>
                                                                            [<?=@$row3['link'];?>]
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <?php }  ?>
                                                        </ol>

                                                    <?php }  ?>

                                                </li>
                                                <?php }  ?>
                                            </ol>
                                        <?php } ?>
                                    </li>
                                    <?php }  ?>
                                </ol>
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

    //select2 biasa
    $("#form_edit .select2_biasa").select2({
        minimumResultsForSearch: 1, minimumInputLength: -1, allowClear:true, dropdownAutoWidth : true,
    });

    //update data
    $('#form_edit #btn_update').on('click',function(){
        $('#form_edit').bValidator();
        $('#form_edit').submit();
        if($('#form_edit').data('bValidator').isValid()){
            var title = "Save Data!";
            var mes = "Are You Sure ?";
            var sus = "Successfully Save Data!";
            var err = "Failed Save Data!";
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
                    var url     = "<?=site_url($url);?>/save_edit";
                    var param   = $('#form_edit').serializeArray();
                    Metronic.blockUI({ target: '.modal-dialog',  boxed: true});
                    $.post(url,param,function(msg){
                        Metronic.unblockUI('.modal-dialog');
                        $('#popup_edit').modal('hide');
                        toastr.options = call_toastr('4000');
                        if(msg.status == '1'){
                            window.reload_table_role();
                            toastr['success'](sus, "Success");
                        }else{
                            toastr['error'](err, "Error");
                        }
                    },'json');
            });
        }else{
            // alert('Data Harus Lengkap! \nCoba Cek Inputan');
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
    $('#nestable_list_11').nestable({ group: 1 }).on('change', updateOutput);
    $('#nestable_list_22').nestable({ group: 1 }).on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable_list_11').data('output', $('#nestable_list_11_output')));
    updateOutput($('#nestable_list_22').data('output', $('#nestable_list_22_output')));

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
});
</script>