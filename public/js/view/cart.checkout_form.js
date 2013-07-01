(function() {

	var EL = {};
	var TIMER = {};

	function setPublicKey() {
		Stripe.setPublishableKey(EL.stripeData.data('pub-key'));	
	}

	function stripeResponseHandler(status, response) {
		var submit = $('button[type="submit"]', EL.form);
		var payErrors = $('.payment-errors', EL.payForm);
		var payErrorsAlert = payErrors.closest('.alert');
		if (response.error) {
			payErrors.text(response.error.message);
			submit.prop('disabled', false);
			payErrorsAlert.removeClass('hide');
		} else {
			payErrorsAlert.addClass('hide');
			var token = $('<input type="hidden" name="stripe_token" value="'+ response.id +'" />');
			EL.form.append(token);
			EL.form.get(0).submit();
		}
	}
 
	function handlePaymentSubmit(e) {
		$('button[type="submit"]', EL.form).prop('disabled', true);

		Stripe.createToken({
			number: $('#cart-cart').val(),
			cvc: $('#cart-cvc').val(),
			exp_month: $('#cart-exp-month').val(),
			exp_year: $('#cart-exp-year').val()
		}, stripeResponseHandler);

		return false;
	}

	//function handleQuantity(e) {
		//if (TIMER.quantity) clearTimeout(TIMER.quantity);
		//TIMER.quantity = setTimeout(function() {
			//var el = $(e.currentTarget);
			//var tr = el.closest('tr');
			//var total = $('td.total', tr);
			//var val = parseInt($.trim(el.val()), 10);			
			//el.val(isNaN(val) ? 0 : val);

			//var oldTotal = total.html();
			//total.html('...');
			//$.post('/bookbag/quantity', {
				//id: tr.data('id'),
				//val: val
			//}, function(json) {
				//if (!json || !json.success) {
					//total.html(oldTotal);		
					//return;
				//}

				//total.fadeOut(50, function() {
					//total.html('$'+json.total)
						//.fadeIn(50);
					//updateTotals();
				//})
			//}, 'json');
		//}, 500);
	//}

	//function updateTotals() {
		//var total = 0.00;
		//$('td.total', EL.bookbag).each(function(n, el) {
			//var val = $(el).html().replace(/[^0-9\.]+/, '');
			//total += parseFloat(val);
		//});
		//if (total) {
			//showForm();
			//var ship = EL.ship.html().replace(/[^0-9\.]+/, '');
			//total += parseFloat(ship);
		//} else {
			//hideForm();
		//}
		//EL.gt.fadeOut(50, function() {
			//EL.gt.html('$'+ total.toFixed(2))
				//.fadeIn(50);
		//});
	//}

	//function hideForm() {
		//$('#no-data').slideDown(100);
		//EL.ship.css('text-decoration', 'line-through');
		//EL.form.slideUp(100);
	//}

	//function showForm() {
		//$('#no-data').slideUp(100);
		//EL.ship.css('text-decoration', 'none');
		//EL.form.slideDown(100);
	//}

	function onlyNumbers(e) {
		var el = e.currentTarget;
		var out = el.value.replace(/[^0-9]+/, '');
		el.value = out;
	}

	//function handleRemove(e) {
		//e.preventDefault();
		//var el = $(e.currentTarget);	
		//var tr = el.closest('tr');
		//var api = el.attr('href');
		//$.get(api, function(json) {
			//if (json && json.success) {
				//tr.remove();
				//updateTotals();
			//}
		//}, 'json');
	//}

	function addEventListeners() {
		EL.form.on('submit', handlePaymentSubmit);
		//EL.bookbag.on('keyup', '.quantity input', handleQuantity); 
		//EL.bookbag.on('click', '.action-col .remove', handleRemove);
		EL.card.on('keyup', onlyNumbers);
		EL.cvc.on('keyup', onlyNumbers);
	}

	function init() {
		EL.form       = $('#checkout-cart');
		EL.stripeData = $('#stripe-data');
		EL.address    = $('#cart-address');
		EL.card       = $('#cart-card');
		EL.cvc        = $('#cart-cvc');
		//EL.gt         = $('#grand-total');
		//EL.ship       = $('#shipping');
		addEventListeners();

		if (typeof Stripe === 'undefined') {
			setTimeout(function() {
				setPublicKey();
			}, 1000);
		} else {
			setPublicKey();	
		}
	}

	$(init);
}());
