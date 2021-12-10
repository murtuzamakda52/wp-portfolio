<?php
get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
            'post_type' => 'portfolio',
            'posts_per_page' => 10,
            'order' =>'ASC',
            'paged' => $paged,
        );

        $query = new WP_Query( $args );

        $tax = 'category';
        $terms = get_terms( $tax );
        $count = count( $terms );

        if ( $count > 0 ): ?>
            <div class="post-tags">
            <a href="" class="portfolio-filter" title="">All</a>
            <?php
            foreach ( $terms as $term ) {
                $term_link = get_term_link( $term, $tax );
                echo '<a href="' . $term_link . '" class="portfolio-filter" title="' . $term->slug . '">' . $term->name . '</a> ';
            } ?>
            </div>
            <center class="texonomy-name"><h2>All</h2></center>
        <div>
        <div style="width:200px; margin:auto;">
        </div>
        <?php endif;
        if ( $query->have_posts() ): ?>
        <div class="tagged-portfolio">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div class="single-portfolio" data-title="<?php the_title(); ?>">
            <h2><a href="<?php the_permalink(); ?>"><?php  

            if(has_post_thumbnail()){
            the_post_thumbnail(array(400, 300)); ?></a></h2>
            <?php
            }
            else{
            echo '<img src="'.plugins_url('/assets/placeholderimage.jpg',__FILE__).'" width="400" height="300" style="height:167px !important">';
            }
            ?>
            </div>

            <?php endwhile; ?>
        </div>

        <?php else: ?>
            <div class="tagged-posts">
                <h2>No portfolio found</h2>
            </div>


        <div style="width:200px; margin:auto">
        </div>
    </div>
        <?php endif; 

    $total_pages = $query->max_num_pages;
    if ($total_pages > 1){

        $current_page = max(1, get_query_var('paged'));
        ?>
        <div class="pagination">
        <?php
        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '/page/%#%',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_text'    => __('« prev'),
            'next_text'    => __('next »'),
        ));
        ?>
    </div>
    <?php
    }    
get_footer();
?>