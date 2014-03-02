<?php $group_id_list = array('0'=>'アカウント停止','2'=>'有効(一般ユーザ)','6'=>'有効（管理者）');?>
<?php echo Form::open(array("class"=>"form-horizontal")); ?>

<fieldset>
    <div class="form-group">
        <?php echo Form::label('ユーザID', 'id', array('class'=>'control-label col-md-2')); ?>

        <div class="controls col-md-1">
            <?php echo Form::input('id',Input::post('id', isset($account) ? $account['id'] : ''), array('class' => 'form-control','readonly')); ?>

        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('ニックネーム', 'nickname', array('class'=>'control-label col-md-2')); ?>

        <div class="controls col-md-2">
            <?php echo Form::input('nickname',Input::post('nickname', isset($account) ? $account['nickname'] : ''), array('class' => 'form-control','readonly')); ?>

        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('メールアドレス', 'email', array('class'=>'control-label col-md-2')); ?>

        <div class="controls col-md-3">
            <?php echo Form::input('email',Input::post('email', isset($account) ? $account['email'] : ''),  array('class' => 'form-control')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('権限', 'group_id', array('class'=>'control-label col-md-2')); ?>

        <div class="controls col-md-2">
            <?php echo Form::select('group_id', Input::post('group_id', isset($account) ? $account['group_id'] : ''),$group_id_list, array('class' => 'form-control', 'placeholder'=>'Group id')); ?>
        </div>
    </div>

    <div class="control-group">
        <label class='control-label col-md-2'>&nbsp;</label>
        <div class='controls'>
            <?php echo Form::submit('submit', '保存', array('class' => 'btn btn-primary')); ?>			</div>
    </div>
    <?php echo Form::close(); ?>
    <hr>

    <div class="control-group">
        <label class='control-label col-md-2'>パスワードリセット</label>
        <div class='controls'>
            <a data-toggle="modal" href="#myModal" class="btn btn-primary">アカウント削除</a>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">アカウント削除</h4>
                        </div>
                        <div class="modal-body">
                            <p>※ランダムなパスワードを設定し、そのパスワードをユーザにメールで送信します。<br />
                                　メールアドレスが正しいかを確認後、パスワードリセットを押下して下さい。</p>
                            <?php echo Form::open(array('action' => 'admin/manageaccount/passwordreset', 'method' => 'post',"class"=>"form-horizontal")); ?>
                            <?php echo Form::hidden('email',Input::post('email', isset($account) ? $account['email'] : '')); ?>
                            <?php echo Form::submit('submit', 'パスワードリセット', array('class' => 'btn btn-primary')); ?>
                            <?php echo Form::close(); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="control-group">
        <label class='control-label col-md-2'>アカウント削除</label>
        <div class='controls'>
            <a data-toggle="modal" href="#myModal" class="btn btn-primary">アカウント削除</a>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">アカウント削除</h4>
                        </div>
                        <div class="modal-body">
                            <p>アカウントを削除します。<br />
                               そのアカウントに関するデータはすべて削除され元には戻せません。<br /></p>
                            <?php echo Form::open(array('action' => 'admin/manageaccount/delete', 'method' => 'post')); ?>
                            <?php echo Form::hidden('id',Input::post('id', isset($account) ? $account['id'] : '')); ?>
                            <?php echo Form::submit('submit', 'アカウント削除', array('class' => 'btn btn-primary')); ?>
                            <?php echo Form::close(); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</fieldset>


<p>
	<?php echo Html::anchor('admin/manageaccount', 'Back'); ?></p>
