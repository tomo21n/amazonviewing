<h2>Listing MyAccount</h2>
<br>
<?php if ($access_token): ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>YahooUserId</th>
            <th>OpenId</th>
            <th>updated_at</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($access_token as $item): ?>		<tr>

            <td><?php echo $item->yahoo_user_id; ?></td>
            <td><?php echo $item->open_id; ?></td>
            <td><?php echo $item->updated_at; ?></td>
            <td>
                <?php echo Html::anchor('yahoo/myauction/editaccesstoken/'.$item->id, 'Edit'); ?>
            </td>
        </tr>
        <?php endforeach; ?>	</tbody>
    </table>

<?php else: ?>
    <p>No My Yahoo Account.</p>

<?php endif; ?><p>
    <?php echo Html::anchor('yahoo/myauction/yconnect', 'AddYahooAccout', array('class' => 'btn btn-success')); ?>
</p>

<?php echo Html::anchor('myauction', 'Back'); ?>