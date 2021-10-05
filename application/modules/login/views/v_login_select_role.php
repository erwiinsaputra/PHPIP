
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
		        <?php $i=0; foreach ($arr_role as $key=>$val) {?>
				<a href="<?=site_url('login/change_session_role_id');?>/<?=$arr_role_id[$i];?>" data-wow-delay=".5s" 
					class="col-md-4 col-sm-6 feature-item wow animated bounceIn1 feature-item-title btn_role_name"
					style="padding:30px;"
				 >
	                <i class="fa fa-users  fa-3x fa-fw"></i>
                    <p class="feature-item-title"><strong><?=strtoupper($val);?></strong></p>
                    <p id="desc" align="justify" class="comment more">
                    	<?=$role_desc[$key];?>
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
	        });
	    </script>
	</body>
		<!-- END BODY -->
	</html>