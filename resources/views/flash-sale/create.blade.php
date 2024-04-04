{{ Form::open(['route' => 'flash-sale.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'repeater']) }}
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('Start Date'), ['class' => 'form-label']) !!}
        {!! Form::date('start_date', null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('End Date'), ['class' => 'form-label']) !!}
        {!! Form::date('end_date', null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('Start Time'), ['class' => 'form-label']) !!}
        {!! Form::time('start_time', null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('End Time'), ['class' => 'form-label']) !!}
        {!! Form::time('end_time', null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('Discount Type'), ['class' => 'form-label']) !!}
        {!! Form::select(
            'discount_type',
            ['' => __('Select Discount Type'), 'percentage' => 'Percentage', 'flat' => 'Flat'],
            null,
            ['class' => 'form-control', 'required'],
        ) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('Discount Amount'), ['class' => 'form-label']) !!}
        {!! Form::number('discount_amount', null, [
            'class' => 'form-control',
            'min' => '0',
            'step' => '0.01',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-md-12">
        <small>{{ __('Note: Override this discount if Sale is set  in another flash deal.')}}</small>
    </div>
    <div class="form-group col-md-8">
        {!! Form::label('', __('Apply this Campaign when these conditions are matched:'), ['class' => 'form-label']) !!}
    </div>
    <div class="col-md-4">
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
            <a href="#" class="btn btn-sm btn-primary newfield" id="add-field-btn"
                title="{{ __('Add New Field') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    </div>
    <div class="add_list repeater-container" id="repeater-container">
        <div class="row">
            @for ($i = 0; $i <= 0; $i++)
                <div class="row filter-css mt-2 form-group-container"
                    data-id="form-group-container{{ $i }}">
                    <div class="col-md-4">
                        <div class="btn-box">
                            {!! Form::select('fields[' . $i . '][condition_option]', App\Models\FlashSale::$options, null, [
                                'class' => 'form-control condition_option',
                                'id' => 'condition_option_' . $i,
                                'placeholder' => 'Select option',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
                        <div class="btn-box condition">
                            {!! Form::select('fields[' . $i . '][condition]', App\Models\FlashSale::$condition, null, [
                                'class' => 'form-control',
                                'placeholder' => 'Select option',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
                        <div class="btn-box emp_div">
                            {{ Form::select('fields[' . $i . '][conditionlist][]', [], null, ['class' => 'sub form-control select project_select multi-select', 'id' => 'options_id_' . $i, 'multiple' => ' ', 'required' => 'required', 'placeholder' => 'Select option']) }}
                        </div>
                    </div>
                    <div class="form-group col-md-1 ml-auto text-end">
                        <button type="button" class="btn btn-sm btn-danger  disabled delete-icon">
                            <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip" title="Delete"></i>
                        </button>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Create" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
<link rel="stylesheet" href="{{ asset('public/assets/css/plugins/daterangepicker.css') }}">
<script src="{{ asset('public/assets/js/plugins/daterangepicker.js') }}"></script>
<script src="{{ asset('public/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/js/repeater.js') }}"></script>
<script>
    $(document).ready(function() {
        setTimeout(() => {
            $('.condition_option').trigger('change');
        }, 100);
    });

    $(document).on('change', '.condition_option', function(e) {
        var id = $(this).val();

        var dataId = $(this).closest('.form-group-container').data('id');
        var match = dataId.match(/\d+/);

        if (match) {
            var index = parseInt(match[0], 10);
        }
        var data = {
            id: id,
        }
        $.ajax({
            url: '{{ route('get.options') }}',
            method: 'POST',
            data: data,
            context: this,
            success: function(response) {
                if (response.message == 'campaign for shop') {
                    $(this).parent().parent().parent().find(".condition").empty();
                    $(this).parent().parent().parent().find(".emp_div").empty();


                } else if (response.message == 'campaign for price') {
                    var num = Math.floor(Math.random() * 90000) + 10000;
                    var slect_ids = 'options_id_' + index;

                    var slect_divs = $(this).parent().parent().parent().find(
                        '.condition');
                    $(slect_divs).empty();

                    var emp_selcts = `<select class="form-control" id="` + slect_ids + `"  required="required" name="fields[${index}][condition]">
                        </select>`;
                    $(slect_divs).html(emp_selcts);

                    var options = $(this).parent().parent().parent().find('.condition')
                        .find('.form-control');
                    $.each(response.condition, function(i, item) {
                        $(options).append('<option value="' + i + '">' + item +
                            '</option>');
                    });

                    //selection
                    var num = Math.floor(Math.random() * 90000) + 10000;
                    var slect_ids = 'options_id_' + index;

                    var slect_div = $(this).parent().parent().parent().find('.emp_div');
                    $(slect_div).empty();

                    var emp_selct =
                        `<input type="number" class="form-control" id="` +
                        slect_ids +
                        `"  required="required" name="fields[${index}][conditionlist][]">`;

                    $(slect_div).html(emp_selct);

                } else {
                    var num = Math.floor(Math.random() * 90000) + 10000;
                    var slect_ids = 'options_id_' + index;
                    //condition
                    var slect_divs = $(this).parent().parent().parent().find('.condition');


                    var slect_divs = $(this).parent().parent().parent().find(
                        '.condition');
                    $(slect_divs).empty();

                    var emp_selcts =
                        `<select class="form-control" required="required" name="fields[${index}][condition]"></select>`;

                    $(slect_divs).html(emp_selcts);

                    var options = $(this).parent().parent().parent().find('.condition')
                        .find('.form-control');
                    $.each(response.condition, function(i, item) {
                        $(options).append('<option value="' + i + '">' + item +
                            '</option>');
                    });

                    //selection
                    var num = Math.floor(Math.random() * 90000) + 10000;
                    var slect_ids = 'options_id_' + index;

                    var slect_div = $(this).parent().parent().parent().find('.emp_div');
                    $(slect_div).empty();

                    var emp_selct =
                        `<select class="form-control select project_select multi-select" id="` +
                        slect_ids + `" multiple="" required="required" name="fields[${index}][conditionlist][]">
                            </select>`;
                    $(slect_div).html(emp_selct);
                    var option = $(this).parent().parent().parent().find('.emp_div')
                        .find('.project_select');

                    $.each(response.product, function(i, item) {
                        $(option).append('<option value="' + item.id + '">' +
                            item.name + '</option>');
                    });

                    $('[id^="options_id_"]').each(function() {
                        new Choices(this, {
                            removeItemButton: true,
                        });
                    });
                }
            }
        });
    });
    if ($(".multi-select").length > 0) {
        $($(".multi-select")).each(function(index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });
    }
</script>
<script>
    $(document).ready(function() {
    let plusFieldIndex = 0;

    function addNewField(index) {
        plusFieldIndex++;
        const newContainer = $("#repeater-container").find(".form-group-container").first().clone();

        newContainer.attr("data-id", "form-group-container" + plusFieldIndex);
        newContainer.find('.condition_option').attr('name', 'fields[' + plusFieldIndex + '][condition_option]');
        newContainer.find('.condition').attr('name', 'fields[' + plusFieldIndex + '][condition]');
        newContainer.find('.project_select').attr('name', 'fields[' + plusFieldIndex + '][conditionlist][]');
        newContainer.find('.option').attr('name', 'fields[' + plusFieldIndex + '][option]');

        newContainer.find('.delete-icon').removeClass('disabled');
        newContainer.find('.delete-icon').removeClass('d-none');

        $("#repeater-container").append(newContainer);
    }

    $("#add-field-btn").on("click", function() {
        addNewField(plusFieldIndex);
    });

    $(document).on("click", ".delete-icon:not(.disabled)", function() {
        var container = $(this).closest('.form-group-container');
        if (container.attr("id") !== "form-group-container0") {
            container.remove();
        }
    });
});
</script>
