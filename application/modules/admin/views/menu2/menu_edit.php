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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Menu
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" name="menu" placeholder="Nama Menu" class="form-control required" value="<?=$item->MENU?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Icon
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="input-group input-large">
                                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                            <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn default btn-file">
                                            <span class="fileinput-new">Select file </span>
                                            <span class="fileinput-exists">Change </span>
                                            <input type="file" name="file" class="required">
                                        </span>
                                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput">Remove </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Parent
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-8">
                                <select name="jenis_parent" id="jenis_parent" class="required form-control">
                                    <option value="NO" <?=($item->parent == 'NO' ? 'selected' : '') ?> >NO</option>
                                    <option value="YES" <?=($item->parent == 'YES' ? 'selected' : '') ?> >YES</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="parent_yes"> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-3">Parent
                                    <span class="required" aria-required="true">*</span>
                                </label>
                                <div class="col-md-8">
                                    <input name="parent" id="parent" type="text" class="required form-control" placeholder="pilih Parent" value="<?=$item->parent?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-3">Nama Controler
                                    <span class="required" aria-required="true">*</span>
                                </label>
                                <div class="col-md-8">
                                    <input name="controler" id="controler" type="text" class="required form-control" placeholder="Nama Controler" value="<?=$item->controler?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="parent_no"> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-3">Module
                                    <span class="required" aria-required="true">*</span>
                                </label>
                                <div class="col-md-8">
                                    <select name="jenis_module" id="jenis_module" class="required form-control" >
                                    <option value="NO">NO</option>
                                    <option value="YES">YES</option>
                                </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="module_yes">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Nama Module Folder
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <input name="module" id="module" type="text" class="required form-control" placeholder="Nama Module Folder" value="<?=$item->module?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="module_no">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Nama Controler
                                        <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <input name="controler" id="controler" type="text" class="required form-control" placeholder="Nama Controler" value="<?=$item->controler?>"/>
                                    </div>
                                </div>
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
    });
</script>