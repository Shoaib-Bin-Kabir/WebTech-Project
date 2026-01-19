<?php
?>

function valProductName(){
    let productName = document.getElementById('pname').value;
    if (productName === '') {
        document.getElementById('pnameErr').innerHTML = 'Product Name is required.';
        return false;
    }

    if (productName.length < 3 ) {
        document.getElementById('pnameErr').innerHTML = 'Product Name must be at least 3 characters long.';
        return false;
    }

    return true;
}

function valCategory(){
    let category = document.getElementById('pdesc').value;
    if (category === '') {
        document.getElementById('pdescErr').innerHTML = 'Product Category is required.';
        return false;
    }

    return true;
}

function valPrice(){
    let price = document.getElementById('pprice').value;
    if (price === '') {
        document.getElementById('ppriceErr').innerHTML = 'Product Price is required.';
        return false;
    }

    if (isNaN(price) || Number(price) <= 0) {
        document.getElementById('ppriceErr').innerHTML = 'Product Price must be a positive number.';
        return false;
    }

    return true;
}

function valQuantity(){
    let quantity = document.getElementById('pquantity').value;
    if (quantity === '') {
        document.getElementById('pquantityErr').innerHTML = 'Product Quantity is required.';
        return false;
    }

    if (!Number.isInteger(Number(quantity)) || Number(quantity) < 0) {
        document.getElementById('pquantityErr').innerHTML = 'Product Quantity must be a non-negative integer.';
        return false;
    }

    return true;
}

function valPhoto() {
    let photo = document.getElementById('pPhoto').files[0];
    
    if (!photo) {
        document.getElementById('pPhotoErr').innerHTML = 'Product Photo is required';
        return false;
    }
    
    let allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(photo.type)) {
        document.getElementById('pPhotoErr').innerHTML = 'Only JPG, JPEG, PNG, and GIF files are allowed';
        return false;
    }
    
    if (photo.size > 5 * 1024 * 1024) {
        document.getElementById('pPhotoErr').innerHTML = 'File size must be less than 5MB';
        return false;
    }
    
    return true;
}

function clearErrors(){
    document.getElementById('pnameErr').innerHTML = '';
    document.getElementById('pdescErr').innerHTML = '';
    document.getElementById('ppriceErr').innerHTML = '';
    document.getElementById('pquantityErr').innerHTML = '';
    document.getElementById('pPhotoErr').innerHTML = '';
}

function validateAddProduct(event){
    clearErrors();
    event.preventDefault();

    let valid = true;

    if (!valProductName()) valid = false;
    if (!valCategory()) valid = false;
    if (!valPrice()) valid = false;
    if (!valQuantity()) valid = false;
    if (!valPhoto()) valid = false;

    if (valid) {
        document.querySelector('form').submit();
    }
}