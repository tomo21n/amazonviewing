<h2>Editing SalesPrice</h2>
<br>

<?php echo render('user/salesprice/_form'); ?>
<p>
	<?php echo Html::anchor('user/salesprice/view/'.$salesPrice->id, 'View'); ?> |
	<?php echo Html::anchor('user/salesprice', 'Back'); ?></p>
