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
    });
    
    $("body").on("submit", "#select-items-form", function(e) {
        e.preventDefault();
        var form = $(this);
        if ($('.quote-details').find('.item-not-selected').length == $('.quote-details').find('.detail').length) {
            swal({
                title: 'Error!',
                text: 'No items are selected to send to order',
                type: 'error',
                animation: false,
                customClass: 'animated tada'
            });
        } else {
            form.postform({}, function() { 
                generateurl(function(url) { 
                    window.location.href = url;
                });
            })
        }
    });
});

function validate_selection() {
    var form = $("#select-items-form");
}
