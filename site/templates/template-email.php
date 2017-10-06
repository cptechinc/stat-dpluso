<?php
    $order = json_decode(file_get_contents($config->paths->content.'email/test/order-head.json'), true);
    $orderdetails = json_decode(file_get_contents($config->paths->content.'email/test/order-details.json'), true);
    $css = file_get_contents($config->paths->templates.'styles/email/email-styles.css');
    include ($config->paths->content.'email/templates/order-details-table.php');
    include ($config->paths->content.'email/templates/order-confirmation.php');
    $send = SimpleMail::make()
    ->setTo('paul@cptechinc.com', 'Paul Gomez')
    ->setFrom('sales@cptechinc.com', 'CPTech Sales')
    ->setSubject('Order Confirmation')
    ->setMessage($page->body)
    ->setReplyTo('sales@cptechinc.com', 'Cptech Sales')
    ->setHtml()
    //->setWrap(100)
    //->send();
    ;
    //echo ($send) ? 'Email sent successfully' : 'Could not send email';

    echo $page->body;
