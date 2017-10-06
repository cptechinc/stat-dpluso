<?php
    $tb = new Table('class=order');
    $tb->section('thead');
        $tb->row('');
        $tb->headercell('', 'Item');
        $tb->headercell('', 'Qty.');
        $tb->headercell('', 'Price');
        $tb->headercell('', 'Ext. Price');
    $tb->closesection('thead');
    $tb->section('tbody');
        foreach($orderdetails as $detail) {
            $tb->row('');
            $tb->cell('', $detail['itemid']);
            $tb->cell('', $detail['qtyordered']);
            $tb->cell('', formatmoney($detail['price']). ' / '. $detail['uom']);
            $tb->cell('class="text-right"', formatmoney($detail['extamt']));

        }
        $tb->row('');
        $tb->cell('colspan=2', 'Tax');
        $tb->cell('colspan=2', 'TBD');
        $tb->row('');
        $tb->cell('colspan=2', 'Subtotal');
        $tb->cell('colspan=2', '200.00');
    $tb->closesection('tbody');
    $orderdetailtable = $tb->close();
