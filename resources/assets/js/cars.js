bindUserCanDriveForms = function () {
    $('.user-can-drive-delete-form').on('submit', function (event) {
        event.preventDefault();

        messageBox = $('#car-settings-message-box');
        name = $(this).find('input[name="name"]').val();

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            $('#users-can-drive-table').load('/users-can-drive-table?' + $.param({'name' : name}), bindUserCanDriveForms);
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        })
    });
};

$('document').ready(function () {

    $('#cars-table').load('/cars-table');

    $('#car-settings-modal').on('show.bs.modal', function (event) {
        row = $(event.relatedTarget);
        name = row.find('td[data-field="name"]').text();
        description = row.find('td[data-field="description"]').text();
        manufacturer = row.find('td[data-field="manufacturer"]').text();
        model = row.find('td[data-field="model"]').text();

        modal = $(this);
        modal.find('.modal-title').text(name);

        form = modal.find('#car-settings-form');
        form.find('#input-name').val(name);
        form.find('#input-description').val(description);
        form.find('#input-manufacturer').val(manufacturer);
        form.find('#input-model').val(model);

        $('#user-can-drive-input-name').val(name);

        modal.find('#car-settings-delete').attr('data-name', name);
        modal.find('#car-settings-message-box').text('');

        $('#users-can-drive-table').load('/users-can-drive-table?' + $.param({'name' : name}), bindUserCanDriveForms);
    });

    $('#car-settings-submit').click(function (event) {
        form = $('#car-settings-form');
        messageBox = $('#car-settings-message-box');
        app.submitForm(form, function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            $('#cars-table').load('/cars-table');
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });

    });

    $('#car-create-modal').on('show.bs.modal', function (event) {
        form = $(this).find('#car-create-form');
        form.find('#input-name').val('');
        form.find('#input-description').val('');
        form.find('#input-manufacturer').val('');
        form.find('#input-model').val('');
    });

    $('#car-create-submit').click(function (event) {
        form = $('#car-create-form');
        messageBox = $('#car-create-message-box');

        app.submitForm(form, function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            $('#cars-table').load('/cars-table');
            setTimeout(function() {
                $('#car-create-modal').modal('hide');
            }, 1500);
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });
    });

    $('#car-delete-modal').on('show.bs.modal', function (event) {
        $(this).find('#car-delete-message-box').text('');
        name = $(event.relatedTarget).attr('data-name');
        $(this).find('#car-delete-modal-name').text(name);
        $(this).find('input[name="name"]').val(name);
        $('#car-settings-modal').hide();
    });

    $('#car-delete-modal').on('hide.bs.modal', function (event) {
        $('#car-settings-modal').show();
    });

    $('#car-delete-no').click(function (event) {
        $('#car-settings-modal').show();
        $('#car-delete-modal').modal('hide');
    });

    $('#car-delete-form').submit(function (event) {
        event.preventDefault();

        messageBox = $('#car-delete-message-box');

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});

            setTimeout(function() {
                $('#car-delete-modal').modal('hide');
            }, 1000);

            setTimeout(function() {
                $('#car-settings-modal').modal('hide');
            }, 1000);

            $('#cars-table').load('/cars-table');
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        })
    });

    $('.user-can-drive-add-form').on('submit', function (event) {
        event.preventDefault();

        messageBox = $('#car-settings-message-box');
        name = $(this).find('input[name="name"]').val();

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            $('#users-can-drive-table').load('/users-can-drive-table?' + $.param({'name' : name}), bindUserCanDriveForms);
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        })
    });
});
