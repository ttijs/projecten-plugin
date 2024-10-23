<?php
function Custom_Fields(){
    add_meta_box(
        'projecten_cf',
        'Projecten Info',           // Title of Custom Fields     
        'CF',                       // Custom fields function
        'projecten',                // Custom Post Type
        'normal',
        'low'
    );
}
function CF(){
    ?>
        <style type="text/css">
            .text {
                font-size: 1.5rem;
                color: black;
                /* text-align: center; */
            }
            input {
                width: 100%;
            }
        </style>

        <?php
            global $wpdb;
            $db = $wpdb->prefix.'portofolio_project_info';
            $project_id = get_the_id();
            $temp_website_link = $wpdb->get_var("SELECT `website_link` FROM $db WHERE `project_ID` = $project_id");
            $temp_github_link = $wpdb->get_var("SELECT `github_link` FROM $db WHERE `project_ID` = $project_id");
        ?>

        <p class="text">Website Link</p>
        <input type="text" name="website-link" placeholder="u230838.gluweb.nl/website-hier" value="<?= $temp_website_link ?>">
        <p class="text">Github Link</p>
        <input type="text" name="github-link" placeholder="github.com/repo-hier" value="<?= $temp_github_link ?>">
        <!-- add custom input for an image -->
    <?php
}

function save_custom_fields($post_id){
    $website_link = $_POST['website-link'];
    $github_link = $_POST['github-link'];

    $project_id = get_the_id();
    $project_title = get_the_title();

    global $wpdb;
    $db = $wpdb->prefix.'portofolio_project_info';


    // $temp_website_link = $wpdb->get_var("SELECT `website_link` FROM $db WHERE `project_ID` = $project_id");
    // $temp_github_link = $wpdb->get_var("SELECT `github_link` FROM $db WHERE `project_ID` = $project_id");

    if ($website_link == null || $github_link == null){
        echo 'asdf';
        exit;

        $wpdb->insert(
            $wpdb->prefix.'portofolio_project_info',
            [
                'project_ID' => $project_id,
                'project_title' => $project_title,
                'website_link' => $website_link,
                'github_link' => $github_link
                // add img to wp_postmeta
                // add path to said location to get it
            ]
        );
    }
    else {
        $wpdb->update(
            $wpdb->prefix.'portofolio_project_info',
            [
                'project_title' => $project_title,
                'website_link' => $website_link,
                'github_link' => $github_link
            ],
            [
                'project_ID' => $project_id
            ]
        );
        
    }
    
    
}
add_action('save_post', 'save_custom_fields');