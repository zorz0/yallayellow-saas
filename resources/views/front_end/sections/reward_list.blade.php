<table class="order-history-tbl">
    <thead>
        <tr>
            <th scope="col">{{ __('Order ID') }}</th>
            <th scope="col">{{ __('Order Date') }}</th>
            <th scope="col">{{ __('Reward point') }}</th>            
        </tr>
    </thead>
    <tbody>
        @if (count($orders) > 0)
            @foreach ($orders as $Order)        
                @php $order_data = $Order->order_detail($Order->id); @endphp    
                <tr>
                    <td> {{ $order_data['order_id'] }} </td> 
                    <td> {{ $order_data['delivery_date'] }} </td>
                    <td> {{ $order_data['order_reward_point'] }} </td>
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
    <b>Showing {{ $orders->firstItem() }}</b> to {{ $orders->lastItem() }} of {{ $orders->currentPage() }} ({{ $orders->lastPage() }} Pages)
</div>
<div class="form-container">
    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
        @php
            $previousPageUrl = '';
            $nextPageUrl = '';
            if ($orders->currentPage() < 1) {
                $previousPageUrl = $orders->previousPageUrl();
            }
            if ($orders->lastPage() > 1 && $orders->currentPage() != $orders->lastPage()) {
                $nextPageUrl = $orders->nextPageUrl();
            }
        @endphp
        <button class="btn-secondary back-btn-acc" onclick="get_reward('{{ $previousPageUrl }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
            </svg>
            {{ __('Back') }}
        </button>
        <button class="btn continue-btn" onclick="get_reward('{{ $nextPageUrl }}')">
            {{ __('Next') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
            </svg>
        </button>
    </div>
</div>
