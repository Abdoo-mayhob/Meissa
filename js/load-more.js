/*
 *
 * Load More Related Codes.
 *
 */ 

jQuery(function($){
// Org: (function($){...})(jQuery)
// New: jQuery(function($){...})

archive_posts_container = $('.archive-posts');
loadmore_button = $("#load-more-button");
let loadmore_page = 1;

// Infinate Scroll TODO: CleanUp
let intersec_obs_options = {
    root: null,
    rootMargin: '0px',
    threshold: 0.5,
};
const observer = new IntersectionObserver(
    (entries) => intersec_obs_callback(entries),
    intersec_obs_options
);
observer.observe(loadmore_button[0]);

function intersec_obs_callback(entries){
    entries.forEach((entry) => {
        if(entry.isIntersecting){
            load_more_posts();
        }
    });
}

loadmore_button.click(load_more_posts);

function load_more_posts(e){
    loadmore_page++;
    var data = {
        action: 'meissa_load_more_posts_archive',
        page: loadmore_page,
        template_to_use: archive_posts_container.data("template"),
        org_wp_query_vars: meissa_globals.current_wp_query_vars,
    };

    button = loadmore_button; 
    button.css('pointer-events', 'none')
    button.css('opacity', '0.5');

    $.post(
        meissa_globals.ajaxurl, 
        data, 
        function(response) {
            if(response == '0'){
                button.hide();
                return;
            }
            archive_posts_container.append(response);
            button.css('pointer-events', 'initial');
            button.css('opacity', '1');

            // Trigger a custom event
            $(document).trigger('loaded_more_posts');
        }
    );

}

}); // JQuery