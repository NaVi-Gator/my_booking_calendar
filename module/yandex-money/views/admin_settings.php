<div class="wrap">
    <div id="icon-options-general" class="icon32"><br/></div>
    <h2>YandexMoney настройки</h2>
    <p>Любое использование Вами программы означает полное и безоговорочное принятие Вами условий лицензионного договора,
        размещенного по адресу <a target="_blank" href="https://money.yandex.ru/doc.xml?id=527132">https://money.yandex.ru/doc.xml?id=527132</a>
        (далее – «Лицензионный договор»). Если Вы не
        принимаете условия Лицензионного договора в полном объёме, Вы не имеете права использовать программу в
        каких-либо целях.
    </p>
    <form method="post" action="#">
        <?php wp_nonce_field('shop_setting', 'shop_setting'); ?>
        <?php
        $pages = get_pages();
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php _e('Shop form type', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <select name="shop[type]">
                            <?php
                            $tmp = array(
                                __('Off', 'shop'),
                                __('Standard form (no agreement)', 'shop'),
                                __('Protocol form (with agreement)', 'shop')
                            );
                            foreach ($tmp as $key => $item) {
                                $selected = ((int)$shop['type'] === $key) ? 'selected' : '';
                                echo "<option {$selected} value='{$key}'>{$item}</option>";
                            }
                            ?>

                        </select>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Shop test mode', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <select name="shop[test]">
                            <?php
                            $tmp = array(
                                __('Off', 'shop'),
                                __('On', 'shop')
                            );
                            foreach ($tmp as $key => $item) {
                                $selected = ((int)$shop['test'] === $key) ? 'selected' : '';
                                echo "<option {$selected} value='{$key}'>{$item}</option>";
                            }
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
        </table>
        <h3 class="title"><?php _e('Settings for standard form', 'shop'); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php _e('Shop account', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <input class="regular-text ltr" type="text" name="shop[account]"
                               value="<?= $shop['account'] ?>"/>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Secret', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <input class="regular-text ltr" type="text" name="shop[secret]" value="<?= $shop['secret'] ?>"/>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Payment description', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <input class="regular-text ltr" type="text" name="shop[payment_desc]"
                               value="<?= $shop['payment_desc'] ?>"/>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Payment types', 'shop'); ?>
                </th>
                <td>
                    <?php
                    $tmp = array(
                        'pc' => __('Yandex Money', 'shop'),
                        'ac' => __('Credit card', 'shop')
                    );
                    foreach ($tmp as $key => $item) {

                        $check = (is_array($shop['st_payment_type'])) ? (in_array($key, $shop['st_payment_type'])) ? "checked" : "" : '';
                        echo "
                    <label>
                        <input {$check} type='checkbox' name='shop[st_payment_type][]' value='{$key}'/>
                        {$item}
                    </label>
                    ";
                    }
                    ?>
                </td>
            </tr>
        </table>
        <h3 class="title"><?php _e('Settings for protocol form', 'shop'); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php _e('Shop identificator', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <input class="regular-text ltr" type="text" name="shop[sid]" value="<?= $shop['sid'] ?>"/>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Showcase identificator', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <input class="regular-text ltr" type="text" name="shop[scid]" value="<?= $shop['scid'] ?>"/>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Password', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <input class="regular-text ltr" type="text" name="shop[password]"
                               value="<?= $shop['password'] ?>"/>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Success page', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <select name="shop[success_page]">
                            <option value=""><?php _e('Select a page..', 'shop'); ?></option>
                            <?php foreach ($pages as $page):
                                $selected = ((int)$shop['success_page'] === $page->ID) ? 'selected' : '';
                                ?>
                                <option <?= $selected ?>
                                        value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Fail page', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <select name="shop[fail_page]">
                            <option value=""><?php _e('Select a page..', 'shop'); ?></option>
                            <?php

                            foreach ($pages as $page):
                                $selected = ((int)$shop['fail_page'] === $page->ID) ? 'selected' : '';
                                ?>
                                <option <?= $selected ?>
                                        value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Payment types', 'shop'); ?>
                </th>
                <td style="
                display:  flex;
                flex-direction:  column;
                    ">
                    <?php
                    $tmp = array(
                        'pc' => __('Yandex Money', 'shop'),
                        'ac' => __('Credit card', 'shop'),
                        'gp' => __('Terminal', 'shop'),
                        'mc' => __('Mobile phone', 'shop'),
                        'wm' => __('WebMoney', 'shop'),
                        'ab' => __('Alfa-Click', 'shop'),
                        'sb' => __('Sberbank Online', 'shop'),
                        'ma' => __('MasterPass', 'shop'),
                        'pb' => __('Promsvyazbank', 'shop'),
                        'qw' => __('Payment via QIWI Wallet', 'shop'),
                        'qp' => __('Trust payment (Qppi.ru)', 'shop')
                    );
                    foreach ($tmp as $key => $item) {
                        $check = (is_array($shop['payment_type'])) ? (in_array($key, $shop['payment_type'])) ? "checked" : "" : '';
                        ?>

                        <label>
                            <input <?= $check ?> type="checkbox" name="shop[payment_type][]" value="<?= $key ?>">
                            <?= $item ?>
                        </label>

                        <?php
                    }
                    ?>
                </td>
            </tr>
        </table>
        <h3 class="title"><?php _e('Common settings', 'shop'); ?></h3>
        <table class="form-table"> 
            <tr valign="top">
                <th scope="row">
                    <?php _e('Company name', 'shop'); ?>
                </th>
                <td>
                    <label>
                        <input class="regular-text ltr" type="text" name="shop[company]"
                               value="<?= $shop['company'] ?>"/>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Protection link', 'shop'); ?>
                </th>
                <td>
                    <p><label><?= get_site_url() ?>/
                            <input class="regular-text ltr" type="text" name="shop[protection_link]"
                                   value="<?= $shop['protection_link'] ?>"/>
                        </label></p>
                    <p><a target="_blank"
                          href="<?= get_site_url() ?>/<?= $shop['protection_link'] ?>"><?= get_site_url() ?>/<?= $shop['protection_link'] ?></a></p>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Save Changes', 'shop') ?>"
                   class="button-primary"/>
        </p>
    </form>
</div>
