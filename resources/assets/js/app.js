
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