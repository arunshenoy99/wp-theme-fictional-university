<?php

get_header(); 
pageBanner(array(
  'title' => 'All Events',
  'subtitle' => 'See what is going on in our world.'
));
?>

<div class="container container--narrow page-section">
  <?php

  while(have_posts()) {
    the_post(); 
    $eventDate = new DateTime(get_field('event_date'));
    get_template_part('template-parts/content', 'event');
}
echo paginate_links();
?>

<hr class="section-break" />

<p>Looking for a recap our past events? <a href="<?php echo site_url('/past-events'); ?>">Check out our past events page.</a></p>
</div>

<?php get_footer(); ?>