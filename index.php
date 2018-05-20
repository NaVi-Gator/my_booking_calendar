<?php

/*
Plugin Name: Reservations calendar
Description: Календарь бронирования.
Version: 1.0
Author: dimon-den
*/

define('PREFIX', 'my_reservations_');
define('PLUGIN_URL', plugin_dir_url(__FILE__));
define('POST_TYPE_QUEST', 'quest_list');
define('WPLANG', 'ru_RU');
add_action('plugins_loaded', 'true_load_plugin_textdomain');
register_activation_hook(__FILE__, array('My_Reservations', 'install'));
function true_load_plugin_textdomain()
{
    load_plugin_textdomain('shop', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}


class My_Reservations
{
    public function __construct()
    {
        add_action('save_post', array($this, 'save_post_data'));
        add_action('add_meta_boxes', array($this, 'dex_my_reservations_custom_box'));
        add_action('admin_print_styles-post-new.php', array($this, 'dex_my_custom_box_style'), 11);
        add_action('admin_print_styles-post.php', array($this, 'dex_my_custom_box_style'), 11);
        add_action('admin_menu', array($this, 'dex_my_reservations_menu'));
        add_action('wp_ajax_ajax_result', array($this, 'ajax_result'));
        add_action('wp_ajax_nopriv_ajax_result', array($this, 'ajax_result'));
        add_action('wp_ajax_remove_user', array($this, 'remove_user'));
        add_action('wp_ajax_nopriv_remove_user', array($this, 'remove_user'));
        add_action('wp_ajax_reserve_from_page', array($this, 'reserve_from_page'));
        add_action('wp_ajax_nopriv_reserve_from_page', array($this, 'reserve_from_page'));
        add_action('parse_request', array($this, 'checkPayment'));

    }

//include page
    public
    function install()
    {

        if (!is_array(get_option(PREFIX))) {
            update_option(PREFIX, array(
                'dey' => array('П', 'В', 'С', 'Ч', 'П', 'С', 'В'),
                'time_zone' => 'Europe/Kiev',
                'enable_dey' => 14,
                'tmp_time' => 5,
                'control' => 'Я принимаю условия: соглшаения о персональных данных (ссылка), пользовательского соглашения (ссылка).'
            ));
        }
        if (!is_array(get_option(PREFIX . 'shop_setting'))) {
            update_option(PREFIX . 'shop_setting', array(
                'type' => 0,
                'test' => 1,
                'st_payment_type' => array('pc', 'ac'),
                'payment_type' => array('pc', 'gp', 'wm', 'pb'),
                'protection_link' => 'protection/link'
            ));
        }

    }

    private
    function get_view($name, $option = null)
    {
        include(plugin_dir_path(__FILE__) . '/view/' . $name . '.php');
    }

    private
    function include_resourse($connect_resurse)
    {
        $parent = "jquery";
        foreach ($connect_resurse['js'] as $key => $item) {
            $code = PREFIX . $key . "_js";
            wp_register_script($code, $item, $parent);
            wp_enqueue_script($code);
            $parent = $code;
        }

        foreach ($connect_resurse['css'] as $key => $item) {
            $code = PREFIX . $key . "_css";
            wp_register_style($code, $item);
            wp_enqueue_style($code);
        }
    }

    //to pots type
    public
    function dex_my_reservations_meta_box()
    {
        $options = array();
        $post_id = (int)$_GET['post'];
        $options['dey_list'] = get_option(PREFIX)['dey'];
        $options['user_list_game'] = get_post_meta($post_id, 'dex_my_list_meta_box', true);
        $options['max_players'] = get_post_meta($post_id, 'dex_my_list_max_players', true);
        $this->get_view('show_dey', $options);
    }

    public
    function save_post_data($post_id)
    {

        if (wp_verify_nonce($_POST['dex_my_list_meta_box'], 'dex_my_list_meta_box')) {
            update_post_meta($post_id, 'dex_my_list_meta_box', $_POST['users_gra']);
            update_post_meta($post_id, 'dex_my_list_max_players', $_POST['max_players']);
        }

    }

    public
    function dex_my_custom_box_style()
    {
        $connect_resurse = array(

            "js" => array(
                'jquery-ui' => 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js',
                'fancybox' => 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js',
                'datepicker-ru' => PLUGIN_URL . 'js/datepicker-ru.js',
                'maskedinput' => PLUGIN_URL . 'js/jquery.maskedinput.min.js',
                'validator' => PLUGIN_URL . 'js/validator.js',
                'main' => PLUGIN_URL . 'js/main.js'
            ),
            "css" => array(
                'jquery-ui' => 'http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
                'fancybox' => 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css',
                'style' => PLUGIN_URL . 'css/style.css'
            )

        );

        $this->include_resourse($connect_resurse);
    }

    public
    function dex_my_reservations_custom_box()
    {

        add_meta_box('dex_my_reservations_meta_box', __('Quest settings', 'shop'), array($this, 'dex_my_reservations_meta_box'), POST_TYPE_QUEST);
    }


    //to post type


    public
    function confirm_user($key)
    {
        $block_list = get_option(PREFIX . 'bloch_list');
        $user = $block_list[$key];
        $time = $user['date'] . " " . $user['time'];
        $block_list[$key]['tmp_block'] = $time;
        return update_option(PREFIX . 'bloch_list', $block_list);
    }

    public
    function dex_my_reservations_menu()
    {
        $page = array();
        $page[] = add_menu_page('RC', 'RC', 'read', 'dex_my_reservations', array($this, 'dex_my_reservations'), 'dashicons-calendar-alt');
        $page[] = add_submenu_page('dex_my_reservations', 'Список бронирования', 'Бронирования', 'read', PREFIX . 'block_list', array($this, 'block_users_list'));
        $page[] = add_submenu_page('dex_my_reservations', 'Почта', 'Рассылка', 'read', PREFIX . 'newsletter', array($this, 'block_users_newsletter'));
        $page[] = add_submenu_page('dex_my_reservations', 'Яндекс Касса', 'Яндекс Касса', 'read', PREFIX . 'yandex_cashier', array($this, 'yandex_cashier'));
        foreach ($page as $key => $item) {
            add_action('admin_print_styles-' . $item, array($this, 'dex_my_referral_scripts_css_jquery'));
        }
    }

    public
    function yandex_cashier()
    {
        if (wp_verify_nonce($_REQUEST['shop_setting'], 'shop_setting')) {
            update_option(PREFIX . 'shop_setting', $_REQUEST['shop']);
        }
        $shop = get_option(PREFIX . 'shop_setting');

        include_once(plugin_dir_path(__FILE__) . 'module/yandex-money/views/admin_settings.php');
    }

    public
    function get_payment_form($order_id)
    {
        $block_list = get_option(PREFIX . 'bloch_list');
        if (isset($block_list[$order_id])) {
            $option = array();
            $shop = get_option(PREFIX . 'shop_setting');
            $option['user'] = $block_list[$order_id];
            $post = get_post($option['user']['code']);
            $list = get_post_meta($post->ID, 'dex_my_list_meta_box', true);
            $data = $this->get_dey($option['user']['date']);
            $option['sum'] = $list[$data['day_of_week']][$option['user']['time']][$option['user']['user_count']];
            $option['id'] = $order_id;
            $shop['url'] = ($shop['test'] === '1') ? 'https://demomoney.yandex.ru/eshop.xml' : 'https://money.yandex.ru/eshop.xml';
            switch ($shop['type']) {
                case "1":
                    $shop['st_payment_type'] = (is_array($shop['st_payment_type'])) ? $shop['payment_type'] : array('AC', 'PC');
                    include_once(plugin_dir_path(__FILE__) . 'module/yandex-money/views/form_standard.php');
                    break;
                case "2":
                    $shop['payment_type'] = (is_array($shop['payment_type'])) ? $shop['payment_type'] : array('AC', 'PC', 'GP', 'MC', 'WM', 'AB', 'SB', 'MA', 'PB', 'QW', 'QP');
                    include_once(plugin_dir_path(__FILE__) . 'module/yandex-money/views/form_protocol.php');
                    break;
                default:
                    break;
            }
        }
    }

    public
    function block_users_newsletter()
    {

        $this->get_view("header", array('title' => __('Sending messages', 'shop')));
        $options = array();
        $options['users_list'] = get_option(PREFIX . 'bloch_list');
        if (wp_verify_nonce($_REQUEST['send_mail'], 'send_mail')) {
            update_option(PREFIX . 'send_mail', $_REQUEST['mail']);
            if (isset($_REQUEST['send'])) {
                $mess_option = get_option(PREFIX . 'send_mail');
                $_REQUEST['send'] = (is_array($_REQUEST['send'])) ? $_REQUEST['send'] : array('send' => $_REQUEST['send']);
                foreach ($_REQUEST['send'] as $key => $value) {
                    $mess = $mess_option['mess'];
                    foreach ($options['users_list'][$value] as $key2 => $item) {
                        $text_key = "[*{$key2}]";
                        $mess = str_replace($text_key, $item, $mess);

                    }
                    $to = $options['users_list'][$value]['email'];
                    $subject = $mess_option['subject'];
                    $headers = 'Content-Type: text/html; charset=UTF-8';
                    wp_mail($to, $subject, $mess, $headers);
                }
            }
        }

        $options['mail'] = get_option(PREFIX . 'send_mail');
        $insert_fields = array_shift($options['users_list']);
        unset($insert_fields['code']);
        unset($insert_fields['tmp_block']);
        $options['insert_fields'] = array_keys($insert_fields);
        $options['users_list'] = get_option(PREFIX . 'bloch_list');
        $this->get_view("mail_page", $options);

    }


    public
    function reserve_from_page()
    {
        if (wp_verify_nonce($_REQUEST['reserve_from_page'], 'reserve_from_page')) {

            switch ($_REQUEST['case']) {
                case "datepicker":
                    $data = $this->get_dey($_REQUEST['data']);
                    $post_ID = $_REQUEST['code'];
                    $list = get_post_meta($post_ID, 'dex_my_list_meta_box', true);
                    if (is_array($list[$data['day_of_week']])) {
                        $now_test = new DateTime("now", new DateTimeZone(get_option(PREFIX)['time_zone']));
                        $t_n = $now_test->format('d.m.Y H:i');
                        foreach ($list[$data['day_of_week']] as $time => $users) {
                            $t1 = $data['data'] . " " . $time;
                            if (!$this->check_data_time($data['data'], $time, $post_ID)) {
                                unset($list[$data['day_of_week']][$time]);
                            } else {
                                if (strtotime($t1) <= strtotime($t_n)) {
                                    unset($list[$data['day_of_week']][$time]);
                                }
                            }
                        }
                    }
                    echo json_encode($list[$data['day_of_week']]);
                    break;
                case "add_user_admin":

                    $data = $_REQUEST['data'];
                    if ($this->check_data_time($data['date'], $data['time'], $data['code'])) {
                        $order = $this->add_block($data);
                        echo ($order) ? __('Reserved', 'shop') : __('Not booked', 'shop');
                    }
                    break;
                default:
                    print_r($_REQUEST);
                    break;
            }
        }
        wp_die();
    }

    public
    function remove_user()
    {

        if (wp_verify_nonce($_POST['user_list_remove'], 'user_list_remove')) {
            switch ($_POST['case']) {
                case"remove" :
                    $block_list = get_option(PREFIX . 'bloch_list');
                    unset($block_list[$_POST['item_id']]);
                    echo update_option(PREFIX . 'bloch_list', $block_list);
                    break;
                case "confirm":
                    echo $this->confirm_user($_POST['item_id']);
                    break;
                default:
                    print_r($_REQUEST);
                    break;
            }
        }
        wp_die();
    }

    function ajax_result()
    {
        if (wp_verify_nonce($_POST['dex_my_setting'], 'dex_my_setting')) {
            switch ($_POST['case']) {
                case 'get_calendar_by_data':
                    $this->get_info_by_data($_POST['data']);
                    break;
                case 'blocked_quest':
                    $data = $_POST['data'];
                    if ($this->check_data_time($data['date'], $data['time'], $data['code'])) {
                        $order = $this->add_block($data);
                        if ($order) {
                            $this->get_payment_form($order);
                        } else {
                            echo "0";
                        }
                    }
                    break;
                default:
                    print_r($_POST);
                    break;
            }
        }
        wp_die();
    }

    private
    function add_block($data)
    {
        $block_list = get_option(PREFIX . 'bloch_list');
        $block_list = (is_array($block_list)) ? $block_list : array();
        $block_key = strtotime($data['date'] . " " . $data['time']) . $data['code'];
        $tmp_time = (int)get_option(PREFIX)['tmp_time'];
        $datetime = new DateTime('Now', new DateTimeZone(get_option(PREFIX)['time_zone']));
        try {
            $datetime->add(new DateInterval('PT' . $tmp_time . 'M'));
            $data['tmp_block'] = $datetime->format('d.m.Y H:i');
            $block_list[$block_key] = $data;
            if (update_option(PREFIX . 'bloch_list', $block_list)) {
                return $block_key;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

    }

    private
    function check_data_time($data, $time, $postId)
    {
        $block_list = get_option(PREFIX . 'bloch_list');
        $block_key = strtotime($data . " " . $time) . $postId;
        if (!isset($block_list[$block_key])) return true;
        $item = $block_list[$block_key];
        $now_test = new DateTime("now", new DateTimeZone(get_option(PREFIX)['time_zone']));
        $t_n = $now_test->format('d.m.Y H:i');
        $tmp_block = $item['tmp_block'];
        if (strtotime($tmp_block) <= strtotime($t_n)) return true;
        return false;
    }

    public
    function block_users_list()
    {
        $this->get_view("header", array('title' => __('List of users', 'shop')));
        $block_list = get_option(PREFIX . 'bloch_list');
        $option = array();
        foreach ($block_list as $key => $item) {
            $data_time = $item['date'] . " " . $item['time'];
            $status = 'error';
            $now_test = new DateTime("now", new DateTimeZone(get_option(PREFIX)['time_zone']));
            $t_n = $now_test->format('d.m.Y H:i');
            if (strtotime($data_time) === strtotime($item['tmp_block'])) {
                $status = "updated";
            } else {
                if (strtotime($t_n) < strtotime($item['tmp_block'])) {
                    $status = 'warning';
                }
            }
            $post = get_post($item['code']);
            $list = get_post_meta($post->ID, 'dex_my_list_meta_box', true);
            $dey = $this->get_dey($data_time);
            $option[$key] = array(
                'user' => array(
                    'name' => $item['name'],
                    'last_name' => $item['lastName'],
                    'phone' => $item['phone'],
                    'email' => $item['email'],
                ),
                'time' => array(
                    'tmp' => $item['tmp_block'],
                    'block' => $data_time,
                    'status' => $status,
                    'dey' => $dey['dey']
                ),
                'post' => array(
                    'name' => $post->post_title,
                    'edit' => get_edit_post_link($post->ID)
                ),
                'users' => array(
                    'prise' => $list[$dey['day_of_week']][$item['time']][$item['user_count']],
                    'count' => $item['user_count']
                )

            );

        }
        $this->get_view('show_blocket_users', $option);
    }


    public
    function dex_my_referral_scripts_css_jquery()
    {
        $connect_resurse = array(

            "js" => array(
                'jquery' => 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',
                'jquery-ui' => 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js',
                'fancybox' => 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js',
                'ckeditor' => 'https://cdn.ckeditor.com/4.9.2/full/ckeditor.js',
                'datepicker-ru' => PLUGIN_URL . 'js/datepicker-ru.js',
                'maskedinput' => PLUGIN_URL . 'js/jquery.maskedinput.min.js',

                'validator' => PLUGIN_URL . 'js/validator.js',
                'main' => PLUGIN_URL . 'js/main.js'
            ),
            "css" => array(
                'jquery-ui' => 'http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
                'fancybox' => 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css',
                'style' => PLUGIN_URL . 'css/style.css'
            )

        );

        $this->include_resourse($connect_resurse);
    }


    public
    function dex_my_reservations()
    {
        $this->get_view("header", array('title' => __('Settings', 'shop')));
        if (wp_verify_nonce($_POST['dex_my_setting'], "dex_my_setting")) {
            update_option(PREFIX, $_POST[PREFIX]);
        }
        $options = get_option(PREFIX);
        $this->get_view('seting', array(
            PREFIX => $options
        ));
    }


    public
    function show_calendar()
    {
        $count_dey = get_option(PREFIX)['enable_dey'];
        $calendar_list = array();
        $dataoptions = $this->get_dey();
        for ($i = 0; $i < $count_dey; $i++) {
            $new_data = $this->get_dey(date('d.m.Y', strtotime($dataoptions['data'] . ' + ' . $i . ' days')));
            array_push($calendar_list, $new_data);
        }
        $this->get_view('show_page_calendar', array(
                'calendar_list' => $calendar_list,
                'now_day' => $dataoptions,
                'control' => get_option(PREFIX)['control']
            )
        );
    }

    private
    function get_dey($data = false)
    {
        $datetime = new DateTime('Now', new DateTimeZone(get_option(PREFIX)['time_zone']));
        if ($data) {
            $datetime = new DateTime($data);
        }

        $day_of_week = $datetime->format('w') - 1;
        $day_of_week = ($day_of_week < 0) ? 6 : $day_of_week;
        $list = get_option(PREFIX)['dey'];
        $list = (is_array($list)) ? $list : array('П', 'В', 'С', 'Ч', 'П', 'С', 'В');
        $resulr = array(
            'day_of_week' => $day_of_week,
            "dey" => $list[$day_of_week],
            "number" => $datetime->format('j'),
            'data' => $datetime->format('d') . "." . $datetime->format('m') . "." . $datetime->format('Y'),
        );
        return $resulr;


    }

    public
    function get_info_by_data($data)
    {

        $my_posts = new WP_Query;
        $myposts = $my_posts->query(array(
            'post_type' => POST_TYPE_QUEST,
            'post_status' => 'publish'
        ));
        $info_array = array();

        foreach ($myposts as $key => $post) {
            $list = get_post_meta($post->ID, 'dex_my_list_meta_box', true);

            if (is_array($list[$data['day_of_week']])) {
                $now_test = new DateTime("now", new DateTimeZone(get_option(PREFIX)['time_zone']));
                $t_n = $now_test->format('d.m.Y H:i');
                foreach ($list[$data['day_of_week']] as $time => $users) {
                    $t1 = $data['data'] . " " . $time;
                    $list[$data['day_of_week']][$time] = array();
                    $list[$data['day_of_week']][$time]['users'] = $users;
                    $list[$data['day_of_week']][$time]['status'] = ($this->check_data_time($data['data'], $time, $post->ID)) ? 'enable' : 'disable';
                    if (strtotime($t1) <= strtotime($t_n)) {
                        unset($list[$data['day_of_week']][$time]);
                    }
                }


            }


            array_push($info_array, array(
                'quest' => array(
                    'code' => $post->ID,
                    'title' => $post->post_title
                ),
                'max_players' => get_post_meta($post->ID, 'dex_my_list_max_players', true),
                'url' => get_post_permalink($post->ID),
                'img' => array(
                    'full' => get_the_post_thumbnail_url($post->ID, 'full'),
                    'thumbnail' => get_the_post_thumbnail_url($post->ID, array(40, 40))
                ),
                'schedule' => $list[$data['day_of_week']]
            ));

        }
        $this->get_view('show_dey_page', $info_array);

    }

    public
    function checkPayment()
    {
        $shop = get_option(PREFIX . 'shop_setting');
        if (trim($_SERVER['REQUEST_URI'], '/') === $shop['protection_link']) {

            switch ($shop['type']) {
                case "1":
                    $hash = sha1($_POST['notification_type'] . '&' . $_POST['operation_id'] . '&' . $_POST['amount'] .
                        '&' . $_POST['currency'] . '&' . $_POST['datetime'] . '&' . $_POST['sender'] . '&' .
                        $_POST['codepro'] . '&' . $shop["secret"] . '&' . $_POST['label']);
                    if (strtolower($hash) === strtolower($_POST['sha1_hash'])) {
                        $this->confirm_user($_POST['label']);
                        die();
                    }
                    break;
                case "2":
                    $hash = md5($_POST['action'] . ';' . $_POST['orderSumAmount'] . ';' . $_POST['orderSumCurrencyPaycash'] . ';' .
                        $_POST['orderSumBankPaycash'] . ';' . $_POST['shopId'] . ';' . $_POST['invoiceId'] . ';' .
                        $_POST['customerNumber'] . ';' . $shop['password']);
                    if (strtolower($hash) != strtolower($_POST['md5'])) {
                        $code = 1;
                    } else {
                        $order_id = $_POST['orderNumber'];
                        $block_list = get_option(PREFIX . 'bloch_list');
                        if (!(isset($block_list[$order_id]))) {
                            $code = 200;
                        } else {
                            $option = array();
                            $option['user'] = $block_list[$order_id];
                            $post = get_post($option['user']['code']);
                            $list = get_post_meta($post->ID, 'dex_my_list_meta_box', true);
                            $data = $this->get_dey($option['user']['date']);
                            $option['sum'] = $list[$data['day_of_week']][$option['user']['time']][$option['user']['user_count']];
                            if ((int)$option['sum'] !== (int)$_POST['orderSumAmount']) {
                                $code = 100;
                            } else {
                                $code = 0;
                                if ($_POST['action'] == 'paymentAviso') {
                                    if (!$this->confirm_user($order_id)) {
                                        $code = 1000;
                                    }
                                }
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
            header('Content-Type: application/xml');
            include(plugin_dir_path(__FILE__) . 'module/yandex-money/views/response_xml.php');

            die();


        }

    }

}

$my_reservations = new My_Reservations();
function print_array($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

add_action('init', 'register_post_types_quest');

function register_post_types_quest()
{
    register_post_type(POST_TYPE_QUEST, array(
        'label' => null,
        'labels' => array(
            'name' => __('Quests', 'shop'),
            'singular_name' => __('Quests', 'shop'),
            'add_new' => __('Add quest', 'shop'),
            'add_new_item' => __('Adding a quest', 'shop'),
            'edit_item' => __('Editing the quest', 'shop'),
            'new_item' => __('New quest', 'shop'),
            'view_item' => __('Watch the quest', 'shop'),
            'search_items' => __('Search for quest', 'shop'),
            'not_found' => __('Not found', 'shop'),
            'not_found_in_trash' => __('Not found in cart', 'shop'),
            'parent_item_colon' => '',
            'menu_name' => __('Quests', 'shop'),
        ),
        'description' => '',
        'public' => true,
        'publicly_queryable' => null,
        'exclude_from_search' => null,
        'show_ui' => null,
        'show_in_menu' => null,
        'show_in_admin_bar' => null,
        'show_in_nav_menus' => null,
        'show_in_rest' => null,
        'rest_base' => null,
        'menu_position' => null,
        'menu_icon' => "dashicons-analytics",
        'hierarchical' => false,
        'supports' => array('title', 'thumbnail'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
        'taxonomies' => array(),
        'has_archive' => false,
        'rewrite' => true,
        'query_var' => true,
    ));
}
