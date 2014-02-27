<h2>Listing Inventories</h2>
<br>
<?php if ($inventories): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>ASIN</th>
			<th>SKU</th>
			<th>タイトル</th>
			<th>販売チャネル</th>
			<th>出品数</th>
			<th>出品価格</th>
			<th>最低価格１</th>
			<th>最低価格２</th>
			<th>最低価格３</th>
			<th>備考</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($inventories as $item): ?>		<tr>

            <td><?php echo $item->salespart->asin; ?></td>
            <td><?php echo $item->sku; ?></td>
            <td><?php echo $item->salespart->title; ?></td>
			<td><?php echo $item->channel; ?></td>
			<td><?php echo $item->sale_qty; ?></td>
			<td><?php echo $item->sale_price; ?></td>
			<td><?php echo $item->offerprice ? $item->offerprice->new_1st_price : 0; ?></td>
			<td><?php echo $item->offerprice ? $item->offerprice->new_2nd_price : 0; ?></td>
			<td><?php echo $item->offerprice ? $item->offerprice->new_3rd_price : 0; ?></td>
			<td><?php echo $item->comment; ?></td>
			<td>
				<?php //echo Html::anchor('user/inventory/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('user/inventory/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('user/inventory/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Inventories.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('user/inventory/create', 'Add new Inventory', array('class' => 'btn btn-success')); ?>

</p>
