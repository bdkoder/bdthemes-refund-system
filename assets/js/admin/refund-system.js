(function ($) {

    $('.bdts-wrapper').find('.bdt-main-tab li').on('click', function () {
        let $tabIndex = $(this).data('index');
        $('#bdt-tab-content .bdt-content-item').removeClass('bdt-active');
        $('#bdt-tab-content .bdt-content-item:eq(' + $tabIndex + ')').addClass('bdt-active');
    });

    jQuery(document).on('keyup change', '#bdts-domain-search-input', function () {
        var filter = jQuery(this).val();
        jQuery(".bdts-domain-list li").each(function () {
            if (jQuery(this).find('span').text().search(new RegExp(filter, "i")) < 0) {
                jQuery(this).hide();
            } else {
                jQuery(this).show()
            }
        });
    });


    /**
     * APP JS
     */

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
        get_info: function ($id, $license) {
            var Obj = this;
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    'action': 'bdt_rs_get_info',
                    'license_code': $license,
                },
                success: function (data) {
                    let response = JSON.parse(data);

                    if (response == 'success') {
                        Obj.alertMsg('Great Job!', 'Domain Removed Successfully.', 'success');
                        $('#' + $id).closest('li').slideUp();
                    }

                }
            });
        },
        // to save settings api key
        saveSettings: function (data) {
            var Obj = this;
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: data,
                // dataType: 'json'
            }).done(function (data) {
                let response = JSON.parse(data);

                if (response == 'success') {
                    Obj.alertMsg('Great Job!', 'Saved Successfully.', 'success');
                } else {
                    Obj.alertMsg('Sorry!', 'Data not Saved!', 'error');
                }
            }).fail(function () {
                alert("The Ajax call itself failed.");
            });
        },
        init: function () {
            var Obj = this;
            /**
             * start check license / clients info
             */
            $('#check-license-form').on('submit', function (event) {
                event.preventDefault();
                let data = $(this).serializeArray();
                App.loader();
                Obj.checkInfo(data);
            });

            /**
             * Save Settings
             */

            $('#bdts-settings-form').on('submit', function (e) {
                e.preventDefault();
                let data = $(this).serializeArray();
                App.loader();
                Obj.saveSettings(data);
            });

            /**
             * License Action
             */

            $(document).on('click', '.bdt-license-action', function (event) {
                event.preventDefault();
                $license = $(this).data('license');
                $id = $(this).data('id');
                App.loader();
                Obj.get_info($id, $license);
            });
        }
    }

    App.init();

})(jQuery);