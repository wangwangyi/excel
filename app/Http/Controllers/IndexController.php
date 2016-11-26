<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Info;
use App\Http\Requests;
use App\Condition;
class IndexController extends Controller
{


	public function index(Request $request)
	{
		if ($request->has('create_time') and $request->create_time != '') {
			$time =  $request->input('create_time');
			$star = $time." 00:00:00";
			$end = $time." 23:59:59";
		}else{
			$time = date('Y-m-d',strtotime(Carbon::now()));
			$star = $time." 00:00:00";
			$end = $time." 23:59:59";
		}
		$info = Info::whereBetween('create_time', [$star,$end])->get()->count();
		$data[] = [
				'time' => $time,
				'num' => $info
		];
		$condition = Condition::orderBy('created_at','desc')->paginate(15);
		return view('welcome')->with('data',$data)->with('condition',$condition);
	}
	/*public function getDataCount (){
		$arrMonth = [];
		for($i=0;$i>-30;$i--){
			$dayBegin = date("Y-m-d 00:00:00",strtotime($i." day"));
			$dayEnd = date("Y-m-d 23:59:59",strtotime($i." day"));
			$count = Info::whereBetween('create_time', [$dayBegin, $dayEnd])->count();
			$arrMonth[$i]=[
					"date"=>$dayBegin,
					"num"=>$count
			];
		}
		return $arrMonth;
	}*/


	/*public function plan()
	{
		$total = session()->get('infos');
		$upload_now_num = count(session()->get('info'));
		return $upload_now_num;
		$plan = (Info::get()->count())/(count($total)+$upload_now_num);
		if($plan == 1){
			$res['status'] = 2;
			$res['num'] = $plan;
		}else{
			$res['status'] = 1;
			$res['num'] = $plan;
		}
		return $res;
	}*/

}
