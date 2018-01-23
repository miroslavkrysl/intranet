$('document').ready(function () {

    $('#settings-form').submit(function (event) {
        event.preventDefault();
        messageBox = $('#settings-form-message-box');

        if ($(this).find('input[name="password"]').val() !== $(this).find('input[name="password1"]').val()) {
            app.setMessages(messageBox, {'error' : ['Hesla se neshodují']}, true);
            return;
        }

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });
    });
});
