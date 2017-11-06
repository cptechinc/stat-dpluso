var custlookupform = "#ci-cust-lookup";

$(function() {
	$("body").on("focus", ".sweet-alert.show-input input", function(event) {
        console.log('focused');
        listener.stop_listening();
    });

	$(config.modals.ajax).on('hide.bs.modal', function(event) {
        listener.listen();
    });

    $(config.modals.ajax).on('shown.bs.modal', function(event) {
        listener.stop_listening();
		hidetoolbar();
    });

	listener.simple_combo("n", function() {toggleshipto();});

	$("body").on("submit", custlookupform, function(e) {
		e.preventDefault();
		var custID = $(this).find('.custID').val();
		var modal = config.modals.ajax;
        var loadinto =  modal+" .modal-content";
		var href = URI($(this).attr('action')).addQuery('q', custID).addQuery('function', 'ci').addQuery('modal', 'modal').normalizeQuery().toString();
		showajaxloading();
		$(loadinto).loadin(href, function() {
			hideajaxloading();
			$(modal).find('.modal-body').addClass('modal-results');
			$(modal).resizemodal('lg').modal();
		});
	});


	$("body").on("submit", "#cust-sales-history-form", function(event) {
		event.preventDefault();
		var form = $(this);
		var custID = form.find('input[name=custID]').val();
		var shipID = form.find('input[name=shipID]').val();
		var startdate = form.find('input[name=date]').val();
		var shownotes = 'N';
		if (form.find('input[name=shownotes]').is(':checked')) { shownotes = 'Y'; }
		var modal = config.modals.ajax;
		var loadinto =  modal+" .modal-content";
		var href = URI(config.urls.customer.load.ci_saleshistory).addQuery("custID", custID)
																 .addQuery("shipID", shipID)
																 .addQuery("startdate", startdate)
																 .addQuery("shownotes", shownotes)
																 .addQuery('modal', 'modal')
																 .toString();
		showajaxloading();
		ci_saleshistory(custID, shipID, startdate, function() {
			$(loadinto).loadin(href, function() {
				hideajaxloading();
				$(modal).find('.modal-body').addClass('modal-results');
				$(modal).resizemodal('lg').modal();
			});
		});
	});

	$("body").on("change", "select#shownotes", function(event) {
		event.preventDefault();
		var select = $(this);
		var shownotesvalue = select.val();
		var href = URI(select.data('link')).addQuery('shownotes', shownotesvalue).addQuery('modal', 'modal').toString();
		var ajax = select.data('ajax');
		if (ajax == 'Y') {
			var modal = config.modals.ajax;
	        var loadinto =  modal+" .modal-content";
			showajaxloading();
			$(loadinto).loadin(href, function() {
				hideajaxloading();
				$(modal).resizemodal('lg').modal();
			});
		} else {
			window.location.href = href;
		}
	});
	
	$("body").on("click", '.load-order-documents', function(e) {
		e.preventDefault();
		var custID = $(this).find('.custID').val();
		var modal = config.modals.ajax;
        var loadinto =  modal+" .modal-content";
		var ordn = $(this).data('ordn');
		var type = $(this).data('type');
		var href = URI($(this).attr('href')).addQuery('modal', 'modal').toString();
		showajaxloading();
		ci_getorderdocuments(custID, ordn, type, function() {
			wait(500, function() {
				$(loadinto).loadin(href, function() {
					console.log(href); hideajaxloading();
					$(modal).find('.modal-body').addClass('modal-results');
					$(modal).resizemodal('lg').modal();
				});
			});
		});
	});
});


