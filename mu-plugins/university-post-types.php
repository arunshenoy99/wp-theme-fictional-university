<?php

function university_post_types() {
    register_post_type('event', array(
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
}
  
add_action('init', 'university_post_types');
?>