<div class="modal modal-popup" id="commanModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-inner lg-dialog" role="document">
            <div class="modal-content">
                <div class="popup-content">
                    <div class="modal-header  popup-header align-items-center">
                        <div class="modal-title">
                            <h6 class="mb-0" id="modelCommanModelLabel"></h6>
                        </div>
                        <button type="button" class="close close-button" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>

            </div>
        </div>
    </div>

   <!--  jQuery Validation  -->
   <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
   <script src="{{ asset('js/jquery-cookie.min.js') }}"></script>
@stack('recentViewModelPopup')
<!--scripts end here-->

<!--scripts start here-->
<script>
    var guest = '{{ Auth::guest() }}';
    var filterBlog = "{{ route('blogs.filter.view',$store->slug) }}";
    var cartlistSidebar = "{{ route('cart.list.sidebar',$store->slug) }}";
    var ProductCart = "{{ route('product.cart',$store->slug) }}";
    var addressbook_data = "{{ route('get.addressbook.data', $store->slug) }}";
    var shippings_data = "{{ route('get.shipping.data', $store->slug) }}";
    var get_shippings_data = "{{ route('get.shipping.data', $store->slug) }}";
    var shippings_methods = "{{ route('shipping.method', $store->slug) }}";
    var apply_coupon = "{{ route('applycoupon', $store->slug) }}";
    var paymentlist = "{{ route('paymentlist', $store->slug) }}";
    var additionalnote = "{{ route('additionalnote', $store->slug) }}";
    var state_list = "{{ route('states.list', $store->slug) }}";
    var city_list = "{{ route('city.list', $store->slug) }}";
    var changeCart = "{{ route('change.cart', $store->slug) }}";
    var wishListSidebar = "{{ route('wish.list.sidebar', $store->slug) }}";
    var removeWishlist = "{{ route('delete.wishlist', $store->slug) }}";
    var addProductWishlist = "{{ route('product.wishlist', $store->slug) }}";
    var isAuthenticated = "{{ auth('customers')->check() ? 'true' : 'false' }}";
    var removeCart = "{{  route('cart.remove', $store->slug)  }}";
    var productPrice = "{{ route('product.price', $store->slug) }}";
    var wishList = "{{ route('wish.list',$store->slug) }}";
    var whatsappNumber = "{{ $whatsapp_contact_number ?? '' }}";
    var taxes_data = "{{ route('get.tax.data', $store->slug) }}";
    var searchProductGlobaly = "{{ route('search.product', $store->slug) }}";
    var loginUrl = "{{ route('customer.login', $store->slug) }}";
</script>
<script src="{{ asset('assets/js/front-theme.js') }}" defer="defer"></script>
