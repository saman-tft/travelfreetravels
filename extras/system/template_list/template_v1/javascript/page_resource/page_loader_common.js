$(document).ready(function () {
	function enable_validation() {
		$('[type="submit"]').on('click', function (event) {
			var current_form = $(this).closest('form');
			var failure_count = 0;
			if (current_form) {
				if ((current_form.attr('novalidate') == undefined || current_form.attr('novalidate') == "") && (current_form.attr('disabled') == undefined || current_form.attr('disabled') != 'disabled')) {
					$('input:not(:disabled), select:not(:disabled), textarea:not(:disabled)', current_form).filter(':not([type=submit], [type=button], [type=reset], button)').each(function () {
						if ($(this).attr('required') || $(this).val().trim() != '') {
							if (core_validate(this) == false) {
								failure_count++;
							}
						}
					});
				}
			}
			if (failure_count > 0) {
				event.preventDefault();
				// $('input[type="text"].invalid-ip:visible').first().focus();
			}
		});
	}
	enable_validation();
	//change keyup blur
	$('input:not(:disabled), select:not(:disabled), textarea:not(:disabled)').filter(':not([type=submit], [type=button], [type=reset], button)').on('change textInput input', function () {
		core_validate(this);
	});

	function core_validate(current_element) {
		// alert();
		var _status = true;
		switch (current_element.type) {
			case 'text':
			case 'number':
			case 'email':
			case 'password':
			case 'date':
			case 'textarea':
			case 'file':
			case 'select-one':
			case 'radio':
			case 'checkbox':
				if (current_element.id) {
					var dt = $('#' + current_element.id).attr("DT");
					var regExp = '';
					_status = checkBlank(current_element, regExp);
				}
				break;
		}
		return _status;
	}

	function checkBlank(currElement, regexExp) {

		var _status = true;
		if (currElement.type == 'radio' || currElement.type == 'checkbox') {
			if ($('input[id=' + currElement.id + ']:checked').length <= 0) {
				$(currElement).addClass("invalid-ip");
			} else {
				$(currElement).removeClass("invalid-ip");
			}
		} else {
			// console.log(currElement.value.trim());
			if ($(currElement).attr('required') && (currElement.value.trim() == '' || currElement.value == 'undefined' || (currElement.value == 'INVALIDIP' && currElement.type == 'select-one'))) {
				$(currElement).addClass("invalid-ip");
				// console.log("ttt2");
				_status = false;
			} else {
				_status = true;
				$(currElement).removeClass("invalid-ip");
			}
		}
		return _status;
	}
	$('.provabHelpText').popover();
	window.provab_popover = function (popover_list) {
		if ($.isArray(popover_list) == true) {
			selectors = '';
			$.each(popover_list, function (k, v) {
				selectors += '#' + v + ',';
			});
			selectors = selectors.substr(0, (parseInt(selectors.length) - 1));
			$(selectors).popover();
		}
	}

	
});