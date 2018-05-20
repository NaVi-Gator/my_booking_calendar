$(function () {
    $("#tabs").tabs();
});


let status = 0;
$(document).on('click', '#tabs .add_time', function () {
    if (status === 0) {
        let dt = new Date();
        $(this).parents('.add_time_block').find('label input').val(dt.getHours() + ":" + dt.getMinutes());
        $(this).parents('.add_time_block').find('label').addClass('active');
        $(this).text('Добавить');
    }
    if (status === 1) {


        let time = $(this).parents('.add_time_block').find('label input').val();
        if (time === '') {
            status = 0;

        }
        else {
            $(this).parents('.add_time_block').find('label').removeClass('active');
            const data_marker = $(this).attr('data-marker');
            add_time(time, data_marker);
            $(this).text('Добавить время');
            status = -1;
        }
    }

    status++;

});

$(function () {
    $("#tabs table tbody[data-dey-marker]").sortable();
    $("#tabs table tbody[data-dey-marker]").disableSelection();
});

function add_time(time, key) {


    let html = " <tr data-sort-time='" + time + "' class='remove_block'>\n" +
        "                    <td>" + time + "</td>\n" +
        "                    <td>\n" +
        "                        <table>\n" +
        "                            <thead>\n" +
        "                            <tr>\n" +
        "                                <th>Количестуо</th>\n" +
        "                                <th>Цена</th>\n" +
        "                                      <th><input type='number' min='1' value='1'><div class='add button'>+</div></th>" +
        "                            </tr>\n" +
        "                            </thead>\n" +
        "                            <tbody data-time-marker='" + time + "'>\n" +
        "                            </tbody>\n" +
        "                        </table>\n" +
        "                    </td>\n" +
        "                    <td class='button remove_time'>-</td>\n" +
        "                </tr>";
    $('tbody[data-dey-marker=' + key + ']').append(html);
}

$(document).on('click', '.button.remove_time', function () {
    $(this).parents('.remove_block').remove();
});
let sub_status = 0;
$(document).on('click', 'tr[data-sort-time] thead th  .add.button', function () {
    let count_auto = 2;
    switch (sub_status) {
        case 0:
            let cust_count = parseInt($(this).parents('*[data-sort-time]').find('*[data-time-marker] .remove_count').last().find('td:first-child').text());
            cust_count++;
            count_auto = (cust_count > 2) ? cust_count : count_auto;

            $(this).parent('th').find('input').val(count_auto);
            $(this).parent('th').find('input').addClass('active');
            sub_status++;
            break;
        case 1:
            let count = parseInt($(this).parent('th').find('input').val());
            if (count > 0) {
                let dey = $(this).parents('*[data-dey-marker]').attr('data-dey-marker');
                let time = $(this).parents('*[data-sort-time]').attr('data-sort-time');
                const html = '<tr class="remove_count">\n' +
                    '                                <td>' + count + '</td>\n' +
                    '                                <td><input type="number" name="users_gra[' + dey + '][' + time + '][' + count + ']"\n' +
                    '                                           min="1" placeholder="">\n' +
                    '                                </td>\n' +
                    '                                <td><div class="remove_sub button">-</div></td>\n' +
                    '                            </tr>';
                $('tbody[data-dey-marker=' + dey + '] tbody[data-time-marker="' + time + '"]').append(html);
                $(this).parent('th').find('input').removeClass('active');
                sub_status = 0;
            }
            break
    }
});
$(document).on('click', '.remove_sub.button', function () {
    $(this).parents('.remove_count').remove();
});

$(function () {
    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _create: function () {
            this._super();
            this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
        },
        _renderMenu: function (ul, items) {
            let that = this,
                currentCategory = "";
            $.each(items, function (index, item) {
                let li;
                if (item.category !== currentCategory) {
                    ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                    currentCategory = item.category;
                }
                li = that._renderItemData(ul, item);
                if (item.category) {
                    li.attr("aria-label", item.category + " : " + item.label);
                }
            });
        }
    });
    if ($('#search_time_zone').length > 0) {
        $("#search_time_zone").catcomplete({
            delay: 0,
            source: zone
        });
    }
});
$(document).on('click', 'div[data-remove]', function () {
    const item = $(this).attr('data-remove');
    url = $('#users_block_list').attr('action');
    method = $('#users_block_list').attr('method');
    user_list_remove = $('#users_block_list').find('input[name=user_list_remove]').val();
    _wp_http_referer = $('#users_block_list').find('input[name=_wp_http_referer]').val();
    action = $('#users_block_list').find('input[name=action]').val();
    const data_out = {
        user_list_remove: user_list_remove,
        _wp_http_referer: _wp_http_referer,
        action: action,
        item_id: item,
        case: 'remove'
    };
    const remove = $(this).parents('.user');
    $.ajax({
        method: method,
        url: url,
        data: data_out
    }).done(function (msg) {
        if (parseInt(msg) === 1) {
            remove.remove();
        }
    });
});
$(document).on('click', 'div[data-confirm]', function () {
    const item = $(this).attr('data-confirm');
    url = $('#users_block_list').attr('action');
    method = $('#users_block_list').attr('method');
    user_list_remove = $('#users_block_list').find('input[name=user_list_remove]').val();
    _wp_http_referer = $('#users_block_list').find('input[name=_wp_http_referer]').val();
    action = $('#users_block_list').find('input[name=action]').val();
    const data_out = {
        user_list_remove: user_list_remove,
        _wp_http_referer: _wp_http_referer,
        action: action,
        item_id: item,
        case: 'confirm'
    };
    $.ajax({
        method: method,
        url: url,
        data: data_out
    }).done(function (msg) {
        console.log(msg);
        if (parseInt(msg) === 1) {
            location.reload()
        }
    });
});