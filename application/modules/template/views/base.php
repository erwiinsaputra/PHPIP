
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $this->config->item('app_name') ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <meta http-equiv="Content-Type" content="svg/xml"/>


        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <!-- <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/> -->
        <link href="<?php echo base_url('public/assets/global/plugins/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css');?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('public/assets/global/plugins/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet" type="text/css"/>
        <!-- 
        <link href="<?php echo base_url('public/assets/global/plugins/bootstrap/css/custom.min.css');?>" rel="stylesheet" type="text/css"/>
         -->
        <link href="<?php echo base_url('public/assets/global/plugins/uniform/css/uniform.default.css');?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css');?>" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
        <?php foreach ($this->config->item('plugin') as $key => $value):
            echo get_css($value)."\n";
        endforeach; ?>
        <!-- END PAGE LEVEL PLUGIN STYLES -->

        <!-- BEGIN PAGE STYLES -->
        <link href="<?php echo base_url('public/assets/admin/pages/css/error.css');?>" rel="stylesheet" type="text/css"/>
        <!-- END PAGE STYLES -->

        <!-- BEGIN THEME STYLES -->
        <!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
        <link href="<?php echo base_url('public/assets/global/css/components.css');?>" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('public/assets/global/css/plugins.css');?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('public/assets/admin/layout4/css/layout.css');?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('public/assets/admin/layout4/css/themes/light.css');?>" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="<?php echo base_url('public/assets/admin/layout4/css/custom.css');?>" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="<?php echo base_url('public/assets/app/img/logo/logo.png') ?>"/>
        <!-- Tmbahan CSS Custome-->
        <!-- <link href="<?php //echo base_url('public/assets/app/css/custom.css');?>" rel="stylesheet" type="text/css"/>  -->
        <link href="<?php echo base_url('public/assets/global/css/title_tip.css');?>" rel="stylesheet" type="text/css"/> 

        <!-- ===========================================================================================================  -->

        <!-- jquery -->
        <script src="<?php echo base_url('public/assets/global/plugins/jquery.min.js');?>" type="text/javascript"></script>

        <!-- highchart -->
        <script src="<?=base_url('public/assets/global/plugins/highcharts/highcharts.js')?>" type="text/javascript"></script>
        <script src="<?=base_url('public/assets/global/plugins/highcharts/highcharts-more.js')?>" type="text/javascript"></script>
        <script src="<?=base_url('public/assets/global/plugins/highcharts/funnel.js')?>"></script>
        <script src="<?=base_url('public/assets/global/plugins/highcharts/exporting.js')?>" type="text/javascript"></script>
        <!-- 
        <script src="<?=base_url('public/assets/global/plugins/highcharts/data.js')?>" type="text/javascript"></script>
        <script src="<?=base_url('public/assets/global/plugins/highcharts/drilldown.js')?>" type="text/javascript"></script>
 -->
        <script src="<?=base_url('public/assets/global/plugins/bootstrap/js/jquery.cycle2.min.js')?>" type="text/javascript"></script>
        
        <script type="text/javascript">
            //url javascript
            var base_url    = '<?php echo base_url();?>';
            var baseUrl     = '<?php echo base_url();?>';
            var site_url    = '<?php echo site_url();?>';
            var app_name    = "<?php echo $this->config->item('app_name') ?>";
            var myBaseUrl = "<?php echo site_url('global/dashboard');?>";

            //encript function
            var Base64 = {
                characters: "ABCDEFGHIJKLMNOPQRSTUVWXYZbase_url('publicfghijklmnopqrstuvwxyz0123456789+/=" ,

                encode: function( string )
                {
                    var characters = Base64.characters;
                    var result     = '';

                    var i = 0;
                    do {
                        var a = string.charCodeAt(i++);
                        var b = string.charCodeAt(i++);
                        var c = string.charCodeAt(i++);

                        a = a ? a : 0;
                        b = b ? b : 0;
                        c = c ? c : 0;

                        var b1 = ( a >> 2 ) & 0x3F;
                        var b2 = ( ( a & 0x3 ) << 4 ) | ( ( b >> 4 ) & 0xF );
                        var b3 = ( ( b & 0xF ) << 2 ) | ( ( c >> 6 ) & 0x3 );
                        var b4 = c & 0x3F;

                        if( ! b ) {
                            b3 = b4 = 64;
                        } else if( ! c ) {
                            b4 = 64;
                        }

                        result += Base64.characters.charAt( b1 ) + Base64.characters.charAt( b2 ) + Base64.characters.charAt( b3 ) + Base64.characters.charAt( b4 );

                    } while ( i < string.length );

                    return result;
                } ,

                decode: function( string )
                {
                    var characters = Base64.characters;
                    var result     = '';

                    var i = 0;
                    do {
                        var b1 = Base64.characters.indexOf( string.charAt(i++) );
                        var b2 = Base64.characters.indexOf( string.charAt(i++) );
                        var b3 = Base64.characters.indexOf( string.charAt(i++) );
                        var b4 = Base64.characters.indexOf( string.charAt(i++) );

                        var a = ( ( b1 & 0x3F ) << 2 ) | ( ( b2 >> 4 ) & 0x3 );
                        var b = ( ( b2 & 0xF  ) << 4 ) | ( ( b3 >> 2 ) & 0xF );
                        var c = ( ( b3 & 0x3  ) << 6 ) | ( b4 & 0x3F );

                        result += String.fromCharCode(a) + (b?String.fromCharCode(b):'') + (c?String.fromCharCode(c):'');

                    } while( i < string.length );

                    return result;
                }
            };



            // var Base64 = {
            //     // private property
            //     _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZbase_url('publicfghijklmnopqrstuvwxyz0123456789+/=",

            //     // public method for encoding
            //     encode : function (input) {
            //         var output = "";
            //         var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
            //         var i = 0;
            //         input = Base64._utf8_encode(input);
            //         while (i < input.length) {
            //             chr1 = input.charCodeAt(i++);
            //             chr2 = input.charCodeAt(i++);
            //             chr3 = input.charCodeAt(i++);
            //             enc1 = chr1 >> 2;
            //             enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            //             enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            //             enc4 = chr3 & 63;
            //             if (isNaN(chr2)) {
            //                 enc3 = enc4 = 64;
            //             }else if (isNaN(chr3)) {
            //                 enc4 = 64;
            //             }
            //             output = output +
            //             Base64._keyStr.charAt(enc1) + Base64._keyStr.charAt(enc2) +
            //             Base64._keyStr.charAt(enc3) + Base64._keyStr.charAt(enc4);
            //         }
            //         return output;
            //     },

            //     // public method for decoding
            //     decode : function (input) {
            //         var output = "";
            //         var chr1, chr2, chr3;
            //         var enc1, enc2, enc3, enc4;
            //         var i = 0;
            //         input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
            //         while (i < input.length) {
            //             enc1 = Base64._keyStr.indexOf(input.charAt(i++));
            //             enc2 = Base64._keyStr.indexOf(input.charAt(i++));
            //             enc3 = Base64._keyStr.indexOf(input.charAt(i++));
            //             enc4 = Base64._keyStr.indexOf(input.charAt(i++));
            //             chr1 = (enc1 << 2) | (enc2 >> 4);
            //             chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            //             chr3 = ((enc3 & 3) << 6) | enc4;
            //             output = output + String.fromCharCode(chr1);
            //             if (enc3 != 64) {
            //                 output = output + String.fromCharCode(chr2);
            //             }
            //             if (enc4 != 64) {
            //                 output = output + String.fromCharCode(chr3);
            //             }
            //         }
            //         output = Base64._utf8_decode(output);
            //         return output;
            //     },

            //     // private method for UTF-8 encoding
            //     _utf8_encode : function (string) {
            //         string = string.replace(/\r\n/g,"\n");
            //         var utftext = "";
            //         for (var n = 0; n < string.length; n++) {
            //             var c = string.charCodeAt(n);
            //             if (c < 128) {
            //                 utftext += String.fromCharCode(c);
            //             }else if((c > 127) && (c < 2048)) {
            //                 utftext += String.fromCharCode((c >> 6) | 192);
            //                 utftext += String.fromCharCode((c & 63) | 128);
            //             }else{
            //                 utftext += String.fromCharCode((c >> 12) | 224);
            //                 utftext += String.fromCharCode(((c >> 6) & 63) | 128);
            //                 utftext += String.fromCharCode((c & 63) | 128);
            //             }
            //         }
            //         return utftext;
            //     },


            //     // private method for UTF-8 decoding
            //     _utf8_decode : function (utftext) {
            //         var string = "";
            //         var i = 0;
            //         var c = c1 = c2 = 0;
            //         while ( i < utftext.length ) {
            //             c = utftext.charCodeAt(i);
            //             if (c < 128) {
            //                 string += String.fromCharCode(c);
            //                 i++;
            //             }else if((c > 191) && (c < 224)) {
            //                 c2 = utftext.charCodeAt(i+1);
            //                 string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
            //                 i += 2;
            //             }else{
            //                 c2 = utftext.charCodeAt(i+1);
            //                 c3 = utftext.charCodeAt(i+2);
            //                 string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            //                 i += 3;
            //             }
            //         }
            //         return string;
            //     }
            // }

        
            $(document).ready(function() {
                //default template
                Metronic.init(); // init metronic core componets
                Layout.init(); // init layout
                Demo.init(); // init demo features
                QuickSidebar.init(); // init quick sidebar

                // title_tip 
                $('.title_tip').hover(function(){
                        // Hover over code
                        var title = $(this).attr('title');
                        $(this).data('tipText', title).removeAttr('title');
                        $('<p class="title_css"></p>').text(title).appendTo('body').fadeIn('slow');
                }, function() {
                        // Hover out code
                        $(this).attr('title', $(this).data('tipText'));
                        $('.title_css').remove();
                }).mousemove(function(e) {
                        var mousex = e.pageX + 20; //Get X coordinates
                        var mousey = e.pageY + 10; //Get Y coordinates
                        $('.title_css').css({ top: mousey, left: mousex })
                });

            });
        </script>

    </head>
