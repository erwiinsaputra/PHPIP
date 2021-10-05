<?php
    $menu       = get_menu();
    $menu       = json_encode($menu);
    $uri        = $this->uri->uri_string();
    // echo '<pre>';print_r($menu);echo '</pre>';exit;
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
    
    <ul class="menu_sidebar page-sidebar-menu page-sidebar-menu-according-submenu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <!-- my dashboard -->
        <li class="">
            <a title="My Dashboard" href="<?=site_url('app/mydashboard')?>" class="menu ajaxify" id="" controler="app/mydashboard" 
                style="text-align:center;font-size:1.5em;background: radial-gradient(#00B8FF 0%, #009AD9 30%, #006A96 100%);color:white;">
                <div style="margin-bottom: -1.5em;">
                    My SIMO <br>Dashboard
                </div>
                <span class="title"></span>
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
                <h4 class="modal-title" style="color:white;text-align:center;font-weight:bold;">Change Password</h4>
            </div>
            <div class="modal-body">
                <form id="form_change_pass" method="post" action="javascript:;" class="form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-4">OLD PASSWORD</label>
                                    <div class="col-md-7">
                                        <input name="old_pass" type="password" class="form-control required" placeholder="Old Password" autocomplete="off"> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-4">NEW PASSWORD</label>
                                    <div class="col-md-7">
                                        <input name="new_pass" type="password" class="form-control required" placeholder="New Password" autocomplete="off">
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

    //show menu 
    var menu = <?=$menu?>;
    var html = '';
    // console.log(menu);
    $.each(menu,function(key,row){
        // console.log(row);
        var id = '';
        if (row.controler !== null) { id = row.controler.replace('/',''); }
        var name = row.name;
        if(row.icon == '' || row.icon == null){
            var icon = '<img style="width:23px;height:23px;margin-left:-2.3em;margin-bottom:-5px;" src="'+base_url+'public/assets/app/img/icon/'+row.icon_img+'.png">';
        }else{
            var icon = '<i class="icon-'+row.icon+'" style="margin-left:-30px; color:#97b1c3; font-size:18px; top: 4px;"></i>';
        }
        var sub = (row.sub.length > 0) ? '<span class="arrow"></span>':'';
        var controler = (row.controler == null) ? 'javascript:;': base_url+row.controler;
        var ajaxify = (row.controler == null) ? '': 'ajaxify';


        //menu awal
        html += ''+
            '<li class="">'+
                '<a title="'+name+'" href="'+controler+'" class="menu '+ajaxify+' " id="'+id+'" controler="'+row.controler+'" style="padding-left:40px; margin-right:0px;">'+
                    '<div style="margin-bottom: -16px; margin-left: 0px;">'+icon+'</div>'+
                    '<span class="title" style="">'+name+'</span>'+sub+
                '</a>';
        
            if(row.sub.length > 0){
                html += '<ul class="sub-menu">';
                $.each(row.sub,function(key2,row2){
                    var id2 = '';
                    if (row2.controler !== null) { id2 = row2.controler.replace('/',''); }
                    var name2 = row2.name;
                    if(row2.icon == '' || row2.icon == null){
                        var icon2 = '<img style="width:23px;height:23px;margin-left:-2.3em;margin-bottom:-5px;" src="'+base_url+'public/assets/app/img/icon/'+row2.icon_img+'.png">';
                    }else{
                        var icon2 = '<i class="icon-'+row2.icon+'" style="margin-left:-30px; color:#97b1c3; font-size:18px; top: 4px;"></i>';
                    }
                    var sub2 = (row2.sub.length > 0) ? '<span class="arrow"></span>':'';
                    var controler2 = (row2.controler == null) ? 'javascript:;': base_url+row2.controler;
                    var ajaxify2 = (row2.controler == null) ? '': 'ajaxify';
                    html += ''+
                        '<li class="">'+
                            '<a title="'+name2+'" href="'+controler2+'" class="menu '+ajaxify2+'" id="'+id2+'" controler="'+row2.controler+'" style="padding-left:40px; margin-right:0px;">'+
                                '<div style="margin-bottom: -16px; margin-left: 0px;">'+icon2+'</div>'+
                                '<span class="title" style="">'+name2+'</span>'+sub2+
                            '</a>';

                        if(row2.sub.length > 0){
                            html += '<ul class="sub-menu">';
                            $.each(row2.sub,function(key3,row3){
                                var id3 = '';
                                if(row3.controler !== null) { id3 = row3.controler.replace('/',''); }
                                var name3 = row3.name;
                                if(row3.icon == '' || row3.icon == null){
                                    var icon3 = '<img style="width:23px;height:23px;margin-left:-2.3em;margin-bottom:-5px;" src="'+base_url+'public/assets/app/img/icon/'+row3.icon_img+'.png">';
                                }else{
                                    var icon3 = '<i class="icon-'+row3.icon+'" style="margin-left:-30px; color:#97b1c3; font-size:18px; top: 4px;"></i>';
                                }
                                var sub3 = (row3.sub.length > 0) ? '<span class="arrow"></span>':'';
                                var controler3 = (row3.controler == null) ? 'javascript:;': base_url+row3.controler;
                                var ajaxify3 = (row3.controler == null) ? '': 'ajaxify';
                                html += ''+
                                    '<li class="">'+
                                        '<a title="'+name3+'" href="'+controler3+'" class="menu '+ajaxify3+'" id="'+id3+'" controler="'+row3.controler+'" style="padding-left:40px; margin-right:0px;">'+
                                            '<div style="margin-bottom: -16px; margin-left: 0px;">'+icon3+'</div>'+
                                            '<span class="title" style="">'+name3+'</span>'+sub3+
                                        '</a>';
                                html += '</li>';
                            });
                            html += '</ul>';
                        }
                        html += '</li>';
                });
                html += '</ul>';
            }
            html += '</li>';
    });
    $('.menu_sidebar').append(html);


    //active menu default dan logout
    var menu_sidebar = $('.menu_sidebar').find('a');
    $.each(menu_sidebar,function(key,row){
        //active
        var uri = "<?=$uri?>";
        var a = $(this).attr('href');
        var cek = a.indexOf(uri);
        if(cek != '-1'){
            $(this).parent('li').addClass('active');
            $(this).parent('li').parent('ul').parent('li').addClass('active');
            $(this).parent('li').parent('ul').parent('li').parent('ul').parent('li').addClass('active');
        }
        //logout
        var a = $(this).attr('href');
        var cek = a.indexOf('login/out');
        if(cek != '-1'){
            $(this).attr('href','javascript:;');
            $(this).addClass('btn_logout');
        }
    });


    //change password
    $('#btn_save_change_pass').off().on('click',function(){
        var url = "<?php echo site_url();?>template/template_base/change_password";
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

    //logout
    $('.btn_logout').on('click',function(){
        localStorage.clear();
        sessionStorage.clear();
        window.location.href = "<?= site_url('login/out'); ?>";
    });


    //reload notif
    window.reload_notif = function(id=''){
        var arr_controler = {};
        var arr_menu = $('.menu');
        var arr_controler_notif = ["app/monev_si","app/request_ic"];
        //menu
        if(arr_menu.length > 0){
            $.each(arr_menu, function(i, val) {
                var controler = $(this).attr('controler');
                if(arr_controler_notif.indexOf(controler) != -1){
                    arr_controler[i] = controler;
                }
            });

            //cek notif post
            var url = "<?php echo site_url();?>template/template_base/reload_notif";
            var param = {arr_controler:arr_controler};
            // Metronic.blockUI({ target: '.notif_request',  boxed: true});
            $.post(url, param, function(msg){
                //foreach sesuai menu requestnya
                $.each(msg.controler, function(i, val) {
                    //total request
                    var id = val.replace('/','');
                    var total = parseFloat(msg.total[i]);
                    var html = '<span class="badge badge-warning notif_menu" style="margin-right: -0.5em;">'+total+'</span>';
                    $('#'+id).find('.notif_menu').remove();
                    if(total > 0){
                        $('#'+id).append(html);
                    }
                });
                // Metronic.unblockUI('.notif_request');
            },'json');
        }
    }
    window.reload_notif();

});
</script>

