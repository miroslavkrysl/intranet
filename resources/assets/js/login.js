$('document').ready(function () {

    $('#login-form').submit(function (event) {
        event.preventDefault();
        errorBox = $('#login-form-error-box');

        app.submitForm($(this), function (response) {
            window.location.href = '/';
        }, function (response) {
            console.log(errorBox);
            app.setErrors(errorBox, response.responseJSON.errors);
        });
    });
});
