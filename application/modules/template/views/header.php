<?php @$userdata = $this->session->userdata('USER'); ?>

<style type="text/css">
    .page-header.navbar {
        background: linear-gradient(to right, #FFFFFF 40%, rgb(44 89 129) 100%);
    }
    .page-header.navbar .top-menu .navbar-nav > li.dropdown-dark .dropdown-menu {
        background: #FFFFFF;
    }
    .page-sidebar-closed.page-sidebar-closed-hide-logo .page-header.navbar .menu-toggler.sidebar-toggler{
        margin-top: 28px !important;
    }
    button.ColVis_Button{
        background-color: #000000 !important;
    }
    .page-sidebar{
        width:240px;
    }
    .page-header.navbar .top-menu .navbar-nav > li.dropdown-dark .dropdown-menu.dropdown-menu-default > li a:hover{
        background-color: #008aac !important;
        color: white !important;
    }
    .page-header.navbar .top-menu .navbar-nav > li.dropdown .dropdown-toggle:hover .username{
        color: black !important;
    }
</style>


<style>
    .dropdown_2-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        /* background-color: rgb(0, 106, 150) !important; */
        color:black;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        padding: 12px 16px;
        z-index: 1;
        /* margin-left:-13.5em;
        margin-top:-2.5em; */
    }
    .select_data{
        color: black;
        cursor: pointer;
    }
    .btn_select_role{
        word-wrap: break-word;
        min-width: 11.6em;
        font-size: 1em;
        padding:10px 0px 10px 0px;
        background-color: #f9f9f9 !important;
    }
    .btn_select_role:hover{
        /* background-color: #008aac !important; */
        background-color: black !important;
        color:white;
    }
</style>



