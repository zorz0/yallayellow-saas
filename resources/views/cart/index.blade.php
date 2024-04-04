
@extends('layouts.app')
@section('page-title')
{{ __('Abandon Cart')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Abandon Cart') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($abandon_cart as $cart)
                                    @php
                                            $cart_id = $cart->customer_id;
                                            $cart_count = App\Models\Cart::where('customer_id',$cart_id)->where('theme_id', APP_THEME())->count();

                                    @endphp
                                <tr>
                                    <td>{{ !empty($cart->UserData) ? $cart->UserData->first_name : '' }}</td>
                                    <td>{{ !empty($cart->UserData) ? $cart->UserData->email : '' }}</td>
                                    <td>
                                    {{-- @permission('Show Cart') --}}
                                    <button class="btn btn-sm  btn-outline-success me-2"
                                        data-url="{{ route('carts.show', $cart->id) }}" data-size="md"
                                        data-ajax-popup="true" data-title="{{ __('Show Products') }}">
                                        <i  data-bs-toggle="tooltip" title="Product"> </i>{{ __('Show Product')}}
                                    </button>
                                    {{-- @endpermission --}}
                                    <span class="badge rounded p-2 f-w-600  bg-light-success">
                                        {{$cart_count}}</span>
                                    </td>
                                    <td class="text-end">
                                        {{-- @permission('Abandon Cart') --}}
                                        <button class="btn btn-sm btn-primary me-2 bandoncart"
                                          data-size="md" data-id ="{{$cart->id}}">
                                            <i class="fa fa-paper-plane"  data-bs-toggle="tooltip" title="send mail" > </i>
                                        </button>
                                        {{-- @endpermission --}}
                                        {{-- @permission('Abandon Cart') --}}
                                        <button class="btn btn-sm btn-primary me-2 bandoncartmess"
                                          data-size="md" data-id ="{{$cart->id}}">
                                            <i class=""  data-bs-toggle="tooltip" title="send message" >
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><style>svg{fill:#ffffff}</style><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg></i>
                                        </button>
                                        {{-- @endpermission --}}
                                        {{-- @permission('Delete Cart') --}}
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['carts.destroy', $cart->id], 'class' => 'd-inline']) !!}
                                        <button type="button" class="btn btn-sm btn-danger show_confirm">
                                            <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                title="delete"></i>
                                        </button>
                                        {!! Form::close() !!}
                                        {{-- @endpermission --}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('custom-script')
<script>
    $(document).ready(function() {

    $(document).on('click', '.bandoncart', function(e) {
        var cart_id  = $(this).attr('data-id');
        $.ajax({
            url: '{{ route('carts.emailsend') }}',
            method: 'POST',
            data: { cart_id: cart_id },
            context: this,
            success: function(response) {
                if(response.is_success){

                    show_toastr('Success', response.message, 'success');
                }else{
                    show_toastr('Error', response.message, 'error');

                }
            }
        });

    });
});
$(document).ready(function() {

$(document).on('click', '.bandoncartmess', function(e) {
    var cart_id  = $(this).attr('data-id');
    var cart_id  = $(this).attr('data-id');
    $.ajax({
        url: '{{ route('carts.messagesend') }}',
        method: 'POST',
        data: { cart_id: cart_id },
        context: this,
        success: function(response) {
            if(response.is_success){

                show_toastr('Success', response.message, 'success');
            }else{
                show_toastr('Error', response.message, 'error');

            }


        }
    });

});
});



</script>
@endpush


