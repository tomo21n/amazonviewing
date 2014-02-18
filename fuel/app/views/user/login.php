<?php if (isset($login_error)||$val->error('email')||$val->error('password')): ?>
    <div class="alert alert-danger">
        <button class="close" data-dismiss="alert">×</button>
        <?php if($val->error('email')): ?><p><?php echo $val->error('email')->get_message('メールアドレスを入力してください'); ?></p><?php endif;?>
        <?php if($val->error('password')): ?><p><?php echo $val->error('password')->get_message('パスワードは必須です'); ?></p><?php endif;?>
        <?php if (isset($login_error)): ?><p><?php echo $login_error; ?></p><?php endif; ?>
    </div>
<?php endif; ?>
<?php echo Form::open(array("class"=>"form-horizontal")); ?>

<?php if (isset($_GET['destination'])): ?>
    <?php echo Form::hidden('destination',$_GET['destination']); ?>
<?php endif; ?>

    <fieldset class="col-md-3">
        <div class="form-group">
            <?php echo Form::label('メールアドレス', 'email', array('class'=>'control-label')); ?>

            <?php echo Form::input('email', Input::post('email'), array('class' => 'col-md-4 form-control', 'placeholder'=>'Email')); ?>

        </div>
        <div class="form-group">
            <?php echo Form::label('パスワード', 'password', array('class'=>'control-label')); ?>

            <?php echo Form::password('password', Input::post('password'), array('class' => 'col-md-4 form-control', 'placeholder'=>'Password')); ?>

        </div>
        <div class="form-group">
            <label class='control-label'>&nbsp;</label>
            <?php echo Form::submit('submit', 'ログイン', array('class' => 'btn btn-primary')); ?>
            <a class="btn btn-primary" href="/amazonviewing/public/account/create">アカウント登録</a>
        </div>
    </fieldset>
<?php echo Form::close(); ?>