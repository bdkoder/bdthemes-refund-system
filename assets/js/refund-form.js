(function ($) {

	var WidgetRefundForm = function ($scope, $) {
		var $form = $scope.find('.bdt-rs-form'),
			$settings = $form.data('settings');

		console.log($settings);

	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/bdt-rs-form.default', WidgetRefundForm);
	});
})(jQuery);