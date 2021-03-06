<?php
  
  get_header();

  while(have_posts()) {
    the_post();
    pageBanner();
    ?>

  <div class="container container--narrow page-section">
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to Programs Home</a> <span class="metabox__main"><?php the_title(); ?></span>
        </p>
      </div>
    <div class="generic-content">
      <?php the_field('main_body_content'); ?>
    </div>
    <?php

      $relatedProfessors = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type'      => 'professor',
        // We want the events with earlier event dates to show first, these are numbers hence _num.
        'orderby' => 'title',
        'order' => 'ASC',
        //Do not show events that are in the past wrt to the present date.
        'meta_query' => array(
          array (
            'key' => 'related_programs',
            'compare' => 'LIKE',
            // We search for "id" here because in the DB the array of related programs is serialized into a single string
            // For ex array(12, 13, 1200) is serialized to "{"i" : "12", "i" : "1200"}" hence we need to compare with a double quoted value. 
            'value' => '"' . get_the_ID() . '"',
          )
        )
      ));
      if ($relatedProfessors->have_posts()) {
      echo '<hr class="section-break" />';
      echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';
      echo '<ul class="professor-cards">';

      while($relatedProfessors->have_posts()) {
      $relatedProfessors->the_post(); ?>
      <li class="professor-card__list-item">
        <a class="professor-card" href="<?php the_permalink(); ?>">
          <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="">
          <span class="professor-card__name"><?php the_title(); ?></span>
        </a>
    </li>
      <?php }
      echo '</ul>';
      }
      // We always need to reset data before running another custom query that depends on global functions. the_post we call on the custom query
      // object hijacks the global functions like the_ID and the_title(). Notice that our next custom query
      // depends on get_the_ID() which gets overridden by the professors object id instead of the page id.
      wp_reset_postdata();


          // Same format as our custom field.
          $today = date('Ymd');
          $homepageEvents = new WP_Query(array(
            'posts_per_page' => 2,
            'post_type'      => 'event',
            // Order by a custom field
            'meta_key' => 'event_date',
            // We want the events with earlier event dates to show first, these are numbers hence _num.
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            //Do not show events that are in the past wrt to the present date.
            'meta_query' => array(
              array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type'  => 'numeric'
              ),
              array (
                'key' => 'related_programs',
                'compare' => 'LIKE',
                // We search for "id" here because in the DB the array of related programs is serialized into a single string
                // For ex array(12, 13, 1200) is serialized to "{"i" : "12", "i" : "1200"}" hence we need to compare with a double quoted value. 
                'value' => '"' . get_the_ID() . '"',
              )
            )
          ));
          if ($homepageEvents->have_posts()) {
            echo '<hr class="section-break" />';
          echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

        while($homepageEvents->have_posts()) {
          $homepageEvents->the_post();
          get_template_part('template-parts/content', 'event');
        } 
      }

      wp_reset_postdata();
      $relatedCampuses = get_field('related_campus');

      if($relatedCampuses) {
        echo '<hr class="section-break" />';
        echo '<h2 class="headline headline--medium">' . get_the_title() . ' is available at these campuses</h2>';
        echo '<ul class="min-list link-list">';
        foreach($relatedCampuses as $relatedCampus) {
          ?> <li><a href="<?php echo get_the_permalink($relatedCampus); ?>"><?php echo get_the_title($relatedCampus); ?></a></li> <?php
        }
        echo '</ul>';
      }

      ?>
  </div>
    
  <?php }

  get_footer();

?>