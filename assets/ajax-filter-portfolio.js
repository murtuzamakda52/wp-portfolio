jQuery(document).ready(function($) {
    $('.portfolio-filter').click( function(event) {
        // Prevent default action - opening tag page
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }
 
        // Get tag slug from title attirbute
        var selecetd_taxonomy = $(this).attr('title');
        var texonomy_name = $(this).text();
 
        // After user click on tag, fade out list of posts
        $('.tagged-posts').fadeOut();
        data = {
            action: 'filter_portfolio', // function to execute
            portfolio_nonce: portfolio_vars.portfolio_nonce, // wp_nonce
            taxonomy: selecetd_taxonomy, // selected tag
            };
        $.post( portfolio_vars.portfolio_ajax_url, data, function(response) {
 
            if( response ) {
                // Display posts on page
                $('.tagged-portfolio').html( response );
                // Restore div visibility
                $('.tagged-portfolio').fadeIn('slow');
                $('.texonomy-name > h2').text(texonomy_name);
                
            };
        });
    });

    jQuery('.single-post').hover(function(){

    });
});