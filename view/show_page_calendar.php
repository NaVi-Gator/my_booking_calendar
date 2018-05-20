<form id="dex_show_clock_module" action="<?= admin_url('admin-ajax.php') ?>" method="post">
    <input type="hidden" name="action" value="ajax_result">
    <?php wp_nonce_field('dex_my_setting', 'dex_my_setting'); ?>
    <div id="calendar_list">
        <?php
        foreach ($option['calendar_list'] as $key => $item) {

            ?>
            <div class="item <?= ($key === 0) ? 'active' : '' ?>" data-setting='<?= json_encode($item) ?>'>
                <p class="dey"><?= $item['dey'] ?></p>
                <div class="number"><?= $item['number'] ?></div>
            </div>

            <?php
        }
        ?>
    </div>
    <div id="info_block">
        <?php
        $this->get_info_by_data($option['now_day']);
        ?>
    </div>
    <div data-fancybox="" data-src="#popup_window"></div>
    <div id="popup_window" style="display: none">
        <div class="send_bloc">
            <p><?= _e('Booking a quest', 'shop') ?>: <span class="quest_name"></span></p>
            <p><?= _e('Date and time of booking', 'shop') ?>: <span class="quest_data"></span></p>
            <input type="hidden" name="date" value="">
            <input type="hidden" name="time" value="">
            <input type="hidden" name="code" value="">
            <div class="user_info">
                <input type="text" name="name" placeholder="Ваше Имя">
                <input type="text" name="lastName" placeholder="Ваша Фамилия">
                <input type="tel" name="phone" placeholder="Ваш Телефон">
                <input type="email" name="email" placeholder="Ваш Email">
                <label><?= _e('Number of Players', 'shop') ?>:
                    <select name="user_count" type="select">
                    </select>
                </label>
                <label>
                    <input type="checkbox" name="control_cot">
                    <?= $option['control'] ?>
                </label>
            </div>
            <div class="block_button error_button"><?= _e('To book', 'shop') ?></div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css"/>
    <link rel="stylesheet" href="<?= PLUGIN_URL ?>css/page_style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
    <script src="<?= PLUGIN_URL ?>js/jquery.maskedinput.min.js"></script>
    <script src="<?= PLUGIN_URL ?>js/page_js.js"></script>
</form>
<div id="pay_form" style="display: none"></div>
