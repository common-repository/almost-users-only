<?php

// Make sure uninstall was called from WP
if(!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

// Remove options from the database
delete_option('mp_uo_options');
    
?>
