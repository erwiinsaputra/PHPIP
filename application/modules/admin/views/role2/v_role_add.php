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
                            <label class="control-label col-md-2">Nama Role
                                <span class="required" aria-required="true">*</span>
                            </label>
                            <div class="col-md-7">
                                <input name="name" type="text" class="form-control required" placeholder="Nama Role">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MENU -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">AKSES MENU</div>
                                <div class="tools"><input class="chk_semua" type="checkbox" /></div>
                            </div>
                            <div class="portlet-body">
                                <?php  $i = 1; $tot = count($data_menu); foreach ($data_menu as $row) : ?>

                                    <?php if( $i % 3 === 0 || $i == 1): ?>
                                    <div class="row">
                                    <?php endif;?>
                                        <div class="col-md-4">

                                            <div class="portlet box grey">
                                                <div class="portlet-title">
                                                    <div class="caption"><?php echo $row->MENU_NAME;?></div>
                                                    <div class="tools"><input name="chk[]" class="chk_semua" type="checkbox" value="<?php echo $row->MENU_ID;?>"/></div>
                                                </div>
                                                
                                                <?php if(@$row->sub != ''){?>
                                                    <div class="portlet-body">
                                                        <table class="table table-bordered group">
                                                            <?php  foreach( @$row->sub as $row2): ?>
                                                            <tr>
                                                                <td><?php echo $row2->MENU_NAME;?></td>
                                                                <td width="10px"><input type="checkbox" class="chk_sub" name="chk[]" value="<?php echo $row2->MENU_ID;?>" /></td>
                                                            </tr>
                                                            <?php endforeach?>
                                                        </table>
                                                    </div>
                                                <?php }?>

                                            </div>

                                        </div>
                                    <?php if( $i % 3 === 0 || $i == $tot): ?>
                                    </div>
                                    <?php endif;?>
                                <?php $i++; endforeach;?>
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

        $('.chk_semua').click(function(event) {
            if($(this).is(':checked')){
                var ele = $(this).closest('.portlet');
                $('input[type="checkbox"]', ele).prop('checked', true);
                $('input[type="checkbox"]', ele).closest('span').addClass('checked');
            }else{
                var ele = $(this).closest('.portlet');
                $('input[type="checkbox"]', ele).prop('checked', false);
                $('input[type="checkbox"]', ele).closest('span').removeClass('checked');
            }
        });

        $('.chk_sub').click(function(event) {
            var arr_cek = $(this).closest('.portlet-body').find('.chk_sub');
            var cek = 'kosong';
            $.each(arr_cek, function(index, val) {
                if($(this).is(':checked')){
                    cek = 'ada'; 
                }
            });

            if($(this).is(':checked')){
                if(cek == 'ada'){
                    var ele = $(this).closest('.portlet').find('.chk_semua');
                    ele.attr('checked', 'checked');
                    ele.closest('span').addClass('checked');
                }else{
                    var ele = $(this).closest('.portlet').find('.chk_semua');
                    ele.attr('checked', 'checked');
                    ele.closest('span').removeClass('checked');
                }
            }else{
                if(cek == 'ada'){
                    var ele = $(this).closest('.portlet').find('.chk_semua');
                    ele.attr('checked', 'checked');
                    ele.closest('span').addClass('checked');
                }else{
                    var ele = $(this).closest('.portlet').find('.chk_semua');
                    ele.attr('checked', 'checked');
                    ele.closest('span').removeClass('checked');
                }
            }
            
        });

    });
</script>