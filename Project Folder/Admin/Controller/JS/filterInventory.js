function filterInventory() {
    const sortBy = document.getElementById('sortBy').value;
    const category = document.getElementById('categoryFilter').value;
    
    const xhr = new XMLHttpRequest();
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                document.getElementById('productsContainer').innerHTML = xhr.responseText;
            } else {
                console.error('Error filtering products');
            }
        }
    };
    
    const params = 'sortBy=' + encodeURIComponent(sortBy) + '&category=' + encodeURIComponent(category);
    
    xhr.open('POST', '../Controller/filterProducts.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(params);
}