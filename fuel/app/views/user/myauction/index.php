<h2>Listing MyAuction</h2>
<br>
<?php echo Form::open(array('action'=>'user/myauction/','class'=>'form-horizontal','method'=>'get')); ?>
<fieldset>
    <!-- Open Id -->
    <div class="form-group">
        <?php echo Form::label('OpenId', 'open_id',array('class'=>'control-label col-md-2')); ?>
        <div class="controls col-md-4">
            <?php echo Form::select('open_id', Input::get('open_id'),$open_ids,array('class' => 'col-md-4 form-control')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class='control-label col-md-2'>&nbsp;</label>
        <div class="controls col-md-2">
            <?php echo Form::submit('search', '検索', array('class' => 'btn btn-primary ')); ?>
            <?php echo Form::submit('update', '更新', array('class' => 'btn btn-info')); ?>
        </div>
    </div>
</fieldset>
<?php echo Form::close(); ?>
<hr>
<?php if ($myauctions): ?>
<table class="table table-striped">
	<thead>
		<tr>
            <th>AuctionId</th>
            <th>Image</th>
			<th>タイトル</th>
			<th>落札価格</th>
			<th>落札者</th>
			<th>メッセージタイトル</th>
			<th>終了時刻</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($myauctions as $item): ?>		<tr>

            <td><?php echo Html::anchor($item->auction_item_url, $item->auction_id); ?></td>
            <td><?php echo Html::img($item->image_url); ?></td>
            <td><?php echo $item->title; ?></td>
			<td><?php echo $item->highest_price; ?></td>
			<td><?php echo Html::anchor("http://openuser.auctions.yahoo.co.jp/jp/user/".$item->winner_id, $item->winner_id); ?></td>
			<td><?php echo Html::anchor($item->winner_contact_url,$item->message_title); ?></td>
			<td><?php echo $item->end_time; ?></td>
			<td>
				<?php //echo Html::anchor('user/inventory/view/'.$item->id, 'View'); ?>
				<?php //echo Html::anchor('user/myauction/edit/'.$item->id, 'Edit'); ?>
				<?php //echo Html::anchor('user/myauction/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No myauctions.</p>

<?php endif; ?><p>
	<?php //echo Html::anchor('user/myauction/create', 'Add new myauction', array('class' => 'btn btn-success')); ?>

</p>
