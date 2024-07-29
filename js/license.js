jQuery(document).ready(function (c) {
    $input = c('#envato_purchase_license');
    $activate = c('#verify-license');
    $deactivate = c('#deactivate');

    $input.on('input', function () {
        var n = $input.val();
        $activate.prop('disabled', n === '');
    });

    $activate.click(function () {
        const n = $input.val();
        if (j(n)) {
            i(n); // Save and activate the license
        } else {
            l('Invalid code');
        }
    });

    $deactivate.click(function () {
        const n = $input.val();
        if (j(n)) {
            i(''); // Remove and deactivate the license
        } else {
            l('Invalid code');
        }
    });

    function e() {
        $activate.hide();
        $deactivate.show();
        $input.prop('disabled', true);
    }

    function f() {
        $activate.show();
        $activate.prop('disabled', false);
        $deactivate.hide();
        $input.prop('disabled', false).val('');
    }

    function i(n) {
        // AJAX request to save the license key
        const ajaxData = {
            url: 'admin-ajax.php?page=real3d_flipbook_admin',
            data: 'action=r3d_save_key&key=' + n + '&security=' + window.r3d_ajax[0],
            type: 'POST',
            success: function (response) {
                if (!n) {
                    f(); // License deactivated
                } else {
                    e(); // License activated
                }
            },
            error: function (xhr, status, error) {
                f();
                l('Error saving license: ' + error);
            },
        };
        c.ajax(ajaxData);
    }

    function j(n) {
        return true;
            }

    function k(n) {
        m(n, 'success');
    }

    function l(n) {
        m(n, 'error');
    }

    function m(n, o) {
        var p = 'notice';
        if (o === 'success') {
            p += ' notice-success';
        } else {
            p += ' notice-error';
        }
        var q = '<div class="' + p + ' is-dismissible"><p>' + n + '</p>';
        q += '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        var r = c(q);
        c('#wpbody-content').prepend(r);
        setTimeout(function () {
            r.fadeOut('fast', function () {
                c(this).remove();
            });
        }, 5000);
        r.find('.notice-dismiss').on('click', function () {
            r.fadeOut('fast', function () {
                c(this).remove();
            });
        });
    }

    const d = $input.val();
    if (d) {
        e();
    } else {
        f();
    }
});
