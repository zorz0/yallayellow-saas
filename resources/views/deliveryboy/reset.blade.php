{{ Form::model($deliveryBoy, ['route' => ['deliveryboy.reset.password', $deliveryBoy->id], 'method' => 'post']) }}

<div class="row">
    <div class="form-group">
        {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
        <div class="form-icon-user">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" required autocomplete="new-password" placeholder="Enter Password">
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group">
        {{ Form::label('password_confirmation', __('Confirm Password'), ['class' => 'form-label']) }}
        <div class="form-icon-user">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                autocomplete="new-password" placeholder="Enter Confirm Password">
        </div>
    </div>
</div>
<div class="form-group col-12 d-flex justify-content-end col-form-label">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
