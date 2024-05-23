/* === Header & Footer functionality Scripts  === */
document.querySelectorAll('form.newsletter button').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        email_dom_ele = document.querySelector('form.newsletter input[type="email"]');
        if (email_dom_ele.checkValidity()){

            button.style.pointerEvents = 'none';
            button.style.opacity = '0.5';

            // Ajax
            var data = new URLSearchParams({
                action: 'meissa_newsletter_subs_add_sub',
                email: email_dom_ele.value
            });
            
            fetch(meissa_globals.ajaxurl, {
                method: 'POST',
                body: data
            })            
            .then(response => response.json())
            .then(response => {
                console.log(response);
                if (response['status'] == 200) {
                    console.log("DONE !");
                } 
                else {
                    console.log("Error !");
                }

                button.style.pointerEvents = 'initial';
                button.style.opacity = '1';
            });
        }
        email_dom_ele.reportValidity(); // Shows the Tooltip
    });
});


// Increase Post Views Count via AJAX
if(meissa_globals.is_single == true && meissa_globals.post_id != undefined){
    var data = new URLSearchParams({
        action: 'set_post_views',
        post_id: meissa_globals.post_id
    });
    
    fetch(meissa_globals.ajaxurl, {
        method: 'POST',
        body: data
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    }).then(data => {
        console.log(data);
    }).catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
    
}
