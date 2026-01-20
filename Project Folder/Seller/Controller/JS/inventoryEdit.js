let editingField = null;

function validateQuantity(quantity) {
    if (quantity === '') {
        return 'Quantity is required';
    }
    if (!/^[0-9]+$/.test(quantity)) {
        return 'Quantity must be a whole number';
    }
    if (parseInt(quantity) < 0) {
        return 'Quantity cannot be negative';
    }
    return '';
}

function validatePrice(price) {
    if (price === '') {
        return 'Price is required';
    }
    if (!/^[0-9]+(\.[0-9]{1,2})?$/.test(price)) {
        return 'Price must be a valid number (up to 2 decimal places)';
    }
    if (parseFloat(price) < 0) {
        return 'Price cannot be negative';
    }
    return '';
}

function toggleEdit(fieldName, productId) {
    const fieldKey = fieldName + '_' + productId;
    
    if (editingField && editingField !== fieldKey) {
        alert('Please save the current edit before editing another field.');
        return;
    }
    
    const displayId = fieldName + 'Display_' + productId;
    const formId = fieldName + 'Form_' + productId;
    const btnId = fieldName + 'Btn_' + productId;
    
    const displayElement = document.getElementById(displayId);
    const formElement = document.getElementById(formId);
    const btnElement = document.getElementById(btnId);
    
    if (editingField === null) {
       
        displayElement.style.display = 'none';
        formElement.style.display = 'inline';
        btnElement.value = 'Save';
        editingField = fieldKey;
    } else {
        
        const inputElement = formElement.querySelector('input[type="number"], input[type="text"]');
        const value = inputElement.value.trim();
        let errorMessage = '';
        
        if (fieldName === 'quantity') {
            errorMessage = validateQuantity(value);
        } else if (fieldName === 'price') {
            errorMessage = validatePrice(value);
        }
        
        if (errorMessage) {
            alert(errorMessage);
            return;
        }
        
        formElement.submit();
    }
}

function togglePhotoEdit(productId) {
    const fieldKey = 'photo_' + productId;
    
    if (editingField && editingField !== fieldKey) {
        alert('Please save the current edit before editing another field.');
        return;
    }
    
    const displayId = 'photoDisplay_' + productId;
    const formId = 'photoForm_' + productId;
    const btnId = 'photoBtn_' + productId;
    
    const displayElement = document.getElementById(displayId);
    const formElement = document.getElementById(formId);
    const btnElement = document.getElementById(btnId);
    
    if (editingField === null) {
        displayElement.style.display = 'none';
        formElement.style.display = 'block';
        btnElement.value = 'Save Photo';
        editingField = fieldKey;
    } else {
        const fileInput = formElement.querySelector('input[type="file"]');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Please select a photo to upload');
            return;
        }
        formElement.submit();
    }
}

function previewProductPhoto(input, productId) {
    const preview = document.getElementById('photoPreview_' + productId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" width="100" height="100" alt="Photo Preview">';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function confirmDelete(productId, productName) {
    if (confirm('Are you sure you want to delete "' + productName + '"? This action cannot be undone.')) {
        document.getElementById('deleteForm_' + productId).submit();
    }
}