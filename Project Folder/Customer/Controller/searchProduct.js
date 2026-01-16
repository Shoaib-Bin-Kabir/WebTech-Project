function searchProduct() {
    var q = document.getElementById('searchText').value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('productResult').innerHTML = this.responseText;
        }
    };

    xhttp.open('GET', '../Controller/searchProducts.php?q=' + encodeURIComponent(q), true);
    xhttp.send();
}
