<?php
/*
Plugin Name: Projecten Plugin
Plugin URI: http://voorbeeld.com/
Description: Dit is mijn eerste zelfgemaakte WordPress-plugin voor projecten inladen.
Version: 1.0
Author: Jouw Naam
Author URI: http://voorbeeld.com/
License: GPLv2 or later
*/

// Security
if ( ! defined('ABSPATH')){
    echo 'Nothing I can do when Im called directly!';
    die;
}


// Custom Post Type: Projecten
include('functions/CPT.php');
add_action( 'init', 'cpt_projecten' );


// Custom Fields
include('functions/CF.php');
add_action('admin_init', 'Custom_Fields');


// Create a Database
function database_creation(){
    global $wpdb;
    $project_info = $wpdb->prefix.'portofolio_project_info';
    $charset = $wpdb->get_charset_collate;

    $prjct_info = "CREATE TABLE ".$project_info."(
        project_ID             int(11)     NOT NULL,
        project_title          varchar(255),
        website_link           varchar(255),
        github_link            varchar(255),
        image_link             varchar(255),
        PRIMARY KEY            (project_ID)
    ) $charset;";

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');

    dbDelta($prjct_info);
}

register_activation_hook(__FILE__, 'database_creation');