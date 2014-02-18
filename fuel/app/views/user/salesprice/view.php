<h2>Viewing #<?php echo $salesPrice->id; ?></h2>

<p>
	<strong>Part id:</strong>
	<?php echo $salesPrice->part_id; ?></p>
<p>
	<strong>Survey dtm:</strong>
	<?php echo $salesPrice->survey_dtm; ?></p>
<p>
	<strong>Country:</strong>
	<?php echo $salesPrice->country; ?></p>
<p>
	<strong>New 1st price:</strong>
	<?php echo $salesPrice->new_1st_price; ?></p>
<p>
	<strong>New 2nd price:</strong>
	<?php echo $salesPrice->new_2nd_price; ?></p>
<p>
	<strong>New 3rd price:</strong>
	<?php echo $salesPrice->new_3rd_price; ?></p>
<p>
	<strong>Used 1st price:</strong>
	<?php echo $salesPrice->used_1st_price; ?></p>
<p>
	<strong>Used 2nd price:</strong>
	<?php echo $salesPrice->used_2nd_price; ?></p>
<p>
	<strong>Used 3rd price:</strong>
	<?php echo $salesPrice->used_3rd_price; ?></p>
<p>
	<strong>New 1st qty:</strong>
	<?php echo $salesPrice->new_1st_qty; ?></p>
<p>
	<strong>New 2nd qty:</strong>
	<?php echo $salesPrice->new_2nd_qty; ?></p>
<p>
	<strong>New 3rd qty:</strong>
	<?php echo $salesPrice->new_3rd_qty; ?></p>
<p>
	<strong>Used 1st qty:</strong>
	<?php echo $salesPrice->used_1st_qty; ?></p>
<p>
	<strong>Used 2nd qty:</strong>
	<?php echo $salesPrice->used_2nd_qty; ?></p>
<p>
	<strong>Used 3rd qty:</strong>
	<?php echo $salesPrice->used_3rd_qty; ?></p>
<p>
	<strong>New 1st shipfee:</strong>
	<?php echo $salesPrice->new_1st_shipfee; ?></p>
<p>
	<strong>New 2nd shipfee:</strong>
	<?php echo $salesPrice->new_2nd_shipfee; ?></p>
<p>
	<strong>New 3rd shipfee:</strong>
	<?php echo $salesPrice->new_3rd_shipfee; ?></p>
<p>
	<strong>Used 1st shipfee:</strong>
	<?php echo $salesPrice->used_1st_shipfee; ?></p>
<p>
	<strong>Used 2nd shipfee:</strong>
	<?php echo $salesPrice->used_2nd_shipfee; ?></p>
<p>
	<strong>Used 3rd shipfee:</strong>
	<?php echo $salesPrice->used_3rd_shipfee; ?></p>

<?php echo Html::anchor('user/salesprice/edit/'.$salesPrice->id, 'Edit'); ?> |
<?php echo Html::anchor('user/salesprice', 'Back'); ?>