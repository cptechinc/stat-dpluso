<?php $carthead = getcarthead(session_id(), false); ?>
<?php include $config->paths->content."/cart/cart-details.php"; ?>
<br>
<a href="<?php echo $config->pages->cart."redir/?action=create-sales-order"; ?>" class="btn btn-success">
    <span class="fa-stack fa-md">
      <i class="fa fa-usd fa-stack-1x"></i>
      <i class="fa fa-file-o fa-stack-2x"></i>
    </span>
    Create Sales Order
</a>

<a href="<?php echo $config->pages->cart."redir/?action=create-quote"; ?>" class="btn btn- btn-success">
    <span class="fa-stack fa-md" aria-hidden="true">
      <i class="fa fa-quote-left fa-stack-1x"></i>
      <i class="fa fa-file-o fa-stack-2x"></i>
    </span>
    Create Quote
</a>
