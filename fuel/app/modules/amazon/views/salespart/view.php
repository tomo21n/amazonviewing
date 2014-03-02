<h2>Viewing #<?php echo $salesPart->id; ?></h2>

<p>
	<strong>Part id:</strong>
	<?php echo $salesPart->part_id; ?></p>
<p>
	<strong>Asin:</strong>
	<?php echo $salesPart->asin; ?></p>
<p>
	<strong>Ean:</strong>
	<?php echo $salesPart->ean; ?></p>
<p>
	<strong>Titile:</strong>
	<?php echo $salesPart->titile; ?></p>
<p>
	<strong>Category:</strong>
	<?php echo $salesPart->category; ?></p>
<p>
	<strong>Url:</strong>
	<?php echo $salesPart->url; ?></p>
<p>
	<strong>Image s:</strong>
	<?php echo $salesPart->image_s; ?></p>
<p>
	<strong>Image l:</strong>
	<?php echo $salesPart->image_l; ?></p>
<p>
	<strong>Volume:</strong>
	<?php echo $salesPart->volume; ?></p>
<p>
	<strong>Wight:</strong>
	<?php echo $salesPart->wight; ?></p>
<p>
	<strong>Release date:</strong>
	<?php echo $salesPart->release_date; ?></p>

<?php echo Html::anchor('user/salespart/edit/'.$salesPart->id, 'Edit'); ?> |
<?php echo Html::anchor('user/salespart', 'Back'); ?>