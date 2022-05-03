<?php

get_header(); ?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>)"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title">Past Events</h1>
      <div class="page-banner__intro">
      <p>A recap of our past events.</p>
    </div>
  </div>
</div>

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
    ?>
    <div class="event-summary">
    <a class="event-summary__date t-center" href="#">
        <span class="event-summary__month"><?php echo $eventDate->format('M') ?></span>
        <span class="event-summary__day"><?php echo $eventDate->format('d') ?></span>
    </a>
    <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
        <p><?php echo wp_trim_words(get_the_content(), 18); ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
    </div>
    </div>
<?php  }
// Paginate links works with the default query which is why even though you add a posts_per_page to your custom
// query it does not paginate since the default query is for a page(past events) which retrieves just a single page.
// This is why we pass a total key which says the total number of events.
echo paginate_links(array(
    'total' => $pasteventspageQuery->max_num_pages
));
?>
</div>

<?php get_footer(); ?>