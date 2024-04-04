@foreach($plans as $plan)
    <div class="list-group-item my-2 upgrade-plan-model-card">
        <div class="row align-items-center">
            <div class=" d-flex gap-3 ml-n2 align-items-center justify-content-start mb-3">
                <a href="#!" class="badge f-12 p-2 d-block h6 mb-0 bg-warning">{{$plan->name}}</a>
                <div>
                    <span class="f-20 f-w-600">{{GetCurrency().$plan->price}} {{' / '. __(\App\Models\Plan::$arrDuration[$plan->duration])}}</span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-4 justify-content-between mb-3 px-5">
                <div class="text-center ml-n2">
                    <div>
                        <span class="text-lg">{{$plan->max_stores}}</span>
                    </div>
                    <a href="#!" class="d-block h6 mb-0 f-16">{{__('Stores')}}</a>
                </div>
                <div class="text-center ml-n2">
                    <div>
                        <span class="text-lg">{{$plan->max_products}}</span>
                    </div>
                    <a href="#!" class="d-block h6 mb-0 f-16">{{__('Products')}}</a>
                </div>
                <div class="text-center ml-n2">
                    <div>
                        <span class="text-lg">{{$plan->max_users}}</span>
                    </div>
                    <a href="#!" class="d-block h6 mb-0 f-16">{{__('Users')}}</a>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                @if($user->plan_id==$plan->id)
                <span class="d-flex align-items-center badge bg-primary d-block p-2 position-absolute justify-content-center" style="top: 10px; right:0; border-radius:5px 0 0 5px">
                    <i class="f-10 lh-1 fas fa-circle text-primary"></i>
                    <span class="ms-2">{{ __('Active')}}</span>
                </span>
                @else
                    <a href="{{route('plan.active',[$user->id,$plan->id])}}" class="btn btn-xs btn-primary btn-icon w-75" data-toggle="tooltip" data-original-title="{{__('Click to Upgrade Plan')}}">
                        <span class="btn-inner--icon">{{ __('Upgrade Plan')}}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endforeach
