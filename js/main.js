// Org: (function($){...})(jQuery)
// New: jQuery(function($){...})

jQuery(function($){

// Footer Newsletter Form 
$("form.newsletter button").click(function(e){
    e.preventDefault();
    email_dom_ele = $('form.newsletter input[type="email"]')[0]; // JS Object
    if (email_dom_ele.checkValidity()){

        var data = {
            action: 'meissa_newsletter_subs_add_sub',
            email: email_dom_ele.value
        };

        button = $(this);   
        button.css('pointer-events', 'none')
        button.css('opacity', '0.5');

        $.post(
            meissa_globals.ajaxurl, 
            data, 
            function(response) {
                response = JSON.parse(response);
                console.log(response);
                if (response['status'] == 200) {
                    console.log("DONE !");
                } 
                else {
                    console.log("Error !");
                }

                button.css('pointer-events', 'initial')
                button.css('opacity', '1');
            }
        );

    }
    email_dom_ele.reportValidity(); // Shows the Tooltip
});


/* === Archive Only Scripts === */

// Limit excerpt to 3 lines (done with css) but remove some text from the end and add a "Read More" button
// Could've useed dotdotdot.js but I prefered to code it manually
$('.advanced-line-clamp').each(function() {
    let loop_url = $(this).data.loopUrl;
    // If text is truncated (with a line-clamp?)
    if (this.scrollHeight > this.clientHeight) {
        let text = $(this).text();

        let link = $('<span class="read-more-button"> اقرأ المزيد</span>');
        $(this).append(link);

        // Cut a char at a time from text, until it fits (taking into account 
        // also the size of the "read more" link)
        while(this.scrollHeight > this.clientHeight) {
            text = text.slice(0, -1);
            $(this).empty().text(text + '... ');
            $(this).append(link);
        }
    }
});

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
        }
    );

}


}); // JQuery