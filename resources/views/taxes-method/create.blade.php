{{ Form::open(['route' => 'taxes-method.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    {!! Form::hidden('tax_id', $tax_option_id, ['class' => 'form-control']) !!}
    <div class="form-group col-md-6">
        {!! Form::label('', __('Tax Name'), ['class' => 'form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('Rate %'), ['class' => 'form-label']) !!}
        {!! Form::number('tax_rate', 0, ['class' => 'form-control', 'min' => '0', 'step' => '0.10']) !!}
    </div>
    <div class="form-group list_height_css col-md-12">
        <label>{{ __('Country') }}</label>
        {!! Form::label('', __(''), ['class' => 'form-label']) !!}
        {!! Form::select('country_id', $country_option, null, [
            'class' => 'form-control country_change',
        ]) !!}
    </div>
    <div class="form-group list_height_css col-md-12">
        <label>{{ __('State') }}</label>
        <div class="state">
            {!! Form::select('state_id', [], null, [
                'class' => 'form-control state_name state_chage',
                'placeholder' => 'Select State',
                'data-select' => 0,
            ]) !!}
        </div>
    </div>
    <div class="form-group list_height_css col-md-12">
        <label>{{ __('City') }}</label>
        <div class="city">
            {!! Form::select('city_id', [], null, [
                'class' => 'form-control city_name city_chage',
                'placeholder' => 'Select City',
                'data-select' => 0,
            ]) !!}
        </div>
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('Priority'), ['class' => 'form-label']) !!}
        {!! Form::number('priority', 0, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('Shipping'), ['class' => 'form-label']) !!}
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="on" name="enable_shipping" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
                {{ __('Tax rate also gets applied to shipping.') }}
            </label>
        </div>
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Create" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
<script>
    $(document).on('change', '.country_change', function(e) {
        var country_id = $(this).val();
        var data = {
            country_id: country_id
        }
        $.ajax({
            url: '{{ route('shipping-states.list') }}',
            method: 'POST',
            data: data,
            context: this,
            success: function(response) {
                $(this).closest('.row').find('.state_chage').html('').show();
                $(this).closest('.row').find('.nice-select.state_chage').remove();
                var state = $(this).closest('.row').find('.state_chage').attr('data-select');

                var option = '';
                $.each(response, function(i, item) {
                    var checked = '';
                    if (i == state) {
                        var checked = 'checked';
                    }
                    option += '<option value="' + i + '" ' + checked + '>' + item +
                        '</option>';
                });
                $(this).closest('.row').find('.state_chage').html(option);
                $(this).closest('.row').find('.state_chage').val(state);

                if (state != 0) {
                    $(this).closest('.row').find('.state_chage').trigger('change');
                }
                $('select').niceSelect();
            }
        });
    });
    $(document).on('change', '.state_chage', function(e) {
        var state_id = $(this).val();
        var data = {
            state_id: state_id
        };
        $.ajax({
            url: '{{ route('cities.list') }}',
            method: 'POST',
            data: data,
            context: this,
            success: function(response) {
                $(this).closest('.row').find('.city_chage').html('').show();
                $(this).closest('.row').find('.nice-select.city_chage').remove();
                var city = $(this).closest('.row').find('.city_chage').attr('data-select');

                var option = '';
                $.each(response, function(i, item) {
                    var selected = (i == city) ? 'selected' : '';
                    option += '<option value="' + i + '" ' + selected + '>' + item +
                        '</option>';
                });
                $(this).closest('.row').find('.city_chage').html(option);
                $(this).closest('.row').find('.city_chage').val(city);
                if (city != 0) {
                    $(this).closest('.row').find('.city_chage').trigger('change');
                }
            }
        });
    });
</script>
