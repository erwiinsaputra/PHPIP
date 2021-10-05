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
        <link href="<?php echo base_url('public/assets/global/plugins/bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" type="text/css" />
		<!-- END PAGE LEVEL SCRIPTS -->

		<!-- BEGIN THEME STYLES -->
		<link href="<?php echo base_url('public/assets/global/css/components-rounded.css');?>" id="style_components" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('public/assets/global/css/plugins.css');?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('public/assets/admin/layout/css/layout.css');?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('public/assets/admin/layout/css/custom.css');?>" rel="stylesheet" type="text/css"/>
		<!-- END THEME STYLES -->

		<link href="<?php echo base_url('public/assets/global/plugins/bootstrap-select/bootstrap-select.min.css');?>" rel='stylesheet' type='text/css' />
		<link href="<?php echo base_url('public/assets/global/plugins/select2/select2.css');?>" rel='stylesheet' type='text/css' />
        <link href="<?php echo base_url('public/assets/global/plugins/sweet-alert/sweet-alert.css');?>" rel='stylesheet' type='text/css' />

        <style type="text/css">
		    /*.wrapper { width:960px; margin:0 auto; padding:45px 0; }*/
			.demo-wrapper .rating-text { height:36px; width:100%; font-size:18px; }
			.demo i { margin:0 3px; font-size:30px !important; }
			.font-size i { font-size:9px !important; }
		</style>
        <link rel="shortcut icon" href="<?php echo base_url('public/fav.ico') ?>"/>
	</head>
