<?php wp_nonce_field('dex_my_list_meta_box', 'dex_my_list_meta_box'); ?>
<label><?= _e('Number of participants', 'shop') ?>:
    <input type="text" name="max_players" value="<?= $option['max_players'] ?>">
</label>
<div id="tabs">
    <ul class="show_dey">
        <?php foreach ($option['dey_list'] as $key => $value) { ?>
            <li><a href="#<?= PREFIX ?>_tabs_<?= $key ?>"> <?= $value ?></a></li>
        <?php } ?>
    </ul>
    <?php
    foreach ($option['dey_list'] as $key => $value) { ?>
        <div id="<?= PREFIX ?>_tabs_<?= $key ?>">
            <table>
                <thead>
                <tr>
                    <th><?= _e('Time', 'shop') ?></th>
                    <th><?= _e('Number of participants', 'shop') ?></th>
                    <th><?= _e('Remove', 'shop') ?></th>

                </tr>
                </thead>
                <tbody data-dey-marker="<?= $key ?>">
                <?php
                $items_list = $option['user_list_game'][$key];
                if (is_array($items_list)):
                    foreach ($items_list as $time => $prises):
                        ?>
                        <tr data-sort-time='<?= $time ?>' class='remove_block'>
                            <td><?= $time ?></td>
                            <td>
                                <table>
                                    <thead>
                                    <tr>
                                        <th><?= _e('Amount', 'shop') ?></th>
                                        <th><?= _e('Price', 'shop') ?></th>
                                        <th><input type='number' min='1' value='1' placeholder="">
                                            <div class='add button'>+</div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody data-time-marker='<?= $time ?>'>
                                    <?php
                                    if (is_array($prises)):
                                        foreach ($prises as $count => $prise): ?>

                                            <tr class="remove_count">
                                                <td><?= $count ?></td>
                                                <td><input type="number"
                                                           name="users_gra[<?= $key ?>][<?= $time ?>][<?= $count ?>]"
                                                           min="1" placeholder="" value="<?= $prise ?>">
                                                </td>
                                                <td>
                                                    <div class="remove_sub button">-</div>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    endif; ?>
                                    </tbody>
                                </table>
                            </td>
                            <td class='button remove_time'>-</td>
                        </tr>

                    <?php endforeach;
                endif; ?>
                </tbody>
            </table>
            <div class="add_time_block">
                <label><?= _e('Time', 'shop') ?>
                    <input type="time" name="time_add_custom">
                </label>
                <div class="add_time button" data-marker="<?= $key ?>"><?= _e('Add time', 'shop') ?></div>
            </div>
        </div>
    <?php } ?>
</div>
