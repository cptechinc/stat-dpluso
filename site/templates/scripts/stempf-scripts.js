var loadingwheel = "<div class='la-ball-spin la-light la-3x'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>";
var darkloadingwheel = "<div class='la-ball-spin la-dark la-3x'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>";
var togglearray = {Y: 'on', N: 'off'};
var listener = new window.keypress.Listener();

$(document).ready(function() {
	// LISTENER
	$('input[type=text]').bind("focus", function() { listener.stop_listening(); }).bind("blur", function() { listener.listen(); });

	/*==============================================================
	   INITIALIZE BOOTSTRAP FUNCTIONS
	=============================================================*/
		$('body').popover({selector: '[data-toggle="popover"]', placement: 'top'});

		init_datepicker();
		init_bootstraptoggle();

		$('.phone-input').keyup(function() {
	    	$(this).val(formatphone($(this).val()));
	    });

		$('body').on('click', function (e) {
			$('[data-toggle="popover"]').each(function () {
				//the 'is' for buttons that trigger popups
				//the 'has' for icons within a button that triggers a popup
				if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
					$(this).popover('hide');
				}
			});
		});

		$(config.modals.lightbox).on('show.bs.modal', function (event) {
			var image = $(event.relatedTarget).find('img');
			var source = image.attr('src');
			var desc = image.data('desc');
			var modal = $(this);
			modal.find('.lightbox-image').attr('src', source);
			modal.find('.description').text(desc);
		});

		$(config.modals.ajax).on('shown.bs.modal', function (event) {
			init_datepicker();
		});

		$(config.modals.pricing).on('shown.bs.modal', function (event) { // DEPRECATED 8/22/2017 DELETE ON 9/1
			init_datepicker();
			init_bootstraptoggle();
		});

		$(config.modals.lightbox).on('shown.bs.modal', function (event) {
			makeshadow();
		});
		$(config.modals.lightbox).on('hide.bs.modal', function (event) {
			removeshadow();
		});

	/*==============================================================
	   PAGE SCROLLING FUNCTIONS
	=============================================================*/
		$(window).scroll(function() {
			if ($(this).scrollTop() > 50) { $('#back-to-top').fadeIn(); } else { $('#back-to-top').fadeOut(); }
		});

		// scroll body to 0px on click
	   $('#back-to-top').click(function () {
		   $('#back-to-top').tooltip('hide');
		   $('body,html').animate({ scrollTop: 0 }, 800);
		   return false;
	   });

	/*==============================================================
	   YOUTUBE NAVIGATION
	=============================================================*/
		$('.yt-menu-open').on('click', function(e) { //Youtube-esque navigation
			e.preventDefault();
			$('#yt-menu').toggle();
			$(this).toggleClass('menu-open');
			if ($(this).hasClass('menu-open')) {
				$(this).css({"background-color":"#242F40", "color": "#f8f8f8"});
			} else {
				$(this).removeClass('menu-open').css({"background-color":"", "color": ""});
			}
		});

		$('.slide-menu-open').on('click', function(e) { //Youtube-esque navigation
			e.preventDefault();
			$('#slide-menu').toggle().animatecss('fadeInLeft');
			$(this).toggleClass('menu-open');
			if ($(this).data('function') === 'show') {
				$(this).data('function', "hide").css({"background-color":"#242F40", "color": "#f8f8f8"});
			} else {
				$(this).data('function', "show").removeClass('menu-open').css({"background-color":"", "color": ""});
			}
		});

		$(config.toolbar.button).click(function() {
			if ($(config.toolbar.toolbar).is(":hidden")) {
				showtoolbar();
			} else {
				hidetoolbar();
			}
		});

		$(document).mouseup(function (e) {
			var container = $("#yt-menu");
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				 $('#yt-menu').hide();
				 $('.yt-menu-open').data('function', "show").removeClass('menu-open').css({"background-color":"", "color": ""});
			}
		});

	/*==============================================================
	  FORM FUNCTIONS
	=============================================================*/
		$("body").on("click", ".dropdown-menu .searchfilter", function(e) {
			e.preventDefault();
			var inputgroup = $(this).closest('.input-group');
			var param = $(this).attr("href").replace("#","");
			var concept = $(this).text();
			inputgroup.find('span.showfilter').text(concept);
			inputgroup.find('.search_param').val(param);
		});

		$("body").on("click", ".select-button-choice", function(e) {
			e.preventDefault();
			var tasktype = $(this).data('value');
			$(".select-button-choice").removeClass("btn-primary");
			$(this).parent().find('.select-button-value').val(tasktype);
			$(this).addClass("btn-primary");
		});

		$(".page").on("change", ".results-per-page-form .results-per-page", function() {
			var form = $(this).closest("form");
			var ajax = form.hasClass('ajax-load');
			var href = getpaginationurl(form);
			if (ajax) {
				var loadinto = form.data('loadinto');
				var focuson = form.data('focus');
				loadin(href, loadinto, function() {
					if (focuson.length > 0) { $('html, body').animate({scrollTop: $(focuson).offset().top - 60}, 1000);}
				});
			} else {
				window.location.href = href;
			}
		});

	/*==============================================================
	  AJAX LOAD FUNCTIONS
	=============================================================*/
		$("body").on("click", ".load-link", function(e) {
			e.preventDefault();
			var loadinto = $(this).data('loadinto');
			var focuson = $(this).data('focus');
			var href = $(this).attr('href');
			$(loadinto).loadin(href, function() {
				if (focuson.length > 0) {
					$('html, body').animate({scrollTop: $(focuson).offset().top - 60}, 1000);
				}
				init_bootstraptoggle();
			});
		});

		$("body").on("click", ".load-into-modal", function(e) {
			e.preventDefault();
			var button = $(this);
			var ajaxloader = new ajaxloadedmodal(button);
			$(this).closest('.modal').modal('hide');
			ajaxloader.url = URI(ajaxloader.url).addQuery('modal', 'modal').normalizeQuery().toString();

			$(ajaxloader.loadinto).loadin(ajaxloader.url, function() {
				$(ajaxloader.modal).resizemodal(ajaxloader.modalsize).modal();
			});
		});

		$("body").on("click", ".generate-load-link", function(e) { //MADE TO REPLACE $(.load-detail).click()
			e.preventDefault();
			var loadinto = $(this).data('loadinto');
			var focuson = $(this).data('focus');
			var geturl = $(this).attr('href');
			showajaxloading();
			dplusrequesturl(geturl, function(url) {
				$(loadinto).loadin(url, function() {
					hideajaxloading();
					if (focuson.length > 0) { $('html, body').animate({scrollTop: $(focuson).offset().top - 60}, 1000); }
				});
			});
		});

		$("body").on("click", ".load-notes", function(e) {
		    e.preventDefault();
		    var button = $(this);
		    var ajaxloader = new ajaxloadedmodal(button);
			showajaxloading();
			dplusrequesturl(ajaxloader.url, function(url) {
				wait(500, function() {
					$(ajaxloader.loadinto).loadin(url, function() {
						$(ajaxloader.modal).resizemodal('lg').modal(); hideajaxloading();
					});
				});
			});
		});

	/*==============================================================
	  ORDER LIST FUNCTIONS
	=============================================================*/
		$(".page").on("click", ".edit-order", function(e) {
			e.preventDefault();
			var href = $(this).attr('href');
			dplusrequesturl(href, function(url) {
				window.location.href = url;
			});
		});

		$(".page").on("click", ".load-cust-orders", function(event) { //Changed from #load-cust-orders
			event.preventDefault();
			var loadinto = $(this).data('loadinto');
			var geturl = $(this).attr('href');
			var focuson = $(this).data('focus');
			dplusrequesturl(geturl, function(url) {
				$(loadinto).loadin(url, function() {
					if (focuson.length > 0) { $('html, body').animate({scrollTop: $(focuson).offset().top - 60}, 1000); }
				});
			});
		});

		$("body").on("click", ".search-orders", function(e) {
			e.preventDefault();
			console.log('clicked');
			var button = $(this);
			var ajaxloader = new ajaxloadedmodal(button);
			$(ajaxloader.loadinto).loadin(ajaxloader.url, function() {
				 $(ajaxloader.modal).modal();
			});
		});

		$("body").on("submit", "#order-search-form", function(e)  { //FIXME
			e.preventDefault();
			var form = "#"+$(this).attr('id');
			var loadinto = $(this).data('loadinto');
			var focuson = $(this).data('focus');
			var modal = $(this).data('modal');
			$(form).postform({formdata: false, jsoncallback: false}, function() { //form, overwriteformdata, returnjson, callback
				wait(500, function() {
					generateurl(function(url) {
						$(loadinto).loadin(url, function() {
							$(modal).modal('hide');
							if (focuson.length > 0) {
								$('html, body').animate({scrollTop: $(focuson).offset().top - 60}, 1000);
							}
						});
					 });
				});
			});
		});

		$("body").on("submit", ".item-reorder", function(e) {
			e.preventDefault();
			var form = new itemform($(this));
			var msg = "You added " + form.qty + " of " + form.desc + " to the cart";
			$(form.formID).postformsync({formdata: false, jsoncallback: false}, function() {
				$.notify({
					icon: "glyphicon glyphicon-shopping-cart",
					message: msg +"<br> (Click this Message to go to the cart.)" ,
					url: config.urls.cart,
					target: '_self'
				},{
					type: "success",
					url_target: '_self'
				});
			});
		});

		$("body").on("click", ".view-item-details", function(e) {
			e.preventDefault();
			var button = $(this);
			var ajaxloader = new ajaxloadedmodal(button);
			if (button.data('kit') == 'Y') {
				var itemID = button.data('itemid');
				var qty = 1;
				showajaxloading();
				ii_kitcomponents(itemID, qty, function() {
					$(ajaxloader.loadinto).loadin(ajaxloader.url, function() {
						hideajaxloading();
						$(ajaxloader.modal).resizemodal('lg').modal();
					});
				});
			} else {
				$(ajaxloader.loadinto).loadin(ajaxloader.url, function() {
					$(ajaxloader.modal).resizemodal('lg').modal();
				});
			}
		});

	/*==============================================================
	  ADD ITEM MODAL FUNCTIONS
	=============================================================*/
		$("body").on("submit", ".add-and-edit-form", function(e) { //FIX MAKE IT JUST AJAX ADD ALSO FIX REGULAR ADD ITEM
			//WAS .add-to-order-form | MODIFIED TO SUIT BOTH QUOTES AND ORDERS
			e.preventDefault();
			var form = $(this);
			var addto = form.data('addto');
			var itemID = form.find('input[name="itemID"]').val();
			var custID = form.find('input[name="custID"]').val();
			var loadinto = config.modals.ajax+" .modal-content";
			var parentmodal = $(this).closest('.modal').modal('hide');
			var editurl = '';
			var jsonurl = form.find('input[name="jsondetailspage"]').val();
			var pageurl = new URI().addQuery('show', 'details').hash('#edit-page').toString();+
			showajaxloading();

			$('#'+form.attr('id')).postform({formdata: false, jsoncallback: false}, function() { //{formdata: data/false, jsoncallback: true/false}
				wait(500, function() {
					$.getJSON(jsonurl, function(json) {
						console.log(jsonurl);
						if (addto === 'order') {
							linenumber = json.response.orderdetails.length;
						} else if (addto === 'quote') {
							linenumber = json.response.quotedetails.length;
						}
						editurl = URI(json.response.editurl).addQuery('line', linenumber).addQuery('modal', 'modal').normalizeQuery().toString();
						$('.page').loadin(pageurl, function() {
							edititempricing(itemID, custID,  function() {
								$(loadinto).loadin(editurl, function() {
									hideajaxloading();
									$(config.modals.ajax).resizemodal('xl').modal();
									setchildheightequaltoparent('.row.row-bordered', '.grid-item');
								});
							});
						});
					});
				});
			});
		});

		$('#add-item-modal').on('show.bs.modal', function(event) {
			var button = $(event.relatedTarget);
			var addtype = button.data('addtype'); // order|cart|quote
			var modal = $(this);
			var title = '';
			var resultsurl = URI(button.data('resultsurl')).toString();
			var querystring = URI.parseQuery(URI(resultsurl).search());
			var custID = querystring.custID;
			var shipID = querystring.shipID;
			var addnonstockURI = URI(modal.find('.nonstock-btn').attr('href')).addQuery('custID', custID).addQuery('shipID', shipID);
			var addmultipleURI = URI(modal.find('.add-multiple-items').attr('href')).addQuery('custID', custID).addQuery('shipID', shipID);

			if (addnonstockURI.segment(-2) == addtype) {
				addnonstockURI.segment(-2, "");
				addmultipleURI.segment(-2, "");
			}

			switch (addtype) {
				case 'cart':
					$('#'+modal.attr('id')+ " .custID").val(custID);
					title = "Add item to Cart";
					addnonstockURI.segment('cart');
					addmultipleURI.segment('cart');
					break;
				case 'order':
					var ordn = querystring.ordn;
					$('#'+modal.attr('id')+ " .custID").val(custID);
					title = "Add item to Order #" + ordn;
					addnonstockURI.addQuery('ordn', ordn);
					addnonstockURI.segment('order');
					addmultipleURI.addQuery('ordn', ordn);
					addmultipleURI.segment('order');
					break;
				case 'quote':
					var qnbr = querystring.qnbr;
					$('#'+modal.attr('id')+ " .custID").val(custID);
					title = "Add item to Quote #" + qnbr;
					addnonstockURI.addQuery('qnbr', qnbr);
					addnonstockURI.segment('quote');
					addmultipleURI.addQuery('qnbr', qnbr);
					addmultipleURI.segment('quote');
					break;
			}
			addnonstockURI.segment('');
			addnonstockURI.addQuery('modal', 'modal');
			addmultipleURI.segment('');
			addmultipleURI.addQuery('modal', 'modal');
			$('#add-item-modal-label').text(title);
			$('#add-item-modal .nonstock-btn').attr('href', addnonstockURI.toString());
			$('#add-item-modal .add-multiple-items').attr('href', addmultipleURI.toString());
			$('#'+modal.attr('id')+ " .resultsurl").val(resultsurl);
		});

		$('body').on('submit', '#add-multiple-item-form', function(e) {
            if ($(this).attr('data-checked') == 'true') {
                $(this).submit();
            } else {
                e.preventDefault();
                $(this).validateitemids();
                var invaliditemcount = $(this).find('.form-group.has-error').length;
                if (!invaliditemcount) {
                    $(this).submit();
                }
            }
        });

		$('#add-item-modal').on('shown.bs.modal', function() {
			$('#add-item-modal .searchfield').focus();
		});

		$("body").on("submit", "#add-item-search-form", function(e) {
			e.preventDefault();
			var formid = '#'+$(this).attr('id');
			var resultsurl = $(formid+ " .resultsurl").val();
			var addonurl = $(formid+ " .addonurl").val();
			var loadinto = '#' + $(this).closest('.modal').attr('id') + ' .results';
			$(loadinto).empty();
			var loadingdiv = "<div class='loading-item-results'>"+darkloadingwheel+"</div>";
			$(loadingdiv).appendTo(loadinto);

			$(formid).postformsync({formdata: false, jsoncallback: false}, function() { //{formdata: data/false, jsoncallback: true/false}

				$(loadinto).loadin(resultsurl, function() {

				});
			});
		});

		/*==============================================================
		 CI/II FUNCTIONS
		=============================================================*/
		$("body").on("keyup", ".ii-item-search", function() {
			var thisform = $(this).closest('form');
			var href = thisform.attr('action')+"?q="+urlencode($(this).val());
			var loadinto = '#item-results';
			$(loadinto).loadin(href, function() { });
		});

		$("body").on("submit", "#ci-search-item", function(e) {
			e.preventDefault();
		});

		$("body").on("keyup", ".ci-item-search", function() {
			var input = $(this);
			var thisform = input.parent('form');
			var custID = thisform.find('input[name=custID]').val();
			var shipID = thisform.find('input[name=shipID]').val();
			var action = thisform.find('input[name=action]').val();
			var href  = URI(thisform.attr('action')).addQuery('q', urlencode(input.val()))
												   .addQuery('custID', urlencode(custID))
												   .addQuery('shipID', urlencode(shipID))
												   .addQuery('action', urlencode(action))
												   .toString();
			var loadinto = '#item-results';
			$(loadinto).loadin(href, function() { });
		});

		$("body").on("keyup", ".ci-history-item-search", function() {
				var input = $(this);
				var thisform = input.closest('form');
				var custID = thisform.find('input[name=custID]').val();
				var shipID = thisform.find('input[name=shipID]').val();
				var action = thisform.find('input[name=action]').val();
				var loadinto = '#item-results';
				var href  = URI(input.data('results')).addQuery('q', urlencode(input.val()))
													   .addQuery('custID', urlencode(custID))
													   .addQuery('shipID', urlencode(shipID))
													   .addQuery('action', urlencode(action))
													   .toString();
				$(loadinto).loadin(href, function() { });
		});

		$("body").on("submit", "#cust-index-search-form", function(e) {
			e.preventDefault();
		});

		$("body").on("keyup", ".cust-index-search", function() {
				var thisform = $(this).closest('form');
				var pagefunction = thisform.find('[name=function]').val();
				var loadinto = '#cust-results';
				var href = URI(thisform.attr('action')).addQuery('q', $(this).val())
													   .addQuery('function', pagefunction)
													   .toString();
				$(loadinto).loadin(href+' '+loadinto, function() { });
		});

		$('body').on('click', '.load-doc', function(e) {
			e.preventDefault();
			var button = $(this);
			var doc = button.data('doc');
			var href = config.urls.json.ii_moveitemdoc + "?docnumber="+doc;

			$.getJSON(href, function(json) {
				if (!json.response.error) {
					var td = button.parent();
					td.find('.load-doc').remove();
					var href = "<a href='"+config.urls.orderfiles+json.response.file+"' class='btn btn-sm btn-success' target='_blank'><i class='fa fa-file-text' aria-hidden='true'></i> View Document</a>";
					$(href).appendTo(td);
				}
			});
		});


		/*==============================================================
		  ACTION FUNCTIONS
		=============================================================*/
		$("body").on("change", "#actions-panel .change-action-type, #actions-modal-panel .change-action-type", function() {
			var select = $(this);
			var actiontype = select.val();
			var regexhref = select.data('link');
			var href = regexhref.replace(/{replace}/g, actiontype);
			var loadinto = $(this).data('loadinto');
			var focuson = $(this).data('focus');
			$(loadinto).loadin(href, function() { });
		});

		$("body").on("change", "#actions-panel .change-actions-user, #actions-modal-panel .change-actions-user", function() {
			var select = $(this);
			var userID = select.val();
			var href = URI(select.data('link')).addQuery('assignedto', userID).toString();
			var loadinto = $(this).data('loadinto');
			var focuson = $(this).data('focus');
			showajaxloading();
			$(loadinto).loadin(href, function() {
				hideajaxloading();
			});
		});

		$("body").on("change", "#view-action-task-status", function(e) {
			e.preventDefault();
			var status = $(this).val();
			var loadinto = $(this).data('loadinto');
			var focuson = $(this).data('focus');
			var href = URI($(this).data('url')).addQuery('tasks-status', status).toString();
			loadin(href, loadinto, function() { //ON PURPOSE to know it was reloaded
				if (focuson.length > 0) { $('html, body').animate({scrollTop: $(focuson).offset().top - 60}, 1000); }
				init_bootstraptoggle();
			});
		});

		$("body").on("click", ".add-action", function(e) {
			e.preventDefault();
			var button = $(this);
			swal({
				title: 'What type of Action would you like to make?',
				input: 'select',
				buttonsStyling: false,
				type: 'question',
				confirmButtonClass: 'btn btn-sm btn-success',
				cancelButtonClass: 'btn btn-sm btn-danger',
				inputClass: 'form-control',
				inputOptions: {
					'tasks': 'Task',
					'notes': 'Note',
					'actions': 'Action'
				},
				inputPlaceholder: 'Select Action Type',
				showCancelButton: true,
				inputValidator: function (value) {
			    return new Promise(function (resolve, reject) {
			      if (value.length) {
			        resolve();
			      } else {
			        reject('You need to select an Action Type')
			      }
			    })
			  }
			}).then(function (result) {
				var regexhref = button.attr('href');
				var href = URI(regexhref.replace(/{replace}/g, result)).addQuery('modal', 'modal').toString();
				var modal = button.data('modal');
				var loadinto =  modal+" .modal-content";
				$(loadinto).loadin(href, function() {
					$(modal).resizemodal('lg').modal();
				});
			}).catch(swal.noop);
		});

		$("body").on("click", ".load-action", function(e) {
			e.preventDefault();
			var button = $(this);
			var ajaxloader = new ajaxloadedmodal(button);
			showajaxloading();
			ajaxloader.modal = config.modals.ajax;
			wait(500, function() {
				$(ajaxloader.loadinto).loadin(ajaxloader.url, function() {
					hideajaxloading();
					$(ajaxloader.modal).resizemodal('lg').modal();
				});
			});
		});

		$("body").on("click", ".complete-action", function(e) {
			e.preventDefault();
			var button = $(this);
			var url = button.attr('href');
			var taskid = button.data('id');
			$.getJSON(url, function(json) {
				if (json.response.error) {
					swal({
						title: 'Error',
						text: json.response.message,
						type: 'error',
					}).catch(swal.noop);
				} else {
					button.closest('.modal').modal('hide');
					swal({
						title: 'Confirm task as complete?',
						html:
							'<b>ID:</b> ' + json.response.action.id + '<br>' +
							'<b>description:</b> ' + json.response.action.textbody,
						type: 'question',
						showCancelButton: true,
						confirmButtonText: 'Confirm as complete'
					}).then(function() {
						swal({
						  title: "Leave Reflection Note?",
						  text: "Enter note or leave blank",
						  input: 'textarea',
						  showCancelButton: true
						}).then(function (text) {
						  if (text) {
						    $.post(json.response.action.urls.completion, {reflectnote: text})
								.done(function(json) {
									console.log(json.sql);
									$('.actions-refresh').click(); $(config.modals.ajax).modal('hide');
								});
						} else {
							$.get(json.response.action.urls.completion, function() { $('.actions-refresh').click(); $(config.modals.ajax).modal('hide'); });
						}
						swal.close();
						}).catch(swal.noop);
					}).catch(swal.noop); //FOR CANCEL
				}
			});
		});

		$("body").on("click", ".reschedule-task", function(e) {
	        e.preventDefault();
	        var button = $(this);
	        var url = button.attr('href');
	        var modal = config.modals.ajax;
	        var loadinto = config.modals.ajax+" .modal-content";
	        $(loadinto).loadin(url, function() {
	            $(modal).resizemodal('lg').modal();
	        });
	    });

		$("body").on("change", "#view-action-completion-status", function(e) {
			e.preventDefault();
			var select = $(this);
			var url = select.data('url');
			var completionstatus = select.val();
			var loadinto = select.data('loadinto');
			var focuson = select.data('focuson');
			var href = URI(url).addQuery('action-status', completionstatus).toString();
			$(loadinto).loadin(href, function() { });
		});

		$("body").on("submit", "#new-action-form", function(e) {
			e.preventDefault();
			var form = $(this);
			var modal = form.data('modal');
			var formid = "#"+$(this).attr('id');
			var action = form.attr('action');
			var elementreload = form.data('refresh');
			var isformcomplete = form.formiscomplete('tr');
			if (isformcomplete) {
				$(formid).postform({formdata: false, jsoncallback: true}, function(json) {
					$.notify({
						icon: json.response.icon,
						message: json.response.message,
					},{
						element: modal + " .modal-body",
						type: json.response.notifytype,
						placement: {
							from: "top",
							align: "center"
						},
						onClose: function() {
							wait(500, function() {
								$(elementreload + " .actions-refresh").click();
								$(modal).modal('hide');
								/* swal({
									title: "Your "+json.response.actiontype+" was created!",
									text: "Would you like to create an action for this "+json.response.actiontype+"?",
									type: "success",
									showCancelButton: true,
									confirmButtonText: "Yes, Create Action",
								}).then(function () {
									swal.close();
									var href = new URI($('#actions-panel .add-action').attr('href')).addQuery('actionID', json.response.actionid).toString();
									console.log(href);
									$('#actions-panel .add-action').attr('href', href).click();
									href = URI(href).removeQuery('actionID').toString();
									$('#actions-panel .add-action').attr('href', href);
								}).catch(swal.noop); */
							});
						}
					});
				});
			}
		});

		$('body').on("change", ".change-assignedto", function() {
			var select = $(this);
			$('#new-action-form').find('input[name="assignedto"]').val(select.val());
		});

	/*==============================================================
 		EDIT LINE ITEM FUNCTIONS
	=============================================================*/
		$(".page").on("click", ".update-line", function(e) {
			e.preventDefault();
			showajaxloading();
			var url = URI($(this).attr('href')).addQuery('modal', 'modal').toString();
			var itemID = $(this).data('itemid');
			var custID = $(this).data('custid');
			var modal = config.modals.ajax;
			var loadinto = modal + " .modal-content";
			if ($.inArray(itemID, nonstockitems) > -1) {
				console.log('skipping item get');
				$(loadinto).loadin(url, function() {
					hideajaxloading();
					$(modal).resizemodal('xl').modal();
				});
			} else {
				edititempricing(itemID, custID,  function() {
					$(loadinto).loadin(url, function() {
						hideajaxloading();
						$(modal).resizemodal('xl').modal();
						setchildheightequaltoparent('.row.row-bordered', '.grid-item');
						$('.item-form').height($('.item-information').actual('height'));
					});
				});
			}
		});
});

