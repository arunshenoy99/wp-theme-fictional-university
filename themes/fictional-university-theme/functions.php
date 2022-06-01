<?php

function universityQueryVars($vars) {
  $vars[] = 'skyColor';
  $vars[] = 'grassColor';
  return $vars;
}
add_filter('query_vars', 'universityQueryVars');

// Has the search REST endpoint register and the logic
require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');

function university_custom_rest() {
  // Adds custom fields to the wordpress rest route for retrieving posts.
  register_rest_field('post', 'authorName', array(
    'get_callback' => function () {
      return get_the_author();
    }
  ));

  register_rest_field('note', 'userNoteCount', array(
    'get_callback' => function () {
      return count_user_posts(get_current_user_id(), 'note');
    }
  ));
}

// Customize the wordpress rest api to include custom fields.
add_action('rest_api_init', 'university_custom_rest');

// make args optional by making it equal to NULL
function pageBanner($args = NULL) {
  if (!$args['title']) {
    $args['title'] = get_the_title();
  }
  if (!$args['subtitle']) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }
  if (!$args['photo']) {
    if (get_field('page_banner_background_image') and !is_archive() and !is_home()) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
    
  }
  ?>
  <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
        <div class="page-banner__intro">
          <p><?php 
            echo $args['subtitle'];
          ?></p>
        </div>
      </div>
    </div>
<?php }

function university_files() {
  wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), 'One.0', true);
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

  // This function takes the associative array given as the 3rd argument and adds this to a javascript variable
  // Given as the second argument and enclose this assignment in a script tag.
  // This variable can then be used by our javascript code.
  wp_localize_script('main-university-js', 'universityData', array(
    'root_url'=> get_site_url(),
    // This nonce is sent with destructive requests from the front end
    'nonce' => wp_create_nonce('wp_rest'),
  ));
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
  // register_nav_menu('headerMenuLocation', 'Header Menu Location');
  // register_nav_menu('footerMenuLocationOne', 'Footer Menu Location One');
  // register_nav_menu('footerMenuLocationTwo', 'Footer Menu Location Two');
  // Adds appropriate title tag to the head section of a page
  add_theme_support('title-tag');
  // Enable featured images on default post type, for custom post types need to do this plus add in supports.
  add_theme_support('post-thumbnails');
  // If we don;t specify this then whenever we add images wordpress by default creates multiple copies of the image in the uploads folder with different dimensions leading to a waste of space.
  // We only want the image sizes that we are planning to use.
  // To retroactively delete the already generated images for your posts use the plugin called Regenerate Thumbnails
  add_image_size('professorLandscape', 400, 260, true);
  add_image_size('professorPortrait', 480, 650, true);
  add_image_size('pageBanner', 1500, 350, true);

}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {

  if (!is_admin() and is_post_type_archive('program') and $query->is_main_query()) {
    $query->set('posts_per_page', -1);
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
  }

  if (!is_admin() and is_post_type_archive('campus') and $query->is_main_query()) {
    $query->set('posts_per_page', -1);
  }

  // If we are almost satisfied with the default query being used whenever an archive loads then we can
  // make subtle changes to the default query instead of writing a default query by making use of the hook below.
  if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'event_date_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
      array(
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type'  => 'numeric'
      )
    ));
  }
}

add_action('pre_get_posts', 'university_adjust_queries');

// Redirect subscriber accounts out of wp-admin onto homepage
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend() {
  $ourCurrentUser = wp_get_current_user();
  
  if (count($ourCurrentUser->roles) == 1 and $ourCurrentUser->roles[0] == 'subscriber') {
    wp_redirect(esc_url(site_url('/')));
    exit;
  }
}

//prevent showing the wordpress admin navbar to our subscribers
add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
  $ourCurrentUser = wp_get_current_user();
  
  if (count($ourCurrentUser->roles) == 1 and $ourCurrentUser->roles[0] == 'subscriber') {
    show_admin_bar(false);
  }
}

// Customize login screen
// Customize the url being loaded when the text/logo on the login page is clicked
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
  return esc_url(site_url('/'));
}

// Customize the entire login page by loading your own css like this.
add_action('login_enqueue_scripts', 'ourLoginCss');

function ourLoginCss() {
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

//Adds custom title to the login page
add_filter('login_headertitle', 'ourLoginTitle');


function ourLoginTitle() {
  return get_bloginfo('name');
}

// Force note posts to be private by modifying the note data to have the status as private before saving to the db.
// Filters help us filter content by using their hooks.
// Here 10 gives the priority of the function callback, useful when there are multiple functions for the same hook
//  and 2 says that we will get 2 arguments to the callback function, 
// the second one being the post array having extra info like ID about the post.
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr) {
  if ($data['post_type'] == 'note' ) {
    // We want to ensure we add this check only when we are creating a new note
    // We do not want to die if the post is being updated or edited.
    // Posts that are being created do not have the ID for the first time.
    if (count_user_posts(get_current_user_id(), 'note') > 4 and ! $postarr['ID']) {
      die('You have reached your note limit.');
    }
    // Ensure we do not store unecessary html in our database 
    $data['post_content'] = sanitize_textarea_field($data['post_content']);
    $data['post_title'] = sanitize_text_field($data['post_title']);
  }
  if ($data['post_type'] == 'note' and $data['post_status'] != 'trash') {
    $data['post_status'] = 'private';
  }
  return $data;
}
