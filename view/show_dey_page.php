<?php foreach ($option as $key => $item) { ?>
    <div class="quest" data-quest='<?= json_encode($item['quest']) ?>'>
        <div class="top">
            <a data-fancybox="gallery" href="<?= $item['img']['full'] ?>">
                <img src="<?= $item['img']['thumbnail'] ?>" alt="<?= $item['title'] ?>">
            </a>
            <a target="_blank" href="<?= $item['url'] ?>" class="title">
                <?= $item['quest']['title'] ?>
            </a>
            <p class="count"><?= _e('Number of Players', 'shop') ?>: <span><?= $item['max_players'] ?></span></p>
        </div>
        <div class="schedule">
            <?php if (is_array($item['schedule'])):
                foreach ($item['schedule'] as $time => $users):
                    ?>
                    <div class="time <?= $users['status'] ?>" data-time="<?= $time ?>"
                         data-time-options='<?= json_encode($users['users']) ?>'>
                        <p class="time_text"><?= $time ?></p>
                        <p class="prise"><?= array_shift($users['users']) ?> .<?= _e('rub', 'shop') ?></p>
                    </div>
                <?php
                endforeach;
            else: ?>
                <p><?= _e('No schedule created', 'shop') ?></p>
            <?php endif; ?>

        </div>
    </div>
<?php } ?>