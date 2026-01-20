function updateCartLive() {
    var quantities = {};
    var qtyInputs = document.querySelectorAll('.cart-qty-input');
    
    for (var i = 0; i < qtyInputs.length; i++) {
        var input = qtyInputs[i];
        var productId = input.name.match(/\d+/)[0];
        quantities[productId] = input.value;
    }
    
    var couponInput = document.querySelector('input[name="coupon"]');
    var coupon = "";
    if (couponInput) {
        coupon = couponInput.value;
    }
    
    var params = "coupon=" + coupon;
    for (var pid in quantities) {
        params += "&qty[" + pid + "]=" + quantities[pid];
    }
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            
            if (response.success) {
                for (var productId in response.subtotals) {
                    var inputElement = document.querySelector('input[name="qty[' + productId + ']"]');
                    if (inputElement) {
                        var row = inputElement.closest('.cart-row');
                        if (row) {
                            var subtotalElement = row.querySelector('.cart-col-subtotal');
                            if (subtotalElement) {
                                subtotalElement.innerHTML = response.subtotals[productId];
                            }
                        }
                    }
                }
                
                var totalElements = document.querySelectorAll('.cart-summary div strong');
                if (totalElements.length >= 3) {
                    totalElements[0].innerHTML = response.total;
                    totalElements[1].innerHTML = response.discount;
                }
                
                var payableElement = document.querySelector('.cart-payable strong');
                if (payableElement) {
                    payableElement.innerHTML = response.grandTotal;
                }
            }
        }
    };
    
    xhttp.open("POST", "../Controller/calculateCartTotals.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(params);
}

function decreaseQuantity() {
    var productId = this.getAttribute('data-product-id');
    var input = document.querySelector('input[name="qty[' + productId + ']"]');
    if (input) {
        var currentValue = parseInt(input.value) || 0;
        if (currentValue > 0) {
            input.value = currentValue - 1;
            updateCartLive();
        }
    }
}

function increaseQuantity() {
    var productId = this.getAttribute('data-product-id');
    var input = document.querySelector('input[name="qty[' + productId + ']"]');
    if (input) {
        var currentValue = parseInt(input.value) || 0;
        var maxStock = parseInt(input.getAttribute('data-max')) || 999;
        if (currentValue < maxStock) {
            input.value = currentValue + 1;
            updateCartLive();
        }
    }
}

window.onload = function() {
    var qtyInputs = document.querySelectorAll('.cart-qty-input');
    for (var i = 0; i < qtyInputs.length; i++) {
        qtyInputs[i].addEventListener('input', updateCartLive);
    }
    
    var couponInput = document.querySelector('input[name="coupon"]');
    if (couponInput) {
        couponInput.addEventListener('input', updateCartLive);
    }
    
    var decreaseBtns = document.querySelectorAll('.qty-decrease');
    for (var i = 0; i < decreaseBtns.length; i++) {
        decreaseBtns[i].addEventListener('click', decreaseQuantity);
    }
    
    var increaseBtns = document.querySelectorAll('.qty-increase');
    for (var i = 0; i < increaseBtns.length; i++) {
        increaseBtns[i].addEventListener('click', increaseQuantity);
    }
};