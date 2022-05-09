<?php

function university_post_types() {
    register_post_type('event', array(
      // Creates a custom capability type for event so that we can add custom permissions for this post type. By default for all posts we have the capability type to be post.
      'capability_type' => 'event',
      // map the custom capabilities to our own roles
      'map_meta_cap' => true,
      // If you use the supports key then make sure to pass the editor keyword else it will fallback to the classic editor
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        // We need custom fields if we give our users the control to add custom fields, not needed here.
        // 'custom-fields'
      ),
      'rewrite' => array(
        'slug' => 'events'
      ),
      'has_archive' => true,
      'public' => true,
       // This enables rest api access to this custom post via the wordpress rest api and prevents showing no route found.
      'show_in_rest' => true,
      'labels' => array(
        'name' => 'Events',
        'add_new_item' => 'Add New Event',
        'edit_item' => 'Edit Event',
        'all_items' => 'All Events',
        'singular_name' => 'Event'
      ),
      'menu_icon' => 'dashicons-calendar'
    ));

    register_post_type('program', array(
      // If you use the supports key then make sure to pass the editor keyword else it will fallback to the classic editor
      'supports' => array(
        'title',
        // Remove Editor here so that we can use our custom field for the body content so that our search does not search the content for programs.
        // 'editor'
        // We need custom fields if we give our users the control to add custom fields, not needed here.
        // 'custom-fields'
      ),
      'rewrite' => array(
        'slug' => 'programs'
      ),
      'has_archive' => true,
      'public' => true,
      'show_in_rest' => true,
      'labels' => array(
        'name' => 'Programs',
        'add_new_item' => 'Add New Program',
        'edit_item' => 'Edit Program',
        'all_items' => 'All Programs',
        'singular_name' => 'Program'
      ),
      'menu_icon' => 'dashicons-awards'
    ));

    register_post_type('professor', array(
      // If you use the supports key then make sure to pass the editor keyword else it will fallback to the classic editor
      'supports' => array(
        'title',
        'editor',
        'thumbnail'
        // We need custom fields if we give our users the control to add custom fields, not needed here.
        // 'custom-fields'
      ),
      'public' => true,
      'show_in_rest' => true,
      'labels' => array(
        'name' => 'Professors',
        'add_new_item' => 'Add New Professor',
        'edit_item' => 'Edit Professor',
        'all_items' => 'All Professors',
        'singular_name' => 'Professor'
      ),
      'menu_icon' => 'dashicons-welcome-learn-more'
    ));

    register_post_type('campus', array(
      'capability_type' => 'campus',
      'map_meta_cap' => true,
      // If you use the supports key then make sure to pass the editor keyword else it will fallback to the classic editor
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        // We need custom fields if we give our users the control to add custom fields, not needed here.
        // 'custom-fields'
      ),
      'rewrite' => array(
        'slug' => 'campuses'
      ),
      'has_archive' => true,
      'public' => true,
      'show_in_rest' => true,
      'labels' => array(
        'name' => 'Campuses',
        'add_new_item' => 'Add New Campus',
        'edit_item' => 'Edit Campus',
        'all_items' => 'All Campuses',
        'singular_name' => 'Campus'
      ),
      'menu_icon' => 'dashicons-location-alt'
    ));

    //Note post type
    register_post_type('note', array(
      'capability_type' => 'note',
      'map_meta_cap' => true,
      // If you use the supports key then make sure to pass the editor keyword else it will fallback to the classic editor
      'supports' => array(
        'title',
        'editor'
        // We need custom fields if we give our users the control to add custom fields, not needed here.
        // 'custom-fields'
      ),
      // Setting public to false will ensure that nobody can view the post type but this also hides it from the admin dashboard
      'public' => false,
      // Since we set public to false this hides the post from the admin dashboard, hence we need to use this to ensure that it is visible on the admin dashboard.
      'show_ui' => true,
      'show_in_rest' => true,
      'labels' => array(
        'name' => 'Notes',
        'add_new_item' => 'Add New Note',
        'edit_item' => 'Edit Note',
        'all_items' => 'All Notes',
        'singular_name' => 'Note'
      ),
      'menu_icon' => 'dashicons-welcome-write-blog'
    ));
}
  
add_action('init', 'university_post_types');
?>