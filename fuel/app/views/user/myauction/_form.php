<?php echo Form::open(array("class" => "form-horizontal")); ?>
<?php \myutil::lb_channel(); ?>
    <fieldset>
        <div class="form-group">
            <?php echo Form::label('ASIN', 'asin', array('class' => 'control-label col-md-2')); ?>

            <div class="controls col-md-2">
                <?php if(Uri::segment(3) == 'create'):?>
                    <?php echo Form::input('asin', Input::post('asin', isset($inventory) ? $inventory->salespart->asin : ''), array('class' => 'col-md-4 form-control', 'placeholder' => 'ASIN')); ?>
                <?php else: ?>
                    <?php echo Form::input('asin', Input::post('asin', isset($inventory) ? $inventory->salespart->asin : ''), array('class' => 'col-md-4 form-control', 'readonly')); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('SKU', 'sku', array('class' => 'control-label col-md-2')); ?>

            <div class="controls col-md-2">
                <?php echo Form::input('sku', Input::post('sku', isset($inventory) ? $inventory->sku : ''), array('class' => 'col-md-4 form-control', 'placeholder' => 'SKU')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('出品チャネル', 'channel', array('class' => 'control-label col-md-2')); ?>

            <div class="controls col-md-2">
                <?php echo Form::select('channel', Input::post('channel', isset($inventory) ? $inventory->channel : ''), \myutil::lb_channel(),array('class' => 'col-md-4 form-control', 'placeholder' => 'Channel')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('出品数', 'sale_qty', array('class' => 'control-label col-md-2')); ?>

            <div class="controls col-md-2">
                <?php echo Form::input('sale_qty', Input::post('sale_qty', isset($inventory) ? $inventory->sale_qty : ''), array('class' => 'col-md-4 form-control', 'placeholder' => 'Qty')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('出品価格', 'sale_price', array('class' => 'control-label col-md-2')); ?>

            <div class="controls col-md-2">
                <?php echo Form::input('sale_price', Input::post('sale_price', isset($inventory) ? $inventory->sale_price : ''), array('class' => 'col-md-4 form-control', 'placeholder' => 'Price')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('出品状態', 'condition', array('class' => 'control-label col-md-2')); ?>

            <div class="controls col-md-2">
                <?php echo Form::select('condition', Input::post('condition', isset($inventory) ? $inventory->condition : ''),\myutil::lb_condition(), array('class' => 'col-md-4 form-control', 'placeholder' => 'Condition')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('備考', 'comment', array('class' => 'control-label col-md-2')); ?>

            <div class="controls col-md-6">
                <?php echo Form::textarea('comment', Input::post('comment', isset($inventory) ? $inventory->comment : ''), array('class' => 'col-md-8 form-control', 'rows' => 8, 'placeholder' => 'Comment')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class='control-label col-md-2'>&nbsp;</label>
            <?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>        </div>
    </fieldset>
<?php echo Form::close(); ?>