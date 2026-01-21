function searchHistory() {
    const searchTerm = document.getElementById('searchSeller').value;
    const statusSpan = document.getElementById('searchStatus');
    
 
    if (searchTerm.length > 0) {
        statusSpan.textContent = 'Searching...';
    } else {
        statusSpan.textContent = '';
    }
    
    const xhr = new XMLHttpRequest();
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                document.getElementById('historyContainer').innerHTML = xhr.responseText;
                
                
                if (searchTerm.length > 0) {
                    statusSpan.textContent = '✓ Search complete';
                    setTimeout(function() {
                        statusSpan.textContent = '';
                    }, 2000);
                } else {
                    statusSpan.textContent = '';
                }
            } else {
                statusSpan.textContent = '✗ Search failed';
                console.error('Error searching history');
            }
        }
    };
    
    const params = 'searchTerm=' + encodeURIComponent(searchTerm);
    
    xhr.open('POST', '../Controller/searchHistory.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(params);
}