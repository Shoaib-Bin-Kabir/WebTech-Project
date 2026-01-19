function confirmDeleteSeller(sellerEmail, sellerName) {
    if (confirm('Are you sure you want to remove seller "' + sellerEmail + '"? This action cannot be undone.')) {
        document.getElementById('deleteSellerForm_' + sellerEmail.replace('@', '_').replace('.', '_')).submit();
    }
}