/*==============================================================
 	AJAX FUNCTIONS
=============================================================*/
	function wait(time, callback) {
		var timeoutID = window.setTimeout(callback, time);
	}

	function dplusrequesturl(geturl, callback) {
		$.get(geturl, function() {
			generateurl(function(url) {
				callback(url);
			});
		});
	}

	function generateurl(callback) {
		console.log(config.urls.json.getloadurl);
		$.getJSON(config.urls.json.getloadurl, function(json) {
			callback(json.response.url);
		});
	}

 	function showajaxloading() {
		var close = makeajaxclose("hideajaxloading()");
		var loadingdiv = "<div class='loading'>"+loadingwheel+"</div>";
		$("<div class='modal-backdrop tribute loading-bkgd fade in'></div>").html(close+loadingdiv).appendTo('body');
		listener.simple_combo("esc", function() { hideajaxloading(); });
	}

	function hideajaxloading() {
		$('body').find('.loading-bkgd').remove();
		listener.unregister_combo("esc");
	}

	function makeshadow() {
		$('body').find('.modal-backdrop').addClass('darkAmber').removeClass(config.modals.gradients.default).css({'z-index':'20'});;
	}

	function removeshadow() {
		$('body').find('.modal-backdrop').addClass(config.modals.gradients.default).removeClass('darkAmber').css({'z-index':'15'});;
	}

	function loadin(url, element, callback) {
		var parent = $(element).parent();
		$(element).remove();
		parent.load(url, function() { callback(); });
	}

	(function ( $ ) {
		// Pass an object of key/vals to override
		$.fn.serializeform = function(overrides) {
			// Get the parameters as an array
			var newParams = this.serializeArray();

			for(var key in overrides) {
				var newVal = overrides[key]
				// Find and replace `content` if there
				for (index = 0; index < newParams.length; ++index) {
					if (newParams[index].name == key) {
						newParams[index].value = newVal;
						break;
					}
				}

				// Add it if it wasn't there
				if (index >= newParams.length) {
					newParams.push({
						name: key,
						value: newVal
					});
				}
			}

			// Convert to URL-encoded string
			return $.param(newParams);
		}
	}( jQuery ));

	$.fn.extend({
		postform: function(options, callback) { //{formdata: data/false, jsoncallback: true/false}
			var form = $(this);
			var action = form.attr('action');
			console.log('submitting ' + form.attr('id'));
			if (!options.formdata) {options.formdata = form.serialize(); }
			if (options.jsoncallback) {
				$.post(action, options.formdata, function(json) {callback(json);});
			} else {
				$.post(action, options.formdata).done(callback());
			}
		},
		postformsync: function(options, callback) {
			var form = $(this);
			var action = form.attr('action');

			if (!options.formdata) {options.formdata = form.serialize(); }
			if (options.jsoncallback) {
				$.ajax({async: false, url: action, method: "POST", data: options.formdata}).done(callback(json));
			} else {
				$.ajax({async: false, url: action, method: "POST", data: options.formdata}).done(callback());
			}
		},
		loadin: function(href, callback) {
			var element = $(this);
			var parent = element.parent();
			console.log('loading ' + element.returnelementdescription() + " from " + href);
			parent.load(href, function() { callback(); });
		},
		returnelementdescription: function() {
			var element = $(this);
			var tag = element[0].tagName.toLowerCase();
			var classes = '';
			var id = '';
			if (element.attr('class')) {
				classes = element.attr('class').replace(' ', '.');
			}
			if (element.attr('id')) {
				id = element.attr('id');
			}
			var string = tag;
			if (classes) {
				if (classes.length) {
					string += '.'+classes;
				}
			}
			if (id) {
				if (id.length) {
					string += '#'+id;
				}
			}
			return string;
		},
		validateitemids: function() {
            var custID = $(this).find('input[name="custID"]').val();
            var valid = true;
            $(this).find('input[name="itemID[]"]').each(function() {
                var field = $(this);
                var itemID = $(this).val();
                var href = URI(config.urls.json.validateitemid).addQuery('itemID', itemID).addQuery('custID', custID).toString();
                $.getJSON(href, function(json) {
                    if (json.error) {
                        alert();
                    } else {
                        if (!json.itemexists) {
                            valid = false;
                            field.parent().addClass('has-error');
                        }
                    }
                });
            });

            $(this).attr('data-checked', 'true');
            var invaliditemcount = $(this).find('form-group.has-error').length;
            if (invaliditemcount) {
                $(this).find('.response').createalertpanel('Double Check your itemIDs', '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>', 'warning');
            }
        }
	});

	function getpaginationurl(form) {
		var showonpage = form.find('.results-per-page').val();
		var displaypage = form.attr('action');
		return URI(displaypage).addQuery('display', showonpage).toString();
	}

