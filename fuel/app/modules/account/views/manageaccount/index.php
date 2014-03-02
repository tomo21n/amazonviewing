<?php echo Form::open(array('action'=>'admin/manageaccount','class'=>'form-horizontal','method'=>'get')); ?>
<?php $group_id_list = array('0'=>'アカウント停止','2'=>'有効(一般ユーザ)','6'=>'有効（管理者）');?>

<fieldset>
    <!-- Email -->
    <div class="form-group">
        <?php echo Form::label('Email', 'email',array('class'=>'control-label col-md-2')); ?>
        <div class="controls col-md-4">
            <?php echo Form::input('email', Input::get('email'),array('class' => 'form-control')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class='control-label col-md-2'>&nbsp;</label>
        <div class="controls col-md-4">
            <?php echo Form::submit('', '検索', array('class' => 'btn btn-primary ')); ?>
        </div>
    </div>
</fieldset>
<?php echo Form::close(); ?>
<hr>
<?php echo "Result　:　".$count."　items" ?>
<?php if ($accountlist): ?>
    <?php echo $pagination ?>
<table class="table table-hover">
	<thead>
		<tr>
			<th>ユーザID</th>
			<th>ニックネーム</th>
			<th>Email</th>
			<th>ユーザ権限</th>
			<th>編集</th>
		</tr>
	</thead>
	<tbody>
    <?php foreach ($accountlist as $account): ?>
        <tr>
			<td><?php echo $account['id']; ?></td>
			<td><?php echo $account['nickname']; ?></td>
			<td><?php echo $account['email']; ?></td>
			<td><?php echo $group_id_list[$account['group_id']]; ?></td>
        <td>
            <?php echo Html::anchor('admin/manageaccount/edit/'.$account['id'], '編集'); ?>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>
</table>
    <?php echo $pagination ?>
<?php else: ?>
<p>検索結果がありません.</p>
<?php endif; ?>
