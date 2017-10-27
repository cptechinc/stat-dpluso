function vi_payment(vendorID, callback) {
    var url = config.urls.vendor.redir.vi_payment+"&vendorID="+urlencode(vendorID);
    $.get(url, function() { callback();});
}

function vi_openinv(vendorID, callback) {
    var url = config.urls.vendor.redir.vi_openinv+"&vendorID="+urlencode(vendorID);
    $.get(url, function() { callback();});
}

function vi_shipfrom(vendorID, shipfromID, callback) {
    var url = config.urls.vendor.redir.vi_shipfrom+"&vendorID="+urlencode(vendorID)+"&shipfromID="+urlencode(shipfromID);
    $.get(url, function() { callback();});
}

function vi_purchasehist(vendorID, callback) {
    var url = config.urls.vendor.redir.vi_purchasehist+"&vendorID="+urlencode(vendorID);
    $.get(url, function() { callback();});
}
