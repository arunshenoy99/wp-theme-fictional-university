<?php

function university_like_routes() {
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));

    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}
add_action('rest_api_init', 'university_like_routes');

function createLike($data) {
    if (is_user_logged_in()) {
        $professor = sanitize_text_field($data['professorId']);

        $existQuery = new WP_Query(array(
            'post_type' => 'like',
            'author' => get_current_user_id(),
            'meta_query' => array(
              array(
                'key' => 'liked_professor_id',
                'compare' => '=',
                'value' => $professor
              )
            )
        ));

        // If the professor has already been liked by the current user then don't allow to like again.
        // Check that the id that was passed from front end is actually a valid professor post.
        if ($existQuery->found_posts == 0 and get_post_type($professor) == 'professor') {
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => 'Our PHP Create Post Test',
                'meta_input' => array(
                    'liked_professor_id' => $professor
                )
            ));
        } else {
            die('Invalid professor ID');
        }
    } else {
        die('Only logged in users can create a like');
    }
    
}

function deleteLike($data) {
    $likeId = sanitize_text_field($data['likeId']);
    if (get_current_user_id() == get_post_field('post_author', $likeId) and get_post_type($likeId) == 'like') {
        wp_delete_post($likeId, true);
        return 'Congrats, like deleted';
    } else {
        die('You do not have permission to delete likes.');
    }
}
?>