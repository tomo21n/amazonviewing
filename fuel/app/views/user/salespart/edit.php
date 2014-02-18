<h2>Editing SalesPart</h2>
<br>

<?php echo render('user/salespart/_form'); ?>
<p>
	<?php echo Html::anchor('user/salespart/view/'.$salesPart->id, 'View'); ?> |
	<?php echo Html::anchor('user/salespart', 'Back'); ?></p>
