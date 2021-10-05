<?php
    $menu       = get_menu();
    $uri        = $this->uri->uri_string();
    $segment_1  = $this->uri->segment(2);
    $folder     = h_role_name();
?>



<style type="text/css">
    .notif .notif_hide{
        margin: 0px 0px 0px 200px;
        padding: 2px 15px 2px 5px;
        position:absolute;
        display:none;
        background-color: #000000;
        color:#ffffff;
        border-radius:2px;
        width: auto;
        z-index: 1;
    }

    .notif .notif_hide span, .notif_hide span{
        display:block;
        font-size: 15px;
    }


    .notif_hide{
        margin: 0px 0px 0px 200px;
        padding: 2px 15px 2px 5px;
        position:absolute;
        display:none;
        background-color: #000000;
        color:#ffffff;
        border-radius:2px;
        width: auto;
        z-index: 1;
    }



</style>


<script type="text/javascript">
$(document).ready(function () {
    //datepicker
    $(".notif_request").live('mouseover', function () {
        var no = $(this).attr('no');
        $(".notif_hide").hide();
        $(".notif_popup_"+no).slideToggle('fast');
    });
    $(document).bind('click', function (e) {
        var $clicked = $(e.target);
        if (!$clicked.parents().hasClass("notif_hide")){
            $(".notif_hide").hide();
        }
    });

});
</script>




<div class="page-sidebar navbar-collapse collapse notif">
    <!-- BEGIN SIDEBAR MENU -->
    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->

    <ul class="page-sidebar-menu page-sidebar-menu-according-submenu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <?php
            
            foreach($menu as $row):
                
                //param
                $menu_module    = trim($row->MENU_MODULE);
                $menu_controler = $row->MENU_CONTROLLER;
                $menu_icon      = trim($row->MENU_ICON);
                $menu_name      = $row->MENU_NAME;
                $menu_sub       = @$row->sub;
                //menu global
                if(substr($menu_controler, 0,6) == 'global'){ 
                    $folder = 'global';
                    $menu_controler = substr($menu_controler, 7);
                }else{
                    $folder = h_role_name();
                }
                //url
                $url            = $folder.'/'.$menu_module.($menu_module != '' ? '/' : '').$menu_controler;
                $url_1          = $folder.'/'.$menu_module.'/true';
                $url_segment    = @explode('/', $url)[1];
                // echo '<pre>';print_r($url_segment.'-'.$segment_1);
        ?>
                <li class="start <?php echo ($segment_1 == $url_segment ? 'active' : '') ?>">
                    
                    <a title="<?= $menu_name; ?>" href="<?= ($url == $url_1 ? 'javascript:;' : site_url($url)); ?>" class="<?=($url == $url_1 ? '' : "ajaxify"); ?>">
                        <i class="icon-<?= ($menu_icon == '' ? 'folder' : $menu_icon); ?>"></i>
                        <span class="title"><?= $menu_name; ?></span>
                        <?= (isset($menu_sub) ? "<span class='arrow'></span>" : '') ?>
                    </a>


                    <?php if(isset($menu_sub)){
                        echo "<ul class='sub-menu'>";
                        $no = 0;
                        foreach($menu_sub as $sub){
                            $no ++;
                            //param
                            $sub_module    = trim($sub->MENU_MODULE);
                            $sub_controler = $sub->MENU_CONTROLLER;
                            $sub_menu       = $sub->MENU_NAME;
                            $sub_icon       = trim($sub->MENU_ICON);
                            //sub menu global
                            if(substr($sub_controler, 0,6) == 'global'){ 
                                $sub_folder = 'global';
                                $sub_controler = substr($sub_controler, 7);
                            }elseif(substr($sub_controler, 0,4) == 'nppa'){ 
                                $sub_folder = 'nppa';
                                $sub_controler = substr($sub_controler, 5);
                            }else{
                                $sub_folder = h_role_name();
                            }
                            //url
                            $url = $sub_folder.'/'.$sub_module.($sub_module != '' ? '/' : '').$sub_controler;
                    ?>
                            <li <?=($uri == $url ? "class='active'" : '')?> >
                                <a title="<?=$sub_menu;?>" href="<?=site_url($url)?>" class="ajaxify">

                                    <!-- tambahan jumlah notif -->
                                    <?php if($menu_module == 'request'){ ?>
                                        <span id='<?=$sub_controler;?>' class='badge badge-danger notif_request' no="<?=$no;?>" >0</span>
                                        <div class="notif_hide notif_popup_<?=$no;?>" >
                                            <span>Fatah:&nbsp;&nbsp;0</span>
                                            <span>Alim:&nbsp;&nbsp;0</span>
                                        </div>
                                    <?php } ?>
                                    <!-- END tambahan jumlah notif -->

                                    <i class="icon-<?=($sub_icon == '' ? 'folder' : $sub_icon); ?>"></i> 
                                    <?=$sub_menu;?>
                                </a>
                            </li>

                    <?php }
                        echo "</ul>";
                    } ?>

                </li>
        <?php endforeach; ?>

                <li class="">
                    <a title="Change Password" id="btn_change_pass" data-toggle="modal" href="#popup_change_pass"><i class="icon-key"></i> <span class="title">Change Password</span></a>
                </li>

                <li <?=($uri == $url ? "class='active'" : '')?> class="">
                    <a title="logout" class="btn_logout" href="javascript:;"><i class="icon-logout"></i> <span class="title">Logout</span></a>
                </li>

    </ul>
    <!-- END SIDEBAR MENU -->
