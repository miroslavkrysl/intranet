$('document').ready(function () {

    $('#documents-table').load('/documents-table');

    $('#document-settings-modal').on('show.bs.modal', function (event) {
        row = $(event.relatedTarget).parent();
        name = row.find('td[data-field="name"]').text();
        id = row.find('td[data-field="id"]').text();
        user_username = row.find('td[data-field="user_username"]').text();

        modal = $(this);
        modal.find('.modal-title').text(name);

        form = modal.find('#document-settings-form');
        form.find('#input-name').val(name);
        form.find('#input-id').val(id);
        form.find('#input-user_username').val(user_username);

        modal.find('#document-settings-delete').attr('data-id', id);
        modal.find('#document-settings-message-box').text('');
    });

    $('#document-settings-submit').click(function (event) {
        form = $('#document-settings-form');
        messageBox = $('#document-settings-message-box');
        app.submitForm(form, function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});
            $('#documents-table').load('/documents-table');
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        });

    });

    $('#document-create-modal').on('show.bs.modal', function (event) {
        form = $(this).find('#document-create-form');
        form.find('#input-name').val('');
        form.find('#input-file').val('');

        $('#document-create-message-box').text('');
    });

    running = false;

    $('#document-create-submit').click(function (event) {

        if (running) {
            return;
        }

        running = true;
        text = $(this).html();
        $(this).html('<i class="fa fa-spinner fa-spin"></i>');

        form = $('#document-create-form');
        messageBox = $('#document-create-message-box');

        url = form.attr('action');
        method = form.attr('data-method');

        token= form.find('[name="_token"]').val();

        formData = new FormData();
        formData.append('name', form.find('#input-name').val());
        formData.append('user_username', form.find('#input-user_username').val());
        formData.append('file', document.getElementById('input-file').files[0]);

        $.ajax({
            url: url,
            type: method,
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN" : token
            },
            success: function (response) {
                app.setMessages(messageBox, {'success' : [response['message']]});
                $('#documents-table').load('/documents-table');
                setTimeout(function() {
                    $('#document-create-modal').modal('hide');
                }, 1500);
            },
            error: function (response) {
                app.setMessages(messageBox, response.responseJSON.errors, true);
            },
            complete: function () {
                running = false;
                $('#document-create-submit').html(text);
            }
        });
    });

    $('#document-delete-modal').on('show.bs.modal', function (event) {
        $(this).find('#document-delete-message-box').text('');
        id = $(event.relatedTarget).attr('data-id');
        $(this).find('input[name="id"]').val(id);
        $('#document-settings-modal').hide();
    });

    $('#document-delete-modal').on('hide.bs.modal', function (event) {
        $('#document-settings-modal').show();
    });

    $('#document-delete-no').click(function (event) {
        $('#document-settings-modal').show();
        $('#document-delete-modal').modal('hide');
    });

    $('#document-delete-form').submit(function (event) {
        event.preventDefault();

        messageBox = $('#document-delete-message-box');

        app.submitForm($(this), function (response) {
            app.setMessages(messageBox, {'success' : [response['message']]});

            setTimeout(function() {
                $('#document-delete-modal').modal('hide');
            }, 1000);

            setTimeout(function() {
                $('#document-settings-modal').modal('hide');
            }, 1000);

            $('#documents-table').load('/documents-table');
        }, function (response) {
            app.setMessages(messageBox, response.responseJSON.errors, true);
        })
    });
});
