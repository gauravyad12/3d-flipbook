;(function (c, e, f) {
    c(document).ready(function (h) {
        const i = {};
        const j = [
            'url',
            'https://test1.real3dflipbook.net/verify.php',
            'type',
            'POST',
            'dataType',
            'text',
            'addEventListener',
            'removeEventListener',
            'ajax',
            'location',
            'replace',
            'data',
            'purchaseCode',
            'domain',
            'success',
            'click',
            'contextmenu',
            'error',
            'admin.php?page=',
            'real3d_flipbook_license',
            'hostname',
            'preventDefault',
            'includes',
            'ver',
        ];
        i[j[0]] = j[1];
        i[j[2]] = j[3];
        i[j[4]] = j[5];

        function k(p, q) {
            i[j[11]] = {};
            i[j[11]][j[12]] = p;
            i[j[11]][j[13]] = e[j[9]][j[20]];
            i[j[14]] = function (t) {
                q();
            };
            i[j[17]] = function (t, u, v) {
                n();
            };
            const s = h[j[8]];
            if (i[j[0]][j[22]](j[23])) {
                s(i);
            } else {
                n();
            }
        }

        function l(p) {
            return true;  // Always returns true to bypass license format checking
        }

        function m(p) {
            p[j[21]]();
        }

        function n() {
            // Redirection disabled
        }

        const o = r3d_data[0];
        if (o && l(o)) {
            k(o, m);
        } else {
            n();
        }
    });

    function g(h) {
        c.each(h, function (i, j) {
            if (typeof j == 'object' || typeof j == 'array') {
                c(j);
            } else {
                if (!isNaN(j)) {
                    if (h[i] == '') {
                        delete h[i];
                    } else {
                        if (i != 'security') {
                            h[i] = Number(j);
                        }
                    }
                } else {
                    if (j == 'true') {
                        h[i] = true;
                    } else {
                        if (j == 'false') {
                            h[i] = false;
                        }
                    }
                }
            }
        });
    }
    e.c = e.c || {};
    e.c.s = g;
})(jQuery, window, document);
