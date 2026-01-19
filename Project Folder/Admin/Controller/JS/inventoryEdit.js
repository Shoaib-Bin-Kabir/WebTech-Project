function toggleEdit(fieldName, productId) {
    let displayId = fieldName + 'Display_' + productId;
    let formId = fieldName + 'Form_' + productId;
    let btnId = fieldName + 'Btn_' + productId;
    
    let displayElement = document.getElementById(displayId);
    let formElement = document.getElementById(formId);
    let btnElement = document.getElementById(btnId);
    
    if (displayElement.style.display !== 'none') {
        displayElement.style.display = 'none';
        formElement.style.display = 'inline';
        btnElement.value = 'Save';
    } else {
        formElement.submit();
    }
}

function togglePhotoEdit(productId) {
    let displayId = 'photoDisplay_' + productId;
    let formId = 'photoForm_' + productId;
    let btnId = 'photoBtn_' + productId;
    
    let displayElement = document.getElementById(displayId);
    let formElement = document.getElementById(formId);
    let btnElement = document.getElementById(btnId);
    
    if (displayElement.style.display !== 'none') {
        displayElement.style.display = 'none';
        formElement.style.display = 'block';
        btnElement.value = 'Save Photo';
    } else {
        formElement.submit();
    }
}

function previewProductPhoto(input, productId) {
    let preview = document.getElementById('photoPreview_' + productId);
    
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" width="100" height="100">';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function confirmDelete(productId, productName) {
    if (confirm('Are you sure you want to delete "' + productName + '"?')) {
        document.getElementById('deleteForm_' + productId).submit();
    }
}