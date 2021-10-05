<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>Login</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta content="" name="description"/>
		<meta content="" name="author"/>

		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('public/assets/global/plugins/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css');?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('public/assets/global/plugins/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('public/assets/global/plugins/uniform/css/uniform.default.css');?>" rel="stylesheet" type="text/css"/>
		<!-- END GLOBAL MANDATORY STYLES -->
		<!-- BEGIN PAGE LEVEL STYLES -->
		<link href="<?php echo base_url('public/assets/admin/pages/css/login.css');?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('public/assets/global/plugins/bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" type="text/css" />
		<!-- END PAGE LEVEL SCRIPTS -->
		<!-- BEGIN THEME STYLES -->
		<link href="<?php echo base_url('public/assets/global/css/components-rounded.css');?>" id="style_components" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('public/assets/global/css/plugins.css');?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('public/assets/admin/layout/css/layout.css');?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('public/assets/admin/layout/css/themes/default.css');?>" rel="stylesheet" type="text/css" id="style_color"/>
		<link href="<?php echo base_url('public/assets/admin/layout/css/custom.css');?>" rel="stylesheet" type="text/css"/>
		<!-- END THEME STYLES -->

        <link rel="shortcut icon" href="<?php echo base_url('public/gmf-fav.jpg') ?>"/>
	</head>
<!-- END HEAD -->
	
	<!-- BEGIN BODY -->
	<body class="login" style="background-color:#0d0c1b !important;color:#ffffff">
		<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
		<div class="menu-toggler sidebar-toggler">
		</div>
		<!-- END SIDEBAR TOGGLER BUTTON -->
		<!-- BEGIN LOGO -->
		<div class="logo">
			<!-- <a href="<?php echo site_url('admin') ?>">
				<img width="200" src="<?=site_url()?>public/assets/crm/images/logo_crm_2.png" alt=""/>
			</a> -->
			<h1><b>CRM Aplication</b></h1>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN LOGIN -->
		<div class="content">
			<!-- BEGIN LOGIN FORM -->
			<form class="login-form" action="<?php echo site_url('login/dologin'); ?>" method="post">
				<input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>">
				<h3 class="form-title">Select Login AS :</h3>
				<div class="alert alert-danger display-hide">
					<button class="close" data-close="alert"></button>
					<span>Username, Password dan Captcha harus diisi. </span>
				</div>




				<style type="text/css">
					.role_name{
						text-align: center;
					}
					.btn_role_name{
						cursor:pointer;
						font-size: 20px;
					}
				</style>

				<div class="role_name">
					<?php $i=0; foreach ($arr_role_name as $val) {?>
						<a href="<?=site_url('login/change_session_role_id');?>/<?=$arr_role_id[$i];?>" class="btn btn-primary btn_role_name" >
							<?=strtoupper($val);?>
						</a> 
						<br><br>
					<?php  $i++;}  ?>
				</div>





			</form>
			<!-- END LOGIN FORM -->
		</div>
		<div class="copyright">
			 Copyright @ Customer Relation Management
		</div>
		<!-- END LOGIN -->

		<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
		<!-- BEGIN CORE PLUGINS -->
		<!--[if lt IE 9]>
		<script src="<?php echo base_url('public/assets/global/plugins/respond.min.js');?>"></script>
		<script src="<?php echo base_url('public/assets/global/plugins/excanvas.min.js');?>"></script>
		<![endif]-->
		<script src="<?php echo base_url('public/assets/global/plugins/jquery.min.js');?>" type="text/javascript"></script>
        <script>
            var base_url = '<?php site_url();?>';
            jQuery(document).ready(function() {
                Metronic.init(); // init metronic core components
                Layout.init(); // init current layout
                Demo.init();

                $('.login-form').submit(function (e) {
                    var data = $(this).serialize();
                    var url = $(this).attr('action');

                    //Metronic.blockUI({target: '.content', boxed: true});

                    $.post(url, data, function (msg) {
                        toastr.options = call_toastr('3000');
                        if(msg.status == 1){
                        	location.reload();
                        }else {
                            var $toast = toastr['error'](msg.message, "Error");
							$('.img-captcha').html(msg.captcha);
							$('input[name="captcha"]').val('');
							if(msg.status == 3){
								$('input[name="captcha"]').focus();
							}
							
                        }

                        Metronic.unblockUI('.content');
                    }, 'json');

                    return false;
                });

                $('.login-form input').keypress(function (e) {
                    if(e.keyCode == 13){
                        $(this).trigger('submit');
                    }
                })
            });

			$(window).load(function(e) {
			    // var iframe = $("iframe").contents();
			    // console.log(iframe);
			});

        </script>
		<script src="<?php echo base_url('public/assets/global/plugins/jquery-migrate.min.js');?>" type="text/javascript"></script>
		<script src="<?php echo base_url('public/assets/global/plugins/bootstrap/js/bootstrap.min.js');?>" type="text/javascript"></script>
		<script src="<?php echo base_url('public/assets/global/plugins/jquery.blockui.min.js');?>" type="text/javascript"></script>
		<script src="<?php echo base_url('public/assets/global/plugins/uniform/jquery.uniform.min.js');?>" type="text/javascript"></script>
		<script src="<?php echo base_url('public/assets/global/plugins/jquery.cokie.min.js');?>" type="text/javascript"></script>
		<!-- END CORE PLUGINS -->
		<!-- BEGIN PAGE LEVEL PLUGINS -->
		<script src="<?php echo base_url('public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js');?>" type="text/javascript"></script>
		<script src="<?php echo base_url('public/assets/global/plugins/bootstrap-toastr/toastr.min.js');?>" type="text/javascript"></script>
		<!-- END PAGE LEVEL PLUGINS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<script src="<?php echo base_url('public/assets/global/scripts/metronic.js');?>" type="text/javascript"></script>
		<script src="<?php echo base_url('public/assets/admin/layout/scripts/layout.js');?>" type="text/javascript"></script>
		<script src="<?php echo base_url('public/assets/admin/layout/scripts/demo.js');?>" type="text/javascript"></script>
		<script src="<?php echo base_url('public/assets/admin/pages/scripts/login.js');?>" type="text/javascript"></script>
		<!-- END PAGE LEVEL SCRIPTS -->
		<!-- END JAVASCRIPTS -->
	</body>
	<!-- END BODY -->
</html>