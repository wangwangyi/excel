<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>excel</title>
    <link rel="stylesheet" href="/assets/css/xSystem.css">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<header class="am-topbar am-topbar-inverse admin-header">
    <div class="am-topbar-brand">
        <strong></strong> <small></small>
    </div>

    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

        <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
            <li class="am-hide-sm-only"><a href="{{ url('/logout') }}" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">退出</span></a></li>
        </ul>
    </div>
</header>
<div class="admin-content">
    <div class="admin-content-body">
        <div class="am-g am-g-fixed am-margin-top">
            <div class="am-u-sm-12">
                <h1>excel导入，数据导出</h1>

            </div>
        </div>
        @if(Session::has('success'))
        <div class="am-g am-g-fixed ">
            <div class="am-u-sm-6">
                <div class="am-alert">
                    上传成功！
                </div>
            </div>
        </div>
        @endif

        <div class="am-g am-g-fixed am-margin-top">
            <p class="am-u-sm-12">选择你要导入的Excel文件</p>
            <div class="am-g">
                <div class="am-u-sm-12 am-u-md-9">
                    <form  class="am-form" enctype="multipart/form-data" method="post" action="/excel/import">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="am-g am-margin-top">
                            <div class="am-u-sm-4 am-u-md-2 am-text-right">
                                excel文件
                            </div>
                            <div class="am-u-sm-8 am-u-md-4">
                                <input type="file" class="am-input-sm excel" name="excel">
                            </div>
                            <div class="am-hide-sm-only am-u-md-6"></div>
                            <button style="display:none;"
                                    type="button"
                                    class="am-btn am-btn-success plan"
                                    data-am-modal="{target: '#my-modal-loading'}">
                            </button>
                            <input  type="submit" id="submit" style="display:none;"/>
                            <button type="button" class="am-btn am-btn-primary submit" style="margin-left:30px;">提交</button>
                        </div>

                    </form>
                </div>
            </div>

            <p class="am-u-sm-12">如果你还没有下载Microsoft Excel2013，点击生成！
           <a href="/make"><button type="button" class="am-btn am-btn-primary submit">生成</button></a>
            </p>
            <hr />
            <div class="am-cf am-padding am-padding-bottom-0" >
                <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">导出</strong></div>
            </div>
            <form method="get">
                <div class="am-g">
                    <div class="am-u-sm-12 am-u-md-3">
                        <div class="am-input-group am-input-group-sm">
                            <input type="text" value="" name="create_time" class="am-form-field" placeholder="选择时间" data-am-datepicker readonly required />
                        <span class="am-input-group-btn">
                            <button class="am-btn am-btn-default" type="submit">搜索</button>
                        </span>
                        </div>
                    </div>
                </div>
            </form>

            <div class="am-g">
                <div class="am-u-sm-12">
                    <table class="am-table am-table-striped am-table-hover table-main">
                        <thead>
                        <tr>
                            <th class="table-id">时间</th>
                            <th class="table-title">数量</th>
                            <th class="table-set">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td>{{$d['time']}}</td>
                                <td>{{$d['num']}}</td>
                                <td>
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                            @if($d['num'] > 0)
                                            @for($i=0;$i<($d['num'] / 60000);$i++)
                                                <a href="/excel/{{$i+1}}/{{$d['time']}}">
                                                    <button class="am-btn am-btn-default am-btn-xs am-text-secondary"> 导出表{{$i+1}}</button>
                                                </a>
                                                @endfor
                                                @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="am-cf am-padding am-padding-bottom-0" style="margin-top:30px;">
                <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">导入记录</strong></div>
            </div>

            <div class="am-g">
                <div class="am-u-sm-12">
                    <table class="am-table am-table-striped am-table-hover table-main">
                        <thead>
                        <tr>
                            <th class="table-id">编号</th>
                            <th class="table-title">数量(总数量{{\App\Info::count()}})</th>
                            <th class="table-set">时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($condition as $c)
                            <tr>
                                <td>{{$c->id}}</td>
                                <td>{{$c->in_num}}</td>
                                <td>{{date('Y年m月d日 H:i:s',strtotime($c->created_at))}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="am-cf">
                        <div class="am-fr">
                            {!! $condition->links() !!}
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>
</div>
<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="my-modal-loading">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">正在导入...</div>
        <div class="am-progress am-progress-striped am-progress-sm am-active ">
            <div class="am-progress-bar am-progress-bar-secondary"  style="width: 100%"></div>
        </div>
    </div>
</div>


<script src="/assets/js/jquery.min.js"></script>

<script src="/assets/js/amazeui.min.js"></script>
<script>


    $(function(){
      /*  var intReFlushChartTime = 30000;
        var k=0;
        function startTime() {/!*定时器开始*!/
            var intChartTime = setInterval(function () {
                getTime()
            }, intReFlushChartTime);
        }
        function clearTime() {/!*定时器结束*!/
            clearInterval();
        }
        function getTime(){
            //ajax...
            $.ajax({
                type : "get",
                url: "/plan",
                success: function (res){
                    console.log(res);
                    k++;
                        var html =
                                ' <div class="am-progress-bar" style="width: '+ res.num +'%">'+ res.num +'%</div>';
                        $(html).appendTo(".am-progress").fadeIn(600);

                }
            }, "json")
        }*/
        $(".submit").click(function(){
            var excel = $(".excel").val();
            if(excel != ''){
                $("#submit").click();
               setTimeout(function(){
                    $(".plan").click();
                }, 5000);
               /* setTimeout(function(){
                    getTime();
                }, 100);

               /* if(k=1){
                    startTime();
                }
                if(k=10){
                    clearTime();
                }*/
            }
        })
    })
</script>
<script>
    $.ajaxSetup({
        headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
</script>
</body>
</html>
