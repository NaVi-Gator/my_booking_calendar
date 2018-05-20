<form method="post" action="<?= $shop['url'] ?>" id="payment_form">
    <input type="hidden" name="shopId" value="<?= $shop['sid'] ?>"/>
    <input type="hidden" name="scid" value="<?= $shop['scid'] ?>"/>
    <input type="hidden" name="sum" value="<?= $option['sum'] ?>"/>
    <input type="hidden" name="customerNumber" value="<?= $option['id'] ?>"/>
    <input type="hidden" name="orderNumber" value="<?= $option['id'] ?>"/>
    <input type="hidden" name="cps_email" value="<?= $option['user']['email'] ?>"/>
    <input type="hidden" name="cps_phone" value="<?= $option['user']['phone'] ?>"/>
    <input type="hidden" name="shopSuccessURL" value="<?= get_permalink($shop['success_page']); ?>"/>
    <input type="hidden" name="shopFailURL" value="<?= get_permalink($shop['fail_page']); ?>"/>
    <input type="hidden" name="paymentType" value="<?= implode(',', $shop['payment_type']); ?>"/>
    <!--<input type="submit" value="<?php _e('Click to continue', 'shop'); ?>"/>-->
</form>
