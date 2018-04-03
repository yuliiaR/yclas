<?php defined('SYSPATH') or die('No direct script access.');?>

<button class="btn btn-info pay-btn full-w" onclick="openInvoice()"><?= _e('Pay with Bitcoin') ?></button>
<script src="https://bitpay.com/bitpay.js"></script>
<script>
    function openInvoice() {
        bitpay.setApiUrlPrefix("<?= Core::config('payment.bitpay_sandbox') == 1 ? 'https://test.bitpay.com' : 'https://bitpay.com'?>")
        bitpay.showInvoice("<?= $invoice->getId() ?>");
    }
</script>