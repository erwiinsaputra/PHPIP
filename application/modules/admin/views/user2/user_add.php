<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-file font-green-meadow"></i>
            <span class="font-green-meadow"><?php echo $setting['pagetitle']; ?></span>
        </div>
    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form method="post" action="<?php echo site_url($setting['url'].'add') ?>" class="form-horizontal form-add">
            <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>"><div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span>There are some errors on the form. Please check below!</span>
            </div>
            <div class="alert alert-warning display-hide">
                <button class="close" data-close="alert"></button>
				<span><ul></ul></span>
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-2">Employee Number
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-7">
                                <input name="USER_USERNAME" type="text" class="form-control required" placeholder="Employee Number">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-2">Username
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-7">
                                <input name="USER_NAME" type="text" class="form-control required" placeholder="Username">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-2">Role
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-7">
                                <input id="role" name="USER_ROLE_ID" type="text" class="form-control required" placeholder="Role">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-2">Region
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-7">
                                <input id="reg_id" name="USER_REGION_ID" type="text" class="form-control required" placeholder="Region">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-2">Customer
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-7">
                                <input id="cus_id" name="USER_CUS_ID" type="text" class="form-control required" placeholder="Customer Name">
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn green">Save</button>
                                <a class="ajaxify" href="<?php echo site_url($setting['url']) ?>">
                                    <button type="button" class="btn default">Back</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
            <a href="<?=site_url($setting['url'].$setting['method'])?>" class="ajaxify" id="reload" style="display: none;"></a>
        </form>
        <!-- END FORM-->
    </div>
</div>

<script>
    function checkData(ele, url)
    {
        var input = $(ele);

        if (input.val() === "") {
            input.closest('.form-group').removeClass('has-error').removeClass('has-success');
            $('.fa-check, fa-warning', input.closest('.form-group')).remove();
            return;
        }

        input.attr("readonly", true).
            attr("disabled", true).
            addClass("spinner");

        $.post(url, {
            value: input.val()
        }, function (res) {
            input.attr("readonly", false).
                attr("disabled", false).
                removeClass("spinner");
            // change popover font color based on the result
            if (res.status == 1) {
                input.closest('.form-group').removeClass('has-error').addClass('has-success');
                $('.fa-warning', input.closest('.form-group')).remove();
                input.before('<i class="fa fa-check"></i>');
            } else {
                input.closest('.form-group').removeClass('has-success').addClass('has-error');
                $('.fa-check', input.closest('.form-group')).remove();
                input.before('<i class="fa fa-warning"></i>');
            }
        }, 'json');
    }

    jQuery(document).ready(function() {
        // Fungsi Form Validasi
        var form = '.form-add';

        FormValidation.initDefault(form);

        $("input[name='username']").change(function () {
            var url = '<?php echo site_url($setting['url'].'checkUsername'); ?>';
            checkData(this, url)
        });

        $("input[name='email']").change(function () {
            var url = '<?php echo site_url($setting['url'].'checkEmail'); ?>';
            checkData(this, url);
        });

        $("#role").select2({
            minimumInputLength: 1,
            allowClear: true,
            multiple: true,
            ajax: {
                url: "<?php echo site_url($setting['url'].'get_role'); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return { q: term };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            formatResult: function(item){
                return item.name;
            },
            formatSelection: function(item){
                if(item.id == '2'){
                    $('.input_kabkot').show();
                }else{
                    $('.input_kabkot').hide();
                }
                return item.name;
            }
        });

        $("#cus_id").select2({
            minimumInputLength: 1,
            allowClear: true,
            multiple: true,
            ajax: {
                url: "<?php echo site_url($setting['url'].'select_customer'); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return { q: term };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            formatResult: function(item){
                return item.name;
            },
            formatSelection: function(item){
                if(item.id == '2'){
                    $('.input_kabkot').show();
                }else{
                    $('.input_kabkot').hide();
                }
                return item.name;
            }
        });

        $("#reg_id").select2({
            minimumInputLength: 1,
            allowClear: true,
            multiple: true,
            ajax: {
                url: "<?php echo site_url($setting['url'].'select_region'); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return { q: term };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            formatResult: function(item){
                return item.name;
            },
            formatSelection: function(item){
                if(item.id == '2'){
                    $('.input_kabkot').show();
                }else{
                    $('.input_kabkot').hide();
                }
                return item.name;
            }
        });

    });
</script>