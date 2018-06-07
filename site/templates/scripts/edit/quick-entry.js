$(function() {
    $("body").on("change", ".calculates-price", function(e) {
        var line = $(this).closest('.detail');
        var input_qty = line.find("input[name=qty]");
        var input_price = line.find("input[name=price]");
        var input_minprice = line.find("input[name=min-price]");
        var text_totalprice = line.find('.total-price');

        // ADD code to calculate total price
        var total = input_qty.val() * input_price.val();
        // Replace value in text_totalprice
        $(text_totalprice).text(total.formatMoney(2, '.', ','));
        // check if config.edit.pricing.allow_belowminprice is true

        if (config.edit.pricing.allow_belowminprice == true) {
            // if it is then check if price is below minimum allowed
            if (input_price.val() < input_minprice.val()) {
                input_price.parent().addClass('has-error');
                line.find('.response').createalertpanel('Item price below minimum!', 'Error!', 'danger');
            } else if (input_price.val() > input_minprice.val()) {
                input_price.parent().removeClass('has-error');
                line.find('.response').empty();
            }
        }
    });
});
