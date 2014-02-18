<h2>Viewing #<?php echo $inventory->id; ?></h2>

<p>
	<strong>User id:</strong>
	<?php echo $inventory->user_id; ?></p>
<p>
	<strong>Part id:</strong>
	<?php echo $inventory->part_id; ?></p>
<p>
	<strong>Sku:</strong>
	<?php echo $inventory->sku; ?></p>
<p>
	<strong>Sale count:</strong>
	<?php echo $inventory->sale_count; ?></p>
<p>
	<strong>Sale price:</strong>
	<?php echo $inventory->sale_price; ?></p>
<p>
	<strong>Condition:</strong>
	<?php echo $inventory->condition; ?></p>
<p>
	<strong>Channel:</strong>
	<?php echo $inventory->channel; ?></p>
<p>
	<strong>Comment:</strong>
	<?php echo $inventory->comment; ?></p>

<?php echo Html::anchor('admin/inventory/edit/'.$inventory->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/inventory', 'Back'); ?>