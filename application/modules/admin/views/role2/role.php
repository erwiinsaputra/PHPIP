<link rel="stylesheet" type="text/css" href="<?=base_url()?>/public/assets/global/plugins/jquery-nestable/jquery.nestable.css"/>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light datatable">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-table font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Table <?php echo $setting['pagetitle']?></span>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body">
                
                <div class="row">
                    <div class="col-md-12">
                        <h3>Serialised Output (per list)</h3>
                        <textarea id="nestable_list_1_output" class="form-control col-md-12 margin-bottom-10"></textarea>
                        <textarea id="nestable_list_2_output" class="form-control col-md-12 margin-bottom-10"></textarea>
                    </div>
                </div>

                <div class="row">
						<div class="col-md-6">
							<div class="portlet box blue">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-comments"></i>Nestable List 1
									</div>
									<div class="tools">
										<a href="javascript:;" class="collapse">
										</a>
										<a href="#portlet-config" data-toggle="modal" class="config">
										</a>
										<a href="javascript:;" class="reload">
										</a>
										<a href="javascript:;" class="remove">
										</a>
									</div>
								</div>
								<div class="portlet-body ">
									<div class="dd" id="nestable_list_1">
										<ol class="dd-list">
											<li class="dd-item" data-id="1">
												<div class="dd-handle">
													 Item 1
												</div>
											</li>
											<li class="dd-item" data-id="2">
												<div class="dd-handle">
													 Item 2
												</div>
												<ol class="dd-list">
													<li class="dd-item" data-id="3">
														<div class="dd-handle">
															 Item 3
														</div>
													</li>
													<li class="dd-item" data-id="4">
														<div class="dd-handle">
															 Item 4
														</div>
													</li>
													<li class="dd-item" data-id="5">
														<div class="dd-handle">
															 Item 5
														</div>
														<ol class="dd-list">
															<li class="dd-item" data-id="6">
																<div class="dd-handle">
																	 Item 6
																</div>
															</li>
															<li class="dd-item" data-id="7">
																<div class="dd-handle">
																	 Item 7
																</div>
															</li>
															<li class="dd-item" data-id="8">
																<div class="dd-handle">
																	 Item 8
																</div>
															</li>
														</ol>
													</li>
													<li class="dd-item" data-id="9">
														<div class="dd-handle">
															 Item 9
														</div>
													</li>
													<li class="dd-item" data-id="10">
														<div class="dd-handle">
															 Item 10
														</div>
													</li>
												</ol>
											</li>
											<li class="dd-item" data-id="11">
												<div class="dd-handle">
													 Item 11
												</div>
											</li>
											<li class="dd-item" data-id="12">
												<div class="dd-handle">
													 Item 12
												</div>
											</li>
										</ol>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="portlet box green">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-comments"></i>Nestable List 2
									</div>
									<div class="tools">
										<a href="javascript:;" class="collapse">
										</a>
										<a href="#portlet-config" data-toggle="modal" class="config">
										</a>
										<a href="javascript:;" class="reload">
										</a>
										<a href="javascript:;" class="remove">
										</a>
									</div>
								</div>
								<div class="portlet-body">
									<div class="dd" id="nestable_list_2">
										<ol class="dd-list">
											<li class="dd-item" data-id="13">
												<div class="dd-handle">
													 Item 13
												</div>
											</li>
											<li class="dd-item" data-id="14">
												<div class="dd-handle">
													 Item 14
												</div>
											</li>
											<li class="dd-item" data-id="15">
												<div class="dd-handle">
													 Item 15
												</div>
												<ol class="dd-list">
													<li class="dd-item" data-id="16">
														<div class="dd-handle">
															 Item 16
														</div>
													</li>
													<li class="dd-item" data-id="17">
														<div class="dd-handle">
															 Item 17
														</div>
													</li>
													<li class="dd-item" data-id="18">
														<div class="dd-handle">
															 Item 18
														</div>
													</li>
												</ol>
											</li>
										</ol>
									</div>
								</div>
							</div>
						</div>
					</div>

            </div>
        </div>

        
    </div>
</div>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?=base_url()?>/public/assets/global/plugins/jquery-nestable/jquery.nestable.js"></script>

<script type="text/javascript">
$(document).ready(function() {  

    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // activate Nestable for list 1
    $('#nestable_list_1').nestable({
        group: 1
    }).on('change', updateOutput);

    // activate Nestable for list 2
    $('#nestable_list_2').nestable({
        group: 1
    }).on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable_list_1').data('output', $('#nestable_list_1_output')));
    updateOutput($('#nestable_list_2').data('output', $('#nestable_list_2_output')));

    $('#nestable_list_menu').on('click', function (e) {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

    //mousedown
    $(document).on('mousemove', function(e) {
        var y = e.clientY;
        var h = $(window).height();
        var n = h - y;  
        if (n < 60) {
            var t = parseFloat($(window).scrollTop());
            console.log(t);
            $('html,body').animate({scrollTop:t + 60 + 'px'},200);
        } else {
            $('html,body').stop();
        }
    });

});
</script>