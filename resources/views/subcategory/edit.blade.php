{{Form::model($subCategory, array('route' => array('sub-category.update', $subCategory->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}
    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::label('', __('العنوان'), ['class' => 'form-label']) !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-md-12">
            {!! Form::label('', __('الفئة'), ['class' => 'form-label']) !!}
            {!! Form::select('maincategory_id', $MainCategoryList, null, ['class' => 'form-control', 'data-role' => 'tagsinput', 'id' => 'category_id']) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('الصورة'), ['class' => 'form-label']) !!}
            <label for="upload_image" class="image-upload bg-primary pointer w-100">
                <i class="ti ti-upload px-1"></i> {{ __('اختر الملف هنا') }}
            </label>
            <input type="file" name="image" id="upload_image" class="d-none">
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('الرمز'), ['class' => 'form-label']) !!}
            <label for="icon_path" class="image-upload bg-primary pointer w-100">
                <i class="ti ti-upload px-1"></i> {{ __('اختر الملف هنا') }}
            </label>
            <input type="file" name="icon_path" id="icon_path" class="d-none">
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('الحالة'), ['class' => 'form-label']) !!}
            <div class="form-check form-switch">
                <input type="hidden" name="status" value="0">
                {!! Form::checkbox('status', 1, null, [
                    'class' => 'form-check-input status',
                    'id' => 'status',
                ]) !!}
                <label class="form-check-label" for="status"></label>
            </div>
        </div>
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="إلغاء" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="تحديث" class="btn btn-primary">
    </div>
{{ Form::close() }}
