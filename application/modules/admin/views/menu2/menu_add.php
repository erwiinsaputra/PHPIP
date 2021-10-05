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
                                <input type="text" name="menu" placeholder="Nama Menu" class="form-control required" />
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
                                    <option value="NO">NO</option>
                                    <option value="YES">YES</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="parent_yes"> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-3">Menu Parent
                                    <span class="required" aria-required="true">*</span>
                                </label>
                                <div class="col-md-8">
                                    <input name="parent" id="parent" type="text" class="required form-control" placeholder="pilih Parent" />
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
                                    <input name="controler" id="controler" type="text" class="required form-control" placeholder="Nama Controler" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="parent_no"> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-3">Folder
                                    <span class="required" aria-required="true">*</span>
                                </label>
                                <div class="col-md-8">
                                    <select name="jenis_module" id="jenis_module" class="required form-control">
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
                                        <input name="module" id="module" type="text" class="required form-control" placeholder="Nama Module Folder" />
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
                                        <input name="controler" id="controler" type="text" class="required form-control" placeholder="Nama Controler" />
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
            <a href="<?=site_url($setting['url'].$setting['method'])?>" class="ajaxify" id="reload" style="display: none;"></a>
        </form>
        <!-- END FORM-->
    </div>
</div>
<script>
    jQuery(document).ready(function() {
        // Fungsi Form Validasi
        var form = '.form-add';
        FormValidation.initDefault(form);

        //show hide jenis_parent  
        $('#jenis_parent').on('change',function(){
            var val = $(this).val();
            if(val == 'YES'){
                $('#parent_no').show();
                $('#parent_yes').hide();
            }else{
                $('#parent_no').hide();
                $('#parent_yes').show();
            }
        });
        $('#jenis_parent').change();

        //show hide jenis_module  
        $('#jenis_module').on('change',function(){
            var val = $(this).val();
            if(val == 'NO'){
                $('#module_no').show();
                $('#module_yes').hide();
            }else{
                $('#module_no').hide();
                $('#module_yes').show();
            }
        });
        $('#jenis_module').change();


        $("#parent").select2({
            minimumInputLength: 1,
            ajax: {
                url: "<?= site_url($setting['url'])?>/get_parent",
                dataType: 'json',
                quietMillis: 250,
                cache: true,
                data: function (term, page) {
                    return { q: term };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= site_url($setting['url'])?>/get_parent/"+ id, {
                    dataType: "json"
                    }).done( function(data) { callback(data[0]); });
                }
            },
            formatResult: formatResult,
            formatSelection: formatResult,
        });

        //format select2
        function formatResult(item){
            return item.name;
        }


    });
</script>