// Extend dayjs with UTC plugin
dayjs.extend(window.dayjs_plugin_utc);

document.addEventListener('DOMContentLoaded', () => {
    const now = dayjs.utc();
    
    // Find all elements with date visibility attributes
    const elements = document.querySelectorAll('[data-show-from], [data-show-until]');
    
    elements.forEach(element => {
        const showFrom = element.getAttribute('data-show-from');
        const showUntil = element.getAttribute('data-show-until');
        
        let shouldShow = true;
        
        if (showFrom) {
            const fromDate = dayjs.utc(showFrom);
            if (now.isBefore(fromDate)) {
                shouldShow = false;
            }
        }
        
        if (showUntil) {
            const untilDate = dayjs.utc(showUntil);
            if (now.isAfter(untilDate)) {
                shouldShow = false;
            }
        }
        
        if (shouldShow) {
            element.style.display = '';
        }
    });
});
