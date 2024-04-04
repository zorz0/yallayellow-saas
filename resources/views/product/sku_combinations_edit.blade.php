
@if(count($combinations[0]) > 0)
	<table class="table table-bordered">
		<thead>
			<tr>
				<td class="text-center">
					<label for="" class="control-label">{{__('Variant')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Variant Price')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('SKU')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Stock')}}</label>
				</td>
				<td class="text-center">
                    <label for="" class="control-label">{{ __('Default ') }}</label>
                </td>
			</tr>
		</thead>
		<tbody>
@foreach ($combinations as $key => $combination)
	@php
		$sku = $product_name;

		$str = '';
		foreach ($combination as $key => $item){
			if($key > 0 ){
				$str .= '-'.str_replace(' ', '', $item);
				$sku .='-'.str_replace(' ', '', $item);
			}
			else{
                $str .= str_replace(' ', '', $item);
                $sku .='-'.str_replace(' ', '', $item);
			}
		}
	@endphp
	@if(strlen($str) > 0)
	@php
		$ProductVariantData = $product->ProductVariant($str);
	@endphp
		<tr data-id="{{ !empty($ProductVariantData->id) ? $ProductVariantData->id : 'new'  }}">
			<td>
				<label for="" class="control-label">{{ $str }}</label>
			</td>
			<td>
				{!! Form::number('price_'.$str, !empty($ProductVariantData) ? $ProductVariantData->price : 0, ["min"=>"0", "step"=>"0.01", "class"=>"form-control", "required"]) !!}
			</td>
			<td>
				<input type="text" name="sku_{{ $str }}" value="{{ $sku }}" class="form-control" required>
			</td>
			<td>
				{!! Form::number('stock_'.$str, !empty($ProductVariantData->stock) ? $ProductVariantData->stock : 0, ["min"=>"0", "step"=>"1", "class"=>"form-control", "required"]) !!}
			</td>
			<td>
				<div class="form-check">
					{!! Form::radio('default_variant', $sku, (!empty($ProductVariantData->id) && $product->default_variant_id == $ProductVariantData->id) ? 1 : 0 , ['class' => 'form-check-input']) !!}
				</div>
			</td>
		</tr>
	@endif
@endforeach

	</tbody>
</table>
@endif
