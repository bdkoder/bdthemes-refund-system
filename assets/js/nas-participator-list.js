(function ($) {

	var WidgetHelloWorldHandler = function ($scope, $) {
		var $participatorList = $scope.find('.nas-participator-list'),
            $settings = $participatorList.data('settings');
 
        $(document).ready(function() {
            $($settings.id+'-table').DataTable({
                "pageLength": 150,
                "lengthMenu": [[50, 150, 450, -1], [50, 150, 450, "All"]]
            });
        } );
		
	};
    
	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/nas-participator-list.default', WidgetHelloWorldHandler);
	});
})(jQuery);

