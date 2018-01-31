$('document').ready(function () {

    $('#users-table').load('/users-table');

    $('#user-settings-modal').on('show.bs.modal', function (event) {
        row = $(event.relatedTarget);
        username = row.find('td[data-field="username"]').text();
        name = row.find('td[data-field="name"]').text();
        email = row.find('td[data-field="email"]').text();
        role_name = row.find('td[data-field="role_name"]').text();

        modal = $(this);
        modal.find('.modal-title').text(username);

        form = modal.find('#user-settings-form');
        form.find('#input-username').val(username);
        form.find('#input-name').val(name);
        form.find('#input-email').val(email);
        form.find('#input-role_name').val(role_name);

        modal.find('#user-settings-delete').attr('data-username', username);
        modal.find('#user-settings-message-box').text('');
    });

    $('#user-settings-submit').click(function (event) {

        form = $('#user-settings-form');
        messageBox = $('#user-settings-message-box');
        app.submitForm(form, function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            $('#users-table').load('/users-table');
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });

    });

    $('#user-create-modal').on('show.bs.modal', function (event) {
        form = $(this).find('#user-create-form');
        form.find('#input-username').val('');
        form.find('#input-name').val('');
        form.find('#input-email').val('');
        form.find('#input-role_name').val('');

        $('#user-create-message-box').text('');
    });

    $('#user-create-submit').click(function (event) {
        form = $('#user-create-form');
        messageBox = $('#user-create-message-box');

        app.submitForm(form, function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            $('#users-table').load('/users-table');
            setTimeout(function() {
                $('#user-create-modal').modal('hide');
            }, 1500);
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });
    });

    $('#user-delete-modal').on('show.bs.modal', function (event) {
        $(this).find('#user-delete-message-box').text('');
        username = $(event.relatedTarget).attr('data-username');
        console.log(username);
        $(this).find('#user-delete-modal-username').text(username);
        $(this).find('input[name="username"]').val(username);
        $('#user-settings-modal').hide();
    });

    $('#user-delete-modal').on('hide.bs.modal', function (event) {
        $('#user-settings-modal').show();
    });

    $('#user-delete-no').click(function (event) {
        $('#user-settings-modal').show();
        $('#user-delete-modal').modal('hide');
    });

    $('#user-delete-form').submit(function (event) {
        event.preventDefault();

        messageBox = $('#user-delete-message-box');

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});

            setTimeout(function() {
                $('#user-delete-modal').modal('hide');
            }, 1000);

            setTimeout(function() {
                $('#user-settings-modal').modal('hide');
            }, 1000);

            $('#users-table').load('/users-table');
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        })
    });
});