</div>















<!-- START Popup change Pass -->
<div id="popup_change_pass" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background:lightblue;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" style="color:black;text-align:center;font-weight:bold;">Change Password</h4>
            </div>
            <div class="modal-body">
                <form id="form_change_pass" method="post" action="javascript:;" class="form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-4">OLD PASSWORD</label>
                                    <div class="col-md-7">
                                        <input name="old_pass" type="password" class="form-control required" placeholder="Old Passeord" autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-4">NEW PASSWORD</label>
                                    <div class="col-md-7">
                                        <input name="new_pass" type="password" class="form-control required" placeholder="New Password" autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12" align="center">
                                <input type="hidden" id="username" name="username" autocomplete="username" value="">
                                <button id="btn_save_change_pass" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn default">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Popup change Pass -->




<script type="text/javascript">
$(document).ready(function () {


    window.reload_jum_notif = function(id=''){
        var arr_id = {};
        var arr_notif = $('.notif_request');
        if(arr_notif.length > 0){
            $.each(arr_notif, function(i, val) {
                arr_id[i] = $(this).attr('id');
            });
            if(id != ''){var arr_id = {}; arr_id[0] = id; }
            
            var url = "<?php echo site_url();?>/template/template_base/hitung_jum_notif";
            var param = {arr_id:arr_id};
            Metronic.blockUI({ target: '.notif_request',  boxed: true});
            $.post(url, param, function(msg){
                $.each(msg.id, function(i, val) {
                    var id = msg.id[i];
                    var jum = msg.val[i];
                    if(jum == '0'){
                        $('#'+id).hide();
                    }else{
                        $('#'+id).show();
                    }
                    $('#'+id).html(jum);
                });
                Metronic.unblockUI('.notif_request');
            },'json');
        }
    }
    window.reload_jum_notif();


    //change password
    $('#btn_save_change_pass').die().live('click',function(){
        var url = "<?php echo site_url();?>/template/template_base/change_password";
        var param   = $('#form_change_pass').serialize();
        Metronic.blockUI({ target: '#form_change_pass',  boxed: true});
        $.post(url, param, function(msg){
            if(msg.status == '1'){
                $('#popup_change_pass').modal('hide');
                swal("Success", msg.message, "success");
            }else{
                swal("Failed", msg.message, "error");
            }
            Metronic.unblockUI('#form_change_pass');
        },'json');
    });

    //update notif otomatis selama 30 detik
    setInterval(function(){ window.reload_jum_notif(); }, 60000);

    //kill ajax reques if click other menu
    $('.ajaxify').die().live('click',function(){

        var str = "<?=$_SERVER['REQUEST_URI'];?>";
        var cek = str.indexOf("dashboard");

        if(cek != '-1'){
            var url = window.location.href;
            location.assign(url);
        }
    });

   
});
</script>