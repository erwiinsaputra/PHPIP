<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-file font-green-meadow"></i>
            <span class="font-green-meadow"><?php echo $setting['pagetitle']; ?></span>
        </div>
    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form method="post" action="<?php echo site_url($setting['url'].'edit/'.$id) ?>" class="form-horizontal form-add">
            <input type="hidden" name="ex_csrf_token" value="<?= csrf_get_token(); ?>"><div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span>There are some errors on the form. Please check below!</span>
            </div>
            <div class="alert alert-warning display-hide">
                <button class="close" data-close="alert"></button>
				<span>
                    <ul></ul>
				</span>
            </div>
            <div class="form-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4">Employee Number
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-7">
                                <div class="input-icon right">
                                    <input readonly type="text" class="form-control" name="USER_USERNAME" placeholder="Username" value="<?php echo $data->USER_USERNAME ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4">Role
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-7">
                                <div class="checkbox-list">
                                    <input name="USER_ROLE_ID" type="text" class="form-control required" id="role" value="<?= $data->USER_ROLE_ID ?>" placeholder="Role"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4">Active
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-7">
                                <select class="form-control select2_biasa" name="USER_IS_ACTIVE" placeholder="Active" >
                                    <option value="0" <?= ($data->USER_IS_ACTIVE == '0') ? 'selected' : ''; ?> >Non-Active</option>
                                    <option value="1" <?= ($data->USER_IS_ACTIVE == '1') ? 'selected' : ''; ?> >Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="show_ext_ams">  
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4">Name Initial
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-7">
                                <input name="USER_INITIAL" type="text" class="form-control" placeholder="Nama" value="<?php echo $data->USER_INITIAL ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4">Customer Company
                                <span class="required" aria-required="true"></span>
                            </label>
                            <div class="col-md-7">
                                <select class="form-control select2_biasa" name="USER_CUS_COMPANY" placeholder="Level"  >
                                    <option value="0" <?= ($data->USER_CUS_COMPANY == '0') ? 'selected' : ''; ?> >GA</option>
                                    <option value="1" <?= ($data->USER_CUS_COMPANY == '1') ? 'selected' : ''; ?> >NGA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-4 col-md-8">
                                <button type="submit" class="btn green">Save</button>
                                <a class="ajaxify" href="<?php echo site_url($setting['url']) ?>">
                                    <button type="button" class="btn default">Back</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
            </div>
            <a href="<?=site_url($setting['url'].'show_edit/'.$id)?>" class="ajaxify" id="reload" style="display: none;"></a>
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
            var url = '<?php echo site_url($setting['url'].'checkUsername/'.$id); ?>';
            checkData(this, url)
        });

        $("input[name='email']").change(function () {
            var url = '<?php echo site_url($setting['url'].'checkEmail/'.$id); ?>';
            checkData(this, url)
        });

        $("#role").select2({
            minimumInputLength: -1,  
            allowClear:true,  
            dropdownAutoWidth : true, 
            multiple : true,
            ajax: {
                url: "<?php echo site_url($setting['url'].'get_role') ?>",
                dataType: 'json',
                quietMillis: 250,
                cache: true,
                data: function (term, page) { return { q: term }; },
                results: function (data, page) { return { results: data.item }; },
            },
            formatResult: function (item){ return item.name;},
            formatSelection: function (item){return item.name;},
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    var id = id.replace(/,/g , "-");
                    $.ajax("<?=site_url($setting['url'].'get_role')?>" +"/"+ id, {
                    dataType: "json" }).done( function(data) { callback(data); });
                }
            },
        });
        
        $(".select2_biasa").select2({
            minimumInputLength: -1,
            allowClear:true,
            dropdownAutoWidth : true,
        });

    });
</script>