<!-- END HEAD -->
	
	<!-- BEGIN BODY -->
	<body>

		<div style="text-align:center;margin:30px;">


			<form id="form_survey" method="post" action="javascript:;" class="form-horizontal">

				<input name="ams_id" type="hidden" value="<?=$ams_id?>" class="form-control" />

				<div class="form-body">
					<div class="row">
				        <div class="col-md-12" style="text-align:left;">
							<img src="<?=base_url()?>public/GMF-AeroAsia.png" style="width:25%;height:25%;">
						</div>
					</div>
					<div style="margin-top:10px;border-top:3px solid black"></div>
					<div class="row" style="width:100%;">
				        <div class="col-md-12">
							<div style="padding-top:10px;text-align:center;font-size:25px;color:black;font-weight:bold;">
								<u>Customer Feed Back</u>
							</div>
						</div>
					</div>
					<br>
				    <div class="row">
				        <div class="col-md-12" style="text-align:center;">
							<label><b>What Service(s) did your company use for this project?</b></label>
							<br>
							<div style="width:45%;margin:0px auto;">
								<table width="100%">
									<tbody>

										<?php 
										$jum_category = count($category)/3; 
										for ($i=0; $i < $jum_category; $i++) { 
											$a = $i * 3;
										?>

										<tr style="text-align:left;">
											<td style="padding-right:10px;">
												<?php if(@$category[$a]->category_id != '') { ?>
												<label class="checkbox-inline"><input class="cek_category" type="checkbox" name="category_id[]"  id="category_id_<?=@$category[$a]->category_id;?>" value="<?=@$category[$a]->category_id;?>"> <?=str_replace(" ", "&nbsp;", @$category[$a]->category_name);?></label>
												<?php  } ?>
											</td>
											<td style="padding-right:10px;">
												<?php if(@$category[$a+1]->category_id != '') { ?>
												<label class="checkbox-inline"><input class="cek_category" type="checkbox" name="category_id[]"  id="category_id_<?=@$category[$a+1]->category_id;?>" value="<?=@$category[$a+1]->category_id;?>"> <?=str_replace(" ", "&nbsp;", @$category[$a+1]->category_name);?></label>
												<?php  } ?>
											</td>
											<td style="">
												<?php if(@$category[$a+2]->category_id != '') { ?>
												<label class="checkbox-inline"><input class="cek_category" type="checkbox" name="category_id[]"  id="category_id_<?=@$category[$a+2]->category_id;?>" value="<?=@$category[$a+2]->category_id;?>"> <?=str_replace(" ", "&nbsp;", @$category[$a+2]->category_name);?></label>
												<?php  } ?>
											</td>
										</tr>
										<?php  } ?>
									</tbody>
								</table>
							</div>
							
						</div>
					</div>
				    <div class="row load_survey" style="margin-top:10px;"></div>
					<div class="row">
				    	<div class="col-md-12" style="margin-top:-10px;">
							<br>
							<label><b>Remark / Other Comments :</b></label>
							<br>
							<textarea name="survey_text" class="" rows="3" style="width:40%;padding:10px;"></textarea>
						</div>
						<div class="col-md-12">
							<button class="btn btn-primary" id="btn_save"><i class="fa fa-check"></i> SEND</button>
						</div>
					</div>

				</div>
			</form>
		</div>


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
        <script src="<?php echo base_url('public/assets/global/plugins/bootstrap-select/bootstrap-select.min.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/select2/select2.min.js');?>" type="text/javascript"></script>
		<!-- END JAVASCRIPTS -->


        <script type="text/javascript">
        $(document).ready(function () {

            //cek kategory
            $('.cek_category').on('click',function(){
            	var id = $(this).val();
			    var name = $(this).parent().text();
			    //cek checked	
            	var cek = $(this).attr('checked');
            	if(cek == 'checked'){
    	            
			    	//load survey
			    	var html_load_survey = '';
			    	html_load_survey += 
			    	'<div class="survey_'+id+'" style="width:50%; margin:0px auto; border:2px solid black; margin-bottom:20px;">'+
				    	// '<div class="col-md-2" style="text-align:right;">'+
							'<br>'+
							'<label style="font-size:25px;"><b><u>'+name+'</u></b></label>'+
							'<br>'+
							'<label><b> - How would you rate GMF performance ?</b></label>'+
							'<br><br>'+
				    	// '</div>'+
				    	// '<div class="col-md-10">'+
							'<div class="wrapper">'+
					            '<div class="demo-wrapper">'+
					                '<div class="demo star_'+id+'"></div>'+
					            '</div>'+
					            '<div class="load_pertanyaan_'+id+'" style="margin-bottom:20px;"></div>'+
					            '<div style="margin:0px auto; width:50%;text-align:left;">'+
					            	'<div class="checkbox-list load_cekbox_'+id+'" style="margin-top:-20px;">&nbsp;</div>'+
					            '</div>'+
					        '</div>'+
					    // '</div>'+
					    // '<div class="col-md-6">'+
							'<input type="hidden" name="survey_rate[]" id="survey_rate_'+id+'">'+
							'<input type="hidden" name="survey_category[]" value="'+id+'">'+
						// '</div>'+
					'</div>';
		            $('.load_survey').append(html_load_survey);

			    	//rate
			    	$(".star_"+id).stars({ 
		            	// text: ["Bad", "Not so bad", "Good", "Very Good", "Perfect"],
		            	text: ["Very Unsatisfied", "Unsatisfied", "Neutral", "Satisfied", "Very Satisfied"],
		            	value: 3,
		            	click: function(i) {
		                    var star_rate  = i;
		                    var star_category = id;
		                    var url = "<?php echo site_url($setting['url'].'/login/index/get_option_survey'); ?>";
		                    var param = {star_rate:star_rate, star_category:star_category};
		                    Metronic.blockUI({ target: '.load_cekbox_'+id,  boxed: true});
		                    $.post(url, param, function(msg){
		                    	var id = msg.star_category;
		                    	var star_rate = msg.star_rate;
		                    	//load option
		                    	$('.load_cekbox_'+id).html('&nbsp;');
		                    	var html = '';
		                    	$.each(msg.data, function(index, val) {

		                    		html += '<label><input type="checkbox" name="survey_option_'+id+'[]"  id="survey_option_'+index+'" value="'+val.id+'"> '+val.name+' </label>';
		                    		$('.load_cekbox_'+id).html(html);
		                    	});
		                    	$('#survey_rate_'+id).val(star_rate);
		                        Metronic.unblockUI('.load_cekbox_'+id);
		                    }, 'json');
		                    //tambahan pertanyaan jika rate kurang dari 3
	                    	if(i <= 3){
								$('.load_pertanyaan_'+id).html('<b>What aspect(s) do you think can be improved?</b>');
							}else{
								$('.load_pertanyaan_'+id).html('<b>What aspect(s) are you most satisfied about?</b>');
							}
		                    //text rate
		            		var txt_rate = $(this).attr('data-rating-text');
		            		$(this).parent().find('.rating-text').text(txt_rate);
		                }
		            });
					$(".star_"+id).find('.selected').eq(2).click()
    	        }else{
    	        	$('.survey_'+id).remove();
    	        }
            });

	
			$('#form_survey').on('click', '#btn_save',function(){

				var arr_cek = $('.cek_category');
				var ada = "";
				$.each(arr_cek, function(index, val) {
					var cek = $(this).attr('checked');
            		if(cek == 'checked'){
            			ada = "ada";
            		}
				});

				if(ada == 'ada'){
					var id = $(this).val();
				    var name = $(this).parent().text();
				    //cek checked	
	            	var cek = $(this).attr('checked');

				    //validasi
				    var url = "<?php echo site_url($setting['url'].'/login/index/save_survey'); ?>";
				    var param  = $('#form_survey').serializeArray();
				    Metronic.blockUI({ target: '#form_survey',  boxed: true});
				    $.post(url, param, function(msg){
				        
				        //reload 
		                var html = '<div style="width:60%; margin:80px auto; border:5px solid black; padding:10px;">'+
										'<div style="font-size:30px; color:blue; font-weight:bold;">Thank you for your feedback</div>'+
										'<div style="font-size:20px; color:black;">We hope to continue serve you in the future.</div>'+
									'</div>';
						$('#form_survey').html(html);
						
						//alert
				        swal({
		                        title: 'THANKS',
		                        text: " Thank You For your feedback. We Hope to Continue Serve in the future.",
		                        type: "success",
		                        // showCancelButton: true,
		                        confirmButtonColor: '#1e1c9c',
		                        confirmButtonText: 'OK',
		                        closeOnConfirm: true
		                });
				        
				        Metronic.unblockUI('#form_survey');
				    });
				}else{
					alert('Please checking option Service did your company use ?');
				}

            	
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