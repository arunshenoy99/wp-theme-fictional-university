<?php
  
  get_header();

  while(have_posts()) {
    the_post(); ?>
    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php the_title(); ?></h1>
        <div class="page-banner__intro">
          <p>Don't forget to replace me later.</p>
        </div>
      </div>
    </div>

  <div class="container container--narrow page-section">
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to Programs Home</a> <span class="metabox__main"><?php the_title(); ?></span>
        </p>
      </div>
    <div class="generic-content">
      <?php the_content(); ?>
    </div>
    <?php
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
          $homepageEvents->the_post(); ?>
          <div class="event-summary">
          <a class="event-summary__date t-center" href="#">
            <!-- get_field is given by the advanced cutom fields plugin to retrieve the custom fields -->
            <span class="event-summary__month"><?php
              $eventDate = new DateTime(get_field('event_date'));
              echo $eventDate->format('M');
            ?></span>
            <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>
          </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php if (has_excerpt()) {
              // Prevent line breaks created by the_excerpt() by using a manual echo.
              echo get_the_excerpt();
            } else {
              echo wp_trim_words(get_the_content(), 18);
            } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
          </div>
        </div>
        <?php } 
          }
        
        ?>
  </div>
    
  <?php }

  get_footer();

?>