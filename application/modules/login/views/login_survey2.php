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

        <link rel="stylesheet" href="<?php echo base_url('public/assets/crm/plugin/rating_stars/style.css');?>">
        <link href="<?php echo base_url('public/assets/global/plugins/sweet-alert/sweet-alert.css');?>" rel='stylesheet' type='text/css' />


        <link rel="shortcut icon" href="<?php echo base_url('public/fav.ico') ?>"/>
	</head>
<!-- END HEAD -->
	
	<!-- BEGIN BODY -->
	<body class="login">
		<form id="form_survey" method="post" action="javascript:;" class="form-horizontal">
			
			<div class="wrapper">
				<div style="text-align:center;font-size:40px;color:black;font-weight:bold;">
					Feed Back For Us
				</div>
				<br>
				<br>

	            <div class="demo-wrapper text-center">
	                <div class="demo"></div>
	                <input type="hidden" name="survey_rate" value="3" id="survey_rate">
	                <input type="hidden" name="survey_category" value="<?=$category?>" id="survey_category">
	            </div>
				<div class="row " style="text-align:center;">
					<div class="col-md-12" >
						<label><b>Select Your Suggestion ?</b></label>
						<div class="checkbox-list load_cekbox">

							<?php $i=0; foreach ($arr_option as $row) { $i++; ?>
							<label class="checkbox-inline">
							<input type="checkbox" name="survey_option[]" id="star_option_<?=$i?>" value="<?=$row->star_id;?>"> <?=$row->star_option;?> </label>
							<?php } ?>
							
						</div>
					</div>
					<div class="col-md-12">
						<br>
						<label><b>Write your Command below :</b></label>
						<br>
						<textarea name="survey_text" class="" rows="3" style="width:40%;padding:10px;"></textarea>
					</div>
					<div class="col-md-12">
						<button class="btn btn-primary" id="btn_save"><i class="fa fa-check"></i> SEND</button>
					</div>
				</div>
			</div>
		</form>



		<script src="<?php echo base_url('public/assets/global/plugins/jquery.min.js');?>" type="text/javascript"></script>
		<script type="text/javascript">
			var base_url    = '<?php echo base_url();?>';
            var baseUrl     = '<?php echo base_url();?>';
            var site_url    = '<?php echo site_url();?>';
            var app_name    = 'G-SMART';
            var myBaseUrl = "<?php echo site_url('global/dashboard');?>";
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
		<!-- END PAGE LEVEL SCRIPTS -->

        <script type="text/javascript" src="<?php echo base_url('public/assets/crm/plugin/rating_stars/stars.min.js');?>"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/sweet-alert/sweet-alert.min.js');?>" type="text/javascript"></script>
		<!-- END JAVASCRIPTS -->


        <script type="text/javascript">
        $(document).ready(function () {
            
            $(".demo").stars({ 
            	text: ["Bad", "Not so bad", "Good", "Very Good", "Perfect"],
            	value: 3,
            	click: function(i) {

                    var bintang = i;
                    var category = "<?=$category;?>";
                    var url = "<?php echo site_url($setting['url'].'/login/index/get_option_survey'); ?>";
                    var param = {bintang:bintang, category:category};
                    Metronic.blockUI({ target: '.load_cekbox',  boxed: true});
                    $.post(url, param, function(msg){
                    	$('.load_cekbox').html('');
                    	var html = '';
                    	$.each(msg.data, function(index, val) {
                    		html += '<label class="checkbox-inline"><input type="checkbox" name="survey_option[]"  id="survey_option_'+index+'" value="'+val.id+'"> '+val.name+' </label>';
                    		$('.load_cekbox').html(html);
                    	});
                    	$('#survey_rate').val(msg.survey_rate);
                        Metronic.unblockUI('.load_cekbox');
                    }, 'json');

                }
            });
	
			$('#form_survey').on('click', '#btn_save',function(){
			    //validasi
			    var url = "<?php echo site_url($setting['url'].'/login/index/save_survey'); ?>";
			    var param  = $('#form_survey').serializeArray();
			    Metronic.blockUI({ target: '#form_survey',  boxed: true});
			    $.post(url, param, function(msg){
			        
			        //reload 
	                var html = '<div style="text-align:center;font-size:60px;color:black;font-weight:bold;">'+
									'Thanks For Partisipan'+
								'</div>';
					$('#form_survey').html(html);
					
					//alert
			        swal({
	                        title: 'THANKS',
	                        text: " Thanks For Your Partisipan",
	                        type: "success",
	                        // showCancelButton: true,
	                        confirmButtonColor: '#1e1c9c',
	                        confirmButtonText: 'Close',
	                        closeOnConfirm: true
	                });
			        
			        Metronic.unblockUI('#form_survey');
			    });
			});
            
            // $(".more-stars").stars({ stars:20 });
            // $(".font-size").stars();
            // $(".value-set").stars({ value:4 });
            // $(".green-color").stars({ color:'#73AD21' });
            // $(".icon-change").stars({
            //     emptyIcon: 'fa-thumbs-o-up',
            //     filledIcon: 'fa-thumbs-up'
            // });
            // $(".text").stars({ 
            //     text: ["1 star", "2 star", "3 star", "4 star", "5 star"]
            // });
        });
        </script>


	</body>
	<!-- END BODY -->
</html>