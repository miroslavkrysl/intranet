loadDrivers = function (form) {
    driverSelect = form.find('[name="driver_username"]');
    driverSelect.html('');
    driverSelect.val('');

    carName = form.find('[name="car_name"]').val();

    $.getJSON('/users-can-drive?' + $.param({name : carName}), function (data) {
        users = data.users;
        for (user in users) {
            driverSelect.append(
                $('<option>', { value : users[user].username }).text(users[user].name)
            );
        }
    });
};

$('document').ready(function () {
    $('#requests-table').load('/requests-table');

    $('#request-create-modal').on('show.bs.modal', function (event) {
        form = $(this).find('#user-create-form');
        form.find('#input-car_name').val('');
        form.find('#input-driver_username').val('');
        form.find('#input-reserved_from').val('');
        form.find('#input-reserved_to').val('');
        form.find('#input-destination').val('');
        form.find('#input-purpose').val('');
        form.find('#input-passengers').val('');

        loadDrivers($('#request-create-form'));
    });

    $('#request-create-form').find('select[name="car_name"]').change(function () {
        loadDrivers($('#request-create-form'));
    });

    $('#request-create-submit').click(function (event) {
        form = $('#request-create-form');
        messageBox = $('#request-create-message-box');

        app.submitForm(form, function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            $('#requests-table').load('/requests-table');
            setTimeout(function() {
                $('#request-create-modal').modal('hide');
            }, 1500);
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });
    });

    datetimePickerCreateFrom = $('#request-create-form').find('#input-reserved_from');
    datetimePickerCreateTo = $('#request-create-form').find('#input-reserved_to');

    datetimePickerCreateFrom.datetimepicker({
        format : 'j.m.Y H:i',
        step : 15,
        defaultTime: '8:00'
    });
    datetimePickerCreateTo.datetimepicker({
        format : 'j.m.Y H:i',
        step : 15,
        defaultTime: '8:00'
    });
    $.datetimepicker.setLocale('cs');

    $('#request-settings-modal').on('show.bs.modal', function (event) {
        row = $(event.relatedTarget);

        id = row.find('td[data-field="id"]').text();
        user_username = row.find('td[data-field="user_username"]').text();
        driver_username = row.find('td[data-field="driver_username"]').text();
        car_name = row.find('td[data-field="car_name"]').text();
        reserved_from = row.find('td[data-field="reserved_from"]').text();
        reserved_to = row.find('td[data-field="reserved_to"]').text();
        destination = row.find('td[data-field="destination"]').text();
        purpose = row.find('td[data-field="purpose"]').text();
        passengers = row.find('td[data-field="passengers"]').text();
        confirmed = row.find('td[data-field="passengers"]').text().toLowerCase() === 'ano' ;

        modal = $(this);

        form = modal.find('#request-settings-form');
        form.find('#input-id').val(id);
        form.find('#input-user_username').val(user_username);
        form.find('#input-driver_username').val(driver_username);
        form.find('#input-car_name').val(car_name);
        form.find('#input-reserved_from').val(reserved_from);
        form.find('#input-reserved_to').val(reserved_to);
        form.find('#input-destination').val(destination);
        form.find('#input-purpose').val(purpose);
        form.find('#input-passengers').val(passengers);

        modal.find('#request-settings-delete').attr('data-id', id);
        modal.find('#request-settings-confirm').attr('data-id', id);
        modal.find('#request-settings-message-box').text('');

        loadDrivers($('#request-settings-form'));
    });


    datetimePickerSettingsFrom = $('#request-settings-form').find('#input-reserved_from');
    datetimePickerSettingsTo = $('#request-settings-form').find('#input-reserved_to');

    datetimePickerSettingsFrom.datetimepicker({
        format : 'j.m.Y H:i',
        step : 15,
        defaultTime: '8:00'
    });
    datetimePickerSettingsTo.datetimepicker({
        format : 'j.m.Y H:i',
        step : 15,
        defaultTime: '8:00'
    });

    $('#request-settings-submit').click(function (event) {
        form = $('#request-settings-form');
        messageBox = $('#request-settings-message-box');
        app.submitForm(form, function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            $('#requests-table').load('/requests-table');
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });

    });

    $('#request-delete-modal').on('show.bs.modal', function (event) {
        $(this).find('#request-delete-message-box').text('');
        id = $(event.relatedTarget).attr('data-id');

        $(this).find('input[name="id"]').val(id);
        $('#request-settings-modal').hide();
    });

    $('#request-delete-modal').on('hide.bs.modal', function (event) {
        $('#request-settings-modal').show();
    });

    $('#request-delete-no').click(function (event) {
        $('#request-settings-modal').show();
        $('#request-delete-modal').modal('hide');
    });

    $('#request-delete-form').submit(function (event) {
        event.preventDefault();

        messageBox = $('#request-delete-message-box');

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});

            setTimeout(function() {
                $('#request-delete-modal').modal('hide');
            }, 1000);

            setTimeout(function() {
                $('#request-settings-modal').modal('hide');
            }, 1000);

            $('#requests-table').load('/requests-table');
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        })
    });

    $('#request-confirm-modal').on('show.bs.modal', function (event) {
        $(this).find('#request-confirm-message-box').text('');
        id = $(event.relatedTarget).attr('data-id');

        $(this).find('input[name="id"]').val(id);
        $('#request-settings-modal').hide();
    });

    $('#request-confirm-modal').on('hide.bs.modal', function (event) {
        $('#request-settings-modal').show();
    });

    $('#request-confirm-no').click(function (event) {
        $('#request-settings-modal').show();
        $('#request-confirm-modal').modal('hide');
    });

    $('#request-confirm-form').submit(function (event) {
        event.preventDefault();

        messageBox = $('#request-confirm-message-box');

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});

            setTimeout(function() {
                $('#request-confirm-modal').modal('hide');
            }, 1000);

            setTimeout(function() {
                $('#request-settings-modal').modal('hide');
            }, 1000);

            $('#requests-table').load('/requests-table');
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        })
    });
});
