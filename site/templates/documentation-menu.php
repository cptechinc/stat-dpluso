<?php include('./_head.php'); // include header markup ?>
    <div class="jumbotron pagetitle">
        <div class="container">
        	<?php if ($page == $page->rootParent) : ?>
           		 <h1><?php echo $page->get('pagetitle|headline|title') ; ?></h1>
        	<?php else : ?>
           		 <h1><?php echo 'Docs: ' . $page->get('pagetitle|headline|title') ; ?></h1>
           	<?php endif; ?>
           
        </div>
    </div>
    <div class="container page">
    	<div class="col-sm-3">
    		<?php 
			

				// if there's more than 1 page in this section...
				if ($page->hasChildren) {
					// output sidebar navigation
					// see _init.php for the renderNavTree function
					if ($page == $page->rootParent) {
						renderNavTree($page, 1);
					} else {
						renderNavTree($page);
					}
					
				}
			?>
    	</div>
    	<div class="col-sm-9">
    		<ol class="breadcrumb">
    			<?php $parents = $page->parents("template!=home"); ?>
    			<?php foreach ($parents as $parent) : ?>
    				<li><a href="<?php echo $parent->url; ?>"><?php echo $parent->title; ?></a></li>
    			<?php endforeach; ?>
				<li class="active"><?php echo $page->title; ?></li>
			</ol>
    	</div>
		
    </div>
<?php include('./_foot.php'); // include footer markup ?>