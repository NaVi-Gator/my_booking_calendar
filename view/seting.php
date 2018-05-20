<form action="#" method="post">

    <?php wp_nonce_field('dex_my_setting', 'dex_my_setting'); ?>
    <table>
        <?php

        if (is_array($option[PREFIX]['dey'])):
            foreach ($option[PREFIX]['dey'] as $key => $dey): ?>
                <tr>
                    <td> <?= $dey ?>:</td>
                    <td><label>

                            <input type="text" name="<?= PREFIX ?>[dey][<?= $key ?>]" value="<?= $dey ?>">
                        </label>
                    </td>
                </tr>
            <?php endforeach;endif; ?>

        <tr>
            <th><label for="search_time_zone"><?= _e('Time zone', 'shop') ?>:</label></th>
            <td>
                <label>

                    <input id="search_time_zone" name="<?= PREFIX ?>[time_zone]"
                           value="<?= $option[PREFIX]['time_zone'] ?>">
                </label>
            </td>
        </tr>
        <tr>
            <td><?= _e('The number of days available in the calendar', 'shop') ?>:</td>
            <td><label>
                    <input type="number" name="<?= PREFIX ?>[enable_dey]" min="7"
                           value="<?= $option[PREFIX]['enable_dey'] ?>">
                </label>
            </td>
        </tr>
        <tr>
            <td><?= _e('Temporary blockage (min)', 'shop') ?>:</td>
            <td>
                <label>
                    <input type="number" name="<?= PREFIX ?>[tmp_time]" min="1" max="59"
                           value="<?= $option[PREFIX]['tmp_time'] ?>">
                </label>
            </td>
        </tr>
        <tr>
            <td><?= _e('Rules and Policies', 'shop') ?></td>
            <td>
                <label>
                    <textarea name="<?= PREFIX ?>[control]"><?= $option[PREFIX]['control'] ?></textarea>
                </label>
                <script>
                    CKEDITOR.replace('<?= PREFIX ?>[control]');
                </script>
            </td>
        </tr>
    </table>
    <button class="button"><?= _e('Save', 'shop') ?></button>
</form>
<?php
$result = array();

