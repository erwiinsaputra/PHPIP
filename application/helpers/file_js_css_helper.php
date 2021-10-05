<?php
    function get_css($css)
    {
        switch($css){
            case 'bootstrap-select':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-select/bootstrap-select.min.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'bootstrap-switch':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'bootstrap-fileinput':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'colorbox':
                echo "<link href='".base_url('public/assets/global/plugins/colorbox/colorbox.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'datatables':
                echo "<link href='".base_url('public/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')."' rel='stylesheet' type='text/css' />";
                echo "<link href='".base_url('public/assets/global/plugins/datatables/colvis/dataTables.colVis.css')."' rel='stylesheet' type='text/css' />";
                echo "<link href='".base_url('public/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'datepicker':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'daterangepicker':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'datetimepicker':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'fileinput':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'dropzone':
                echo "<link href='".base_url('public/assets/global/plugins/dropzone/css/dropzone.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'fullcalendar':
                echo "<link href='".base_url('public/assets/global/plugins/fullcalendar/fullcalendar.min.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'gtreetable':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-gtreetable/bootstrap-gtreetable.min.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'icheck':
                echo "<link href='".base_url('public/assets/global/plugins/icheck/skins/all.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'jqvmap':
                echo "<link href='".base_url('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'jstree':
                echo "<link href='".base_url('public/assets/global/plugins/jstree/dist/themes/default/style.min.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'morris':
                echo "<link href='".base_url('public/assets/global/plugins/morris/morris.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'profile':
                echo "<link href='".base_url('public/assets/admin/pages/css/profile.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'select2':
                echo "<link href='".base_url('public/assets/global/plugins/select2/select2.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'sweet-alert':
                echo "<link href='".base_url('public/assets/global/plugins/sweet-alert/sweet-alert.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'tags':
                echo "<link href='".base_url('public/assets/global/plugins/jquery-tags-input/jquery.tagsinput.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'tasks':
                echo "<link href='".base_url('public/assets/admin/pages/css/tasks.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'timepicker':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'toastr':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-toastr/toastr.min.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'typeahead':
                echo "<link href='".base_url('public/assets/global/plugins/typeahead/typeahead.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'wysihtml5':
                echo "<link href='".base_url('public/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'validation2':
                echo "<link href='".base_url('public/assets/global/plugins/validasi/css/bvalidator.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'validation2':
                echo "<link href='".base_url('public/assets/global/plugins/validasi/css/bvalidator.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'multi-select':
                echo "<link href='".base_url('public/assets/global/plugins/jquery-multi-select/css/multi-select.css')."' rel='stylesheet' type='text/css' />";
                break;
            case 'nestable':
                echo "<link href='".base_url('public/assets/global/plugins/jquery-nestable/jquery-nestable.css')."' rel='stylesheet' type='text/css' />";
                break;
        }
    }

    function get_js($js){
        switch($js){
            case 'amcharts':
                echo '<script src="'.base_url('public/assets/global/plugins/amcharts/amcharts/amcharts.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/amcharts/amcharts/serial.js').'" type="text/javascript"></script>';
                break;
            case 'bootstrap-select':
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-select/bootstrap-select.min.js').'" type="text/javascript"></script>';
                break;
            case 'bootstrap-switch':
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js').'" type="text/javascript"></script>';
                break;
            case 'bootstrap-fileinput':
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js').'" type="text/javascript"></script>';
                break;
            case 'ckeditor':
                echo '<script src="'.base_url('public/assets/global/plugins/ckeditor/ckeditor.js').'" type="text/javascript"></script>';
                break;
            case 'colorbox':
                echo '<script src="'.base_url('public/assets/global/plugins/colorbox/jquery.colorbox-min.js').'" type="text/javascript"></script>';
                break;
            case 'datatables':
                echo '<script src="'.base_url('public/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/scripts/datatable.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/datatables/colvis/dataTables.colVis.js').'" type="text/javascript"></script>';
                break;
            case 'datepicker':
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js').'" type="text/javascript"></script>';
                break;
            case 'daterangepicker':
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-daterangepicker/moment.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js').'" type="text/javascript"></script>';
                break;
            case 'datetimepicker':
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js').'" type="text/javascript"></script>';
                break;
            case 'dropzone':
                echo '<script src="'.base_url('public/assets/global/plugins/dropzone/dropzone.js').'" type="text/javascript"></script>';
                break;
            case 'easypiechart':
                echo '<script src="'.base_url('public/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js').'" type="text/javascript"></script>';
                break;
            case 'fileinput':
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js').'" type="text/javascript"></script>';
                break;
            case 'flot':
                echo '<script src="'.base_url('public/assets/global/plugins/flot/jquery.flot.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/flot/jquery.flot.resize.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/flot/jquery.flot.categories.min.js').'" type="text/javascript"></script>';
                break;
            case 'fullcalendar':
                echo '<script src="'.base_url('public/assets/global/plugins/fullcalendar/fullcalendar.min.js').'" type="text/javascript"></script>';
                break;
            case 'gtreetable':
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-gtreetable/bootstrap-gtreetable.min.js').'" type="text/javascript"></script>';
                break;
            case 'icheck':
                echo '<script src="'.base_url('public/assets/global/plugins/icheck/icheck.min.js').'" type="text/javascript"></script>';
                break;
            case 'input-mask':
                // echo '<script src="'.base_url('public/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/input-mask/jquery.inputmask.bundle.js').'" type="text/javascript"></script>';
                break;
            case 'jqvmap':
                echo '<script src="'.base_url('public/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js').'" type="text/javascript"></script>';
                break;
            case 'jstree':
                echo '<script src="'.base_url('public/assets/global/plugins/jstree/dist/jstree.min.js').'" type="text/javascript"></script>';
                break;
            case 'morris':
                echo '<script src="'.base_url('public/assets/global/plugins/morris/morris.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/morris/raphael-min.js').'" type="text/javascript"></script>';
                break;
            case 'pulsate':
                echo '<script src="'.base_url('public/assets/global/plugins/jquery.pulsate.min.js').'" type="text/javascript"></script>';
                break;
            case 'select2':
                echo '<script src="'.base_url('public/assets/global/plugins/select2/select2.min.js').'" type="text/javascript"></script>';
                break;
            case 'sweet-alert':
                echo '<script src="'.base_url('public/assets/global/plugins/sweet-alert/sweet-alert.min.js').'" type="text/javascript"></script>';
                break;
            case 'sparkline':
                echo '<script src="'.base_url('public/assets/global/plugins/jquery.sparkline.min.js').'" type="text/javascript"></script>';
                break;
            case 'timepicker';
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js').'" type="text/javascript"></script>';
                break;
            case 'tags':
                echo '<script src="'.base_url('public/assets/global/plugins/jquery-tags-input/jquery.tagsinput.min.js').'" type="text/javascript"></script>';
                break;
            case 'toastr':
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-toastr/toastr.min.js').'" type="text/javascript"></script>';
                break;
            case 'typeahead':
                echo '<script src="'.base_url('public/assets/global/plugins/typeahead/handlebars.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/typeahead/typeahead.bundle.min.js').'" type="text/javascript"></script>';
                break;
            case 'wysihtml5':
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js').'" type="text/javascript"></script>';
                break;
            case 'validation':
                echo '<script src="'.base_url('public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/jquery-validation/js/additional-methods.min.js').'" type="text/javascript"></script>';
                echo '<script src="'.base_url('public/assets/global/plugins/jquery-validation/js/localization/messages_id.js').'" type="text/javascript"></script>';
                break;
            case 'validation2':
                echo '<script src="'.base_url('public/assets/global/plugins/validasi/js/jquery.bvalidator.js').'" type="text/javascript"></script>';
                break;
            // case 'highchart':
            //     echo '<script src="'.base_url('public/assets/global/plugins/highcharts/highcharts.js').'" type="text/javascript"></script>';
            //     echo '<script src="'.base_url('public/assets/global/plugins/highcharts/highcharts-more.js').'" type="text/javascript"></script>';
            //     echo '<script src="'.base_url('public/assets/global/plugins/highcharts/exporting.js').'" type="text/javascript"></script>';
            //     break;
            case 'highchart_maps':
                echo '<script src="https://code.highcharts.com/maps/highmaps.js" type="text/javascript"></script>';
                echo '<script src="https://code.highcharts.com/modules/tilemap.js" type="text/javascript"></script>';
                echo '<script src="https://code.highcharts.com/modules/exporting.js" type="text/javascript"></script>';
                break;
            case 'multi-select':
                echo '<script src="'.base_url('public/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js').'" type="text/javascript" ></script>';
                break;
            case 'nestable':
                echo '<script src="'.base_url('public/assets/global/plugins/jquery-nestable/jquery-nestable.js').'" type="text/javascript" ></script>';
                break;
                
        }

    }

?>