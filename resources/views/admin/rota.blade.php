@extends('layouts.app')

@section('title', '當值人員')

@section('content')
    @include('common.notifications')
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        <li>後臺管理</li>
        <li class="active">當值人員</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="btn-toolbar" role="toolbar">
                <div class="btn-group" role="group">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $year }} 年 <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            @for ($i = date('Y') - 1, $j = 0; $j < 5; $j ++)
                            <li><a href="/admin/rota/{{ $i + $j }}/{{ $month }}">{{ $i + $j }} 年</a></li>
                            @endfor
                        </ul>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $month }} 月 <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            @for ($i = 1; $i <= 12; $i ++)
                                <li><a href="/admin/rota/{{ $year }}/{{ $i }}">{{ $i }} 月</a></li>
                            @endfor
                        </ul>
                    </div>
                </div>
                <div class="btn-group" role="group">
                    <a href="/admin/rota/{{ date('Y/n', strtotime($year.'-'.$month.'-'.'1-1 month')) }}" class="btn btn-default" aria-label="上個月"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
                    <a href="/admin/rota/{{ date('Y/n') }}" class="btn btn-default">回到今天</a>
                    <a href="/admin/rota/{{ date('Y/n', strtotime($year.'-'.$month.'-'.'1+1 month')) }}" class="btn btn-default" aria-label="下個月"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></a>
                </div>
            </div>
        </div>
    </div>
    <?php
    $raw = $year . '/' . $month . '/1';
    $start = DateTime::createFromFormat('Y/n/j', $raw);
    $start_w = $start->format('w');
    $start_t = $start->format('t');

    $title1_users = App\User::where('title', 'like', '%駐部官%')->get();
    $title2_users = App\User::where('title', 'like', '%理事官%')->get();
    $title3_users = App\User::where('title', 'like', '%值日官%')->get();
    $rota1s = App\User::where('title', 'like', '%駐部官%')->get();
    $rota2s = App\User::where('title', 'like', '%理事官%')->get();
    $rota3s = App\User::where('title', 'like', '%值日官%')->get();
    ?>
    <form action="/admin/rota/{{ $year }}/{{ $month }}" method="POST">
        {{ csrf_field() }}
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>星期日</th>
                    <th>星期一</th>
                    <th>星期二</th>
                    <th>星期三</th>
                    <th>星期四</th>
                    <th>星期五</th>
                    <th>星期六</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < ceil(($start_t+$start_w)/7); $i ++)
                    <tr>
                        @for ($k = 1; $k <= 7; $k ++)
                            <td>
                                @if (($i * 7 + $k - $start_w) > 0 and ($i * 7 + $k - $start_w) <= $start_t)
                                    {{ $month }} / {{ $i * 7 + $k - $start_w }}
                                @endif
                            </td>
                        @endfor
                    </tr>
                    <tr>
                        @for ($k = 1; $k <= 7; $k ++)
                            <td>
                                @if (($i * 7 + $k - $start_w) > 0 and ($i * 7 + $k - $start_w) <= $start_t)
                                    @if (strtotime($year.'-'.$month.'-'.($i * 7 + $k - $start_w)) > strtotime('now'))
                                        <select name="rota-title1-{{ $i * 7 + $k - $start_w }}" class="form-control">
                                            <option style="color: #d9534f" value="{{ $title1->{'d'.($i * 7 + $k - $start_w)} or '' }}">{{ $title1->{'d'.($i * 7 + $k - $start_w)} or '' }}</option>
                                            @foreach ($title1_users as $user)
                                                <option style="color: #d9534f" value="{{ $user->name }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="rota-title2-{{ $i * 7 + $k - $start_w }}" class="form-control">
                                            <option style="color: #5cb85c" value="{{ $title2->{'d'.($i * 7 + $k - $start_w)} or '' }}">{{ $title2->{'d'.($i * 7 + $k - $start_w)} or '' }}</option>
                                            @foreach ($title2_users as $user)
                                                <option style="color: #5cb85c" value="{{ $user->name }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="rota-title3-{{ $i * 7 + $k - $start_w }}" class="form-control">
                                            <option style="color: #f0ad4e" value="{{ $title3->{'d'.($i * 7 + $k - $start_w)} or '' }}">{{ $title3->{'d'.($i * 7 + $k - $start_w)} or '' }}</option>
                                            @foreach ($title3_users as $user)
                                                <option style="color: #f0ad4e" value="{{ $user->name }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <span style="color: #d9534f">{{ $title1->{'d'.($i * 7 + $k - $start_w)} or '' }}</span><br>
                                        <span style="color: #5cb85c">{{ $title2->{'d'.($i * 7 + $k - $start_w)} or '' }}</span><br>
                                        <span style="color: #f0ad4e">{{ $title3->{'d'.($i * 7 + $k - $start_w)} or '' }}</span>
                                    @endif
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
        <div class="text-right">由上至下依序為：<span style="color: #d9534f">駐部官（紅）</span>、<span style="color: #5cb85c">理事官（綠）</span>、<span style="color: #f0ad4e">值日官（黃）</span>
        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> 確認送出</button></div>
    </form>

    <div class="sub-content">

            <h4 class="ribbon" id="edit">編輯群組：</h4>
            <div class="row">
                <div class="col-xs-4 text-center ">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-danger">駐部官</li>
                        @foreach ($title1_users as $user)
                            <li class="list-group-item">
                                <form action="/admin/rota/{{ $year }}/{{ $month }}/{{ $user->id }}/駐部官#add" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    {{ $user->name }} <button type="submit" id="delete-rota1-{{ $user->id }}" class="btn btn-danger btn-xs">
                                        <span class="glyphicon glyphicon-trash"></span> 刪除
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-xs-4 text-center">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-success">理事官</li>
                        @for ($r2 = 0; $r2 < $rota2s->count(); $r2++)
                            <li class="list-group-item">
                                <form action="/admin/rota/{{ $year }}/{{ $month }}/{{ $rota2s[$r2]->id }}/理事官#add" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    {{ $rota2s[$r2]->name }} <button type="submit" id="delete-rota2-{{ $rota2s[$r2]->id }}" class="btn btn-danger btn-xs">
                                        <span class="glyphicon glyphicon-trash"></span> 刪除
                                    </button>
                                </form>
                            </li>
                        @endfor
                    </ul>
                </div>
                <div class="col-xs-4 text-center">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-warning">值日官</li>
                        @for ($r3 = 0; $r3 < $rota3s->count(); $r3++)
                            <li class="list-group-item">
                                <form action="/admin/rota/{{ $year }}/{{ $month }}/{{ $rota3s[$r3]->id }}/值日官#add" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    {{ $rota3s[$r3]->name }} <button type="submit" id="delete-rota3-{{ $rota3s[$r3]->id }}" class="btn btn-danger btn-xs">
                                        <span class="glyphicon glyphicon-trash"></span> 刪除
                                    </button>
                                </form>
                            </li>
                        @endfor
                    </ul>
                </div>
            </div>
            <hr>

        <p id="add">新增：</p>
        <form action="/admin/rota/{{ $year }}/{{ $month }}/add#add" method="POST" class="form-inline">
            {{ csrf_field() }}
            <select name="unit" class="form-control">
                <option value="">單位</option>
                @foreach (['通信系統指揮部', '部本部', '通資綜合科', '通資安全科', '電子系統科', '左營通信隊', '左營通信隊 - 修護區隊', '左營通信隊 - 作業區隊', '左營通信隊 - 龍泉發射台', '臺北通信隊', '臺北通信隊 - 修護區隊', '臺北通信隊 - 作業區隊', '臺北通信隊 - AOC 區隊', '臺北通信隊 - 基隆區隊', '臺北通信隊 - 淡水發射臺', '臺北通信隊 - 八里發射臺', '臺北通信隊 - 三芝天線場', '臺北通信隊 - 綠坵山發射臺', '蘇澳通信隊', '蘇澳通信隊 - 修護區隊', '蘇澳通信隊 - 作業區隊', '蘇澳通信隊 - 花蓮區隊', '馬公通信隊', '馬公通信隊 - 修護區隊', '馬公通信隊 - 作業區隊', '馬公通信隊 - 菜園發射臺'] as $unit)
                    <option value="{{ $unit }}"{{ old('unit') == $unit ? ' selected' : '' }}>{{ $unit }}</option>
                @endforeach
            </select>
            <select class="form-control" name="rank">
                <option value="">階級</option>
                <optgroup label="校官">
                    <option value="上校"{{ old('rank') == '上校' ? ' selected' : '' }}>上校</option>
                    <option value="中校"{{ old('rank') == '中校' ? ' selected' : '' }}>中校</option>
                    <option value="少校"{{ old('rank') == '少校' ? ' selected' : '' }}>少校</option>
                </optgroup>
                <optgroup label="尉官">
                    <option value="上尉"{{ old('rank') == '上尉' ? ' selected' : '' }}>上尉</option>
                    <option value="中尉"{{ old('rank') == '中尉' ? ' selected' : '' }}>中尉</option>
                    <option value="少尉"{{ old('rank') == '少尉' ? ' selected' : '' }}>少尉</option>
                </optgroup>
                <optgroup label="士官">
                    <option value="士官長"{{ old('rank') == '士官長' ? ' selected' : '' }}>士官長</option>
                    <option value="上士"{{ old('rank') == '上士' ? ' selected' : '' }}>上士</option>
                    <option value="中士"{{ old('rank') == '中士' ? ' selected' : '' }}>中士</option>
                    <option value="下士"{{ old('rank') == '下士' ? ' selected' : '' }}>下士</option>
                </optgroup>
                <optgroup label="士兵">
                    <option value="上兵"{{ old('rank') == '上兵' ? ' selected' : '' }}>上兵</option>
                    <option value="一兵"{{ old('rank') == '一兵' ? ' selected' : '' }}>一兵</option>
                    <option value="二兵"{{ old('rank') == '二兵' ? ' selected' : '' }}>二兵</option>
                </optgroup>
            </select>
            <button type="submit" class="btn btn-primary" name="submit"><span class="glyphicon glyphicon-search"></span> 搜尋</button>
        </form>
        @if (!is_null(old('unit')))
            <br><form action="/admin/rota/{{ $year }}/{{ $month }}/add#add" method="POST" class="form-inline">
                {{ csrf_field() }}
                <select class="form-control" name="user">
                    @foreach (App\User::where('unit', 'like', '%'.old('unit').'%')->where('rank', 'like', '%'.old('rank').'%')->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <select class="form-control" name="title">
                    <option value="駐部官" style="color: #d9534f">駐部官</option>
                    <option value="理事官" style="color: #5cb85c">理事官</option>
                    <option value="值日官" style="color: #f0ad4e">值日官</option>
                </select>
                <button type="submit" class="btn btn-primary" name="submit"><span class="glyphicon glyphicon-ok"></span> 新增</button>
            </form>
        @endif

    </div>
@endsection
