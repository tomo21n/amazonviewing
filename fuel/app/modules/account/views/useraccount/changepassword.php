<?php echo Form::open(array("class" => "form-horizontal")); ?>

<fieldset>
    <div class="form-group">
        <?php echo Form::label('現在のパスワード', 'password_old', array('class' => 'control-label col-md-2')); ?>

        <div class="controls col-md-4">
            <?php echo Form::password('password_old', Input::post('password_old'), array('class' => 'form-control', 'placeholder' => 'Old Password')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('パスワード', 'password', array('class' => 'control-label col-md-2')); ?>

        <div class="controls col-md-4">
            <?php echo Form::password('password', Input::post('password'), array('class' => 'form-control', 'placeholder' => 'Password')); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('パスワード再入力', 'password_conf', array('class' => 'control-label col-md-2')); ?>

        <div class="controls col-md-4">
            <?php echo Form::password('password_conf', Input::post('password_conf'), array('class' => 'form-control', 'placeholder' => 'Password')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class='control-label col-md-2'>&nbsp;</label>

        <div class='controls col-md-4'>
            <?php echo Form::submit('submit', 'パスワード変更', array('class' => 'btn btn-primary')); ?>            </div>
    </div>
</fieldset>
<?php echo Form::close(); ?>
<p>
    <?php echo Html::anchor('user/', 'Back'); ?></p>
