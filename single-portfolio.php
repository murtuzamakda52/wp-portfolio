<?php
// single-news.php
get_header();
/* Start the Loop */
while (have_posts()) : the_post();
   ?>
<div class="qa-title"><?php the_title(); ?></div>
<div class="portfolio-container">
<div class="feature-image">
<?php the_post_thumbnail('full'); ?>
</div>

<div class="portfolio-content">
         <?php the_content(); ?>
</div>
</div>
<?php
endwhile; // End of the loop.
?><div class="blog-content">
<h2>Comments</h2>
<?php
if( is_single() ) : 

foreach (get_comments() as $comment): ?>
    <?php endforeach; ?>
<?php comments_template();      

endif;?>
</div><?php
get_footer();