<!-- END HEAD -->

<!-- BEGIN BODY -->
<body class="body page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo" style="background-color:#e9ecf3;">
<!--    <div class="se-pre-con"></div>-->
        <!-- BEGIN HEADER -->
        <?php echo $_header; ?>
        <!-- END HEADER -->
        <div class="clearfix">
        </div>
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <?php echo $_sidebar; ?>
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <?php echo $_fullcontent; ?>
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <?php echo $_footer; ?>
        </div>
        <!-- END FOOTER -->


        <!-- button zoom in out-->
      <!--   <div style="float:right; position:fixed; z-index:999999; right:10px; bottom:10px; background:black; padding:5px;">
            <button type="button" class="btn btn-sm zoom-in">Zoom (+) </button>
            <button type="button" class="btn btn-sm zoom-out">Zoom (-) </button>
        </div> -->
        <script type="text/javascript">
        $(document).ready(function () {
            //default zoom
            // zoomLevel = 0.85;
            // $('.page-header, .page-container, .page-footer').css({ 'zoom': zoomLevel, '-moz-transform': 'scale(' + zoomLevel + ')', 'msTransform':zoomLevel, 'transform':zoomLevel });
            // //zoom in out
            // $('.zoom-in').live('click', function() { updateZoom(0.1); });
            // $('.zoom-out').live('click',function() { updateZoom(-0.1); });
            // var updateZoom = function(zoom) {
            //    zoomLevel += zoom;
            //    $('.page-header, .page-container, .page-footer').css({ 'zoom': zoomLevel, '-moz-transform': 'scale(' + zoomLevel + ')', 'msTransform':zoomLevel, 'transform':zoomLevel });
            //     window.reload_css_table();
            // }
        });
        </script>

        
        <!--[if lt IE 9]>
        <script src="<?php echo base_url('public/assets/global/plugins/respond.min.js');?>"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/excanvas.min.js');?>"></script>
        <![endif]-->
        <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
        <!-- BEGIN CORE PLUGINS -->
        
        <!-- <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script> -->
        <script src="<?php echo base_url('public/assets/global/plugins/jquery-migrate.min.js');?>" type="text/javascript"></script>
        <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
        <script src="<?php echo base_url('public/assets/global/plugins/jquery-ui/jquery-ui.min.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/bootstrap/js/bootstrap.min.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/jquery.blockui.min.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/jquery.cokie.min.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/uniform/jquery.uniform.min.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/jquery.form.min.js')?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/global/plugins/ckeditor/ckeditor.js')?>" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <?php foreach ($this->config->item('plugin') as $key => $value):
            echo get_js($value)."\n";
        endforeach; ?>
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="<?php echo base_url('public/assets/global/scripts/metronic.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/admin/layout4/scripts/layout.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/admin/layout4/scripts/quick-sidebar.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/admin/layout4/scripts/demo.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/admin/pages/scripts/index3.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url('public/assets/admin/pages/scripts/tasks.js');?>" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- END JAVASCRIPTS -->



        <script type="text/javascript">
            // //reload css table
            window.reload_css_table = function(){
                $($.fn.dataTable.tables(true)).css('width', '100%');
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw(false);
            }

            //resize window
            $(window).resize(function () { 
                window.reload_css_table();
                window.reload_logo_title();
            });

            //show filter
            $('.btn_show_filter').die().live('click', function(e) {
                // cek class datatable
                var cek = $(this).parents('.datatable').find('tr.filter').length;
                if(cek > 0){
                    var cek2 = $(this).parents('.table-container').find('tr.filter').length;
                    if(cek2 > 0){
                        $(this).parents('.table-container').find('tr.filter').toggle();
                        var kelas = ".table-container";
                    }else{
                        $(this).parents('.datatable').find('tr.filter').toggle();
                        var kelas = ".datatable";
                    }
                }else{
                    $(this).parents('.table-container').find('tr.filter').toggle();
                    var kelas = ".table-container";
                }
                // console.log(cek);
                if($(this).parents(kelas).find('tr.filter').first().is(":visible")){
                    window.reload_css_table();
                }
            });

            $('.btn_reload_table_global').die().live('click', function(e) {
                //cek class datatables
                var cek = $(this).parents('.datatable').find('tr.filter').length;
                if(cek > 0){
                    var cek2 = $(this).parents('.table-container').find('tr.filter').length;
                    if(cek2 > 0){
                        var kelas = ".table-container";
                    }else{
                        var kelas = ".datatable";
                    }
                }else{
                    var kelas = ".table-container";
                }
                window.reload_css_table();
            });

            //fullscreen
            $('.fullscreen').die().live('click', function(e) {
                window.reload_css_table();
            });

            //btn read more
            $('.btn_more').die().live('dblclick',function(){
                $(this).find('.text_short').toggle();
                $(this).find('.text_full').toggle();
            });


            // title_tip 
            $('.title_tip').hover(function(){
                    // Hover over code
                    var title = $(this).attr('title');
                    $(this).data('tipText', title).removeAttr('title');
                    $('<p class="title_css"></p>').text(title).appendTo('body').fadeIn('slow');
            }, function() {
                    // Hover out code
                    $(this).attr('title', $(this).data('tipText'));
                    $('.title_css').remove();
            }).mousemove(function(e) {
                    var mousex = e.pageX + 20; //Get X coordinates
                    var mousey = e.pageY + 10; //Get Y coordinates
                    $('.title_css').css({ top: mousey, left: mousex })
            });
            
    
        </script>
        
    </body>

    <!-- END BODY -->
</html>