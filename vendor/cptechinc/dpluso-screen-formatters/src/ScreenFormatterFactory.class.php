<?php
    class ScreenFormatterFactory {
        protected $sessionID;
        protected $formatters = array(
            'ii-sales-history' => 'II_SalesHistoryFormatter',
            'ii-sales-orders' => 'II_SalesOrdersFormatter',
            'ii-purchase-orders' => 'II_PurchaseOrdersFormatter',
            'ii-purchase-history' => 'II_PurchaseHistoryFormatter',
            'ii-quotes' => 'II_Quotes',
            'ii-item-page' => 'II_ItemPageFormatter',
            
            // CI
            'ci-sales-orders' => 'CI_SalesOrdersFormatter',
            'ci-sales-history' => 'CI_SalesHistoryFormatter',
            'ci-open-invoices' => 'CI_OpenInvoicesFormatter',
            'ci-payment-history' => 'CI_PaymentHistoryFormatter',
            'ci-quotes' => 'CI_QuotesFormatter',
            
            // VI 
            'vi-purchase-orders' => 'VI_PurchaseOrdersFormatter',
            'vi-purchase-history' => 'VI_PurchaseHistoryFormatter',
            'vi-payment-history' => 'VI_PaymentHistoryFormatter',
            'vi-open-invoices' => 'VI_OpenInvoicesFormatter',
            'vi-unreleased-purchase-orders' => 'VI_UnreleasedPurchaseOrdersFormatter',
            
            // NON FORMATABLE
            'ii-activity' => 'II_ItemActivityScreen',
            'ii-stock' => 'II_ItemWarehouseStockScreen',
            'ii-requirements' => 'II_ItemRequirementsScreen',
            'ii-kit' => 'II_ItemKitScreen',
            
            'ci-customer-page' => 'CI_CustomerScreen',
            'ci-customer-shipto-page' => 'CI_CustomerShiptoScreen',
            'ci-contacts' => 'CI_ContactsScreen'
        );
        
        public function __construct($sessionID) {
            $this->sessionID = $sessionID;
        }
        
        public function generate_screenformatter($type) {
            if (in_array($type, array_keys($this->formatters))) {
                return new $this->formatters[$type]($this->sessionID);
            } else {
                $this->error("Screen Formatter $type does not exist");
                return false;
            }
        }
        
        protected function error($error, $level = E_USER_ERROR) {
			$error = (strpos($error, 'DPLUSO[SCREEN-FORMATTER]: ') !== 0 ? 'DPLUSO[SCREEN-FORMATTER]: ' . $error : $error);
			trigger_error($error, $level);
			return;
		}
    } 
 ?>
