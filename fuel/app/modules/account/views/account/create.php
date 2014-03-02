<?php echo Form::open(array("class"=>"form-horizontal")); ?>

    <fieldset>
        <div class="form-group">
            <?php echo Form::label('名前', 'nickname', array('class'=>'control-label col-md-2')); ?>

            <div class="controls col-md-3">
                <?php echo Form::input('nickname', Input::post('nickname', isset($account) ? $account->nickname : ''), array('class' => 'form-control', 'placeholder'=>'Name')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('メールアドレス', 'email', array('class'=>'control-label col-md-2')); ?>

            <div class="controls col-md-3">
                <?php echo Form::input('email', Input::post('email', isset($account) ? $account->email : ''), array('class' => 'form-control', 'placeholder'=>'Email')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('パスワード', 'password', array('class'=>'control-label col-md-2')); ?>

            <div class="controls col-md-3">
                <?php echo Form::password('password', Input::post('password', isset($account) ? $account->password : ''), array('class' => 'form-control', 'placeholder'=>'Password')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('パスワード再入力', 'password_conf', array('class'=>'control-label col-md-2')); ?>

            <div class="controls col-md-3">
                <?php echo Form::password('password_conf', Input::post('password_conf', isset($account) ? $account->password_conf : ''), array('class' => 'form-control', 'placeholder'=>'Password')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class='control-label col-md-2'>&nbsp;</label>
            <div class='controls col-md-3'>
                <?php echo Form::submit('submit', '登録', array('class' => 'btn btn-primary')); ?>			</div>
        </div>
    </fieldset>
<?php echo Form::close(); ?>