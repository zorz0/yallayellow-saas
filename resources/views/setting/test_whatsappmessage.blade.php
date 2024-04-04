
<form class="px-3" method="post" action="{{ route('whatsapp.send.massage') }}" id="whatsapp-notification">
    @csrf

    <input type="hidden" name="whatsapp_phone_number_id" value="{{ $data['whatsapp_phone_number_id'] }}" />
    <input type="hidden" name="whatsapp_access_token" value="{{ $data['whatsapp_access_token'] }}" />
    <div class="row">
        <div class="col-md-12 mb-3">
            <label for="name" class="form-label">{{ __('Mobile No')}}</label>
            <input type="text" class="form-control" id="mobile" name="mobile" required/>
        </div>
        <div class="col-md-12 mb-2 text-end">
            <label id="email_sending" style="display: none;"><i class="fas fa-clock"></i></label>
            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{ __('send') }}" class="btn-create btn btn-primary">
        </div>
    </div>
</form>

