<?php

function xcopy($source, $dest, $permissions = 0755)
{

    $from_folder    = APPPATH."/third_party/master_crud/page_table_master";
    $to_folder      = APPPATH."/modules/master";
    $this->xcopy($from_folder, $to_folder);
    exit;


    $sourceHash = $this->hashDirectory($source);
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        if($sourceHash != $this->hashDirectory($source."/".$entry)){
            $this->xcopy("$source/$entry", "$dest/$entry", $permissions);
        }
    }

    // Clean up
    $dir->close();
    return true;
}

function hashDirectory($directory){
    if (! is_dir($directory)){ return false; }

    $files = array();
    $dir = dir($directory);

    while (false !== ($file = $dir->read())){
        if ($file != '.' and $file != '..') {
            if (is_dir($directory . '/' . $file)) { $files[] = $this->hashDirectory($directory . '/' . $file); }
            else { $files[] = md5_file($directory . '/' . $file); }
        }
    }

    $dir->close();

    return md5(implode('', $files));
}