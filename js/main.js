/* === Header & Footer functionality Scripts  === */

// Org: (function($){...})(jQuery)
// New: jQuery(function($){...})


jQuery(function($){

// --------------------------------------------------------------------------------------
// Limit excerpt to 3 lines (done with css) but remove some text from the end and add a "Read More" button
// Could've useed dotdotdot.js but I prefered to code it manually
// This code will be ignored unless there is .advanced-line-clamp elements
function advanced_line_clamp(){
    $('.advanced-line-clamp').each(function() {
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
}
// Run by default when page is loaded
advanced_line_clamp();

// Re Run when more posts are loaded via Ajax
$(document).on('loaded_more_posts', function() {
    advanced_line_clamp();
});

// --------------------------------------------------------------------------------------
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
}); // JQuery