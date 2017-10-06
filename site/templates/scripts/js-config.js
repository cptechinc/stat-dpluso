var sitedirectory = 'dpluso';
var sitepath = '/';
if (sitedirectory.length > 0) {
    sitepath = "/" + sitedirectory + "/";
}

var config = {
    edit: {
        pricing: {
            show_minprice_error: false
        }
    },
	urls: {
		index: sitepath,
		cart: sitepath + "cart/",
		orderfiles: "/orderfiles/",
		customer: {
			page: sitepath + "customers/",
			ci: sitepath + "customers/cust-info/",
			redir: {
				ci_customer: sitepath + "customers/redir/?action=ci-customer",
				ci_buttons: sitepath + "customers/redir/?action=ci-buttons",
				ci_shiptos: sitepath + "customers/redir/?action=ci-shiptos",
				ci_shiptoinfo: sitepath + "customers/redir/?action=ci-shipto-info",
                ci_pricing: sitepath + "customers/redir/?action=ci-pricing",
				ci_shiptobuttons: sitepath + "customers/redir/?action=ci-shipto-buttons",
				ci_contacts: sitepath + "customers/redir/?action=ci-contacts",
				ci_documents: sitepath + "customers/redir/?action=ci-documents",
				ci_standingorders: sitepath + "customers/redir/?action=ci-standing-orders",
				ci_credit: sitepath + "customers/redir/?action=ci-credit",
				ci_openinvoices: sitepath + "customers/redir/?action=ci-open-invoices",
                ci_orderdocuments: sitepath + "customers/redir/?action=ci-order-documents",
				ci_paymenthistory: sitepath + "customers/redir/?action=ci-payments",
				ci_quotes: sitepath + "customers/redir/?action=ci-quotes",
                ci_salesorders: sitepath + "customers/redir/?action=ci-sales-orders",
                ci_saleshistory: sitepath + "customers/redir/?action=ci-sales-history",
                ci_custpo: sitepath + "customers/redir/?action=ci-custpo"
			},
			load: {
				loadindex:  sitepath + "ajax/load/customers/cust-index/",
				ci_customer: sitepath + "",
				ci_buttons: sitepath + "",
				ci_shiptos: sitepath + "ajax/load/ci/ci-shiptos/",
				ci_shiptoinfo: sitepath + "ajax/load/ci/ci-shipto-info/",
                ci_pricing: sitepath + "ajax/load/ci/ci-pricing/",
                ci_pricingform: sitepath + "ajax/load/ci/ci-pricing-search/",
				ci_shiptobuttons: sitepath + "",
				ci_contacts: sitepath + "ajax/load/ci/ci-contacts/",
				ci_documents: sitepath + "ajax/load/ci/ci-documents/",
				ci_standingorders: sitepath + "ajax/load/ci/ci-standing-orders/",
				ci_credit: sitepath + "ajax/load/ci/ci-credit/",
				ci_openinvoices: sitepath + "ajax/load/ci/ci-open-invoices/",
                ci_orderdocuments: sitepath + "ajax/load/ci/ci-documents/order/",
				ci_paymenthistory: sitepath + "ajax/load/ci/ci-payment-history/",
				ci_quotes: sitepath + "ajax/load/ci/ci-quotes/",
                ci_salesorders: sitepath + "ajax/load/ci/ci-sales-orders/",
                ci_saleshistory: sitepath + "ajax/load/ci/ci-sales-history/",
                ci_custpo: sitepath + "ajax/load/ci/ci-custpo/"
			}
		},
		products: {
			page: sitepath + "products/",
			iteminfo: sitepath + "products/item-info/",
			redir: {
				getitempricing: sitepath + "products/redir/?action=get-item-price",
				ii_select: sitepath + "products/redir/?action=ii-select",
				ii_pricing: sitepath + "products/redir/?action=ii-pricing",
                ii_costing: sitepath + "products/redir/?action=ii-costing",
                ii_purchaseorder: sitepath + "products/redir/?action=ii-purchase-order",
				ii_quotes: sitepath + "products/redir/?action=ii-quotes",
				ii_purchasehistory: sitepath + "products/redir/?action=ii-purchase-history",
				ii_whereused: sitepath + "products/redir/?action=ii-where-used",
                ii_kitcomponents: sitepath + "products/redir/?action=ii-kit-components",
				ii_bom: sitepath + "products/redir/?action=ii-bom",
				ii_general: sitepath + "", //NOT USED THE MISC, NOTES, AND, USAGE
				ii_usage: sitepath + "products/redir/?action=ii-usage",
				ii_notes: sitepath + "products/redir/?action=ii-notes",
				ii_misc: sitepath + "products/redir/?action=ii-misc",
				ii_activity: sitepath + "products/redir/?action=ii-activity", //NOT USED, ACTIVITY FORM USES POSTFORM
				ii_activityform: sitepath + "", //NOT USED, ACTIVITY FORM USES POSTFORM
				ii_requirements: sitepath + "products/redir/?action=ii-requirements",
				ii_lotserial: sitepath + "products/redir/?action=ii-lot-serial",
				ii_salesorder: sitepath + "products/redir/?action=ii-sales-order",
				ii_saleshistoryform: sitepath + "", // NOT USED
				ii_stock: sitepath + "products/redir/?action=ii-stock",
				ii_substitutes: sitepath + "products/redir/?action=ii-substitutes",
				ii_documents: sitepath + "products/redir/?action=ii-documents",
                ii_order_documents: sitepath + "products/redir/?action=ii-order-documents",
			}
		},
		json: {
			getloadurl: sitepath + "ajax/json/get-load-url/",
			dplusnotes: sitepath + "ajax/json/dplus-notes/",
			loadtask: sitepath + "ajax/json/load-task/",
            loadaction: sitepath + "ajax/json/load-action/",
			getshipto: sitepath + "ajax/json/get-shipto/",
			getorderhead: sitepath + "ajax/json/order/orderhead/",
			getorderdetails: sitepath + "ajax/json/order/details/",
			getquotehead: sitepath + "ajax/json/quote/quotehead/",
            getquotedetails: sitepath + "ajax/json/quote/details/",
			ii_moveitemdoc: sitepath + "ajax/json/ii/ii-move-document/",
			ci_shiptolist: sitepath + "ajax/json/ci/ci-shipto-list/",
            vendorshipfrom: sitepath + "ajax/json/vendor-shipfrom/",
            validateitemid: sitepath + "ajax/json/products/validate-itemid/"
		},
		load: {
			productresults: sitepath + "ajax/load/products/item-search-results/",
			editdetail: sitepath + "ajax/load/edit-detail/", //DEPRECATED
            ii_productresults: sitepath + "ajax/load/ii/search-results/",
			ii_select: sitepath + "", // NOT USED
			ii_pricing: sitepath + "ajax/load/ii/ii-pricing/",
            ii_costing: sitepath + "ajax/load/ii/ii-costing/",
            ii_purchaseorder: sitepath + "ajax/load/ii/ii-purchase-order/",
			ii_quotes: sitepath + "ajax/load/ii/ii-quotes/",
			ii_purchasehistory: sitepath + "ajax/load/ii/ii-purchase-history/",
			ii_whereused: sitepath + "ajax/load/ii/ii-where-used/",
            ii_kitcomponents: sitepath + "ajax/load/ii/ii-kit-components/",
			ii_bom: sitepath + "ajax/load/ii/ii-bom/",
			ii_general: sitepath + "ajax/load/ii/ii-general/",
			ii_usage: sitepath + "ajax/load/ii-usage/", //NOT USED part of ii_general
			ii_notes: sitepath + "ajax/load/ii-notes/", //NOT USED part of ii_general
			ii_misc: sitepath + "ajax/load/ii-misc/", //NOT USED part of ii_general
			ii_activity: sitepath + "ajax/load/ii/ii-activity/",
			ii_activityform: sitepath + "ajax/load/ii/ii-activity/form/",
			ii_requirements: sitepath + "ajax/load/ii/ii-requirements/",
			ii_lotserial: sitepath + "ajax/load/ii/ii-lot-serial/",
			ii_salesorder: sitepath + "ajax/load/ii/ii-sales-orders/",
			ii_saleshistory: sitepath + "ajax/load/ii/ii-sales-history/",
			ii_saleshistoryform: sitepath + "ajax/load/ii/ii-sales-history/form/", // NOT USED
			ii_stock: sitepath + "ajax/load/ii/ii-stock/",
			ii_substitutes: sitepath + "ajax/load/ii/ii-substitutes/",
			ii_documents: sitepath + "ajax/load/ii/ii-documents/",
            ii_order_documents: sitepath + "ajax/load/ii/ii-documents/order/",
		}
	},
	paths: {
		assets: {
			images: sitepath + "site/assets/files/images/"
		}
	},
	modals: {
		pricing: '#pricing-modal',
    	ajax: '#ajax-modal',
		lightbox: '#lightbox-modal',
		gradients: {
			default: 'icarus',
			tribute: 'tribute'
		}
	},
	toolbar: {
		toolbar: '#function-toolbar',
		button: '#show-toolbar'
	}

};

var nonstockitems = ['N'];
