<!-- blueimp Gallery styles -->
<link rel="stylesheet" href="<?=base_url();?>/public/assets/global/plugins/fileupload/css/blueimp-gallery.min.css">
<link rel="stylesheet" href="<?=base_url();?>/public/assets/global/plugins/fileupload/css/style.css">
<link rel="stylesheet" href="<?=base_url();?>/public/assets/global/plugins/fileupload/css/jquery.fileupload.css">
<link rel="stylesheet" href="<?=base_url();?>/public/assets/global/plugins/fileupload/css/jquery.fileupload-ui.css">
<noscript><link rel="stylesheet" href="<?=base_url();?>/public/assets/global/plugins/fileupload/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="<?=base_url();?>/public/assets/global/plugins/fileupload/css/jquery.fileupload-ui-noscript.css"></noscript>


<!-- The file upload form used as target for the file upload widget -->
<form id="fileupload" action="<?=site_url($url);?>/upload_file_ic" method="POST" enctype="multipart/form-data">

    <!-- HIDDEN FILE -->
	<input type="hidden" name="id" id="id_ic" value="<?=@$id;?>"/>

    <div class="row fileupload-buttonbar">
        <div class="col-lg-12">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <div style="text-align:center;">
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="userfile" multiple>
                </span>
                <div class="">
                    Max Size Upload : <b>25 MB</b> , File Type: <b>.txt .pdf .xlsx .docx .msg .jpg .png .rar</b> 
                </div>
            </div>
            
           <!--  <button type="submit" class="btn btn-primary start">
                <i class="glyphicon glyphicon-upload"></i>
                <span>Start upload</span>
            </button>
            <button type="reset" class="btn btn-warning cancel">
                <i class="glyphicon glyphicon-ban-circle"></i>
                <span>Cancel upload</span>
            </button>
            <button type="button" class="btn btn-danger delete">
                <i class="glyphicon glyphicon-trash"></i>
                <span>Delete</span>
            </button>
            <input type="checkbox" class="toggle"> -->
            <!-- The global file processing state -->

            <span class="fileupload-process"></span>
        </div>

        <!-- The global progress state -->
        <div class="col-lg-5 fileupload-progress fade" style="height:20px;">
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
            <div class="progress-extended">&nbsp;</div>
        </div>

    </div>
    <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
</form>

<!-- Note -->
<div class="table-container">
    <table class="table table-striped table-bordered table-hover">            
        <thead>
            <tr role="row" class="heading">
                <th style="text-align:center">No</th>
                <th style="text-align:center">Name File</th>
                <th style="text-align:center">Action</th>
            </tr>
        </thead>
        <tbody class="list_file_upload_ic" idnya="<?=@$id?>">
            <?=@$html_list_file_upload_ic;?>
        </tbody>
    </table>
</div>


<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Upload</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>

<!-- The template to display files available for download -->

<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <!-- <div><span class="label label-danger">Error</span> {%=file.error%}</div> -->
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>


<!-- <script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/jquery.min.js"></script>-->
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<!--<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/bootstrap.min.js"></script>-->
<!-- blueimp Gallery script -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/jquery.blueimp-gallery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/jquery.iframe-transport.js"></script>

<!-- The basic File Upload plugin -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<!-- <script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/jquery.fileupload-audio.js"></script> -->
<!-- The File Upload video preview plugin -->
<!-- <script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/jquery.fileupload-video.js"></script> -->
<!-- The File Upload validation plugin -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->

<script src="<?=base_url();?>/public/assets/global/plugins/fileupload/js/main.js"></script> 

<script type="text/javascript">

    //jangan dihapus function finish upload
    window.finish_upload = function(){
        window.list_file_upload_ic();
        window.list_file_ic();
    }

    window.list_file_upload_ic = function(){
        var id          = "<?=@$id;?>";
        var param       = {id:id};
        var url         = "<?=site_url($url);?>/list_file_upload_ic";
        Metronic.blockUI({ target: '.list_file_upload_ic',  boxed: true});
        $.post(url, param, function(msg){
            $('.list_file_upload_ic').html(msg);
            Metronic.unblockUI('.list_file_upload_ic');
        });
    }
		
</script>