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

    //show menu 
    var menu = <?=$menu?>;
    var html = '';
    // console.log(menu);
    $.each(menu,function(key,row){
        // console.log(row);
        var name = row.name;
        var icon = (row.icon == '') ? "setting": row.icon;
        var sub = (row.sub.length > 0) ? '<span class="arrow"></span>':'';
        var controler = (row.controler == 'true') ? 'javascript:;': base_url+row.controler;
        var ajaxify = (row.controler == 'true') ? 'true': 'ajaxify';
        html += ''+
            '<li class="">'+
                '<a title="'+name+'" href="'+controler+'" class="'+ajaxify+' menu" style="padding-left:40px; margin-right:0px;">'+
                    '<div style="margin-bottom: -16px; margin-left: 0px;">'+
                        '<i class="icon-'+icon+'" style="margin-left:-30px; color:#97b1c3; font-size:18px; top: 4px;"></i>'+
                    '</div>'+
                    '<span class="title" style="">'+name+'</span>'+sub+
                '</a>';
        
            if(row.sub.length > 0){
                html += '<ul class="sub-menu">';
                $.each(row.sub,function(key2,row2){
                    var name2 = row2.name;
                    var icon2 = (row2.icon == '') ? "setting": row2.icon;
                    var sub2 = (row2.sub.length > 0) ? '<span class="arrow"></span>':'';
                    var controler2 = (row2.controler == 'true') ? 'javascript:;': base_url+row2.controler;
                    var ajaxify2 = (row2.controler == 'true') ? '': 'ajaxify';
                    html += ''+
                        '<li class="">'+
                            '<a title="'+name2+'" href="'+controler2+'" class="'+ajaxify2+' menu" style="padding-left:40px; margin-right:0px;">'+
                                '<div style="margin-bottom: -16px; margin-left: 0px;">'+
                                    '<i class="icon-'+icon2+'" style="margin-left:-30px; color:#97b1c3; font-size:18px; top: 4px;"></i>'+
                                '</div>'+
                                '<span class="title" style="">'+name2+'</span>'+sub2+
                            '</a>';

                        if(row2.sub.length > 0){
                            html += '<ul class="sub-menu">';
                            $.each(row2.sub,function(key3,row3){
                                var name3 = row3.name;
                                var icon3 = (row3.icon == '') ? "setting": row3.icon;
                                var sub3 = (row3.sub.length > 0) ? '<span class="arrow"></span>':'';
                                var controler3 = (row3.controler == 'true') ? 'javascript:;': base_url+row3.controler;
                                var ajaxify3 = (row3.controler == 'true') ? '': 'ajaxify';
                                html += ''+
                                    '<li class="">'+
                                        '<a title="'+name3+'" href="'+controler3+'" class="'+ajaxify3+' menu" style="padding-left:40px; margin-right:0px;">'+
                                            '<div style="margin-bottom: -16px; margin-left: 0px;">'+
                                                '<i class="icon-'+icon3+'" style="margin-left:-30px; color:#97b1c3; font-size:18px; top: 4px;"></i>'+
                                            '</div>'+
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
    html += ''+
            '<li class="">'+
                '<a title="logout" class="btn_logout" href="javascript:;" style="margin-left:-7px;">'+
                    '<i class="icon-logout"></i> <span class="title" style="margin-left:4px;">Logout</span>'+
                '</a>'+
           '</li>';
    $('.menu_sidebar').append(html);


    //active menu default
    var menu_sidebar = $('.menu_sidebar').find('a');
    $.each(menu_sidebar,function(key,row){
        var uri = "<?=$uri?>";
        var a = $(this).attr('href');
        var cek = a.indexOf(uri);
        if(cek != '-1'){
            $(this).parent('li').addClass('active');
            $(this).parent('li').parent('ul').parent('li').addClass('active');
            $(this).parent('li').parent('ul').parent('li').parent('ul').parent('li').addClass('active');
        }
    });


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

