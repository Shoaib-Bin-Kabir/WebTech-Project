<?php
?>

function valEmail(){
    let email = document.getElementById('semail').value;
    const emailpattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email === '') {
        document.getElementById('semailErr').innerHTML = 'Seller Email is required.';
        return false;
    }

    if (!emailpattern.test(email)) {
        document.getElementById('semailErr').innerHTML = 'Invalid email format.';
        return false;
    }

    return true;
}

function valPassword(){
    let password = document.getElementById('spassword').value;
    
    if (password === '') {
        document.getElementById('spasswordErr').innerHTML = 'Seller Password is required.';
        return false;
    }

    if (password.length < 4) {
        document.getElementById('spasswordErr').innerHTML = 'Password must be at least 4 characters long.';
        return false;
    }

    let hasNum = false;
    let hasAlpha = false;
    for (let i = 0; i < password.length; i++) {
        if (!isNaN(password[i]) && password[i] !== ' ') {
            hasNum = true;
        }
        if (password[i].match(/[a-zA-Z]/)) {
            hasAlpha = true;
        }
    }

    if (!hasNum || !hasAlpha) {
        document.getElementById('spasswordErr').innerHTML = 'Password must contain both letters and numbers.';
        return false;
    }

    return true;
}

function clearErrors(){
    document.getElementById('semailErr').innerHTML = '';
    document.getElementById('spasswordErr').innerHTML = '';
}

function validateAddSeller(event){
    clearErrors();
    event.preventDefault();

    let isEmailValid = valEmail();
    let isPasswordValid = valPassword();

    if (!isEmailValid || !isPasswordValid) {
        return false;
    }

    document.querySelector('form').submit();
}