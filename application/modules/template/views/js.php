<?php
    foreach ((array)@$custom as $key => $value) {
        echo '<script src="'.base_url('public/assets/app/js/'.$value.'.js').'" type="text/javascript"></script>';
    }
?>