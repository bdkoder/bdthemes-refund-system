(function ($) {

	var WidgetHelloWorldHandler = function ($scope, $) {
		var $eventFormWrapper = $scope.find('.event-form-wrapper'),
			$eventForm = $scope.find('.event-form'),
			$settings = $eventFormWrapper.data('settings');

		$($eventForm).find('#phone').inputmask('99999-99-99-99');


		function loading_submit() {
			Swal.fire({
				title: $settings.loadingTitle,
				html: $settings.loadingText,
				allowEscapeKey: false,
				allowOutsideClick: false,
				didOpen: () => {
					Swal.showLoading()
				}
			});
		}

		function input_require($msg) {
			Swal.fire({
				title: $settings.errorTitle,
				text: $msg,
				icon: 'error',
				allowOutsideClick: false,
				confirmButtonText: 'Ok'
			});
			form_activate();
		}

		function submit_success($msg) {
			Swal.fire({
				title: $settings.successTitle,
				text: $msg,
				icon: 'success',
				allowOutsideClick: false,
				confirmButtonText: 'Ok'
			});
			form_activate();
			$($eventForm).find('.bdt-input-file-btn + .nas-img-preview-area').remove();

			if ($settings.successRedirectURL.length) {
				setTimeout(function () {
					window.location.replace($settings.successRedirectURL);
				}, 2000);
			}

		}

		function filePreview(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$($eventForm).find('.bdt-input-file-btn + .nas-img-preview-area').remove();
					$($eventForm).find('.bdt-input-file-btn').after('<div class="bdt-margin-small-left nas-img-preview-area""><img src="' + e.target.result + '" style="height: 100%; width: 100%; object-fit: cover;"/></div>');
				};
				reader.readAsDataURL(input.files[0]);
			}
			form_activate();
		}


		function form_activate() {
			$($eventForm).find(".bdt-event-submit-button").prop("disabled", false);
			$($eventForm).css('opacity', 1);
		}


		$($eventForm).find('#file').on('change', function () {


			var file = (this.files[0].size);
			var filesize = (this.files.length && this.files[0].size) || '';

			if (filesize > 5242880) {
				$msg = $settings.imgSizeInvalid;
				input_require($msg);
				this.value = '';
				$($eventForm).find('.bdt-input-file-btn + .nas-img-preview-area').remove();
				return;
			};

			var extension = $('#file').val().split('.').pop().toLowerCase();

			if ($.inArray(extension, ['gif', 'png', 'jpg', 'jpeg', 'bmp', 'tiff']) == -1) {
				$msg = $settings.imgInvalid + ' (.' + extension + ')';
				input_require($msg);
				this.value = '';
				$($eventForm).find('.bdt-input-file-btn + .nas-img-preview-area').remove();
				return;
			}

			filePreview(this);

		});



		$($eventForm).find(".event-form").on("submit", function (event) {
			event.preventDefault();
			$($eventForm).find(".bdt-event-submit-button").prop("disabled", true);
			$($eventForm).css('opacity', .6);
			$loading_msg = 'Loading';


			// var name = jQuery('#name').val();
			var file_data = $($eventForm).find('#file').prop('files')[0];

			var form_data = new FormData();

			form_data.append('file', file_data);
			form_data.append('action', 'nas_submit_participator');
			form_data.append('formData', $eventForm.serialize());
			// form_data.append('name', name);



			var category = $("#category").val();
			var name = $("#name").val();
			var guardian = $("#guardian-name").val();
			var phone = $("#phone").val();
			var address = $("#address").val();


			if (category.length == "" || name.length == "" || guardian.length == "" || phone.length == "" || address.length == "") {
				$msg = $settings.fieldsEmpty;
				input_require($msg)
				return;
			}

			// var serialized = $($eventForm).serialize();
			// if (serialized.indexOf('=&') > -1 || serialized.substr(serialized.length - 1) == '=') {
			// 	$msg = $settings.fieldsEmpty;
			// 	input_require($msg)
			// 	return;
			// }

			var vidFileLength = $($eventForm).find('#file')[0].files.length;
			if (vidFileLength === 0) {
				$msg = $settings.imgEmpty;
				input_require($msg)
				return;
			}

			function participate_done() {

				var to = $('#phone').val(),
					name = $('#name').val(),
					success_msg = $settings.submitSuccessSMS;
				success_msg = success_msg.replace('{{NAME}}', name);

				otp_data.append('to', to);
				otp_data.append('message', success_msg);

				$.ajax({
					url: $eventForm.attr('action'),
					type: 'POST',
					contentType: false,
					processData: false,
					//  data: {
					//  	action: 'nas_send_token',
					//  	formData: [ ['otp' , 123], ['to', '01236458']]
					//  },
					data: otp_data,
					error: function (error) {
						// alert("OTP Failed" + error);
					},
					success: function (response) {

					}

				});
			}

			function submit_form() {
				$.ajax({
					url: $eventForm.attr('action'),
					type: 'POST',
					//  data: {
					//  	action: 'nas_submit_participator',
					//  	formData: $eventForm.serialize()
					//  },
					contentType: false,
					processData: false,
					data: form_data,
					datatype: 'html',
					enctype: 'multipart/form-data',
					error: function (error) {
						// alert("Send Failed, Internet Problem, please try agian." + error);
						$msg = $settings.internetIssue;
						input_require($msg);
					},
					success: function (response) {
						if (response == 'submitted') {
							$msg = $settings.submitSuccess;
							submit_success($msg);
							participate_done();
							$eventForm.find("input[type=text], textarea").val("");
						}
						if (response == 'image-failed') {
							$msg = $settings.imageFailedUpload;
							input_require($msg);
						}
						if (response == 'not-image') {
							$msg = $settings.imgInvalid;
							input_require($msg);
						}
					}
				});
			}

			var otp_data = new FormData();
			otp_data.append('action', 'nas_send_token');

			var otp = Math.floor(Math.random() * 900000) + 100000;

			var to = $('#phone').val(),
				otp_sms = $settings.otpSMS;
			otp_data.append('to', to);
			otp_sms = otp_sms.replace('{{OTP}}', otp);

			otp_data.append('message', otp_sms);

			function otp_confrim_dialog() {

				(async () => {

					const { value: entered_top } = await Swal.fire({
						title: $settings.otpTitle,
						input: 'text',
						inputLabel: $settings.otpInputLabel,
						inputPlaceholder: $settings.otpInputPlaceholder,
						allowOutsideClick: false,
						inputAttributes: {
							maxlength: 6,
							autocapitalize: 'off',
							autocorrect: 'off',
							maxWidth: 'unset'
						}
					})

					if (entered_top != otp) {
						$otp_wrong_msg = $settings.otpWrong;
						input_require($otp_wrong_msg);
						setTimeout(function () {
							otp_confrim_dialog();
						}, 1500);

					} else {
						loading_submit();
						submit_form();
					}

				})();
			}
			$.ajax({
				url: $eventForm.attr('action'),
				type: 'POST',
				contentType: false,
				processData: false,
				//  data: {
				//  	action: 'nas_send_token',
				//  	formData: [ ['otp' , 123], ['to', '01236458']]
				//  },
				data: otp_data,
				error: function (error) {
					alert("OTP Failed" + error);
				},
				success: function (response) {

					if (response == 'ok') {
						otp_confrim_dialog();
					} else {
						$msg = $settings.phoneError;
						input_require($msg);
					}

				}
			});



		});

		function isFacebookApp() {
			var ua = navigator.userAgent || navigator.vendor || window.opera;
			return (ua.indexOf("FBAN") > -1) || (ua.indexOf("FBAV") > -1);
		}
		if (isFacebookApp()) {
			alert('তুমি ফেসবুকের ব্রাউজার ব্যবহার করছ, এই ব্রাউজার ব্যবহার করলে ছবি আপলোড হবেনা! তাই মোবাইলের কোনায় তিন ডট [...] এ ক্লিক করে Open in Chrome/Browser মেনুতে ক্লিক করে অন্য কোন ব্রাউজার ব্যবহার করো।');
		}

	};

	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/nas-event-form.default', WidgetHelloWorldHandler);
	});
})(jQuery);
