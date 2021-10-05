<?php
    $menu       = get_menu();
    $uri        = $this->uri->uri_string();
    $segment_1  = $this->uri->segment(2);
    echo '<pre>';print_r($menu);echo '</pre>';exit;

?>

<style>
    .page-sidebar .page-sidebar-menu > li > a > .title, 
    .page-sidebar .page-sidebar-menu > li > ul > li > a > .title{
        font-size: 13px;
    }
    .page-sidebar .page-sidebar-menu > li > a > .title{
        margin-right:-5px !important;
    }
    .page-sidebar .page-sidebar-menu li > a > .arrow:before{
        margin-right:-5px !important;
    }
</style>


<div class="page-sidebar navbar-collapse collapse">
    
    <ul class="page-sidebar-menu page-sidebar-menu-according-submenu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">

        <?php
            foreach($menu as $row):
                //param
                $folder         = trim($row->folder);
                $controler      = $row->controler;
                $icon           = trim($row->icon);
                $name           = $row->name;
                $menu_sub       = @$row->sub;
                $pecah  = explode('/',$controler);
               
                //url
                $url            = $folder.($folder != '' ? '/' : '').$controler;
                $url_1          = $folder.'/true';
                $url_segment    = @explode('/', $url)[1];
        ?>
                <li class="<?php echo ($segment_1 == $url_segment ? 'active' : '') ?>">
                    <a title="<?=$name;?>" href="<?= ($url == $url_1 ? 'javascript:;' : site_url($url)); ?>" class="<?=($url == $url_1 ? '' : "ajaxify"); ?> menu" style="padding-left:40px; margin-right:0px;">
                        <div style="margin-bottom: -16px; margin-left: 0px;">
                            <i class="icon-<?= ($icon == '' ? 'folder' : $icon); ?>" style="margin-left:-30px; color:#97b1c3; font-size:18px; top: 4px;"></i>
                        </div>
                        <span class="title" style=""><?= $name; ?></span>
                        <?= (isset($menu_sub) ? "<span class='arrow'></span>" : '') ?>
                    </a>


                    <!--  ============================= Dropdown level 1 ============================ -->
                    
                    <?php if(isset($menu_sub)){
                        echo "<ul class='sub-menu'>";
                        foreach($menu_sub as $sub){
                            //param
                            $sub_folder    = trim($sub->folder);
                            $sub_controler = $sub->controler;
                            $sub_menu       = $sub->name;
                            $sub_icon       = trim($sub->icon);
                            //sub menu global
                            if(substr($sub_controler, 0,6) == 'global'){ 
                                $sub_controler = substr($sub_controler, 7);
                                $url = 'global/'.$sub_folder.($sub_folder != '' ? '/' : '').$sub_controler;
                            }else{
                                // $sub_folder =start open h_role_name();
                                // $url = $sub_folder.'/'.$sub_folder.($sub_folder != '' ? '/' : '').$sub_controler;
                                $url = $sub_folder.($sub_folder != '' ? '/' : '').$sub_controler;
                            }
                    ?>

                            <li class="start <?=($uri == $url ? 'active': '')?>" >

                                <a title="<?=$sub_menu;?>" href="<?= (empty($sub->sub2) ? site_url($url) : 'javascript:;'); ?>" class="<?=($url == $url_1 ? '' : "ajaxify"); ?>" style="padding-left:40px; margin-right:0px;">
                                    <div style="margin-bottom: -16px; margin-left: 0px;">
                                        <i class="icon-<?=($sub_icon == '' ? 'folder' : $sub_icon); ?>" style="margin-left:-30px; color:#97b1c3; font-size:18px; top: 4px;"></i> 
                                    </div>
                                    <span class="title" style=""><?=$sub_menu;?></span>
                                    <?= (empty($sub->sub2) ? '' : "<span class='arrow'></span>") ?>
                                </a>

                                <!--  ============================= Dropdown level 2 ============================ -->
                                <?php if(isset($menu_sub)){ ?>
                                    <?php echo "<ul class='sub-menu'>";?>
                                    <?php 
                                        foreach($sub->sub2 as $sub2){
                                            //param
                                            $sub2_folder    = trim($sub2->folder);
                                            $sub2_controler = $sub2->controler;
                                            $sub2_menu       = $sub2->name;
                                            $sub2_icon       = trim($sub2->icon);
                                            //sub menu global
                                            if(substr($sub2_controler, 0,6) == 'global'){ 
                                                $sub2_controler = substr($sub2_controler, 7);
                                                $url = 'global/'.$sub2_folder.($sub2_folder != '' ? '/' : '').$sub2_controler;
                                            }else{
                                                // $sub2_folder = h_role_name();
                                                // $url = $sub2_folder.'/'.$sub2_folder.($sub2_folder != '' ? '/' : '').$sub2_controler;
                                                $url = $sub2_folder.($sub2_folder != '' ? '/' : '').$sub2_controler;
                                            }
                                    ?>

                                            <li class="<?=($uri == $url ? 'active': '')?>" >
                                            
                                                <!-- <a title="<?=$sub2_menu;?>" href="<?=site_url($url)?>" href_1="<?=site_url($url)?>" href_2="javascript:;" class="ajaxify">
                                                    <i class="icon-<?=($sub2_icon == '' ? 'folder' : $sub2_icon); ?>"></i> 
                                                    <?=$sub2_menu;?>
                                                </a> -->

                                                <a title="<?=$sub2_menu;?>" href="<?= (empty($sub2->sub3) ? site_url($url) : 'javascript:;'); ?>" class="<?=($url == $url_1 ? '' : "ajaxify"); ?>" style="padding-left:40px; margin-right:0px;">
                                                    <div style="margin-bottom: -16px; margin-left: 0px;">
                                                        <i class="icon-<?=($sub2_icon == '' ? 'folder' : $sub2_icon); ?>" style="margin-left:-30px; color:#97b1c3; font-size:18px; top: 4px;"></i> 
                                                    </div>
                                                    <span class="title" style=""><?=$sub2_menu;?></span>
                                                    <?= (empty($sub2->sub3) ? '' : "<span class='arrow'></span>") ?>
                                                </a>

                                            </li>

                                    <?php } ?>
                                    <?php echo "</ul>"; ?>
                                <?php } ?>
                            </li>

                    <?php }
                        echo "</ul>";
                    } ?>

                </li>
        <?php endforeach; ?>

               <!--  <li class="">
                    <a title="Change Password" class="btn_change_pass" data-toggle="modal" href="#popup_change_pass"><i class="icon-key"></i> <span class="title">Change Password</span></a>
                </li> -->

                <li <?=($uri == $url ? "class='active'" : '')?> class="">
                    <a title="logout" class="btn_logout" href="javascript:;" style="margin-left:-7px;">
                        <i class="icon-logout"></i> <span class="title" style="margin-left:4px;">Logout</span>
                    </a>
                </li>

    </ul>
    <!-- END SIDEBAR MENU -->
</div>








<!-- START Popup change Pass -->
<div id="popup_change_pass" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background:#3c8dbc;">
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


    //change password
    $('#btn_save_change_pass').die().live('click',function(){
        var url = "<?php echo site_url();?>/template/template_base/change_password";
        var param = $('#form_change_pass').serialize();
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


});
</script>