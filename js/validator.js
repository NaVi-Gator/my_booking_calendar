$(document).on('keyup', "input", function () {
    disable_button($(this));
});
$(document).on('click', "input", function () {
    disable_button($(this));
});

function disable_button(dex) {
    if (!checked(dex)) {
        dex.addClass('error');
    } else {
        dex.addClass('ok');
    }
    const form = dex.parents('form');
    if (form.find('.error').length === 0 && form.find('.ok').length > 0) {
        form.find('button').removeClass('error_button');
    } else {
        form.find('button').addClass('error_button');
    }
}

function checked(item) {
    item.removeClass('error');
    item.removeClass('ok');
    const type = item.attr('type');
    let val = item.val();
    switch (type) {
        case "tel":
            if (val.length === 0) return false;
            return (val.indexOf("_") < 0);
        case "hidden":
            return true;
        case "email":
            const reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            return reg.test(val);
        case "checkbox":
            return item.prop('checked');
        default:
            return true;

    }
}


$(document).ready(function () {
    $("input[type=tel]").mask("+7(999)999-99-99");
});

$(function () {
    const this_form = $('#popup_window form');
    const reserve_from_page = this_form.find('input[name=reserve_from_page]').val();
    const url = this_form.attr('action');
    const _wp_http_referer = this_form.find('input[name=_wp_http_referer]').val();
    const action = this_form.find('*[name=action]').val();
    const code = this_form.find('*[name=code]').val();

    $.datepicker.setDefaults($.datepicker.regional["ru"]);
    $("#datepicker").datepicker({

        onSelect: function (e) {
            const out = {
                reserve_from_page: reserve_from_page,
                _wp_http_referer: _wp_http_referer,
                action: action,
                data: e,
                code: code,
                case: 'datepicker'
            };
            $.ajax({
                method: "POST",
                url: url,
                data: out
            }).done(function (msg) {
                try {
                    const this_form = $('#popup_window');
                    const result = JSON.parse(msg);
                    let html = "";
                    let s = true;
                    let valO = "";
                    $.each(result, function (i, val) {
                        if (s) {
                            s = false;
                            valO = i;
                        }
                        html += '<option data-count-user=\'' + JSON.stringify(val) + '\' value="' + i + '">' + i + '</option>'
                    });
                    this_form.find('select[name="time"]').val(valO);
                    this_form.find('select[name="time"]').html(html);
                    checked_time(this_form.find('select[name="time"]'));
                } catch (e) {
                    console.log(e);
                    console.log(msg);
                }
            });

        }
    })
});
$(document).on('change', '#popup_window select[name="time"]', function () {
    checked_time($(this));
});

function checked_time(item) {
    const val = item.val();
    const time = JSON.parse(item.find('option[value="' + val + '"]').attr('data-count-user'));
    let html = "";
    let s = true;
    let valO = 0;
    $.each(time, function (i, val) {
        if (s) {
            s = false;
            valO = i;
        }
        html += '<option value="' + i + '">' + i + ' Цена: ' + val + '.руб</option>'
    });
    const this_form = $('#popup_window');
    this_form.find('select[name="user_count"]').val(valO);
    this_form.find('select[name="user_count"]').html(html);
}

$(document).on('click', '.button.block_button.admin', function () {
    const this_form = $('#popup_window form');
    const reserve_from_page = this_form.find('input[name=reserve_from_page]').val();
    const url = this_form.attr('action');
    const _wp_http_referer = this_form.find('input[name=_wp_http_referer]').val();
    const action = this_form.find('input[name=action]').val();
    const inputs_list = ['name', 'lastName', 'phone', 'email', 'date', 'time', 'code', 'user_count'];
    let data = {};
    for (let i = 0; i < inputs_list.length; i++) {
        data[inputs_list[i]] = this_form.find('*[name=' + inputs_list[i] + ']').val();
    }
    const out = {
        reserve_from_page: reserve_from_page,
        _wp_http_referer: _wp_http_referer,
        action: action,
        case: 'add_user_admin',
        data: data
    };
    console.log({
        method: "POST",
        url: url,
        data: out
    });

    $.ajax({
        method: "POST",
        url: url,
        data: out
    }).done(function (msg) {
        alert(msg);
        $('#popup_window').find('.fancybox-close-small').trigger('click');
    });

});