<!-- <div class="page-header navbar navbar-fixed-top" style="background:#3B3F51;"> -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner" style="margin-top: -10px;">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <!-- <a class="ajaxify logo_detail" href="<?php echo site_url('login') ?>" style="text-align:center;text-decoration: none;">
                <div style="margin: 10px 0px 0px 23px !important;"/>
                    <img style="width:152px;height:47px;" src="<?php echo base_url(); ?>public/assets/simo/img/logo/logo_detail.png">
                </div>
                <div style="margin:15px 0px 0px 10px !important;  width:80px; font-weight:bold;"/>
                    <span style="font-size:20px;">Strategic Initiative Management Office</span>
                </div>
            </a>
             -->
            <!-- <div class="menu-toggler sidebar-toggler" style="float:left;margin:27px 20px 0px 20px;"> -->
            <div class="sidebar-toggler" style="float:left;margin:27px 20px 0px 5px;">
                <img style="width: 35px;height: 35px;margin-top: -0.9em;margin-right: -5px;" src="<?=base_url();?>public/assets/app/img/icon/simo.png">
            </div>

            <div class="logo_detail" style="margin-top:20px !important;display:none; ">
                <span style="font-size:20px;"><b>SIMO</b> (Strategy and Initiative Management Office)</span>
            </div>
            <div class="logo_detail_mobile" style="margin-top:1em !important; float: left;display:none;">
                <span style="font-size:20px;"><b>SIMO</b></span>
            </div>
        </div>
        <!-- END LOGO -->

        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
        <!-- END RESPONSIVE MENU TOGGLER -->

        <!-- BEGIN PAGE TOP -->
        <div class="page-top">

            <!-- BEGIN HEADER SEARCH BOX -->
            <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
           <!--  <form class="search-form" action="extra_search.html" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control input-sm" placeholder="Search..." name="query">
                    <span class="input-group-btn">
                    <a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
                    </span>
                </div>
            </form> -->
            <!-- END HEADER SEARCH BOX -->

            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="separator hide"></li>
                    
                     <!-- <li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="header_notification_bar">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-bell"></i>
                            <span class="badge badge-success"></span>
                        </a>
                    </li>

                    <li class="separator hide"></li>

                    <li class="dropdown dropdown-extended dropdown-inbox dropdown-dark" id="header_inbox_bar">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-envelope-open"></i>
                            <span class="badge badge-danger"> -- </span>
                        </a>
                    </li>

                    <li class="separator hide"></li>
                    
                    <li class="dropdown dropdown-extended dropdown-tasks dropdown-dark" id="header_task_bar">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-calendar"></i>
                            <span class="badge badge-primary"> -- </span>
                        </a>
                    </li> -->

                    <li class="dropdown dropdown-user dropdown-dark btn_user_profile">

                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true" >
                            <span class="username" style="color:white;"><?=h_session('NAME');?> - (<?=h_session('ROLE_NAME');?>) </span>&nbsp;&nbsp;
                            <!-- <span class="username username-hide-on-mobile"><?= h_session('NAME');?></span> -->
                            
                            <!-- <img alt="<?=h_session('NAME');?>" class="img-circle" src="<?=h_session('PHOTO');?>"/> -->
                            <img style="background:#2d5a82 !important; " class="img-circle" src="<?=base_url();?>/public/assets/admin/layout4/img/avatar.png" />
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-default list_menu_profile" style="background:white !important;color:black !important;">
                            <!-- <li>
                                <a href="extra_profile.html"><i class="icon-user"></i> My Profile </a>
                            </li>
                            <li>
                                <a href="inbox.html"><i class="icon-envelope-open"></i> My Inbox <span class="badge badge-danger">1 </span></a>
                            </li>
                            <li>
                                <a href="page_todo.html"><i class="icon-rocket"></i> My Tasks <span class="badge badge-success"> 1 </span></a>
                            </li>
                            <li class="divider"></li> -->
                            <li>
                                <a style="color:black;" title="Change Password" class="btn_change_pass" data-toggle="modal" href="#popup_change_pass"><i class="icon-key"></i> <span class="title">Change Password</span></a>
                                <a style="color:black;" href="javascript:;" class="btn_logout"><i class="icon-logout"></i> Log Out </a>
                            </li>
                            <!-- untuk Select ROLE -->
                            <?php  
                                $jum_role = @$this->session->userdata('USER')['JUM_ROLE']; 
                                $arr_role_name = @$this->session->userdata('USER')['ARR_ROLE_NAME'];
                                $arr_role_id = @$this->session->userdata('USER')['ARR_ROLE_ID'];
                            ?>
                            <?php  if( $jum_role > 1 ) { ?>
                            <li class="dropdown_2">
                                    <a style="color:black;" title="Select Role" data-toggle="modal" href="javascript:;"><i class="icon-user"></i> <span class="title">Select Role</span></a>
                                    <div class="select_data dropdown_2-content">
                                        <?php $i=-1; foreach ($arr_role_name as $val) { $i++;?>
                                            <div class="btn_select_role" val="<?=$arr_role_id[$i];?>">
                                                <table>
                                                    <tr>
                                                        <td><i class="icon-login"></i> &nbsp; &nbsp;</td>
                                                        <td><?=wordwrap($val);?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>
                            </li>
                            <?php } ?>      
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                    
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- <li class="dropdown dropdown-extended quick-sidebar-toggler">
                        <span class="sr-only">Toggle Quick Sidebar</span>
                        <i class="icon-logout"></i>
                    </li> -->
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>

<script type="text/javascript">
$(document).ready(function () {
    //logo title mobile version 
    window.reload_logo_title = function(){
        var size_screen = screen.width;
        if(size_screen >= 767){
            $('.logo_detail').show();
            $('.logo_detail_mobile').hide();
            $('.page-logo').css('width','600px');
        }else{
            $('.logo_detail').hide();
            $('.logo_detail_mobile').show();
            $('.page-logo').css('width','auto');
        }
    }
    window.reload_logo_title();

    //menu minimiza
    $('.menu-toggler').on('click',function(){
        var size_screen = screen.width;
        if(size_screen >= 767){
            $('.logo_detail').toggle();
        }
    });

    //btn select role
    $('.btn_select_role').on('click',function(){
        localStorage.clear();
        sessionStorage.clear();
        //change session
        var val      = $(this).attr('val');
        var url     = "<?=site_url();?>/login/change_session_role_id";
        var param   = {val:val};
        Metronic.blockUI({ target: '.tag_body',  boxed: true});
        $.post(url, param, function(msg){
            Metronic.unblockUI('.tag_body');
            window.location.href = "<?= site_url();?>/login";
        });
    });

    //select role toggle
    $('.dropdown_2').on('click',function(){
        $('.dropdown_2-content').toggle();
    });

    //btn user profile
    $(".btn_user_profile").hover(function(){
        $(".list_menu_profile").show();
    }, function(){
        $(".list_menu_profile").hide();
    });
});
</script>