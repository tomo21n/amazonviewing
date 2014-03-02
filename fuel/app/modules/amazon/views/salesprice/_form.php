<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Part id', 'part_id', array('class'=>'control-label')); ?>

				<?php echo Form::input('part_id', Input::post('part_id', isset($salesPrice) ? $salesPrice->part_id : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Part id')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Survey dtm', 'survey_dtm', array('class'=>'control-label')); ?>

				<?php echo Form::input('survey_dtm', Input::post('survey_dtm', isset($salesPrice) ? $salesPrice->survey_dtm : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Survey dtm')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Country', 'country', array('class'=>'control-label')); ?>

				<?php echo Form::input('country', Input::post('country', isset($salesPrice) ? $salesPrice->country : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Country')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('New 1st price', 'new_1st_price', array('class'=>'control-label')); ?>

				<?php echo Form::input('new_1st_price', Input::post('new_1st_price', isset($salesPrice) ? $salesPrice->new_1st_price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'New 1st price')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('New 2nd price', 'new_2nd_price', array('class'=>'control-label')); ?>

				<?php echo Form::input('new_2nd_price', Input::post('new_2nd_price', isset($salesPrice) ? $salesPrice->new_2nd_price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'New 2nd price')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('New 3rd price', 'new_3rd_price', array('class'=>'control-label')); ?>

				<?php echo Form::input('new_3rd_price', Input::post('new_3rd_price', isset($salesPrice) ? $salesPrice->new_3rd_price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'New 3rd price')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Used 1st price', 'used_1st_price', array('class'=>'control-label')); ?>

				<?php echo Form::input('used_1st_price', Input::post('used_1st_price', isset($salesPrice) ? $salesPrice->used_1st_price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Used 1st price')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Used 2nd price', 'used_2nd_price', array('class'=>'control-label')); ?>

				<?php echo Form::input('used_2nd_price', Input::post('used_2nd_price', isset($salesPrice) ? $salesPrice->used_2nd_price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Used 2nd price')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Used 3rd price', 'used_3rd_price', array('class'=>'control-label')); ?>

				<?php echo Form::input('used_3rd_price', Input::post('used_3rd_price', isset($salesPrice) ? $salesPrice->used_3rd_price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Used 3rd price')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('New 1st qty', 'new_1st_qty', array('class'=>'control-label')); ?>

				<?php echo Form::input('new_1st_qty', Input::post('new_1st_qty', isset($salesPrice) ? $salesPrice->new_1st_qty : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'New 1st qty')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('New 2nd qty', 'new_2nd_qty', array('class'=>'control-label')); ?>

				<?php echo Form::input('new_2nd_qty', Input::post('new_2nd_qty', isset($salesPrice) ? $salesPrice->new_2nd_qty : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'New 2nd qty')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('New 3rd qty', 'new_3rd_qty', array('class'=>'control-label')); ?>

				<?php echo Form::input('new_3rd_qty', Input::post('new_3rd_qty', isset($salesPrice) ? $salesPrice->new_3rd_qty : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'New 3rd qty')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Used 1st qty', 'used_1st_qty', array('class'=>'control-label')); ?>

				<?php echo Form::input('used_1st_qty', Input::post('used_1st_qty', isset($salesPrice) ? $salesPrice->used_1st_qty : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Used 1st qty')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Used 2nd qty', 'used_2nd_qty', array('class'=>'control-label')); ?>

				<?php echo Form::input('used_2nd_qty', Input::post('used_2nd_qty', isset($salesPrice) ? $salesPrice->used_2nd_qty : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Used 2nd qty')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Used 3rd qty', 'used_3rd_qty', array('class'=>'control-label')); ?>

				<?php echo Form::input('used_3rd_qty', Input::post('used_3rd_qty', isset($salesPrice) ? $salesPrice->used_3rd_qty : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Used 3rd qty')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('New 1st shipfee', 'new_1st_shipfee', array('class'=>'control-label')); ?>

				<?php echo Form::input('new_1st_shipfee', Input::post('new_1st_shipfee', isset($salesPrice) ? $salesPrice->new_1st_shipfee : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'New 1st shipfee')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('New 2nd shipfee', 'new_2nd_shipfee', array('class'=>'control-label')); ?>

				<?php echo Form::input('new_2nd_shipfee', Input::post('new_2nd_shipfee', isset($salesPrice) ? $salesPrice->new_2nd_shipfee : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'New 2nd shipfee')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('New 3rd shipfee', 'new_3rd_shipfee', array('class'=>'control-label')); ?>

				<?php echo Form::input('new_3rd_shipfee', Input::post('new_3rd_shipfee', isset($salesPrice) ? $salesPrice->new_3rd_shipfee : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'New 3rd shipfee')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Used 1st shipfee', 'used_1st_shipfee', array('class'=>'control-label')); ?>

				<?php echo Form::input('used_1st_shipfee', Input::post('used_1st_shipfee', isset($salesPrice) ? $salesPrice->used_1st_shipfee : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Used 1st shipfee')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Used 2nd shipfee', 'used_2nd_shipfee', array('class'=>'control-label')); ?>

				<?php echo Form::input('used_2nd_shipfee', Input::post('used_2nd_shipfee', isset($salesPrice) ? $salesPrice->used_2nd_shipfee : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Used 2nd shipfee')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Used 3rd shipfee', 'used_3rd_shipfee', array('class'=>'control-label')); ?>

				<?php echo Form::input('used_3rd_shipfee', Input::post('used_3rd_shipfee', isset($salesPrice) ? $salesPrice->used_3rd_shipfee : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Used 3rd shipfee')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>