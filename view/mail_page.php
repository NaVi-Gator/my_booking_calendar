<form action="#" method="post">
    <?php wp_nonce_field('send_mail', 'send_mail'); ?>
    <table>
        <tr>
            <td><?= _e('Subject', 'shop') ?>:</td>
            <td>
                <label style="width: 100%">
                    <input name="mail[subject]" style="width: 100%" type="text"
                           value="<?= $option['mail']['subject'] ?>">
                </label>
            </td>
        </tr>
        <tr>
            <td><?= _e('Text', 'shop') ?>:</td>
            <td>
                <p class="list_fields"><?= _e('Fields for insertion', 'shop') ?>
                    : <?php foreach ($option['insert_fields'] as $key => $value) {
                        echo "<span>[*{$value}]</span>";
                    }
                    ?>  </p>

                <label>
                    <textarea name="mail[mess]"><?= $option['mail']['mess'] ?></textarea>
                </label>
                <script>
                    CKEDITOR.replace('mail[mess]');
                </script>
            </td>
        </tr>
        <tr>
            <td><?= _e('List of recipients', 'shop') ?>:</td>

        </tr>
    </table>
    <table class="users">
        <tr>
            <th></th>
            <?php foreach ($option['insert_fields'] as $key2 => $value) {
                echo "<th>[*{$value}]</th>";
            }
            ?>
        </tr>

        <?php foreach ($option['users_list'] as $key => $item): ?>
            <tr>
                <td><label>
                        <input type="checkbox" name="send" value="<?= $key ?>">
                    </label>
                </td>
                <?php foreach ($option['insert_fields'] as $key2 => $value) {
                    echo "<td>" . $item[$value] . "</td>";
                }
                ?>
            </tr>
        <?php
        endforeach;
        ?>
    </table>
    <button class="button"><?= _e('Send', 'shop') ?></button>
</form>
