$('document').ready(function () {

    $('#settings-form').submit(function (event) {
        event.preventDefault();
        messageBox = $('#settings-message-box');

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });
    });

    $('#change-password-form').submit(function (event) {
        event.preventDefault();
        messageBox = $('#change-password-message-box');

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });
    });

    $('#change-password-modal').on('show.bs.modal', function (event) {
        form = $(this).find('#change-password-form');
        form.find('#input-password').val('');
        form.find('#input-password-again').val('');
        form.find('#input-_password').val('');
    });

    $('#change-password-submit').click(function (event) {
        form = $('#change-password-form');
        messageBox = $('#change-password-message-box');

        if (form.find('#input-password').val() !== form.find('#input-password-again').val()) {
            app.setMessages(messageBox, {'error' : ['Hesla se neshodují']}, true);
            return;
        }

        app.submitForm(form, function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            setTimeout(function() {
                $('#change-password-modal').modal('hide');
            }, 1000);
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });
    });
});