/*==============================================================
 	TOOLBAR FUNCTIONS
=============================================================*/
	function showtoolbar() {
		var close = makeajaxclose("hidetoolbar()");
		$("<div class='modal-backdrop toolbar fade in'></div>").html(close).appendTo('body');
		$(config.toolbar.toolbar).removeClass('zoomOut').show().animatecss('bounceInLeft');
		$(config.toolbar.button).find('span').removeClass('glyphicon glyphicon-plus').addClass('glyphicon glyphicon-minus');
	}

	function hidetoolbar() {
		$('body').find('.modal-backdrop.toolbar.fade.in').remove();
		$(config.toolbar.toolbar).removeClass('bounceInLeft').hide(1000).animatecss('zoomOut');
		$(config.toolbar.button).find('span').removeClass('glyphicon glyphicon-minus').addClass('glyphicon glyphicon-plus');
	}

/*==============================================================
 	URL FUNCTIONS
=============================================================*/
	function urlencode(str) {
		return encodeURIComponent(str);
	}

	var cleanparams = function(data) {
		var result = {};
		Object.keys(data).filter(function(key) {
			return Boolean(data[key]) && data[key].length;
		}).forEach(function(key) {
			result[key] = data[key];
		});
		return result;
	};


/*==============================================================
 	CUST INDEX FUNCTIONS
=============================================================*/
	function pickcustomer(custID, sourcepage) {
		var loadinto = config.modals.ajax + ' .modal-content';
		var url = URI(config.urls.customer.load.loadindex).addQuery('custID', custID).addQuery('source', sourcepage).toString();
        $(loadinto).loadin(url, function() {  });
    }

