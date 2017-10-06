<?php include('./_head.php'); // include header markup ?>
    <div class="jumbotron pagetitle">
        <div class="container">
            <h1><?php echo $page->parent()->title . ' - ' . $page->get('pagetitle|headline|title') ; ?></h1>
        </div>
    </div>
    <div class="container page">
    	<div class="col-sm-3">
   			<?php include $config->paths->content."documentation/documentation-menu.php"; ?>
    	</div>
    	<div class="col-sm-9">
    		<ol class="breadcrumb">
    			<?php $parents = $page->parents("template!=home"); ?>
    			<?php foreach ($parents as $parent) : ?>
    				<li><a href="<?php echo $parent->url; ?>"><?php echo $parent->title; ?></a></li>
    			<?php endforeach; ?>
				<li class="active"><?php echo $page->title; ?></li>
			</ol>
    		<h2><?= $page->title; ?></h2>
    		<p><?php echo $page->body; ?></p>
    		<?php if (strlen($page->functionhotkey) > 0) : ?>
    			<h4>Keyboard Key: <kbd><?= $page->functionhotkey; ?></kbd></h4>
    		<?php endif; ?>
    		
    		
    	</div>
		
    </div>
<?php include('./_foot.php'); // include footer markup ?>