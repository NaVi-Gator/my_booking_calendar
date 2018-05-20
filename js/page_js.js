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
    const form = dex.parents('#popup_window');
    if (form.find('.error').length === 0 && form.find('.ok').length > 0) {
        form.find('.block_button').removeClass('error_button');
    } else {
        form.find('.block_button').addClass('error_button');
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
            return !(val.length === 0);
        case "email":
            const reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            return reg.test(val);
        case "checkbox":
            return item.prop('checked');
        case 'text':
            val = tgtrimm(val);
            item.val(val);
            return (val.length > 2);
        default:
            return type;

    }
}

function tgtrimm(str) {
    return str.replace(/[^a-zA-ZА-Яа-яЁё]/gi, '').replace(/\s+/gi, ', ');
}

let url = "";
let method = "";
let dex_my_setting = "";
let _wp_http_referer = "";
let action = "";
$(document).ready(function () {
    $("input[type=tel]").mask("+7(999)999-99-99");
    url = $('#dex_show_clock_module').attr('action');
    method = $('#dex_show_clock_module').attr('method');
    dex_my_setting = $('#dex_show_clock_module').find('input[name=dex_my_setting]').val();
    _wp_http_referer = $('#dex_show_clock_module').find('input[name=_wp_http_referer]').val();
    action = $('#dex_show_clock_module').find('input[name=action]').val();
});


$(document).on('click', '#calendar_list .item', function () {
    $('#calendar_list .item').removeClass('active');
    $(this).addClass('active');
    const data = JSON.parse($(this).attr('data-setting'));


    $('#info_block').html('<div class="loader"></div>');
    $.ajax({
        method: method,
        url: url,
        data: {
            dex_my_setting: dex_my_setting,
            _wp_http_referer: _wp_http_referer,
            action: action,
            data: data,
            case: 'get_calendar_by_data'
        }
    }).done(function (msg) {
        $('#info_block').html(msg);
    });
});
$(document).on('click', '#info_block .schedule .time.enable', function () {
    const options = JSON.parse($(this).attr('data-time-options'));
    const time = $(this).attr('data-time');
    const dey_setting = JSON.parse($('.item.active').attr('data-setting'));
    const data_quest = JSON.parse($(this).parents('*[data-quest]').attr('data-quest'));
    const quest_form = $('#popup_window');
    quest_form.find('input[name=date]').val(dey_setting.data);
    quest_form.find('input[name=time]').val(time);
    quest_form.find('input[name=code]').val(data_quest.code);
    quest_form.find('.quest_data').text(dey_setting.data + " " + time);
    quest_form.find('.quest_name').text(data_quest.title);
    let html = "";
    $.each(options, function (key, item) {
        html += '<option value="' + key + '">Игроков: ' + key + ' Цена: ' + item + '.руб</option>'
    });
    quest_form.find('select[name=user_count]').html(html);
    $('*[data-src="#popup_window"]').trigger('click');
});
$(document).on('click', '#popup_window .block_button', function () {

    const quest_form = $('#popup_window .send_bloc');
    const inputs_list = ['name', 'lastName', 'phone', 'email', 'date', 'time', 'code', 'control_cot', 'user_count'];
    for (let i = 0; i < inputs_list.length; i++) {
        disable_button(quest_form.find('*[name=' + inputs_list[i] + ']'));
    }
    if (quest_form.find('.error').length === 0) {
        let data = {};
        for (let i = 0; i < inputs_list.length; i++) {
            data[inputs_list[i]] = quest_form.find('*[name=' + inputs_list[i] + ']').val();
        }
        const data_out = {
            dex_my_setting: dex_my_setting,
            _wp_http_referer: _wp_http_referer,
            action: action,
            data: data,
            case: 'blocked_quest'
        };
        $.ajax({
            method: method,
            url: url,
            data: data_out
        }).done(function (msg) {
            $('#popup_window .fancybox-close-small').trigger('click');
            if (msg === '0') {
                alert("Бронирования не удалось");
            } else {
                $('#pay_form').html(msg);
                $('#payment_form').trigger('submit');
            }

        });
    }

});