<table class="order-history-tbl">
    <thead>
        <tr>
            <th scope="col">{{ __('Product') }}</th>
            <th scope="col">{{ __('Name') }}</th>
            <th scope="col">{{ __('date') }}</th>
            <th scope="col">{{ __('Price') }}</th>
            <th scope="col">{{ __('Total') }}</th>
            <th scope="col">{{ __('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        @if(count($wishlists) > 0)
            @foreach ($wishlists as $wishlist)
                @php
                    $p_id = $wishlist->ProductData->id;
                @endphp
                <tr>
                    <td data-label="Product">
                        <a href="{{route('page.product',[$slug,$p_id])}}">
                            <img src="{{asset($wishlist->ProductData->cover_image_path)}}">
                        </a>
                    </td>
                    <td data-label="Name">
                        <a href="{{route('page.product',[$slug,$p_id])}}">{{ !empty($wishlist->ProductData) ? $wishlist->ProductData->name : '' }}</a>
                        <div class="product-option">
                            <dt>{{ !empty($wishlist->GetVariant) ? $wishlist->GetVariant->variant : ''}}</dt>
                        </div>
                    </td>
                    <td data-label="date">{{$wishlist->created_at->format('d M, Y ')}}</td>
                    <td data-label="Price">
                        {{$currency_icon.$wishlist->ProductData->price}}
                    </td>
                    <td data-label="Total">
                        {{$currency_icon.$wishlist->ProductData->sale_price}}
                    </td>
                    <td data-label="Action">
                        <i class="ti ti-trash text-white py-1 delete_wishlist" data-id="{{ $wishlist->id }}" data-bs-toggle="tooltip" title="delete"></i>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td><h2>{{ __('No records found')}}</h2></td>
            </tr>
        @endif
    </tbody>
</table>

<div class="right-result-tbl text-right">
    <b>Showing {{ $wishlists->firstItem() }}</b> to {{ $wishlists->lastItem() }} of {{ $wishlists->currentPage() }} ({{ $wishlists->lastPage() }} Pages)
</div>
<div class="form-container">
    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
        @php
            $previousPageUrl = '';
            $nextPageUrl = '';
            if ($wishlists->currentPage() < 1) {
                $previousPageUrl = $wishlists->previousPageUrl();
            }
            if ($wishlists->lastPage() > 1 && $wishlists->currentPage() != $wishlists->lastPage()) {
                $nextPageUrl = $wishlists->nextPageUrl();
            }
        @endphp
        <button class="btn-secondary back-btn-acc" onclick="get_wishlist('{{ $previousPageUrl }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
            </svg>
            {{ __('Back') }}
        </button>
        <button class="btn continue-btn" onclick="get_wishlist('{{ $nextPageUrl }}')">
            {{ __('Next') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
            </svg>
        </button>
    </div>
</div>
