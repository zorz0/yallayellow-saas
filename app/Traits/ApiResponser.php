<?php

namespace App\Traits;

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
	/**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
	protected function success($data = [], $message = "successfull", int $code = 200, $count_data = '', $max_price = 0)
	{
		$res_array = [
			'status' => 1,
			'message' => $message,
			'data' => $data
		];
		
		if($count_data != '') {
			$count_data_ARRAY['count'] = $count_data;
			$res_array = array_merge($count_data_ARRAY, $res_array);
		}
		
		if(isset($max_price)) {
			$count_data_ARRAY['max_price'] = $max_price;
			$res_array = array_merge($count_data_ARRAY, $res_array);
		}
		return response()->json($res_array, $code);
	}

	/**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
	protected function error($data = [], $message = 'fail', int $code = 200, $status = 0 , $count_data = '')
	{
		$res_array = [
			'status' => $status,
			'message' => $message,
			'data' => $data
		];

		if($count_data != '') {
			$count_data_ARRAY['count'] = $count_data;
			$res_array = array_merge($count_data_ARRAY, $res_array);
		}
		return response()->json($res_array, $code);
	}

	protected function NumberConvert($number = 0) {
		$final_output = 0;
		if($number != '') {
			$final_output = gettype($number) == "integer" || gettype($number) == "double" ? $number : (float)number_format((float)$number, 2,'.','');
		}
		return $final_output;
	}

}