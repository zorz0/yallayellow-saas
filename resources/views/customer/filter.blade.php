@foreach ($customers as $customer)
    <tr class="font-style">
        @php
            if ($customer->regiester_date !== null) {
                $carbonDate = \Carbon\Carbon::parse($customer->regiester_date);
                $formattedDate = $carbonDate->format('F d, Y');
            } else {
                $formattedDate = null;
            }
            if ($customer->last_active !== null) {
                $active = \Carbon\Carbon::parse($customer->last_active);
                $last_active = $active->format('F d, Y');
            } else {
                $last_active = null;
            }

            $AOV = 0;
            if ($customer->total_spend() != 0 && $customer->Ordercount() != 0) {
                $AOV = number_format($customer->total_spend() / $customer->Ordercount(), 2);
            }
            $activityLogEntry = $activitylog->where('user_id', $customer->id)->first();

        @endphp
        <td>
            @if ($activityLogEntry)
                <a href="{{ route('customer.timeline', $customer->id) }}">
                    <span class="btn-inner--icon"></span>
                    <span class="btn-inner--text">{{ $customer->first_name }}
                        {{ $customer->last_name }}</span>
                </a>
            @else
                {{ $customer->first_name }} {{ $customer->last_name }}
            @endif
        </td>
        <td>{{ $last_active }}</td>
        <td>{{ $formattedDate }}</td>
        <td>{{ $customer->email }}</td>
        <td>
            <a href="{{ route('customer.show', $customer->id) }}">
                {{ $customer->Ordercount() }}
            </a>

        </td>
        <td>{{ $customer->total_spend() }}</td>
        <td>{{ $AOV }}</td>
        <td>{{ $customer->mobile }}</td>
        <td class="Action ignore">
            <div class="d-flex">
                @if ($activityLogEntry)
                    <a href="{{ route('customer.timeline', $customer->id) }}"
                        class="btn btn-sm btn-icon btn-warning me-2" data-bs-placement="top">
                        <i class="ti ti-eye f-20"></i>
                    </a>
                @endif
                {{-- @permission('Show Customer') --}}
                    <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-sm btn-icon btn-info me-2"
                        data-bs-placement="top">
                        <i class="ti ti-shopping-cart f-20"></i>
                    </a>
                {{-- @endpermission --}}
            </div>
        </td>
        <td>
            {{-- @permission('Status Customer') --}}
                @if ($customer->regiester_date != null)
                    <div class="form-check form-switch">
                        <input class="form-check-input page-checkbox" id="{{ $customer->id }}" type="checkbox"
                            name="page_active" data-onstyle="success" data-offstyle="danger" data-toggle="toggle"
                            data-on="off" data-off="on" @if ($customer->status == 1) checked="checked" @endif />
                    </div>
                @endif
            {{-- @endpermission --}}
        </td>
    </tr>
@endforeach
