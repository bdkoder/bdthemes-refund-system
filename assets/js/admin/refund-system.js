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
        getInfo: function (data) {
            var Obj = this;
            jQuery.ajax({
                type: "POST",
                url: bdt_rs_object.ajax_url,
                data: data,
                success: function (data) {
                    let response = JSON.parse(data);

                    if (response == 'field-blank') {
                        Obj.alertMsg('Ops!', 'Field should not be empty!', 'warning')
                        return;
                    }
                    if (response == 'error') {
                        Obj.alertMsg('Ops!', 'Something Wrong or License is not correct!', 'warning')
                        return;
                    }
                    if (response == 'nonce_expired') {
                        Obj.alertMsg('Ops!', 'Session Expired, please reload your webpage.', 'warning')
                        return;
                    }

                    Swal.close();
                    bdtUIkit.modal($('#bdts-modal')).show();
                    $('#bdts-modal-body').html(response);

                },
                error: function (errorThrown) {
                    alert(errorThrown);
                }

            });
        },
        // to Action Trigger
        actionTrigger: function (data) {
            var Obj = this;
            $.ajax({
                type: 'POST',
                url: bdt_rs_object.ajax_url,
                data: data,
            }).done(function (data) {
                let response = JSON.parse(data);

                if (response == 'success') {
                    Obj.alertMsg('Great Job!', 'Operation Successfully.', 'success');
                    location.reload();
                } else {
                    Obj.alertMsg('Sorry!', 'Operation Failed! Or Data maybe not modified.', 'error');
                }
            }).fail(function () {
                alert("The Ajax call itself failed.");
            });
        },
        // to save settings api key
        saveSettings: function (data) {
            var Obj = this;
            $.ajax({
                type: 'POST',
                url: bdt_rs_object.ajax_url,
                data: data,
                // dataType: 'json'
            }).done(function (data) {
                let response = JSON.parse(data);

                if (response == 'success') {
                    Obj.alertMsg('Great Job!', 'Saved Successfully.', 'success');
                } else {
                    Obj.alertMsg('Sorry!', 'Data not Saved!', 'error');
                }
            }).fail(function (e) {
                alert("The Ajax call itself failed." + e);
            });
        },
        init: function () {
            /**
             * Save Settings
             */

            $('#bdts-settings-form').on('submit', function (e) {
                e.preventDefault();
                let data = $(this).serializeArray();
                App.loader();
                App.saveSettings(data);
            });

            /**
             * License Action
             */

            $(document).on('click', '.bdt-license-action', function (event) {
                event.preventDefault();
                let data = {
                    'action': 'bdt_rs_get_info',
                    'license': $(this).data('license'),
                    'id': $(this).data('id'),
                };
                App.loader();
                App.getInfo(data);
            });

            /**
             * License Action Trigger
             */

            $(document).on('click', '#bdt-rs-action-submit', function (event) {
                event.preventDefault();
                $('#bdt_rs_action_nonce').val()

                let data = {
                    'action': 'bdt_rs_action_trigger',
                    'actionValue': $('#bdt-rs-action-select').val(),
                    'id': $('#bdt-rs-action-select').data('id'),
                    'name': $('#rf-modal-client-name').text(),
                    'email': $('#rf-modal-client-email').text(),
                    'submit_email': $('#submit-email-' + $('#bdt-rs-action-select').data('id')).text(),
                    '_wpnonce': $('#bdt_rs_action_nonce').val(),
                    'comments': $('#bdt-rs-comments').val(),
                    'additional_msg': $('#bdt-rs-additional-msg').val(),
                };

                App.loader();
                App.actionTrigger(data);
            });
        }
    }

    App.init();

})(jQuery);