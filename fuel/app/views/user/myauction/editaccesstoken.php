<h2>New Auction</h2>
<br>
<?php echo Form::open(array("class" => "form-horizontal")); ?>
<?php \myutil::lb_channel(); ?>
    <fieldset>
        <div class="form-group">
            <?php echo Form::label('OpenId', 'Open Id', array('class' => 'control-label col-md-2')); ?>

            <div class="controls col-md-4">
                    <?php echo Form::input('open_id', Input::post('open_id', isset($myaccesstoken) ? $myaccesstoken->open_id : ''), array('class' => 'col-md-4 form-control', 'readonly')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('YahooJapanID', 'yahoo_id', array('class' => 'control-label col-md-2')); ?>

            <div class="controls col-md-2">
                    <?php echo Form::input('yahoo_user_id', Input::post('yahoo_user_id', isset($myaccesstoken) ? $myaccesstoken->yahoo_user_id : ''), array('class' => 'col-md-4 form-control', 'placeholder' => 'Yahoo User Id')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class='control-label col-md-2'>&nbsp;</label>
            <?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>
            <?php echo Html::anchor('user/myauction/refreshtoken/'.$myaccesstoken->open_id, 'RefreshToken', array('class' => 'btn btn-success')); ?>
        </div>
    </fieldset>
<?php echo Form::close(); ?>

<p><?php echo Html::anchor('user/myauction', 'Back'); ?></p>
