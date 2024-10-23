<?php
function Custom_Fields()
{
    add_meta_box(
        'projecten_cf',
        'Projecten Info',           // Title of Custom Fields     
        'CF',                       // Custom fields function
        'projecten',                // Custom Post Type
        'normal',
        'low'
    );
}
function CF() {
    ?>
    <style type="text/css">
        .text {
            font-size: 1.5rem;
            color: black;
        }
        input {
            width: 100%;
        }
        .image-preview {
            margin-top: 10px;
            max-width: 300px; /* Adjust the preview size */
            height: auto;
            display: block;
        }
    </style>

    <?php
    global $wpdb;
    $db = $wpdb->prefix.'portofolio_project_info';
    $project_id = get_the_id();
    $temp_website_link = $wpdb->get_var("SELECT `website_link` FROM $db WHERE `project_ID` = $project_id");
    $temp_github_link = $wpdb->get_var("SELECT `github_link` FROM $db WHERE `project_ID` = $project_id");
    $temp_image_link = $wpdb->get_var("SELECT `image_link` FROM $db WHERE `project_ID` = $project_id");
    ?>

    <p class="text">Website Link</p>
    <input type="text" name="website-link" placeholder="u230838.gluweb.nl/website-hier" value="<?= $temp_website_link ?>">
    <p class="text">Github Link</p>
    <input type="text" name="github-link" placeholder="github.com/repo-hier" value="<?= $temp_github_link ?>">

    <p class="text">Project Image</p>
    <input type="file" name="project-image" id="project-image-input" accept="image/*">

    <div id="image-preview-wrapper">
        <?php if ($temp_image_link): ?>
            <p>Current Image:</p>
            <img id="current-image-preview" class="image-preview" src="<?= $temp_image_link ?>" alt="Project Image">
        <?php else: ?>
            <img id="current-image-preview" class="image-preview" style="display: none;" alt="No Image">
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('project-image-input').addEventListener('change', function(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('current-image-preview');
                output.src = reader.result;
                output.style.display = 'block'; // Show the image preview when an image is uploaded
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
    <?php
}



function save_custom_fields($post_id) {
    // Check for the website and github links
    $website_link = isset($_POST['website-link']) ? $_POST['website-link'] : '';
    $github_link = isset($_POST['github-link']) ? $_POST['github-link'] : '';

    // Get project ID and title
    $project_id = get_the_id();
    $project_title = get_the_title();

    global $wpdb;
    $db = $wpdb->prefix . 'portofolio_project_info';

    // Image Upload Handling
    if (!empty($_FILES['project-image']['name'])) {
        $uploaded_file = $_FILES['project-image'];

        // Use the WordPress upload function to handle file uploads
        $upload = wp_handle_upload($uploaded_file, array('test_form' => false));

        if (!isset($upload['error']) && isset($upload['url'])) {
            $image_link = $upload['url']; // Get the uploaded image URL

            // Store or update the image URL in the database
            $temp_image_link = $wpdb->get_var("SELECT `image_link` FROM $db WHERE `project_ID` = $project_id");

            if ($temp_image_link == null) {
                $wpdb->insert(
                    $db,
                    [
                        'project_ID' => $project_id,
                        'project_title' => $project_title,
                        'website_link' => $website_link,
                        'github_link' => $github_link,
                        'image_link' => $image_link // Save image link
                    ]
                );
            } else {
                $wpdb->update(
                    $db,
                    [
                        'project_title' => $project_title,
                        'website_link' => $website_link,
                        'github_link' => $github_link,
                        'image_link' => $image_link // Update image link
                    ],
                    ['project_ID' => $project_id]
                );
            }
        }
    } else {
        // Update existing records if no new image was uploaded
        $wpdb->update(
            $db,
            [
                'project_title' => $project_title,
                'website_link' => $website_link,
                'github_link' => $github_link
            ],
            ['project_ID' => $project_id]
        );
    }
}

add_action('save_post', 'save_custom_fields');
