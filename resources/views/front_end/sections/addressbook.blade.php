<div class="form-container">
    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
        <a href="javascript:void(0)" class="btn btn-sm btn-primary"
            data-ajax-popup="true"
            data-size="xs" data-title="Add Address"
            data-url="{{ route('add.address.form',$slug) }}" data-toggle="tooltip"
            title="{{ __('Add Ticket') }}">
            <i class="ti ti-plus"></i>{{ __('Add Address') }}
        </a>
    </div>
</div>

<table class="order-history-tbl">
    <thead>
        <tr>
            <th scope="col">{{ __('Title') }}</th>
            <th scope="col">{{ __('Address') }}</th>
            <th scope="col">{{ __('Postcode') }}</th>
            <th scope="col">{{ __('Default Address') }}</th>
            <th scope="col"> {{ __('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        @if(count($DeliveryAddress))
            @foreach ($DeliveryAddress as $address)
                <tr>
                    <td> {{ $address->title }} </td> 
                    <td> {{ $address->address }} </td>                                             
                    <td> {{ $address->postcode }} </td>
                    <td> {{ $address->default_address }} </td>
                    <td data-label="Action">
                    <a href="javascript:void(0)" data-ajax-popup="true"
            data-size="xs" data-title="Edit Address"
            data-url="{{ route('edit.address.form',[$slug,'id'=>$address->id]) }}" data-toggle="tooltip"
            title="{{ __('Edit Address') }}">
            <i class="ti ti-eye text-white py-1 edit_address"  data-id="{{ $address->id }}" data-bs-toggle="tooltip" title="edit"></i>
        </a>
                            
                        
                        <i class="ti ti-trash text-white py-1 delete_address" data-id="{{ $address->id }}" data-bs-toggle="tooltip" title="delete"></i>
                    </td> 
                </tr>      
            @endforeach
        @else
            <tr>
                <td><h2>{{ __('No records found') }}</h2></td>
            </tr>
        @endif
    </tbody>
</table>
<div class="right-result-tbl text-right">
    <b>Showing {{ $DeliveryAddress->firstItem() }}</b> to {{ $DeliveryAddress->lastItem() }} of {{ $DeliveryAddress->currentPage() }} ({{ $DeliveryAddress->lastPage() }} Pages)
</div>
<div class="form-container">
    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
        @php
            $previousPageUrl = '';
            $nextPageUrl = '';
            if ($DeliveryAddress->currentPage() < 1) {
                $previousPageUrl = $DeliveryAddress->previousPageUrl();
            }
            if ($DeliveryAddress->lastPage() > 1 && $DeliveryAddress->currentPage() != $DeliveryAddress->lastPage()) {
                $nextPageUrl = $DeliveryAddress->nextPageUrl();
            }
        @endphp
        <button class="btn-secondary back-btn-acc" onclick="get_address('{{ $previousPageUrl }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
            </svg>
            {{ __('Back') }}
        </button>
        <button class="btn continue-btn" onclick="get_address('{{ $nextPageUrl }}')">
            {{ __('Next') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
            </svg>
        </button>
    </div>
</div>
