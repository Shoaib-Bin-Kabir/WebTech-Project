let editingField = null;

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
        formElement.submit();
        editingField = null;
    }
}

function togglePhotoEdit(productId) {
    console.log('togglePhotoEdit called for product ID:', productId);
    let fieldKey = 'photo_' + productId;
    
    if (editingField && editingField !== fieldKey) {
        alert('Please save the current edit before editing another field.');
        return;
    }
    
    let displayId = 'photoDisplay_' + productId;
    let formId = 'photoForm_' + productId;
    let btnId = 'photoBtn_' + productId;
    
    let displayElement = document.getElementById(displayId);
    let formElement = document.getElementById(formId);
    let btnElement = document.getElementById(btnId);
    
    if (editingField === null) {
        console.log('Entering edit mode');
        displayElement.style.display = 'none';
        formElement.style.display = 'block';
        btnElement.value = 'Save Photo';
        editingField = fieldKey;
    } else {
        console.log('Attempting to save photo');
        let actualForm = document.getElementById('photoFormElement_' + productId);
        console.log('Form element:', actualForm);
        
        if (!actualForm) {
            alert('Error: Could not find form element');
            return;
        }
        
        let fileInput = actualForm.querySelector('input[type="file"]');
        console.log('File input:', fileInput);
        console.log('Files:', fileInput ? fileInput.files : 'no file input');
        
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
            alert('Please select a photo to upload');
            return;
        }
        
        console.log('Submitting form...');
        actualForm.submit();
        console.log('Form submitted');
        editingField = null;
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