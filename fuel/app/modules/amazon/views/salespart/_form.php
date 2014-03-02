<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Part id', 'part_id', array('class'=>'control-label')); ?>

				<?php echo Form::input('part_id', Input::post('part_id', isset($salesPart) ? $salesPart->part_id : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Part id')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Asin', 'asin', array('class'=>'control-label')); ?>

				<?php echo Form::input('asin', Input::post('asin', isset($salesPart) ? $salesPart->asin : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Asin')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Ean', 'ean', array('class'=>'control-label')); ?>

				<?php echo Form::input('ean', Input::post('ean', isset($salesPart) ? $salesPart->ean : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Ean')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Title', 'title', array('class'=>'control-label')); ?>

				<?php echo Form::input('title', Input::post('title', isset($salesPart) ? $salesPart->title : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Title')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Category', 'category', array('class'=>'control-label')); ?>

				<?php echo Form::input('category', Input::post('category', isset($salesPart) ? $salesPart->category : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Category')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Url', 'url', array('class'=>'control-label')); ?>

				<?php echo Form::textarea('url', Input::post('url', isset($salesPart) ? $salesPart->url : ''), array('class' => 'col-md-8 form-control', 'rows' => 8, 'placeholder'=>'Url')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Image s', 'image_s', array('class'=>'control-label')); ?>

				<?php echo Form::textarea('image_s', Input::post('image_s', isset($salesPart) ? $salesPart->image_s : ''), array('class' => 'col-md-8 form-control', 'rows' => 8, 'placeholder'=>'Image s')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Image l', 'image_l', array('class'=>'control-label')); ?>

				<?php echo Form::textarea('image_l', Input::post('image_l', isset($salesPart) ? $salesPart->image_l : ''), array('class' => 'col-md-8 form-control', 'rows' => 8, 'placeholder'=>'Image l')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Volume', 'volume', array('class'=>'control-label')); ?>

				<?php echo Form::input('volume', Input::post('volume', isset($salesPart) ? $salesPart->volume : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Volume')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Wight', 'wight', array('class'=>'control-label')); ?>

				<?php echo Form::input('wight', Input::post('wight', isset($salesPart) ? $salesPart->wight : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Wight')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Release date', 'release_date', array('class'=>'control-label')); ?>

				<?php echo Form::input('release_date', Input::post('release_date', isset($salesPart) ? $salesPart->release_date : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Release date')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>