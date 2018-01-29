$('document').ready(function () {

    $('#login-form').submit(function (event) {
        event.preventDefault();
        messageBox = $('#login-form-message-box');

        app.submitForm($(this), function (response) {
            window.location.href = '/';
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });
    });

    $('#forgotten-password-submit').click(function (event) {
        form = $('#forgotten-password-form');
        messageBox = $('#forgotten-password-message-box');

        app.submitForm(form, function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            setTimeout(function() {
                $('#forgotten-password-modal').modal('hide');
            }, 1500);
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });
    });
});
