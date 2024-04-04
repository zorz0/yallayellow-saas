<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeAnalyticController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        if($user->type != 'super admin')
        {
            
            $theme     = APP_THEME();            
            $chartData = $this->getStoreOrderChart(['duration' => 'month'], $theme, $user);
    
            $visitor_url   = \DB::table('shetabit_visits')->selectRaw("count('*') as total, url")->where('theme_id', $theme)->where('store_id', $user->current_store)->groupBy('url')->orderBy('total', 'DESC')->get();
            $user_device   = \DB::table('shetabit_visits')->selectRaw("count('*') as total, device")->where('theme_id', $theme)->where('store_id', $user->current_store)->groupBy('device')->orderBy('device', 'DESC')->get();
            $user_browser  = \DB::table('shetabit_visits')->selectRaw("count('*') as total, browser")->where('theme_id', $theme)->where('store_id', $user->current_store)->groupBy('browser')->orderBy('browser', 'DESC')->get();
            $user_platform = \DB::table('shetabit_visits')->selectRaw("count('*') as total, platform")->where('theme_id', $theme)->where('store_id', $user->current_store)->groupBy('platform')->orderBy('platform', 'DESC')->get();
    
            $devicearray          = [];
            $devicearray['label'] = [];
            $devicearray['data']  = [];
    
            foreach($user_device as $name => $device)
            {
                if(!empty($device->device))
                {
                    $devicearray['label'][] = $device->device;
                }
                else
                {
                    $devicearray['label'][] = 'Other';
                }
                $devicearray['data'][] = $device->total;
            }
    
            $browserarray          = [];
            $browserarray['label'] = [];
            $browserarray['data']  = [];
    
            foreach($user_browser as $name => $browser)
            {
                $browserarray['label'][] = $browser->browser;
                $browserarray['data'][]  = $browser->total;
            }
            $platformarray          = [];
            $platformarray['label'] = [];
            $platformarray['data']  = [];
    
            foreach($user_platform as $name => $platform)
            {
                $platformarray['label'][] = $platform->platform;
                $platformarray['data'][]  = $platform->total;
            }
            return view('theme_analytic',compact('chartData','visitor_url','devicearray','browserarray','platformarray','theme'));
        }
        else{
            return redirect()->route('dashboard')->with('error', __('Permission Denied'));
        }
    }

    public function getStoreOrderChart($arrParam, $theme, $user)
    {
        if($user->type != 'super admin')
        {    
            $arrDuration = [];
            if($arrParam['duration'])
            {
                
                if($arrParam['duration'] == 'month')
                {
                    $previous_month = strtotime("-2 week +2 day");
                    for($i = 0; $i < 15; $i++)
                    {
                        $arrDuration[date('Y-m-d', $previous_month)] = date('d-M', $previous_month);
                        $previous_month                              = strtotime(date('Y-m-d', $previous_month) . " +1 day");
                    }
                }
            }
            $arrTask          = [];
            $arrTask['label'] = [];
            $arrTask['data']  = [];
    
            foreach($arrDuration as $date => $label)
            {
                $data['visitor'] = \DB::table('shetabit_visits')->select(\DB::raw('count(*) as total'))->where('theme_id', $theme)->where('store_id', $user->current_store)->whereDate('created_at', '=', $date)->first();
                $uniq            = \DB::table('shetabit_visits')->select('ip')->distinct()->where('theme_id', $theme)->where('store_id',$user->current_store)->whereDate('created_at', '=', $date)->get();
    
                $data['unique']           = $uniq->count();
                $arrTask['label'][]       = $label;
                $arrTask['data'][]        = $data['visitor']->total;
                $arrTask['unique_data'][] = $data['unique'];
            }
    
            return $arrTask;
        }
        else{
            return redirect()->route('dashboard')->with('error', __('Permission Denied'));
        }

    }
}