$timezones = array(
    'Pacific/Midway' => "(GMT-11:00)",
    'US/Samoa' => "(GMT-11:00)",
    'US/Hawaii' => "(GMT-10:00)",
    'US/Alaska' => "(GMT-09:00)",
    'US/Pacific' => "(GMT-08:00)",
    'America/Tijuana' => "(GMT-08:00)",
    'US/Arizona' => "(GMT-07:00)",
    'US/Mountain' => "(GMT-07:00)",
    'America/Chihuahua' => "(GMT-07:00)",
    'America/Mazatlan' => "(GMT-07:00)",
    'America/Mexico_City' => "(GMT-06:00)",
    'America/Monterrey' => "(GMT-06:00)",
    'Canada/Saskatchewan' => "(GMT-06:00)",
    'US/Central' => "(GMT-06:00)",
    'US/Eastern' => "(GMT-05:00)",
    'US/East-Indiana' => "(GMT-05:00)",
    'America/Bogota' => "(GMT-05:00)",
    'America/Lima' => "(GMT-05:00)",
    'America/Caracas' => "(GMT-04:30)",
    'Canada/Atlantic' => "(GMT-04:00)",
    'America/La_Paz' => "(GMT-04:00)",
    'America/Santiago' => "(GMT-04:00)",
    'Canada/Newfoundland' => "(GMT-03:30)",
    'America/Buenos_Aires' => "(GMT-03:00)",
    'Greenland' => "(GMT-03:00)",
    'Atlantic/Stanley' => "(GMT-02:00)",
    'Atlantic/Azores' => "(GMT-01:00)",
    'Atlantic/Cape_Verde' => "(GMT-01:00)",
    'Africa/Casablanca' => "(GMT)",
    'Europe/Dublin' => "(GMT)",
    'Europe/Lisbon' => "(GMT)",
    'Europe/London' => "(GMT)",
    'Africa/Monrovia' => "(GMT)",
    'Europe/Amsterdam' => "(GMT+01:00)",
    'Europe/Belgrade' => "(GMT+01:00)",
    'Europe/Berlin' => "(GMT+01:00)",
    'Europe/Bratislava' => "(GMT+01:00)",
    'Europe/Brussels' => "(GMT+01:00)",
    'Europe/Budapest' => "(GMT+01:00)",
    'Europe/Copenhagen' => "(GMT+01:00)",
    'Europe/Ljubljana' => "(GMT+01:00)",
    'Europe/Madrid' => "(GMT+01:00)",
    'Europe/Paris' => "(GMT+01:00)",
    'Europe/Prague' => "(GMT+01:00)",
    'Europe/Rome' => "(GMT+01:00)",
    'Europe/Sarajevo' => "(GMT+01:00)",
    'Europe/Skopje' => "(GMT+01:00)",
    'Europe/Stockholm' => "(GMT+01:00)",
    'Europe/Vienna' => "(GMT+01:00)",
    'Europe/Warsaw' => "(GMT+01:00)",
    'Europe/Zagreb' => "(GMT+01:00)",
    'Europe/Athens' => "(GMT+02:00)",
    'Europe/Bucharest' => "(GMT+02:00)",
    'Africa/Cairo' => "(GMT+02:00)",
    'Africa/Harare' => "(GMT+02:00)",
    'Europe/Helsinki' => "(GMT+02:00)",
    'Europe/Istanbul' => "(GMT+02:00)",
    'Asia/Jerusalem' => "(GMT+02:00)",
    'Europe/Kiev' => "(GMT+02:00)",
    'Europe/Minsk' => "(GMT+02:00)",
    'Europe/Riga' => "(GMT+02:00)",
    'Europe/Sofia' => "(GMT+02:00)",
    'Europe/Tallinn' => "(GMT+02:00)",
    'Europe/Vilnius' => "(GMT+02:00)",
    'Asia/Baghdad' => "(GMT+03:00)",
    'Asia/Kuwait' => "(GMT+03:00)",
    'Africa/Nairobi' => "(GMT+03:00)",
    'Asia/Riyadh' => "(GMT+03:00)",
    'Europe/Moscow' => "(GMT+03:00)",
    'Asia/Tehran' => "(GMT+03:30)",
    'Asia/Baku' => "(GMT+04:00)",
    'Europe/Volgograd' => "(GMT+04:00)",
    'Asia/Muscat' => "(GMT+04:00)",
    'Asia/Tbilisi' => "(GMT+04:00)",
    'Asia/Yerevan' => "(GMT+04:00)",
    'Asia/Kabul' => "(GMT+04:30)",
    'Asia/Karachi' => "(GMT+05:00)",
    'Asia/Tashkent' => "(GMT+05:00)",
    'Asia/Kolkata' => "(GMT+05:30)",
    'Asia/Kathmandu' => "(GMT+05:45)",
    'Asia/Yekaterinburg' => "(GMT+06:00)",
    'Asia/Almaty' => "(GMT+06:00)",
    'Asia/Dhaka' => "(GMT+06:00)",
    'Asia/Novosibirsk' => "(GMT+07:00)",
    'Asia/Bangkok' => "(GMT+07:00)",
    'Asia/Jakarta' => "(GMT+07:00)",
    'Asia/Krasnoyarsk' => "(GMT+08:00)",
    'Asia/Chongqing' => "(GMT+08:00)",
    'Asia/Hong_Kong' => "(GMT+08:00)",
    'Asia/Kuala_Lumpur' => "(GMT+08:00)",
    'Australia/Perth' => "(GMT+08:00)",
    'Asia/Singapore' => "(GMT+08:00)",
    'Asia/Taipei' => "(GMT+08:00)",
    'Asia/Ulaanbaatar' => "(GMT+08:00)",
    'Asia/Urumqi' => "(GMT+08:00)",
    'Asia/Irkutsk' => "(GMT+09:00)",
    'Asia/Seoul' => "(GMT+09:00)",
    'Asia/Tokyo' => "(GMT+09:00)",
    'Australia/Adelaide' => "(GMT+09:30)",
    'Australia/Darwin' => "(GMT+09:30)",
    'Asia/Yakutsk' => "(GMT+10:00)",
    'Australia/Brisbane' => "(GMT+10:00)",
    'Australia/Canberra' => "(GMT+10:00)",
    'Pacific/Guam' => "(GMT+10:00)",
    'Australia/Hobart' => "(GMT+10:00)",
    'Australia/Melbourne' => "(GMT+10:00)",
    'Pacific/Port_Moresby' => "(GMT+10:00)",
    'Australia/Sydney' => "(GMT+10:00)",
    'Asia/Vladivostok' => "(GMT+11:00)",
    'Asia/Magadan' => "(GMT+12:00)",
    'Pacific/Auckland' => "(GMT+12:00)",
    'Pacific/Fiji' => "(GMT+12:00)",
);
foreach ($timezones as $zone => $group) {
    array_push($result, array(
        'category' => $group,
        'label' => $zone
    ));
}


?>
<script type="text/javascript">
    let zone = <?=json_encode($result)?>;
</script>