function shipto() { //CAN BE USED IF SHIPTO IS DEFINED
	var custID = $(custlookupform + " .custID").val();
	var shipID = $(custlookupform + " .shipID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_shiptos).addQuery("custID", custID)
														.addQuery("shipID", shipID)
														.addQuery('modal', 'modal')
														.query(cleanparams)
														.toString();
	showajaxloading();
	$.getJSON(config.urls.json.ci_shiptolist, function( json ) {
		console.log(json.data.length);
		if (json.data.length == 1) {
			loadshipto(custID, json.data[0].shipid);
		} else {
			$(loadinto).loadin(href, function() {
				hideajaxloading();
				$(modal).find('.modal-body').addClass('modal-results');
				$(modal).resizemodal('lg').modal();
			});
		}
	});
}

function contact() {
	var custID = $(custlookupform + " .custID").val();
	var shipID = $(custlookupform + " .shipID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_contacts).addQuery("custID", custID).addQuery('shipID', shipID).addQuery('modal', 'modal').query(cleanparams).toString();
	showajaxloading();
	ci_contacts(custID, shipID, function() {
		loadin(href, loadinto, function() {
			hideajaxloading(); console.log(href);
			$(modal).find('.modal-body').addClass('modal-results');
			$(modal).resizemodal('lg').modal();
		});
	});
}

function pricing() {
	var custID = $(custlookupform + " .custID").val();
	var href = URI(config.urls.customer.load.ci_pricingform).addQuery("custID", custID).addQuery('modal', 'modal').toString();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	showajaxloading();
	$(loadinto).loadin(href, function() {
		hideajaxloading(); console.log(href);
		$(modal).find('.modal-body').addClass('modal-results');
		$(modal).resizemodal('lg').modal();
	});
}

function choosecipricingitem(itemID) {
	var custID = $(custlookupform + " .custID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_pricing).addQuery("custID", custID).addQuery("itemID", itemID).addQuery('modal', 'modal').toString();
	ci_pricing(custID, itemID, function() {
		$(loadinto).loadin(href, function() {
			hideajaxloading(); console.log(href);
			$(modal).find('.modal-body').addClass('modal-results');
			$(modal).resizemodal('lg').modal();
		});
	});
}

function salesorder() { //CAN BE USED IF SHIPTO IS DEFINED
	var custID = $(custlookupform + " .custID").val();
	var shipID = $(custlookupform + " .shipID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_salesorders).addQuery("custID", custID).addQuery("shipID", shipID).addQuery('modal', 'modal').toString();
	showajaxloading();
	ci_salesorder(custID, shipID, function() {
		$(loadinto).loadin(href, function() {
			hideajaxloading(); console.log(href);
			$(modal).find('.modal-body').addClass('modal-results');
			$(modal).resizemodal('lg').modal();
		});
	});
}

function saleshist() { //CAN BE USED IF SHIPTO IS DEFINED
	var custID = $(custlookupform + " .custID").val();
	var shipID = $(custlookupform + " .shipID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_saleshistory+"form/").addQuery("custID", custID)
																	 .addQuery("shipID", shipID)
																	 .addQuery('modal', 'modal')
																	 .toString();
	showajaxloading();
	$(loadinto).loadin(href, function() {
		hideajaxloading(); console.log(href);
		$(modal).find('.modal-body').addClass('modal-results');
		$(modal).resizemodal('sm').modal();
	});

}
function custpo() { //CAN BE USED IF SHIPTO IS DEFINED
	var custID = $(custlookupform + " .custID").val();
	var shipID = $(custlookupform + " .shipID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_custpo).addQuery("custID", custID).addQuery("shipID", shipID).toString();
	swal({
		title: "Customer PO Inquiry",
	 	text: "Enter a PO:",
		input: 'text',
		showCancelButton: true,
		inputValidator: function (value) {
			return new Promise(function (resolve, reject) {
				if (value === false) {
					reject("You need to write something!");
				} else if (value === "") {
					reject("You need to write something!");
				} else {
					resolve();
				}
			})
		}
	}).then(function (input) {
		if (input) {
			swal.close();
			href = URI(href).addQuery("custpo", input).addQuery('modal', 'modal').toString();
			ci_custpo(custID, shipID, input, function() {
				$(loadinto).loadin(href, function() {
					hideajaxloading();
					$(modal).find('.modal-body').addClass('modal-results');
					$(modal).resizemodal('lg').modal();
				});
			});

		} else {
			listener.listen();
		}
	}).catch(swal.noop);
}

function quotes() {
	var custID = $(custlookupform + " .custID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_quotes).addQuery("custID", custID).addQuery('modal', 'modal').toString();
	showajaxloading();
	ci_quotes(custID, function() {
		$(loadinto).loadin(href, function() {
			hideajaxloading();
			$(modal).find('.modal-body').addClass('modal-results');
			$(modal).resizemodal('xl').modal();
		});
	});
}

function openinv() {
	var custID = $(custlookupform + " .custID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_openinvoices).addQuery("custID", custID).addQuery('modal', 'modal').toString();
	showajaxloading();
	ci_openinvoices(custID, function() {
		$(loadinto).loadin(href, function() {
			hideajaxloading();
			$(modal).find('.modal-body').addClass('modal-results');
			$(modal).resizemodal('lg').modal();
		});
	});
}

function loadorderdocuments(ordn) {
	var custID = $(custlookupform + " .custID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_orderdocuments).addQuery("custID", custID).addQuery('ordn', ordn).addQuery('modal', 'modal').toString();
	showajaxloading();
	ci_getorderdocuments(custID, ordn, function() {
		wait(500, function() {
			$(loadinto).loadin(href, function() {
				console.log(href); hideajaxloading();
				$(modal).find('.modal-body').addClass('modal-results');
				$(modal).resizemodal('lg').modal();
			});
		});
	});
}

function payment() {
	var custID = $(custlookupform + " .custID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_paymenthistory).addQuery("custID", custID).addQuery('modal', 'modal').toString();
	showajaxloading();
	ci_paymenthistory(custID, function() {
		$(loadinto).loadin(href, function() {
			hideajaxloading();
			$(modal).find('.modal-body').addClass('modal-results');
			$(modal).resizemodal('lg').modal();
		});
	});
}
function custcredit() {
	var custID = $(custlookupform + " .custID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_credit).addQuery("custID", custID).addQuery('modal', 'modal').toString();
	showajaxloading();
	ci_credit(custID, function() {
		$(loadinto).loadin(href, function() {
			hideajaxloading();
			$(modal).find('.modal-body').addClass('modal-results');
			$(modal).resizemodal('lg').modal();
		});
	});
}
function standorders() { //CAN BE USED IF SHIPTO IS DEFINED
	var custID = $(custlookupform + " .custID").val();
	var shipID = $(custlookupform + " .shipID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_standingorders).addQuery("custID", custID)
															   .addQuery("shipID", shipID)
															   .addQuery('modal', 'modal')
															   .toString();
	showajaxloading();
	ci_standingorders(custID, shipID, function() {
		$(loadinto).loadin(href, function() {
			hideajaxloading();
			$(modal).find('.modal-body').addClass('modal-results');
			$(modal).resizemodal('lg').modal();
		});
	});
}
function customerstock() { //CAN BE USED IF SHIPTO IS DEFINED
	//TODO
}
function notes() { //CAN BE USED IF SHIPTO IS DEFINED
	//TODO
}
function docview() {
	var custID = $(custlookupform + " .custID").val();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	var href = URI(config.urls.customer.load.ci_documents).addQuery("custID", custID).addQuery('modal', 'modal').toString();
	showajaxloading();
	ci_documents(custID, function() {
		$(loadinto).loadin(href, function() {
			hideajaxloading();
			$(modal).find('.modal-body').addClass('modal-results');
			$(modal).resizemodal('lg').modal();
		});
	});

}

/*==============================================================
   SUPPLEMENTAL FUNCTIONS
=============================================================*/

function toggleshipto() {
	showajaxloading();
	var custID = $(custlookupform + " .custID").val();
	var nextshipID = '';
	if (!$(custlookupform + " .shipID").val() != '') { nextshipID = $(custlookupform + " .nextshipID").val(); }
	ci_shiptoinfo(custID, nextshipID, function() {
		var href = config.urls.customer.ci + "/"+urlencode(custID)+"/";
		if (nextshipID != '') {
			href += 'shipto-'+nextshipID+'/';
		}
		hideajaxloading();
		window.location.href = href;
	});
}


function loadshiptoinfo(custID, shipID) {
	var href = URI(config.urls.customer.load.ci_shiptoinfo).addQuery("custID", custID)
														   .addQuery('shipID', shipID)
														   .toString();
	var modal = config.modals.ajax;
	var loadinto =  modal+" .modal-content";
	showajaxloading();
	ci_shiptoinfo(custID, shipID, function() {
		$(loadinto).loadin(href, function() {
			hideajaxloading(); console.log(href);
			$(modal).resizemodal('lg').modal();
		});
	});
}

function choosecisaleshistoryitem(itemID) {
	var row = $('[href=#'+itemID+']');
	row.siblings().remove();
	$('.ci-history-item-search').val(itemID);
}
