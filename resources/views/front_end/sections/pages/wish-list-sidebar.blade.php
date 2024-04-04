<style>
    wishDrawer .closewish {
    position: absolute;
    left: -38px;
    top: 20px;
    width: 20px;
    height: 20px;
    opacity: 0;
    visibility: hidden;
}
.wishOpen .wishDrawer .closewish {
    opacity: 1;
    visibility: visible;
}
</style>
<div class="wishDrawer">
    <div class="mini-wish-header">
       <h4>{{ __('My wish') }}</h4>
       {{-- <div class="wish-tottl-itm">0{{ __('Items') }}</div> --}}
       <a href="#" class="closewish">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path
                    d="M20 1.17838L18.8216 0L10 8.82162L1.17838 0L0 1.17838L8.82162 10L0 18.8216L1.17838 20L10 11.1784L18.8216 20L20 18.8216L11.1784 10L20 1.17838Z"
                    fill="white"></path>
            </svg>
        </a>
    </div>
    <div id="wish-body" class="mini-wish-has-item">
       <div class="mini-wish-body">
        @if (!empty($response->data->data))
        @foreach ($response->data->data as $item)
          <div class="mini-wish-item">
             <div class="mini-wish-image">
                <a href="{{url($slug.'/product/'.$item->product_data->slug)}}" title="SPACE BAG">
                   <img src="{{ asset($item->product_image) }}" alt="plant1">
                </a>
             </div>
             <div class="mini-wish-details" style="color: black;">
                <p class="mini-wish-title"><a href="{{url($slug.'/product/'.$item->product_data->slug)}}"> {{$item->product_name}}</a></p>
                @if ($item->variant_id != 0)
                    {!! \App\Models\ProductVariant::variantlist($item->variant_id) !!}
                @endif

                <div class="pvarprice d-flex align-items-center justify-content-between">
                   <div class="price">

                      <ins>{{ currency_format_with_sym( $item->final_price, $store->id, $currentTheme) ?? SetNumberFormat($item->final_price) }}</ins><del>{{ currency_format_with_sym( $item->original_price, $store->id, $currentTheme) ?? SetNumberFormat($item->original_price) }}</del>
                   </div>

                </div>
             </div>

             <a href="JavaScript:void(0)" class="btn  addtocart-btn addcart-btn-globaly" style ="width: 63%;" product_id="{{ $item->product_id }}" variant_id="{{ $item->variant_id}}" qty="1">
                <span>{{__('Add to cart')}}</span>
                <svg viewBox="0 0 10 5">
                    <path
                        d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                    </path>
                </svg>
            </a>

            <a href="javascript:;" class="remove-btn py-1 delete_wishlist" data-id="{{ $item->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" focusable="false" role="presentation" class="icon icon-remove ">
                    <path d="M14 3h-3.53a3.07 3.07 0 00-.6-1.65C9.44.82 8.8.5 8 .5s-1.44.32-1.87.85A3.06 3.06 0 005.53 3H2a.5.5 0 000 1h1.25v10c0 .28.22.5.5.5h8.5a.5.5 0 00.5-.5V4H14a.5.5 0 000-1zM6.91 1.98c.23-.29.58-.48 1.09-.48s.85.19 1.09.48c.2.24.3.6.36 1.02h-2.9c.05-.42.17-.78.36-1.02zm4.84 11.52h-7.5V4h7.5v9.5z" fill="currentColor"></path>
                    <path d="M6.55 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5zM9.45 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5z" fill="currentColor"></path>
                </svg>
            </a>
          </div>
          @endforeach
        @else
            <p class="emptywish text-center">{{ __('You have no items in your shopping wish.') }}</p>
        @endif
       </div>

    </div>
</div>


