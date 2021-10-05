
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

	    <link rel="stylesheet" href="<?php echo base_url('public/assets/login/stylesheet/font-awesome.min.css');?>" />

	    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700" rel="stylesheet" type="text/css">
	    <link rel="stylesheet" href="<?php echo base_url('public/assets/login/stylesheet/bootstrap.min.css');?>" />
	    <link rel="stylesheet" href="<?php echo base_url('public/assets/login/stylesheet/animate.css');?>" />
	    <link rel="stylesheet" href="<?php echo base_url('public/assets/login/stylesheet/style.css');?>" />		

		<!-- END THEME STYLES -->

        <link rel="shortcut icon" href="<?php echo base_url('public/gmf-fav.jpg') ?>"/>
	</head>
<!-- END HEAD -->
	
	<!-- BEGIN BODY -->
<body class="green-color dark-theme">
	<section class="gray">
        <div class="container">
            <div class="row feature-items justify-content-center">
                <div class="col-md-12">
                    <h2 class="wow animated fadeInDown" style="color: #fff;">Select Login AS :</h2>                
                </div>
		        <?php $i=0; foreach ($arr_ams as $row) {?>
		        <a href="javascript:" idnya="<?=$row->USER_ID;?>" nama="<?=$row->USER_NAME;?>" initial="<?=$row->USER_INITIAL;?>" class="btn_select_ams col-md-3 col-sm-6 feature-item wow animated bounceIn1 feature-item-title btn_role_name" data-wow-delay=".5s" >
	                <i class="fa fa-users  fa-3x fa-fw"></i>
                    <p class="feature-item-title"><strong>
                    	<b>[<?=$row->USER_INITIAL;?>]</b> <?=wordwrap($row->USER_NAME);?></strong>
                    </p>
	            </a>
	            <?php  $i++;}  ?>
            </div>
        </div>
    </section>
	    
    <script type="text/javascript" src="<?php echo base_url('public/assets/login/javascript/jquery-3.2.1.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/login/javascript/main.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/login/javascript/bootstrap.min.js');?>"></script>

    <script>
    $(document).ready(function() {
        var showChar = 200;
        var ellipsestext = "...";
        var moretext = "more";
        var lesstext = "less";

        //btn select ams
        $('.btn_select_ams').on('click',function(){
	        localStorage.clear();
	        sessionStorage.clear();
	        var idnya   = $(this).attr('idnya');
	        var initial = $(this).attr('initial');
	        var nama    = $(this).attr('nama');
	        var url     = "<?=site_url();?>/login/change_session_ams";
	        var param   = {idnya:idnya, nama:nama, initial:initial};
	        $.post(url, param, function(msg){
	            window.location.href = "<?= site_url();?>/global/dashboard";
	        });
	    });
	});


    </script>
</body>
	<!-- END BODY -->
</html>