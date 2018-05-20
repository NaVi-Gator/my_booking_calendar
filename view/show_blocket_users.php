<form action="<?= admin_url('admin-ajax.php') ?>" method="post" id="users_block_list">
    <input type="hidden" name="action" value="remove_user">
    <?php wp_nonce_field('user_list_remove', 'user_list_remove'); ?>
    <?php foreach ($option as $item => $value) : ?>
        <div class="user <?= $value['time']['status'] ?>">
            <div data-remove="<?= $item ?>" class="remove"><?= _e('Remove', 'shop') ?></div>
            <?php if ($value['time']['tmp'] !== $value['time']['block']) { ?>
                <div data-confirm="<?= $item ?>" class="confirm"><?= _e('Confirm', 'shop') ?></div>
            <?php } ?>
            <div class="user_info">
                <p class="name"><?= _e('Name', 'shop') ?>: <span><?= $value['user']['name'] ?></span></p>
                <p class="last_name"><?= _e('Surname', 'shop') ?>: <span><?= $value['user']['last_name'] ?></span></p>
                <p class="phone"><?= _e('Phone', 'shop') ?>: <a
                            href="tel:<?= $value['user']['phone'] ?>"><?= $value['user']['phone'] ?></a></p>
                <p class="email"><?= _e('Email', 'shop') ?>: <a
                            href="mailto:<?= $value['user']['email'] ?>"><?= $value['user']['email'] ?></a></p>
            </div>
            <div class="time_info">
                <p class="tmp"><?= _e('Temporary booking', 'shop') ?>: <span><?= $value['time']['tmp'] ?></span></p>
                <p class="block"><?= _e('Event date', 'shop') ?>:
                    <span><?= $value['time']['dey'] . " " . $value['time']['block'] ?></span></p>
            </div>
            <div class="users">
                <p class="count"><?= _e('Players', 'shop') ?>: <span><?= $value['users']['count'] ?></span></p>
                <p class="prise"><?= _e('Price', 'shop') ?>: <span><?= $value['users']['prise'] ?>
                        .<?= _e('rub', 'shop') ?></span></p>
            </div>
            <a class="edit" href="<?= $value['post']['edit'] ?>" target="_blank"><?= $value['post']['name'] ?></a>
        </div>
    <?php endforeach; ?>
</form>


<div data-fancybox="" data-src="#popup_window" class="button"><?= _e('Reservation', 'shop') ?></div>
<div id="popup_window" style="display: none">
    <form action="<?= admin_url('admin-ajax.php') ?>" method="post">
        <div class="send_bloc">
            <?php wp_nonce_field('reserve_from_page', 'reserve_from_page'); ?>
            <input type="hidden" name="action" value="reserve_from_page">


            <label>Квест:
                <select name="code">
                    <?php
                    $my_posts = new WP_Query;
                    $myposts = $my_posts->query(array(
                        'post_type' => POST_TYPE_QUEST,
                        'post_status' => 'publish'
                    ));
                    $info_array = array();
                    foreach ($myposts as $key => $post) {

                        echo "<option value='{$post->ID}'>{$post->post_title}</option>";
                    }
                    ?>

                </select>
            </label>
            <input type="hidden" name="code" value="<?= $_REQUEST['post'] ?>">
            <div class="user_info">
                <div class="top">
                    <label><span><?= _e('Date', 'shop') ?>:</span>
                        <input type="text" name="date" id="datepicker" value="">
                    </label>
                    <label><span><?= _e('Time', 'shop') ?>:</span>
                        <select name="time"></select>
                    </label>
                    <label><span><?= _e('Number of Players', 'shop') ?>:</span>
                        <select name="user_count" type="select"></select>
                    </label>
                </div>
                <input type="text" name="name" placeholder="Ваше Имя">
                <input type="text" name="lastName" placeholder="Ваша Фамилия">
                <input type="tel" name="phone" placeholder="Ваш Телефон">
                <input type="email" name="email" placeholder="Ваш Email">

            </div>
            <div class="button block_button admin"><?= _e('To book', 'shop') ?></div>
        </div>
    </form>
</div>

