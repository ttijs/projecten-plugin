<?php
function cpt_projecten() {
	$labels = array(
		'name'                  => _x( 'Projecten', 'Post type general name', 'projecten' ),
		'singular_name'         => _x( 'Project', 'Post type singular name', 'projecten' ),
		);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'project' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
	);

	register_post_type( 'projecten', $args );
}