/*==============================================================
   ITEM FUNCTIONS
=============================================================*/
	function chooseitemwhse(itemID, whse) { // TODO
		var form = '#'+itemID+"-form";
		var whsefield = '.'+itemID+'-whse';
		var whserow = '.'+whse+"-row";
		$(form+" .item-whse-val").text(whse).parent().show();
		$(whsefield).val(whse);
		$('.warning').removeClass('warning');
		$(whserow).addClass('warning');
	}

	function edititempricing(itemID, custID, callback) {
		var url = config.urls.products.redir.getitempricing+"&itemID="+urlencode(itemID)+"&custID="+urlencode(custID);
		$.get(url, function() { callback(); });
	}

	 function ii_kitcomponents(itemID, qty, callback) {
 		var url = config.urls.products.redir.ii_kitcomponents+"&itemID="+urlencode(itemID)+"&qty="+urlencode(qty);
 		$.get(url, function() { callback(); });
 	}

/*==============================================================
 	SALES ORDER FUNCTIONS
=============================================================*/
	function reorder() {
		var forms = new Array();
		$(".item-reorder").each(function( index ) {
			if ($(this).find('input[name="qty"]').val().length > 0) {
				forms.push($(this).attr('id'));
			}
		});

		var ajaxcalls = forms.length;
		var counter = 0;
		var ajaxcomplete = function() { counter++; if (counter >= ajaxcalls) {console.log('finished with ajax calls'); } };

		for (var i = 0; i < forms.length; i++) {
			var form = new itemform($("#"+forms[i]));
			$(form.formid).postformsync({formdata: false, jsoncallback: false},function(){
				$.notify({
					// options
					title: '<strong>Success</strong>',
					message: 'You added ' + form.qty + ' of ' + form.itemID + ' to the cart'
				},{
					element: "body",
					type: "warning",
					delay: 2500,
					timer: 1000,
					onClosed: function() {
						ajaxcomplete();
					},
				});
			});
		}
	}

