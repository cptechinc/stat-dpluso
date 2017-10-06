<?php $carthead = getcarthead(session_id(), false); ?>
<?php include $config->paths->content."/cart/cart-details.php"; ?>
<br>
<a href="<?php echo $config->pages->cart."redir/?action=create-sales-order"; ?>" class="btn btn-success">Create Sales Order</a>

<a href="<?php echo $config->pages->cart."redir/?action=create-quote"; ?>" class="btn btn-success">Create Quote</a>
