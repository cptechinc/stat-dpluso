<?php
    switch ($page->name) { //$page->name is what we are printing
        case 'order':
            $ordn = $input->get->text('ordn');
            $editorder = array();
            $editorder['ordn'] = $ordn;
            $page->title = 'Viewing Order #' . $ordn;
			$includefile = $config->paths->content."print/orders/outline.php";
            break;
        case 'quote':
            $qnbr = $input->get->text('qnbr');
            $editorder = array();
            $editquote['qnbr'] = $qnbr;
            $page->title = 'Viewing Quote #' . $qnbr;
            $includefile = $config->paths->content."print/quotes/outline.php";
            break;
    }
 ?>
 <?php include('./_head-blank.php'); // include header markup ?>
     <div class="container page">
        <?php include ($includefile); ?>
     </div>
 <?php include('./_foot-blank.php'); // include footer markup ?>
