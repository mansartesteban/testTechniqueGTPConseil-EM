$("form").on("focus", ".datetimePicker", function(e) {
    e.preventDefault();
    $(this).datetimepicker();
});

$("select").chosen({});