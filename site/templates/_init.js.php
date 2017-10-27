<?php 
    $config->js('pwconfig', [
        'appconfig' => [
            'cptechcustomer' => $config->cptechcustomer
        ],
        'edit' => [
            'pricing' => [
                'show_minpriceerror' => false
            ]
        ],
        'products' => [
            'nonstockitems' => [
                'N' => 'N'
            ]
        ],
        'urls' => [
    		'index' => $config->pages->index,
    		'cart' => $config->pages->cart,
    		'orderfiles' => $config->pages->documentstorage,
    		'customer' => [
    			'page' => $config->pages->customer,
    			'ci' => $config->pages->custinfo,
    			'redir' => [
    				'ci_customer' => $config->pages->customer."redir/?action=ci-customer",
    				'ci_buttons' => $config->pages->customer."redir/?action=ci-buttons",
    				'ci_shiptos' => $config->pages->customer."redir/?action=ci-shiptos",
    				'ci_shiptoinfo' => $config->pages->customer."redir/?action=ci-shipto-info",
                    'ci_pricing '=> $config->pages->customer."redir/?action=ci-pricing",
    				'ci_shiptobuttons' => $config->pages->customer."redir/?action=ci-shipto-buttons",
    				'ci_contacts' => $config->pages->customer."redir/?action=ci-contacts",
    				'ci_documents' => $config->pages->customer."redir/?action=ci-documents",
    				'ci_standingorders' => $config->pages->customer."redir/?action=ci-standing-orders",
    				'ci_credit' => $config->pages->customer."redir/?action=ci-credit",
    				'ci_openinvoices' => $config->pages->customer."redir/?action=ci-open-invoices",
                    'ci_orderdocuments' => $config->pages->customer."redir/?action=ci-order-documents",
    				'ci_paymenthistory' => $config->pages->customer."redir/?action=ci-payments",
    				'ci_quotes' => $config->pages->customer."redir/?action=ci-quotes",
                    'ci_salesorders' => $config->pages->customer."redir/?action=ci-sales-orders",
                    'ci_saleshistory' => $config->pages->customer."redir/?action=ci-sales-history",
                    'ci_custpo' => $config->pages->customer."redir/?action=ci-custpo"
    			],
    			'load' => [
    				'loadindex' =>  $config->pages->ajaxload."customers/cust-index/",
    				'ci_customer' => "",
    				'ci_buttons' => "",
    				'ci_shiptos' => $config->pages->ajaxload."ci/ci-shiptos/",
    				'ci_shiptoinfo' => $config->pages->ajaxload."ci/ci-shipto-info/",
                    'ci_pricing' => $config->pages->ajaxload."ci/ci-pricing/",
                    'ci_pricingform' => $config->pages->ajaxload."ci/ci-pricing-search/",
    				'ci_shiptobuttons' => "",
    				'ci_contacts' => $config->pages->ajaxload."ci/ci-contacts/",
    				'ci_documents' => $config->pages->ajaxload."ci/ci-documents/",
    				'ci_standingorders' => $config->pages->ajaxload."ci/ci-standing-orders/",
    				'ci_credit' => $config->pages->ajaxload."ci/ci-credit/",
    				'ci_openinvoices' => $config->pages->ajaxload."ci/ci-open-invoices/",
                    'ci_orderdocuments' => $config->pages->ajaxload."ci/ci-documents/order/",
    				'ci_paymenthistory' => $config->pages->ajaxload."ci/ci-payment-history/",
    				'ci_quotes' => $config->pages->ajaxload."ci/ci-quotes/",
                    'ci_salesorders' => $config->pages->ajaxload."ci/ci-sales-orders/",
                    'ci_saleshistory' => $config->pages->ajaxload."ci/ci-sales-history/",
                    'ci_custpo' => $config->pages->ajaxload."ci/ci-custpo/"
    			]
    		],
    		'products' => [
    			'page' => $config->pages->products,
    			'iteminfo' => $config->pages->iteminfo,
    			'redir' => [
    				'getitempricing' => $config->pages->products."redir/?action=get-item-price",
    				'ii_select' => $config->pages->products."redir/?action=ii-select",
    				'ii_pricing' => $config->pages->products."redir/?action=ii-pricing",
                    'ii_costing' => $config->pages->products."redir/?action=ii-costing",
                    'ii_purchaseorder' => $config->pages->products."redir/?action=ii-purchase-order",
    				'ii_quotes' => $config->pages->products."redir/?action=ii-quotes",
    				'ii_purchasehistory' => $config->pages->products."redir/?action=ii-purchase-history",
    				'ii_whereused' => $config->pages->products."redir/?action=ii-where-used",
                    'ii_kitcomponents' => $config->pages->products."redir/?action=ii-kit-components",
    				'ii_bom' => $config->pages->products."redir/?action=ii-bom",
    				'ii_general' => "", //NOT USED THE MISC, NOTES, AND, USAGE
    				'ii_usage' => $config->pages->products."redir/?action=ii-usage",
    				'ii_notes' => $config->pages->products."redir/?action=ii-notes",
    				'ii_misc' => $config->pages->products."redir/?action=ii-misc",
    				'ii_activity' => $config->pages->products."redir/?action=ii-activity", //NOT USED, ACTIVITY FORM USES POSTFORM
    				'ii_activityform' => "", //NOT USED, ACTIVITY FORM USES POSTFORM
    				'ii_requirements' => $config->pages->products."redir/?action=ii-requirements",
    				'ii_lotserial' => $config->pages->products."redir/?action=ii-lot-serial",
    				'ii_salesorder' => $config->pages->products."redir/?action=ii-sales-order",
    				'ii_saleshistoryform' => "", // NOT USED
    				'ii_stock' => $config->pages->products."redir/?action=ii-stock",
    				'ii_substitutes' => $config->pages->products."redir/?action=ii-substitutes",
    				'ii_documents' => $config->pages->products."redir/?action=ii-documents",
                    'ii_order_documents' => $config->pages->products."redir/?action=ii-order-documents",
    			]
    		],
    		'json' => [
    			'getloadurl' => $config->pages->ajaxjson."get-load-url/",
    			'dplusnotes' => $config->pages->ajaxjson."dplus-notes/",
    			'loadtask' => $config->pages->ajaxjson."load-task/",
                'loadaction' => $config->pages->ajaxjson."load-action/",
    			'getshipto' => $config->pages->ajaxjson."get-shipto/",
    			'getorderhead' => $config->pages->ajaxjson."order/orderhead/",
    			'getorderdetails' => $config->pages->ajaxjson."order/details/",
    			'getquotehead' => $config->pages->ajaxjson."quote/quotehead/",
                'getquotedetails' => $config->pages->ajaxjson."quote/details/",
    			'ii_moveitemdoc' => $config->pages->ajaxjson."ii/ii-move-document/",
    			'ci_shiptolist' => $config->pages->ajaxjson."ci/ci-shipto-list/",
                'vendorshipfrom' => $config->pages->ajaxjson."vendor-shipfrom/",
                'validateitemid' => $config->pages->ajaxjson."products/validate-itemid/"
    		],
    		'load' => [
    			'productresults' => $config->pages->ajaxload."products/item-search-results/",
    			'editdetail' => $config->pages->ajaxload."edit-detail/", //DEPRECATED
                'ii_productresults' => $config->pages->ajaxload."ii/search-results/",
    			'ii_select' => "", // NOT USED
    			'ii_pricing' => $config->pages->ajaxload."ii/ii-pricing/",
                'ii_costing' => $config->pages->ajaxload."ii/ii-costing/",
                'ii_purchaseorder' => $config->pages->ajaxload."ii/ii-purchase-order/",
    			'ii_quotes' => $config->pages->ajaxload."ii/ii-quotes/",
    			'ii_purchasehistory' => $config->pages->ajaxload."ii/ii-purchase-history/",
    			'ii_whereused' => $config->pages->ajaxload."ii/ii-where-used/",
                'ii_kitcomponents' => $config->pages->ajaxload."ii/ii-kit-components/",
    			'ii_bom' => $config->pages->ajaxload."ii/ii-bom/",
    			'ii_general' => $config->pages->ajaxload."ii/ii-general/",
    			'ii_usage' => $config->pages->ajaxload."ii-usage/", //NOT USED part of ii_general
    			'ii_notes' => $config->pages->ajaxload."ii-notes/", //NOT USED part of ii_general
    			'ii_misc' => $config->pages->ajaxload."ii-misc/", //NOT USED part of ii_general
    			'ii_activity' => $config->pages->ajaxload."ii/ii-activity/",
    			'ii_activityform' => $config->pages->ajaxload."ii/ii-activity/form/",
    			'ii_requirements' => $config->pages->ajaxload."ii/ii-requirements/",
    			'ii_lotserial' => $config->pages->ajaxload."ii/ii-lot-serial/",
    			'ii_salesorder' => $config->pages->ajaxload."ii/ii-sales-orders/",
    			'ii_saleshistory' => $config->pages->ajaxload."ii/ii-sales-history/",
    			'ii_saleshistoryform' => $config->pages->ajaxload."ii/ii-sales-history/form/", // NOT USED
    			'ii_stock' => $config->pages->ajaxload."ii/ii-stock/",
    			'ii_substitutes' => $config->pages->ajaxload."ii/ii-substitutes/",
    			'ii_documents' => $config->pages->ajaxload."ii/ii-documents/",
                'ii_order_documents' => $config->pages->ajaxload."ii/ii-documents/order/",
    		],
            'vendor' => [
                'redir' => [
                    'vi_shipfrom' => $config->pages->vendor."redir/?action=vi-shipfrom",
                    'vi_payment' => $config->pages->vendor."redir/?action=vi-payment",
                    'vi_openinv' => $config->pages->vendor."redir/?action=vi-openinv",
                    'vi_purchasehist' => $config->pages->vendor."redir/?action=vi-purchasehist"
                ],
                'load' => [
                    'vi_shipfrom' => $config->pages->ajaxload."vi/vi-shipfrom/",
                    'vi_payment' => $config->pages->ajaxload."vi/vi-payment/",
                    'vi_openinv' => $config->pages->vajaxload."vi/vi-openinv/",
                    'vi_purchasehist' => $config->pages->ajaxload."vi/vi-purchasehist/",
                    'vi_purchasehist_form' => $config->pages->ajaxload."vi/vi-purchasehist/form/"
                ],
                'json' => [
                    'vi_shipfromlist' => $config->pages->ajaxjson."vi/vi-shipfrom-list/"
                    'vi_purchasehist' => $config->pages->ajaxload."vi/vi-purchasehist/"
                ],
                'json' => [
                    'vi_shipfromlist' => $config->pages->ajaxjson."vi/vi-shipfrom-list"
                ]
            ]
    	],
        'paths' => [
    		'assets' => [
    			'images' =>  $config->pages->index."site/assets/files/images/"
    		]
    	],
    	'modals' => [
    		'pricing' => '#pricing-modal',
        	'ajax' => '#ajax-modal',
    		'lightbox' => '#lightbox-modal',
    		'gradients' => [
    			'default' => 'icarus',
    			'tribute' => 'tribute'
    		]
    	],
    	'toolbar' => [
    		'toolbar' => '#function-toolbar',
    		'button' => '#show-toolbar'
    	]
    ]);
