
var fields = {
    qty: ".qty", margin: ".margin", price: ".price", discountamt: ".discount-amt", discountpercent: ".discount-percent", originalprice: ".originalprice", listprice: ".listprice",
    linenumber: ".linenumber", discountprice: ".discountprice", cost: ".cost", calculatefrom: ".calculate-from", extendedamtspan: ".extended-amount-span", whse: '.item-whse', totalprice: '.totalprice',
    minprice: ".minprice"
};


$(function() {

    $("body").on("change", ".special-order-select", function() {
        var select = $(this);
        if (select.val() != 'N') {
            $('.special-order-details').removeClass('hidden');
        } else {
            $('.special-order-details').addClass('hidden');
        }
    });

    $("body").on("change", fields.qty, function() {
        calculateextendedprice();
    });

    $("body").on("change", fields.price, function() {
        calculateextendedprice();
        if ($(fields.price).val() < $(fields.minprice)) {
            console.log('Does not meet Minimum Price');
            if (config.edit.show_minprice_error) {
                $(fields.price).closest('tr').addClass('has-error');
                var descriptionrow = "<tr class='has-error'>"
                                     +"<td class='control-label'>Error: </td>"
                                     +"<td><p class='form-control-static text-danger'>Does not meet Minimum Price</p></td>"
                                     + "</tr>";
                $(fields.price).closest('tr').before(descriptionrow);
            }
        }
    });

    $("body").on("click", '.remove-item', function() {
        var button = $(this);
        var form = button.closest('form');
        form.find('input[name=action]').val('remove-line');
        form.submit();
    });
});


function calculateextendedprice() {
    var price = $(fields.price).val();
    var qty = $(fields.qty).val();
    var extendedamount = price * qty;
    $(fields.extendedamtspan).text(extendedamount.formatMoney(2, '.', ','));
    $(fields.extendedamtspan).text(extendedamount.formatMoney(2, '.', ','));
}
