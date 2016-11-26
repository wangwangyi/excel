<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Excel;
use App\Info;
use DB;
use App\Condition;
class ExcelController extends Controller
{

    public function make(){
        $cellData = [
            ['姓名','邮箱','电话','时间'],
            ['test','test','test','test'],

        ];
        Excel::create('info',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
    //Excel文件导出
    //第一个excel
    public function export($num,$time){
        ini_set("max_execution_time",'300');
        $star = $time." 00:00:00";
        $end = $time." 23:59:59";
        $chunk = Info::skip(($num-1)*60000)->take(60000)->select('name', 'email','tel','create_time')->whereBetween("create_time",[$star,$end])->distinct()->get()->toArray();
        foreach($chunk as $t){
            $c[] = ([
                'name' => $t['name'],
                'email' => $t['email'],
                'tel' => $t['tel'],
                'create_time' => $time,
            ]);
        }
        $res = [];
        for($i=0;$i<count($c);$i++){
            foreach($c[$i] as $k=>$v){
                $res[0]=[
                    "姓名","邮箱","电话","时间"
                ];
                $res[$i+1][]= $v;

            }

        }

        return Excel::create('info'.$num,function($excel) use ($res){
            $excel->sheet('score', function($sheet) use ($res){
                $sheet->rows($res);
            });
        })->export('xls');
    }
/*    //第二个
    public function export_two($time){
        ini_set("max_execution_time",'300');
        $star = $time." 00:00:00";
        $end = $time." 23:59:59";
        $info = Info::select('name', 'email','tel','create_time')->whereBetween("create_time",[$star,$end])->get()->toArray();
        $count = count($info);
        $collection = collect($info);
        if($count > 60000 && $count<120001)
        {
            $chunk1 = $collection->splice(0,60000)->all();
            $chunk2 = $collection->take($count-60000)->all();
        }else if($count > 60000 && $count > 120000){
            $chunk1 = $collection->splice(0,60000)->all();
            $chunk2 = $collection->take(60001)->all();
        }

        foreach($chunk2 as $t){
            $c[] = ([
                'name' => $t['name'],
                'email' => $t['email'],
                'tel' => $t['tel'],
                'create_time' => $time,
            ]);
        }
        $res2 = [];
        for($i=0;$i<count($c);$i++){
            foreach($c[$i] as $k=>$v){
                $res2[0]=[
                    "姓名","邮箱","电话","时间"
                ];
                $res2[$i+1][]= $v;

            }

        }

        Excel::create('info2',function($excel) use ($res2){
            $excel->sheet('score', function($sheet) use ($res2){
                $sheet->rows($res2);
            });
        })->export('xls');
    }
    //第三个
    public function export_three($time){
        ini_set("max_execution_time",'300');
        $star = $time." 00:00:00";
        $end = $time." 23:59:59";
        $info = Info::select('name', 'email','tel','create_time')->whereBetween("create_time",[$star,$end])->get()->toArray();
        $count = count($info);
        $collection = collect($info);
        $chunk1 = $collection->splice(0,60000)->all();
        $chunk2 = $collection->take(60001)->all();
        $chunk3 = $collection->splice(60001,$count-60000)->all();

        foreach($chunk3 as $t){
            $c[] = ([
                'name' => $t['name'],
                'email' => $t['email'],
                'tel' => $t['tel'],
                'create_time' => $time,
            ]);
        }
        $res3 = [];
        for($i=0;$i<count($c);$i++){
            foreach($c[$i] as $k=>$v){
                $res3[0]=[
                    "姓名","邮箱","电话","时间"
                ];
                $res3[$i+1][]= $v;

            }

        }

        Excel::create('info3',function($excel) use ($res3){
            $excel->sheet('score', function($sheet) use ($res3){
                $sheet->rows($res3);
            });
        })->export('xls');

    }*/

   /* public function condition(){
        DB::table('infos')->truncate();
        return back();
    }*/


    //Excel文件导入
    public function import(Request $request){
        file_put_contents(public_path()."/log.txt", "\r\n".date("Y-m-d h:i:s")." begin upload!\r\n", FILE_APPEND);
        ini_set('memory_limit', '1024M');
        ini_set("max_execution_time",'0');
        ignore_user_abort(true);
        set_time_limit(0);
        //判断请求中是否包含name=file的上传文件
        if(!$request->hasFile('excel')){
            session()->flash('null', 'null');
            return back();
        }
        $file = $request->file('excel');
        //判断文件上传过程中是否出错
        if(!$file->isValid()){
            session()->flash('error', 'error');
            var_dump($file->getErrorMessage());
            exit;
        }

        //文件类型
        $allow = array('application/vnd.ms-office');
        $mine = $request->file('excel')->getMimeType();

        if (!in_array($mine, $allow)) {
            session()->flash('type', 'type');
            return "<script>alert('文件格式不对，请下载Microsoft Excel2013！'),location.href= '/';</script>";
        }

        $newFileName = md5(time().rand(0,10000)).'.'.$file->getClientOriginalExtension();
        $savePath = 'test/';
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $request->file('excel')->move($savePath, $newFileName);
        file_put_contents(public_path()."/log.txt", date("Y-m-d h:i:s")." upload success!\r\n", FILE_APPEND);

        $filePath = 'public/test/'.iconv('UTF-8', 'GBK',$newFileName);
        $res = [];
        Excel::load($filePath, function ($reader) use (&$res){
            $data = $reader->all();
            for($i=0;$i<count($data);$i++){
                foreach($data[$i] as $k=>$v){
                    $res[$i+1][$k]= $v;
                }
            }
        });

        $data = [];
       foreach($res as $key){
           $data[] = ([
               'name' => $key['姓名'],
               'email' => $key['邮箱'],
               'tel' => $key['电话'],
               'create_time' => date("Y-m-d H:i:s"),
           ]);
        }

        file_put_contents(public_path()."/log.txt", date("Y-m-d h:i:s")." parse  excel success! rows number ".count($data)."\r\n", FILE_APPEND);

        // unique self
        $uniqueData = $this->assoc_unique($data,'tel');
        file_put_contents(public_path()."/log.txt", date("Y-m-d h:i:s")." unique self success!\r\n", FILE_APPEND);

        // get all datebase rows
        $databaseAllTelphone = $this->getAllDBTelphone();
        file_put_contents(public_path()."/log.txt", date("Y-m-d h:i:s")." get all telphone from db success!\r\n", FILE_APPEND);

        $t=microtime(true);
        foreach ($uniqueData as $key => $row){;
            if(isset($databaseAllTelphone[strval($row['tel'])])){;
                    unset($uniqueData[$key]);
                }
        }


        file_put_contents(public_path()."/log.txt", date("Y-m-d h:i:s")." unique from db success!\r\n", FILE_APPEND);


        $this->insertBach($uniqueData);
        file_put_contents(public_path()."/log.txt", date("Y-m-d h:i:s")." insert into db success!\r\n", FILE_APPEND);
        Condition::create([
            'in_num' => count($uniqueData),
        ]);
        session()->flash('success', 'success');
        return back();
    }

    private function assoc_unique($arr, $key) {
        $tmp_arr = array();
        foreach($arr as $k => $v) {
            if(in_array($v[$key], $tmp_arr)) {
                unset($arr[$k]);
            } else {
                $tmp_arr[] = $v[$key];
            }
        }
        return $arr;

    }

    public function getAllDBTelphone(){
        $total = Info::count();
//        $total = 10; //for test
        $number = 100000;// fetch per sql
        $loop = intval($total / $number)+1;
        $databaseAllTelphone=[];
        for ($i=0; $i<$loop;$i++){
            $ptr = $i*$number;
            $sql = "select tel FROM infos limit {$ptr}, {$number};";
            $dataAll = DB::select($sql);
            foreach($dataAll as $tmpObj){
                $databaseAllTelphone[$tmpObj->tel] = 1;
            }
        }
        return $databaseAllTelphone;
    }

    public function insertBach($uniqueData){
        $data = array_chunk($uniqueData, 50);
        foreach ($data as $rows){
            $re = DB::table('infos')->insert($rows);
        }
    }


}
