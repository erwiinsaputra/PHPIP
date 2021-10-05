<!-- <?php if(!empty($breadcrumb)){ ?>
	<div class="btn-group btn-breadcrumb">
	    <a href="<?php echo site_url(); ?>" class="btn btn-default ajaxify"><i class="fa fa-home"></i></a>
	    <?php foreach($breadcrumb as $key => $row): ?>
	    <a href="<?php echo ($row === TRUE ? 'javascript:;' : site_url($row)) ?>" class="<?php echo ($row !== TRUE ? 'ajaxify' : ''); ?> btn btn-default"><?php echo $key; ?></a>    
	    <?php endforeach; ?>
	</div>
<?php } ?> -->