/*==============================================================
 	STRING FUNCTIONS
=============================================================*/
	function getordinalsuffix(i) {
		var j = i % 10, k = i % 100;
		if (j == 1 && k != 11) { return i + "st"; }
		if (j == 2 && k != 12) { return i + "nd"; }
		if (j == 3 && k != 13) { return i + "rd"; }
		return i + "th";
	}

	Number.prototype.formatMoney = function(c, d, t) {
		var n = this,
		    c = isNaN(c = Math.abs(c)) ? 2 : c,
		    d = d == undefined ? "." : d,
		    t = t == undefined ? "," : t,
		    s = n < 0 ? "-" : "",
		    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
		    j = (j = i.length) > 3 ? j % 3 : 0;
		   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	 };

	 function formatphone(input) {
        // Strip all characters from the input except digits
        input = input.replace(/\D/g,'');

        // Trim the remaining input to ten characters, to preserve phone number format
        input = input.substring(0,10);

        // Based upon the length of the string, we add formatting as necessary
        var size = input.length;
        if (size == 0){
            input = input;
        } else if(size < 4){
            input = input;
        } else if(size < 7){
            input = input.substring(0,3)+'-'+input.substring(3,6);
        } else {
            input = input.substring(0,3)+'-'+input.substring(3,6)+'-'+input.substring(6,10);
        }
        return input;
	}

