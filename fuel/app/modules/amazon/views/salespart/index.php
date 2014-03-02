<h2>Listing SalesParts</h2>
<br>
<?php if ($salesParts): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Part id</th>
			<th>Asin</th>
			<th>Ean</th>
			<th>Title</th>
			<th>Category</th>
			<th>Url</th>
			<th>Image s</th>
			<th>Image l</th>
			<th>Volume</th>
			<th>Wight</th>
			<th>Release date</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($salesParts as $item): ?>		<tr>

			<td><?php echo $item->part_id; ?></td>
			<td><?php echo $item->asin; ?></td>
			<td><?php echo $item->ean; ?></td>
			<td><?php echo $item->title; ?></td>
			<td><?php echo $item->category; ?></td>
			<td><?php echo $item->url; ?></td>
			<td><?php echo $item->image_s; ?></td>
			<td><?php echo $item->image_l; ?></td>
			<td><?php echo $item->volume; ?></td>
			<td><?php echo $item->wight; ?></td>
			<td><?php echo $item->release_date; ?></td>
			<td>
				<?php echo Html::anchor('user/salespart/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('user/salespart/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('user/salespart/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No SalesParts.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('user/salespart/create', 'Add new SalesPart', array('class' => 'btn btn-success')); ?>

</p>
