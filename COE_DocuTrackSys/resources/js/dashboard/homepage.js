$('#addNewAccountBtn').on('click', function(event){
    event.preventDefault();
    $('#accountSettingsBtn').trigger('click');
})