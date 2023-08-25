function advanced_line_clamp(){
    document.querySelectorAll('.advanced-line-clamp').forEach(function(element) {
        // If text is truncated (with a line-clamp?)
        if (element.scrollHeight > element.clientHeight) {
            let text = element.textContent;
    
            let link = document.createElement('span');
            link.className = 'read-more-button';
            link.textContent = ' اقرأ المزيد';
            element.appendChild(link);
    
            // Cut a char at a time from text, until it fits (taking into account 
            // also the size of the "read more" link)
            while(element.scrollHeight > element.clientHeight) {
                text = text.slice(0, -1);
                element.innerHTML = text + '... ';
                element.appendChild(link);
            }
        }
    });
}
// Run by default when page is loaded
advanced_line_clamp();

// Re Run when more posts are loaded via Ajax
document.addEventListener('loaded_more_posts', function() {
    advanced_line_clamp();
});
