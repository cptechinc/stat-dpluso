$(function() {
    $('.select-item').change(function() {
        var checkbox = $(this);
        if (checkbox.is(':checked')) {
            checkbox.closest('tr').removeClass('item-not-selected');
        } else {
            checkbox.closest('tr').addClass('item-not-selected');
        }
    });
    $('#select-all').change(function() {
        var checkbox = $(this);
        if (checkbox.is(':checked')) {
            console.log('is checked');
            $('.select-item').prop('checked', true).change();;
        } else {
            $('.select-item').prop('checked', false).change();
        }
    })
});
