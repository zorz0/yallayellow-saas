{{ Form::model($flashsale, ['route' => ['flash-sale.update', $flashsale->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
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

    <div class="form-group col-md-4">
        {!! Form::label('', __('Discount Type'), ['class' => 'form-label']) !!}
        {!! Form::select(
            'discount_type',
            ['' => __('Select Discount Type'), 'percentage' => 'Percentage', 'flat' => 'Flat'],
            null,
            ['class' => 'form-control'],
        ) !!}
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('', __('Discount Amount'), ['class' => 'form-label']) !!}
        {!! Form::number('discount_amount', null, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
    </div>
    <div class="form-group col-md-12">
        <small>{{ __('Note: Override this discount if Sale is set  in another flash deal.') }}</small>
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
    <input type="hidden" id="flashsaleId" value="{{ $flashsale->id }}">
    <div class="add_list repeater-container" id="repeater-container">
        <div class="row">
            @if (!empty($sale_condition))
                @foreach (json_decode($sale_condition->condition) as $index => $item)
                    <input type="hidden" class="condition_option_value" value="{{ $item->condition_option }}">
                    <div class="row filter-css mt-2 form-group-container"
                        data-id="form-group-container{{ $index }}">
                        <div class="col-md-4">
                            <div class="btn-box">
                                {!! Form::select(
                                    'fields[' . $index . '][condition_option]',
                                    App\Models\FlashSale::$options,
                                    $item->condition_option ?? null,
                                    [
                                        'class' => 'form-control condition_option',
                                        'id' => 'condition_option_' . $index,
                                        'placeholder' => 'Select option',
                                    ],
                                ) !!}
                            </div>
                        </div>
                        @if ($item->condition_option != 0)
                            <div class="col-xl-3 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box condition condition_value">
                                    @if ($item->condition_option == 3)
                                        {!! Form::select(
                                            'fields[' . $index . '][condition]',
                                            App\Models\FlashSale::$price_condition,
                                            $item->condition ?? null,
                                            [
                                                'class' => 'form-control',
                                                'placeholder' => 'Select option',
                                            ],
                                        ) !!}
                                    @else
                                        {!! Form::select(
                                            'fields[' . $index . '][condition]',
                                            App\Models\FlashSale::$condition,
                                            $item->condition ?? null,
                                            [
                                                'class' => 'form-control',
                                                'placeholder' => 'Select option',
                                            ],
                                        ) !!}
                                    @endif
                                </div>
                            </div>
                            @if (!empty($item->conditionlist))
                                @php
                                    $value = implode(',', $item->conditionlist);
                                    $idsArray = explode(',', $value);

                                    $selected_data = $idsArray;
                                    $store_id = Auth::user()->current_store;
                                    $store = \App\Models\Store::find($store_id);
                                    $theme_id = $store->theme_id;

                                    if ($item->condition_option == 1) {
                                        $option = \App\Models\Product::where('theme_id', $theme_id)
                                            ->where('store_id', getCurrentStore())
                                            ->get()
                                            ->pluck('name', 'id')
                                            ->toArray();
                                        $get_datas = \App\Models\Product::whereIn('id', $idsArray)
                                            ->get()
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    } elseif ($item->condition_option == 2) {
                                        $option = \App\Models\MainCategory::where('theme_id', $theme_id)
                                            ->where('store_id', getCurrentStore())
                                            ->get()
                                            ->pluck('name', 'id')
                                            ->toArray();
                                        $get_datas = \App\Models\MainCategory::whereIn('id', $idsArray)
                                            ->get()
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    }
                                @endphp
                                <div class="col-xl-4 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
                                    <div class="btn-box emp_div">
                                        @if ($item->condition_option == 3)
                                            {{ Form::text('fields[' . $index . '][conditionlist][]', $item->conditionlist[0] ?? null, ['class' => 'form-control', 'required']) }}
                                        @elseif ($item->condition_option == 1 || $item->condition_option == 2)
                                            <select name="fields[{{ $index }}][conditionlist][]"
                                                data-role="tagsinput" id="options_id_{{ $index }}" multiple
                                                class="attribute_option_data">
                                                @foreach ($option as $id => $f)
                                                    <option @if (in_array($f, $get_datas)) selected @endif
                                                        value="{{ $id }}">
                                                        {{ $f }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="col-xl-4 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
                                    <div class="btn-box emp_div">
                                        {{ Form::select('fields[' . $index . '][conditionlist][]', [], null, ['class' => 'sub form-control select project_select multi-select', 'id' => 'options_id_' . $index, 'multiple' => ' ', 'required' => 'required', 'placeholder' => 'Select option']) }}
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="col-xl-3 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box condition condition_value">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box emp_div">
                                </div>
                            </div>
                        @endif
                        <div class="form-group col-md-1 ml-auto text-end">
                            <button type="button" class="btn btn-sm btn-danger delete-icon">
                                <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip" title="Delete"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                @for ($i = 0; $i <= 0; $i++)
                    <div class="row filter-css mt-2 form-group-container"
                        data-id="form-group-container{{ $i }}">
                        <div class="col-md-4">
                            <div class="btn-box">
                                {!! Form::select('fields[' . $i . '][condition_option]', App\Models\FlashSale::$options, null, [
                                    'class' => 'form-control condition_option',
                                    'id' => 'condition_option_' . $i, // Added unique ID
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
            @endif
        </div>
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Update" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}

<link rel="stylesheet" href="{{ asset('public/assets/css/plugins/daterangepicker.css') }}">
<script src="{{ asset('public/assets/js/plugins/daterangepicker.js') }}"></script>
<script src="{{ asset('public/js/jquery-ui.min.js') }}"></script>

<script>
    $(document).ready(function() {

    });
    $(document).on('change', '.condition_option', function(e) {
        var id = $(this).val();

        var dataId = $(this).closest('.form-group-container').data('id');
        var match = dataId.match(/\d+/);
        if (match) {
            var index = parseInt(match[0], 10);
            var data = {
                id: id,
            };
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

                    var emp_selcts = `<select class="form-control" id=""  required="required" name="fields[${index}][condition]">
                        </select>`;
                    $(slect_divs).html(emp_selcts);

                    var options = $(this).parent().parent().parent().find('.condition')
                        .find('.form-control');
                    $.each(response.condition, function(i, item) {
                        $(options).append('<option value="' + i + '">' + item +
                            '</option>');
                    });

                    var num = Math.floor(Math.random() * 90000) + 10000;
                    var slect_ids = 'options_id_' + index;

                    var slect_div = $(this).parent().parent().parent().find('.emp_div');
                    $(slect_div).empty();

                    var emp_selct =
                        `<input type="text" class="form-control" required="required" name="fields[${index}][conditionlist][]">`;

                    $(slect_div).html(emp_selct);

                } else {
                    var num = Math.floor(Math.random() * 90000) + 10000;
                    var slect_ids = 'options_id_' + index;

                    var slect_divs = $(this).parent().parent().parent().find(
                        '.condition');
                    $(slect_divs).empty();

                    var emp_selcts = `<select class="form-control" id=""  required="required" name="fields[${index}][condition]">
                        </select>`;
                    $(slect_divs).html(emp_selcts);

                    var options = $(this).parent().parent().parent().find('.condition')
                        .find('.form-control');

                    $.each(response.condition, function(i, item) {
                        $(options).append('<option  value="' + i + '">' + item +
                            '</option>');
                    });

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
</script>
<script>
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

        var lastFormGroupContainer = $('.form-group-container').last();
        var dataId = lastFormGroupContainer.data('id');
        var numericPart = dataId.match(/\d+/);
        if (numericPart !== null) {
            let plusFieldIndex = numericPart[0];
            lastFormGroupContainer.find('.option').addClass('d-none');
        }
        let plusFieldIndex = numericPart[0];

        function addNewField(index) {
            plusFieldIndex++;

            const newContainer = $(".repeater-container").find(".form-group-container").last().clone();
            const newContainer1 = $(".repeater-container").find(".form-group-container");

            newContainer.attr("data-id", "form-group-container" + plusFieldIndex);

            newContainer.find('.condition_option').attr('id', 'condition_option_' + plusFieldIndex).attr('name',
                'fields[' + plusFieldIndex +
                '][condition_option]').val('');

            newContainer.find('.condition').attr('name', 'fields[' + plusFieldIndex + '][condition]');
            newContainer.find('.project_select').attr('name', 'fields[' + plusFieldIndex + '][conditionlist][]')
                .val('');

            newContainer.find('.delete-icon').removeClass('disabled');
            newContainer.find('.delete-icon').removeClass('d-none');

            $(".repeater-container").append(newContainer);
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
