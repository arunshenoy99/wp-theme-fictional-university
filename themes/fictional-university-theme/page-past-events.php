<?php

get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'A recap of our past events.'
));
?>

<div class="container container--narrow page-section">
  <?php
  $today = date('Ymd');
  $pasteventspageQuery = new WP_Query(array(
      // We pass paged here so that when we goto /past-events/page/2 then it retrieves the correct result.(Pagination for custom queries)
      'paged' => get_query_var('paged', 1), // Pass a default value which is used for our first page which will be /past-events
      'posts_per_page' => 1,
      'post_type' => 'event',
      'meta_key'  => 'event_date',
      'orderby'   => 'meta_value_num',
      'order'     => 'ASC',
      'meta_query' => array(
          array(
              'key'     => 'event_date',
              'compare' => '<',
              'value'   => $today,
              'type'    => 'numeric'
          )
      )
  ));

  while($pasteventspageQuery->have_posts()) {
    $pasteventspageQuery->the_post(); 
    $eventDate = new DateTime(get_field('event_date'));
    get_template_part('template-parts/content', 'event');
}
// Paginate links works with the default query which is why even though you add a posts_per_page to your custom
// query it does not paginate since the default query is for a page(past events) which retrieves just a single page.
// This is why we pass a total key which says the total number of events.
echo paginate_links(array(
    'total' => $pasteventspageQuery->max_num_pages
));
?>
</div>

<?php get_footer(); ?>