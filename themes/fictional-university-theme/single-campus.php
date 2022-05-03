<?php
  
  get_header();

  while(have_posts()) {
    the_post();
    pageBanner();
    ?>

  <div class="container container--narrow page-section">
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to Campus Home</a> <span class="metabox__main"><?php the_title(); ?></span>
        </p>
      </div>
    <div class="generic-content">
      <?php the_content(); ?>
    </div>
    <?php

      $relatedPrograms = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type'      => 'program',
        // We want the events with earlier event dates to show first, these are numbers hence _num.
        'orderby' => 'title',
        'order' => 'ASC',
        //Do not show events that are in the past wrt to the present date.
        'meta_query' => array(
          array (
            'key' => 'related_campus',
            'compare' => 'LIKE',
            // We search for "id" here because in the DB the array of related programs is serialized into a single string
            // For ex array(12, 13, 1200) is serialized to "{"i" : "12", "i" : "1200"}" hence we need to compare with a double quoted value. 
            'value' => '"' . get_the_ID() . '"',
          )
        )
      ));
      if ($relatedPrograms->have_posts()) {
      echo '<hr class="section-break" />';
      echo '<h2 class="headline headline--medium">Programs available at this campus.</h2>';
      echo '<ul class="min-list link-list">';

      while($relatedPrograms->have_posts()) {
      $relatedPrograms->the_post(); ?>
      <li>
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
      <?php }
      echo '</ul>';
      }
      // We always need to reset data before running another custom query that depends on global functions. the_post we call on the custom query
      // object hijacks the global functions like the_ID and the_title(). Notice that our next custom query
      // depends on get_the_ID() which gets overridden by the professors object id instead of the page id.
      wp_reset_postdata();
      ?>
  </div>
    
  <?php }

  get_footer();

?>