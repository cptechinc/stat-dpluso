<div id="yt-menu" class="row">
	<div class="col-xs-12">
		<?php if ($user->loggedin) : ?>

		<?php else : ?>
			<br>
		<?php endif; ?>
		<nav>
			<ul class="nav list-unstyled">
				<?php if ($user->loggedin) : ?>
					<li class="welcome"><a href="#">Welcome, <?= $user->fullname ?></a> </li>
				<?php else : ?>

				<?php endif; ?>

				<li> <a href="<?= $config->pages->index; ?>"><i class="glyphicon glyphicon-home"></i> Home</a> </li>
				<li> <a href="<?= $config->pages->customer; ?>"><i class="fa fa-address-book" aria-hidden="true"></i> Customers</a> </li>
				<li> <a href="<?= $config->pages->dashboard; ?>"><i class="glyphicon glyphicon-blackboard"></i> Dashboard</a>
				<li> <a href="<?= $config->pages->cart; ?>"> <i class="glyphicon glyphicon-list-alt"></i> Quote (<?php //echo get_cart_count(session_id()); ?>)</a> </li> </li>
				<li> <a href="<?= $config->pages->user; ?>"><i class="fa fa-user-circle" aria-hidden="true"></i> User</a> </li>
				<li class="divider"></li>
				<li class="dropdown-header">Info Screens</li>
				<li> <a href="<?= $config->pages->iteminfo; ?>"><i class="fa fa-diamond" aria-hidden="true"></i> Item Info</a> </li>
				<li> <a href="<?= $config->pages->custinfo; ?>"><i class="fa fa-users" aria-hidden="true"></i> Cust Info</a> </li>
				<li><a href="<?= $config->pages->vendorinfo; ?>"><i class="fa fa-cubes" aria-hidden="true"></i> Vendor Info</a></li>
				<li class="divider"></li>
				<li> <a href="<?= $config->pages->documentation; ?>"> <i class="fa fa-book" aria-hidden="true"></i> Documentation</a> </li>
				<li class="divider"></li>

				<?php if ($user->loggedin) : ?>
				<li class="logout">
					<a href="<?= $config->pages->account; ?>redir/?action=logout" class="logout">
						<span class="glyphicon glyphicon-log-out"></span> Logout
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</nav>
    </div>
</div>
