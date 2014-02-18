<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php echo Asset::css('bootstrap.css'); ?>
	<style>
		body { margin: 50px; }
	</style>
	<?php echo Asset::js(array(
		'http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js',
		'bootstrap.js'
	)); ?>
	<script>
		$(function(){ $('.topbar').dropdown(); });
	</script>
</head>
<body>

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">AmazonViewing</a>
			</div>
            <?php if ($current_user): ?>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
                    <?php if(Uri::segment(1) == 'admin'): ?>
                        <li class="<?php echo Uri::segment(2) == '' ? 'active' : '' ?>">
                            <?php echo Html::anchor('admin', 'Dashboard') ?>
                        </li>

                        <?php
                        $files = new GlobIterator(APPPATH.'classes/controller/admin/*.php');
                        foreach($files as $file)
                        {
                            $section_segment = $file->getBasename('.php');
                            $section_title = Inflector::humanize($section_segment);
                            ?>
                            <li class="<?php echo Uri::segment(2) == $section_segment ? 'active' : '' ?>">
                                <?php echo Html::anchor('admin/'.$section_segment, $section_title) ?>
                            </li>
                        <?php
                        }
                        ?>
                    <?php endif; ?>
                    <?php if(Uri::segment(1) == 'user'): ?>
                        <li class="<?php echo Uri::segment(2) == '' ? 'active' : '' ?>">
                            <?php echo Html::anchor('user', 'Dashboard') ?>
                        </li>
                        <li class="dropdown <?php echo Uri::segment(2) == 'myinventory' ? 'active' : '' ?>">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">在庫<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?php echo Html::anchor('user/inventory/', '在庫一覧') ?></li>
                                <li><?php echo Html::anchor('user/inventory/create', '個別登録') ?></li>
                                <li><?php echo Html::anchor('user/inventory/bulk', '一括アップロード') ?></li>
                            </ul>
                        </li>
                        <li class="dropdown <?php echo Uri::segment(2) == 'myauction' ? 'active' : '' ?>">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">ヤフオク<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?php echo Html::anchor('user/myauction/accesstokenlist', 'アクセストークン') ?></li>
                                <li><?php echo Html::anchor('user/myauction/', '出品一覧') ?></li>
                            </ul>
                        </li>

                        <li class="dropdown <?php echo Uri::segment(2) == 'useraccount' ? 'active' : '' ?>">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">アカウント<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?php echo Html::anchor('user/useraccount/edit', 'アカウント編集') ?></li>
                                <li><?php echo Html::anchor('user/useraccount/changepassword', 'パスワード変更') ?></li>
                            </ul>
                        </li>
                    <?php endif; ?>
				</ul>
				<ul class="nav navbar-nav pull-right">
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $current_user->nickname ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
                            <?php if(Uri::segment(1) == 'user'): ?>
                            <li><?php echo Html::anchor('user/logout', 'Logout') ?></li>
                            <?php elseif(Uri::segment(1) == 'admin'): ?>
                            <li><?php echo Html::anchor('user/logout', 'Logout') ?></li>
                            <?php endif; ?>

                        </ul>
					</li>
				</ul>
			</div>
            <?php endif; ?>
        </div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1><?php echo $title; ?></h1>
				<hr>
<?php if (Session::get_flash('success')): ?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p>
					<?php echo implode('</p><p>', (array) Session::get_flash('success')); ?>
					</p>
				</div>
<?php endif; ?>
<?php if (Session::get_flash('error')): ?>
				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p>
					<?php echo implode('</p><p>', (array) Session::get_flash('error')); ?>
					</p>
				</div>
<?php endif; ?>
			</div>
			<div class="col-md-12">
<?php echo $content; ?>
			</div>
		</div>
		<hr/>
		<footer>
			<p class="pull-right">Amazon Viewing  all right reserved.</p>
		</footer>
	</div>
</body>
</html>
