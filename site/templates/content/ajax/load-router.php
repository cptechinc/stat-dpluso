<?php
    switch ($input->urlSegment(1)) {
        case 'customers':
            include $config->paths->content . 'ajax/load/cust-router.php';
            break;
        case 'orders': //ADDED 12/22/2016 $input->urlSegment(3) is going to be cust or salesrep
            include $config->paths->content . 'ajax/load/orders-router.php';
            break;
        case 'quotes': //ADDED 12/22/2016 $input->urlSegment(3) is going to be cust or salesrep
            include $config->paths->content . 'ajax/load/quotes-router.php';
            break;
        case 'notes':
            include $config->paths->content . 'ajax/load/notes-router.php';
            break;
        case 'products':
            include $config->paths->content . 'ajax/load/products-router.php';
            break;
        case 'edit-detail':
            include $config->paths->content . 'ajax/load/edit-detail-router.php';
            break;
        case 'add-detail':
            include $config->paths->content . 'ajax/load/add-detail-router.php';
            break;
        case 'view-detail':
            include $config->paths->content . 'ajax/load/view-detail-router.php';
            break;
        case 'order':
            include $config->paths->content . 'ajax/load/order-router.php';
            break;
        case 'ii':
            include $config->paths->content . 'ajax/load/ii-router.php';
            break;
        case 'ci':
            include $config->paths->content . 'ajax/load/ci-router.php';
            break;
        case 'vi':
            include $config->paths->content . 'ajax/load/vi-router.php';
            break;
        case 'vendors':
            include $config->paths->content . 'ajax/load/vendor-router.php';
            break;
        default:
            throw new Wire404Exception();
            break;
    }
?>
