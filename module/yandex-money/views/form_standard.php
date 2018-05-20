<form method="post" action="<?= $shop['url'] ?>" id="payment_form">
    <input type="hidden" name="receiver" value="<?= $shop['account']; ?>"/>
    <input type="hidden" name="formcomment" value="<?= $shop['payment_desc']; ?>"/>
    <input type="hidden" name="short-dest" value="<?= $shop['payment_desc']; ?>"/>
    <input type="hidden" name="quickpay-form" value="shop"/>
    <input type="hidden" name="targets" value="<?= $shop['payment_desc']; ?>"/>
    <input type="hidden" name="sum" value="<?= $option['sum'] ?>"/>
    <input type="hidden" name="label" value="<?= $option['id'] ?>"/>
    <input type="hidden" name="need-fio" value="false"/>
    <input type="hidden" name="need-email" value="false"/>
    <input type="hidden" name="need-phone" value="false"/>
    <input type="hidden" name="need-address" value="false"/>
    <input type="hidden" name="paymentType" value="<?= implode(',', $shop['st_payment_type']); ?>"/>
    <!--<input type="submit" value="<?php _e('Click to continue', 'shop'); ?>"/>-->
</form>
