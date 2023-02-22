(function ($) {

	var WidgetRefundForm = function ($scope, $) {
		var $form = $scope.find('.bdt-rs-form'),
			$settings = $form.data('settings');

		var App = {
			alertMsg: function ($title, $text, $icon) {
				Swal.fire({
					title: $title,
					text: $text,
					icon: $icon,
				})
			},
			loader: function () {
				Swal.showLoading();
			},
			submitForm: function (data) {
				var Obj = this;
				$.ajax({
					type: 'POST',
					url: ElementPackConfig.ajaxurl,
					data: data,
					// dataType: 'json'
				}).done(function (data) {
					let response = JSON.parse(data);

					if (response.status == 'success') {
						Obj.alertMsg('Request Accepted!', 'Our refund team will respond to you very soon. Thank you.', 'success');
						$($form).find('form')[0].reset();
					} else if (response.status == 'error') {
						Obj.alertMsg('Sorry!', response.msg, 'error');
					} else {
						Obj.alertMsg('Sorry!', data, 'error');
					}
				}).fail(function () {
					alert("The Ajax call itself failed.");
				});
			}
		}

		$('#bdt-rs-form').on('submit', function (e) {
			e.preventDefault();
			let data = $(this).serializeArray();
			console.log(data);
			App.loader();
			App.submitForm(data);
		});

	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/bdt-rs-form.default', WidgetRefundForm);
	});
})(jQuery);