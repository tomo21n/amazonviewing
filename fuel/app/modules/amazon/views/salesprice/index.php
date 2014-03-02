<h2>Listing SalesPrices</h2>
<br>
<?php if ($salesPrices): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Part id</th>
			<th>Survey dtm</th>
			<th>Country</th>
			<th>New 1st price</th>
			<th>New 2nd price</th>
			<th>New 3rd price</th>
			<th>Used 1st price</th>
			<th>Used 2nd price</th>
			<th>Used 3rd price</th>
			<th>New 1st qty</th>
			<th>New 2nd qty</th>
			<th>New 3rd qty</th>
			<th>Used 1st qty</th>
			<th>Used 2nd qty</th>
			<th>Used 3rd qty</th>
			<th>New 1st shipfee</th>
			<th>New 2nd shipfee</th>
			<th>New 3rd shipfee</th>
			<th>Used 1st shipfee</th>
			<th>Used 2nd shipfee</th>
			<th>Used 3rd shipfee</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($salesPrices as $item): ?>		<tr>

			<td><?php echo $item->part_id; ?></td>
			<td><?php echo $item->survey_dtm; ?></td>
			<td><?php echo $item->country; ?></td>
			<td><?php echo $item->new_1st_price; ?></td>
			<td><?php echo $item->new_2nd_price; ?></td>
			<td><?php echo $item->new_3rd_price; ?></td>
			<td><?php echo $item->used_1st_price; ?></td>
			<td><?php echo $item->used_2nd_price; ?></td>
			<td><?php echo $item->used_3rd_price; ?></td>
			<td><?php echo $item->new_1st_qty; ?></td>
			<td><?php echo $item->new_2nd_qty; ?></td>
			<td><?php echo $item->new_3rd_qty; ?></td>
			<td><?php echo $item->used_1st_qty; ?></td>
			<td><?php echo $item->used_2nd_qty; ?></td>
			<td><?php echo $item->used_3rd_qty; ?></td>
			<td><?php echo $item->new_1st_shipfee; ?></td>
			<td><?php echo $item->new_2nd_shipfee; ?></td>
			<td><?php echo $item->new_3rd_shipfee; ?></td>
			<td><?php echo $item->used_1st_shipfee; ?></td>
			<td><?php echo $item->used_2nd_shipfee; ?></td>
			<td><?php echo $item->used_3rd_shipfee; ?></td>
			<td>
				<?php echo Html::anchor('user/salesprice/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('user/salesprice/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('user/salesprice/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No SalesPrices.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/salesprice/create', 'Add new SalesPrice', array('class' => 'btn btn-success')); ?>

</p>
