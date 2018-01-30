
$('document').ready(function () {

    app = {

        template : {
            error : '<div class="error-message alert alert-danger"><div>',
            success : '<div class="success-message alert alert-success"><div>',
            message :
            '<div class="message alert alert-primary alert-dismissible">' +
            '   <button type="button" class="close" data-dismiss="alert">' +
            '       <span>&times;</span>' +
            '   </button>' +
            '</div>',
            spinner : '<i class="fa fa-spinner fa-spin"></i>'
        },

        messageBox : $('#message-box'),

        setMessages : function (messageBox, messages, error) {
            messageBox.children().slideUp(300, function () {
                $(this).remove();
            });

            messages = Object.values(messages);

            for (var i = 0; i < messages.length; i++) {
                fieldMessages = Object.values(messages[i]);
                for (var j = 0; j < fieldMessages.length; j++) {
                    $(error ? app.template.error : app.template.success).text(fieldMessages[j]).appendTo(messageBox).hide().slideDown();
                }
            }
        },

        submitForm : function (form, success, error) {
            form = $(form);
            url = form.attr('action');
            method = form.attr('data-method');
            id = form.attr('id');

            $.ajax({
                url: url,
                type: method,
                dataType: 'json',
                data: form.serialize(),
                success: success,
                error: error
            });
        }

    };

    $('#logout-form').submit(function (event) {
        event.preventDefault();

        app.submitForm($(this), function (response) {
            window.location.href = '/';
        }, null);
    });
});

$('#datetimepicker-from').datetimepicker({
    format:'d.m.Y H:i',
        onShow:function( ct ){
            this.setOptions({
                maxDate:$('#datetimepicker-to').val()?$('#datetimepicker-to').val():false
            })
        }
    });
$('#datetimepicker-to').datetimepicker({
        format:'d.m.Y H:i',
        onShow:function( ct ){
            this.setOptions({
                minDate:$('#datetimepicker-from').val()?$('#datetimepicker-from').val():false
            })
        }
    });
$.datetimepicker.setLocale('cs');