/*==============================================================
 	FORM FUNCTIONS
=============================================================*/
	function comparefieldvalues(field1, field2) {
		if ($(field1).val() == $(field2).val()) { return true; } else { return false; }
	}

	$.fn.extend({
		formiscomplete: function(highightelement) {
			var form = $(this);
			var missingfields = new Array();
			form.find('.has-error').removeClass('has-error');
			form.find('.response').empty();
			form.find('.required').each(function() {
				if ($(this).val() === '') {
					var row = $(this).closest(highightelement);
					row.addClass('has-error');
					missingfields.push(row.find('.control-label').text());
				}
			});
			if (missingfields.length > 0) {
				var message = 'Please Check the following fields: <br>';
				for (var i = 0; i < missingfields.length; i++) {
					message += missingfields[i] + "<br>";
				}
				$('#'+form.attr('id') + ' .response').createalertpanel(message, "<span class='glyphicon glyphicon-warning-sign'></span> Error! ", "danger");
				$('html, body').animate({scrollTop: $('#'+form.attr('id') + ' .response').offset().top - 120}, 1000);
				return false;
			} else {
				return true;
			}
		}
	});



/*==============================================================
 	CONTENT FUNCTIONS
=============================================================*/
	$.fn.extend({
		animatecss: function (animationName) {
			var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
			this.addClass('animated ' + animationName).one(animationEnd, function() {
				$(this).removeClass('animated ' + animationName);
			});
			return $(this);
		},
		resizemodal: function (size) {
			$(this).children('.modal-dialog').removeClass('modal-xl').removeClass('modal-lg').removeClass('modal-sm').removeClass('modal-md').removeClass('modal-xs').addClass('modal-'+size);
			return $(this);
		},
		createalertpanel: function(alert_message, exclamation, alert_type) {
			var alertheader = '<div class="alert alert-'+alert_type+' alert-dismissible" role="alert">';
			var closebutton = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
			var message = '<strong>'+exclamation+'</strong> ' + alert_message
			var closeheader = '</div>';
			var thealert = alertheader + closebutton + message + closeheader;
			$(this).html(thealert);
		}
	});

	function setequalheight(container) {
		var height = 0;
		$(container).each(function() {
			if ($(this).actual( 'height' ) > height) {
				height = $(this).actual( 'height' );
			}
		});
		$(container).height(height);
	}

	function setchildheightequaltoparent(parent, child) {
		$(parent).each(function() {
			var parentheight = $(this).actual('height');
			$(this).find(child).height(parentheight);
		});
	}

	function makeajaxclose(onclick) {
		return '<div class="close"><a href="#" onclick="'+onclick+'"><i class="material-icons md-48 md-light"></i></a></div>';
	}

	function init_datepicker() {
		$('.datepicker').each(function(index) {
			$(this).datepicker({
				date: $(this).find('.date-input').val(),
				allowPastDates: true,
			});
		});
	}

	function init_bootstraptoggle() {
		$('.check-toggle').bootstrapToggle({on: 'Yes', off: 'No', onstyle: 'info'});
	}

	function duplicateitem(list) {
		$('.duplicable-item:last').clone()
                          .find("input:text").val("").end()
                          .appendTo(list);
		$('.duplicable-item:last input:first').focus();
	}
