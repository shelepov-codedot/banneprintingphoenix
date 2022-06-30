<?php
$date = date("F j, Y");
$total = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>

    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            font-size: 100%;
            font-family: 'Avenir Next', "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            line-height: 1.65;
        }

        img {
            max-width: 100%;
            margin: 0 auto;
            display: block; }

        body,
        .body-wrap {
            width: 100% !important;
            height: 100%;
            background: #efefef;
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
        }

        .container {
            display: block !important;
            clear: both !important;
            margin: 0 auto !important;
            max-width: 680px !important;
        }

        .container table {
            width: 100% !important;
            border-collapse: collapse;
        }

        .container .masthead {
            padding: 20px 30px;
            background: #EE21C9;
            color: white;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .container .masthead h1 {
            font-size: 24px;
            font-weight: 400;
            line-height: 62px;
            color: #fff;
        }

        .container .table-content {
            border: 1px solid #E6E6E6;
        }

        .container .table-content__inner {
            background: white;
            padding: 30px 55px;
        }

        .container .table-content__inner h2 {
            font-size: 20px;
            color: #EE21C9;
            margin-bottom: 20px;
        }

        .container .table-content__inner p {
            font-weight: 400;
            line-height: 20px;
            margin-bottom: 10px;
        }

        .container .table-content__inner p a {
            color: #EE21C9;
        }

        .table-order {
            border: 3px solid #E6E6E6;
            margin-bottom: 45px;
        }

        .table-order thead td {
            border: 3px solid #E6E6E6;
            padding: 10px;
        }

        .table-order tbody td .table-order__product-title {
            margin-bottom: 20px;
        }

        .table-order tbody tr td{
            border: 3px solid #E6E6E6;
            padding: 10px;
        }

        .table-order tfoot tr td{
            border: 3px solid #E6E6E6;
            padding: 10px;
        }

        .container .table-content__address {
            border: 1px solid #E6E6E6;
            margin-bottom: 45px;
        }

        .container .table-content__address p {
            margin: 0;
        }

        .container .table-content__address p:first-child {
            font-style: italic;
        }

        .container .table-content__address .customer-name {
            font-style: normal;
            font-weight: 700;
        }

        .container .table-content__address tr td {
            padding: 15px;
        }

        .table-content__footer {
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .container .table-content__footer p {
            margin-bottom: 0;
            color: #000000;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
<table class="body-wrap">
    <tr>
        <td class="container">
            <table style="margin-top: 20px">
                <tr>
                    <td align="left" class="masthead">
                        <h1>New Custom Order for Customer</h1>
                    </td>
                </tr>
                <tr class="table-content">
                    <td class="table-content__inner">

                        <p>Hi,</p>

                        <p>This is a custom invoice from <a href="https://bannerprintingphoenix.com/">https://bannerprintingphoenix.com/</a>. </p>

                        <h2>[Custom Order] (<?= $date ?>)</h2>

                        <table class="table-order">
                            <thead>
                            <tr>
                                <td>Product</td>
                                <td width="25%">Quantity</td>
                                <td width="25%">Price</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($order['products'] as $product):
                                $price = get_post_meta($product['id'], '_regular_price', true);
                                $price = $price * $product['count'];

                                if ($product['customize']['new_name']) {
                                    $price = $product['customize']['new_price'] * $product['count'];
                                }
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($product['customize']['new_name']): ?>
                                            <p class="table-order__product-title"><?= $product['customize']['new_name'] ?></p>
                                        <?php else: ?>
                                            <p class="table-order__product-title"><?= $product['name'] ?></p>
                                        <?php endif; ?>

                                        <?php if ($product['options']):
                                            foreach ($product['options'] as $option): ?>
                                                <p><?= $option['name'] ?>: <?= $option['value'] ?></p>
                                            <?php endforeach;
                                        endif;?>
                                    </td>
                                    <td><?= $product['count'] ?></td>
                                    <td><?= get_woocommerce_currency_symbol() ?><?= $price ?></td>
                                </tr>
                                <?php $total += $price; endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2" style="text-align:left">Total:</td><td><?= get_woocommerce_currency_symbol() ?><?= $total ?></td>
                            </tr>
                            </tfoot>
                        </table>

                        <h2>Billing address</h2>

                        <table class="table-content__address">
                            <tr>
                                <td>
                                    <?php
                                    $customer_data = $order['account'];
                                    if (!empty($customer_data[4]['value']) && !empty($customer_data[5]['value']) && !empty($customer_data[6]['value']) && !empty($customer_data[7]['value']) && !empty($customer_data[8]['value']) && !empty($customer_data[9]['value'])):
                                        if (isset($customer_data[2]['value']) || $customer_data[3]['value']) {
                                            $name = $customer_data[2]['value'] . ' ' . $customer_data[3]['value'];
                                        }
                                        $address = $customer_data[4]['value'] . ', ' . $customer_data[5]['value'] . ', ' . $customer_data[8]['value'] . ', ' . $customer_data[9]['value'] . ', ' . $customer_data[6]['value'];
                                        ?>
                                        <p class="customer-name"><?= $name ?></p>
                                        <p class="customer-address"><?= $address ?></p>
                                    <?php else:?>
                                        <p>N/A</p>
                                    <?php endif; ?>
                                    <p><a href="mailto:<?php echo $order['account'][0]['value'] ?>"><?php echo $order['account'][0]['value'] ?></a></p>
                                </td>
                            </tr>
                        </table>

                        <p>Thanks for using <a href="https://bannerprintingphoenix.com">bannerprintingphoenix.com</a></p>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="container">
            <table class="table-content__footer">
                <tr>
                    <td align="center">
                        <p>Banner Printing Phoenix - best price and quality - Built with WooCommerce</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>