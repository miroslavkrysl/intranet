$('document').ready(function () {

    $('#change-password-form').submit(function (event) {
        event.preventDefault();
        messageBox = $('#change-password-message-box');

        if ($(this).find('#input-password').val() !== $(this).find('#input-password-again').val()) {
            app.setMessages(messageBox, {'error' : ['Hesla se neshodují']}, true);
            return;
        }

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            setTimeout(function () {
                window.location.href = '/';
            }, 3000);
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });
    });
});