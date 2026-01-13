let editingField = null;

function validateName(name) {
    if (name.length < 3) {
        return 'Name must be at least 3 characters long';
    }
    if (!/^[a-zA-Z\s]+$/.test(name)) {
        return 'Name can only contain letters and spaces';
    }
    return '';
}

function validateNID(nid) {
    if (!/^[0-9]+$/.test(nid)) {
        return 'NID must contain only numbers';
    }
    if (nid.length > 10) {
        return 'NID cannot be greater than 10 digits';
    }
    return '';
}

function validatePhone(phone) {
    if (!/^[0-9]+$/.test(phone)) {
        return 'Phone number must contain only numbers';
    }
    if (phone.length !== 11) {
        return 'Phone number must be exactly 11 digits';
    }
    if (!/^01[3-9][0-9]{8}$/.test(phone)) {
        return 'Phone number must start with 013, 014, 015, 016, 017, 018, or 019';
    }
    return '';
}

function validateEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        return 'Invalid email format';
    }
    return '';
}

function toggleEdit(fieldName) {
    if (editingField && editingField !== fieldName) {
        alert('Please save the current edit before editing another field.');
        return;
    }
    
    const displayId = fieldName + 'Display';
    const formId = fieldName + 'Form';
    const btnId = fieldName + 'Btn';
    
    const displayElement = document.getElementById(displayId);
    const formElement = document.getElementById(formId);
    const btnElement = document.getElementById(btnId);
    
    if (editingField === null) {
        displayElement.style.display = 'none';
        formElement.style.display = 'inline';
        btnElement.value = 'Done';
        editingField = fieldName;
    } else {
        // Validate before submitting
        const inputElement = formElement.querySelector('input[type="text"], input[type="email"]');
        const value = inputElement.value.trim();
        let errorMessage = '';
        
        if (fieldName === 'name') {
            errorMessage = validateName(value);
        } else if (fieldName === 'nid') {
            errorMessage = validateNID(value);
        } else if (fieldName === 'phone') {
            errorMessage = validatePhone(value);
        } else if (fieldName === 'email') {
            errorMessage = validateEmail(value);
        }
        
        if (errorMessage) {
            alert(errorMessage);
            return;
        }
        
        formElement.submit();
    }
}

function togglePhotoEdit() {
    const photoDisplay = document.getElementById('photoDisplay');
    const photoForm = document.getElementById('photoForm');
    const photoBtn = document.getElementById('photoBtn');
    
    if (editingField === null) {
        photoDisplay.style.display = 'none';
        photoForm.style.display = 'block';
        photoBtn.value = 'Done';
        editingField = 'photo';
    } else {
        const fileInput = document.querySelector('input[name="photo"]');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Please select a photo to upload');
            return;
        }
        document.getElementById('photoFormElement').submit();
    }
}

function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" width="150" height="150" alt="Photo Preview">';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}