<?php 
	/**
	 * Class for handling of getting and displaying booking records from the database
	 * @author Paul Gomez paul@cptechinc.com
	 */
	class BookingsPanel {
		use ThrowErrorTrait;
		use MagicMethodTraits;
		use AttributeParser;
		
		/**
		 * Object that stores page location and where to load
		 * and search from
		 * @var Purl\Url
		 */
		protected $pageurl;
		/**
		 * Session Identifier
		 * @var string
		 */
		protected $sessionID;
		
		/**
		 * Modal to use
		 * @var string
		 */
		protected $modal;
		
		/**
		 * String that contains attributes for ajax loading
		 * @var string
		 */
		protected $ajaxdata;
		
		/**
		 * What path segment to paginate after
		 * @var string
		 */
		protected $paginateafter = 'bookings';
		
		/**
		 * Array of booking records
		 * @var string
		 */
		protected $bookings;
		/**
		 * What interval to Use
		 * day | week | month 
		 * // NOTE if blank, the default is day unless there's more than 90 days then we switch to month  
		 * @var string
		 */
		protected $interval;
		
		/**
		 * Array of filters to filterdown the data
		 * @var array
		 */
		protected $filters = false;
		
		/**
		 * Array of filterable fields and the attributes
		 * for each filterable
		 * @var array
		 */
		protected $filterable = array(
			'bookdate' => array(
				'querytype' => 'between',
				'datatype' => 'date',
				'date-format' => 'Ymd',
				'label' => 'Book Date'
			)
		);
		
		/**
		 * Time intervals used in the filtering of data
		 * @var array
		 */
		public static $intervals = array('day' => 'daily', 'week' => 'weekly', 'month' => 'monthly');
		
		/* =============================================================
			CONSTRUCTOR FUNCTIONS
		============================================================ */
		/**
		 * Constructor
		 * @param string  $sessionID Session Identifier
		 * @param Purl\Url $pageurl  Object that contains the URL of the page
		 * @param string  $modal     ID of Modal to use
		 * @param bool    $ajaxdata  Attributes used for ajax loading
		 * @uses
		 */
		public function __construct($sessionID, Purl\Url $pageurl, $modal = '', $ajaxdata = false) {
			$this->sessionID = $sessionID;
			$this->pageurl = new Purl\Url($pageurl->getUrl());
			$this->modal = $modal;
			$this->ajaxdata = $this->parse_ajaxdata($ajaxdata);
			$this->setup_pageurl();
		}
		
		/* =============================================================
			GETTER FUNCTIONS
		============================================================ */
		/**
		 * Queries the database and returns with booking records
		 * that meets the criteria defined in the $this->filters array
		 * @param  bool $debug Whether or not to execute and return list | return SQL Query
		 * @return array       Booking records | SQL Query
		 * @uses
		 */
		public function get_bookings($debug = false) {
			$this->determine_interval();
			$bookings = get_userbookings($this->sessionID, $this->filters, $this->filterable, $this->interval, $debug);
			return $debug ? $bookings : $this->bookings = $bookings;
		}
		
		public function get_daybookingordernumbers($date, $debug = false) {
			return get_daybookingordernumbers($this->sessionID, $date, false, false, $debug);
		}
		
		public function count_daybookingordernumbers($date, $debug = false) {
			return count_daybookingordernumbers($this->sessionID, $date, false, false, $debug);
		}
		
		public function count_todaysbookings($debug = false) {
			return count_todaysbookings($this->sessionID, false, false, $debug);
		}
		
		public function get_bookingsummarybycustomer($debug = false) {
			$bookings = get_bookingsummarybycustomer($this->sessionID, $this->filters, $this->filterable, $this->interval, $debug);
			return $debug ? $bookings : $this->bookings = $bookings;
		}
		
		/**
		 * Determines the interval to use based on the filters
		 * and based on the interval it creates the title description
		 * @return string [description] "Viewing (daily | weekly | monthly) bookings between $from and $through"
		 */
		public function generate_title() {
			$this->determine_interval();
			
			if (!empty($this->interval)) {
				$intervaldesc = self::$intervals[$this->interval];
				$from = $this->filters['bookdate'][0];
				$through = $this->filters['bookdate'][1];
				return "Viewing $intervaldesc bookings between $from and $through";
			}
		}
		
		/* =============================================================
			SETTER FUNCTIONS
		============================================================ */
		/**
		 * Used when constructed, this sets the PageURL path to point at the bookings ajax URL
		 * @return void 
		 */
		public function setup_pageurl() {
			$this->pageurl->path = DplusWire::wire('config')->pages->ajaxload."bookings/";
		}
		/**
		 * Defines the interval
		 * @param string $interval Must be one of the predefined interval types
		 * @uses
		 */
		public function set_interval($interval) {
			if (in_array($interval, array_keys(self::$intervals))) {
				$this->interval = $interval;
			} else {
				$this->error("interval $interval is not valid");
			}
		}
		
		/* =============================================================
			CLASS FUNCTIONS
		============================================================ */
		/**
		 * Returns the URL to bookings panel's normal state
		 * @return string URL
		 * @uses
		 */
		public function generate_refreshurl() {
			$url = new Purl\Url($this->pageurl->getURL());
			$url->query = '';
			return $url->getURL();
		}
		
		/**
		 * Returns the HTML link for refreshing bookings
		 * @return string HTML link
		 * @uses
		 */
		public function generate_refreshlink() {
			$bootstrap = new Contento();
			$href = $this->generate_refreshurl();
			$icon = $bootstrap->createicon('fa fa-refresh');
			$ajaxdata = $this->generate_ajaxdataforcontento();
			return $bootstrap->openandclose('a', "href=$href|class=load-link|$ajaxdata", "$icon Refresh Bookings");
		}
		
		/**
		 * Looks through the $input->get for properties that have the same name
		 * as filterable properties, then we populate $this->filter with the key and value
		 * @param  ProcessWire\WireInput $input Use the get property to get at the $_GET[] variables
		 */
		public function generate_filter(ProcessWire\WireInput $input) {
			if (!$input->get->filter) {
				$this->filters = array(
					'bookdate' => array(date('m/d/Y', strtotime('-1 year')), date('m/d/Y'))
				);
			} else {
				$this->filters = array();
				
				foreach ($this->filterable as $filter => $type) {
					if (!empty($input->get->$filter)) {
						if (!is_array($input->get->$filter)) {
							$value = $input->get->text($filter);
							$this->filters[$filter] = explode('|', $value);
						} else {
							$this->filters[$filter] = $input->get->$filter;
						}
					} elseif (is_array($input->get->$filter)) {
						if (strlen($input->get->$filter[0])) {
							$this->filters[$filter] = $input->get->$filter;
						}
					}
				}
				
				if (!isset($this->filters['bookdate'])) {
					$this->generate_defaultfilter();
				}
			}
		}
		
		/**
		 * Defines the filter for default
		 * Goes back one year
		 * @param  string $interval Allows to defined interval
		 * @return void
		 */
		protected function generate_defaultfilter($interval = '') {
			if (!empty($inteval)) {
				$this->set_interval($interval);
			}
			
			if (!$input->get->filter) {
				$this->filters = array(
					'bookdate' => array(date('m/d/Y', strtotime('-1 year')), date('m/d/Y'))
				);
			} else {
				$this->filters['bookdate'] = array(date('m/d/Y', strtotime('-1 year')), date('m/d/Y'));
			}
		}
		
		/**
		 * Determines the interval needed if inteval not defined
		 * if there are more than 90 days between from and through dates then
		 * the interval is set to month
		 * @return void
		 */
		protected function determine_interval() {
			$days = DplusDateTime::subtract_days($this->filters['bookdate'][0], $this->filters['bookdate'][1]);
			
			if ($days >= 90 && empty($this->interval)) {
				$this->set_interval('month');
			} elseif (empty($this->interval)) {
				$this->set_interval('day');
			}
		}
		
		/**
		 * Return the description for todays bookings
		 * @return string $bookingscount booking(s) made today
		 * @uses
		 */
		public function generate_todaysbookingsdescription() {
			$bookingscount = $this->count_todaysbookings();
			$description = $bookingscount == 1 ? 'booking' : 'bookings';
			return "$bookingscount bookings made today";
		}
		
		public function generate_viewsalesordersbydayurl($date) {
			$url = new Purl\Url($this->pageurl->getUrl());
			$url->path = DplusWire::wire('config')->pages->ajaxload."bookings/sales-orders/";
			$url->query->set('date', $date);
			return $url->getUrl();
		}
		
		public function generate_viewsalesordersbydaylink($date) {
			$bootstrap = new Contento();
			$href = $this->generate_viewsalesordersbydayurl($date);
			$icon = $bootstrap->createicon('glyphicon glyphicon-new-window');
			$ajaxdata = "data-modal=$this->modal";
			return $bootstrap->openandclose('a', "href=$href|class=load-into-modal btn btn-primary btn-sm|$ajaxdata", "$icon View Sales Orders");
		}
	}
