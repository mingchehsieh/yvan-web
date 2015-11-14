@extends('layouts.app')

@section('title', '生活花絮')

@section('content')
    @include('common.errors')
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        <li>後臺管理</li>
        <li class="active">生活花絮</li>
    </ol>
    <form action="/admin/life" method="GET">
        <div class="row">
            <div class="col-xs-2">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle btn-block" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">隊別 <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    @foreach (['通信系統指揮部', '部本部', '通資綜合科', '通資安全科', '電子系統科', '左營通信隊', '左營通信隊 - 修護區隊', '左營通信隊 - 作業區隊', '左營通信隊 - 龍泉發射台', '臺北通信隊', '臺北通信隊 - 修護區隊', '臺北通信隊 - 作業區隊', '臺北通信隊 - AOC 區隊', '臺北通信隊 - 基隆區隊', '臺北通信隊 - 淡水發射臺', '臺北通信隊 - 八里發射臺', '臺北通信隊 - 三芝天線場', '臺北通信隊 - 綠坵山發射臺', '蘇澳通信隊', '蘇澳通信隊 - 修護區隊', '蘇澳通信隊 - 作業區隊', '蘇澳通信隊 - 花蓮區隊', '馬公通信隊', '馬公通信隊 - 修護區隊', '馬公通信隊 - 作業區隊', '馬公通信隊 - 菜園發射臺'] as $unit)
                        <li><a href="/admin/life?q={{ $unit }}">{{ $unit }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-xs-8">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">搜尋：</span>
                    <input type="text" class="form-control" name="q" value="{{ $q }}">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-primary">送出</button>
                        <a href="/admin/life" class="btn btn-default">清除</a>
                    </div>
                </div>
            </div>
            <div class="col-xs-2">
                <a href="#add" class="btn btn-default btn-sm btn-block">新增相片</a>
            </div>
        </div>
    </form>

    @if ($lives->count() !== 0)
        <table class="table table-striped max-last3-td">
            <thead>
                <tr>
                    <th>上傳日期</th>
                    <th>單位</th>
                    <th>標題</th>
                    <th>圖片</th>
                    <th>動作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lives as $life)
                <tr>
                    <td>{{ $life->created_at->toDateString() }}</td>
                    <td>{{ $life->unit }}</td>
                    <td>{{ $life->subject }}</td>
                    <td>
                        <a href="/images/uploads/{{ $life->fileentry->filename }}" target="_blank">
                            <img src="/images/uploads/{{ $life->fileentry->filename }}" width="100">
                        </a>
                    </td>
                    <td>
                        <form action="/admin/life/{{ $life->id }}" method="POST" class="delete-form">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a href="/admin/life/{{ $life->id }}/edit" class="btn btn-default btn-xs">
                                <span class="glyphicon glyphicon-pencil"></span> 修改
                            </a>
                            <button type="submit" id="delete-life-{{ $life->id }}" class="btn btn-danger btn-xs">
                                <span class="glyphicon glyphicon-trash"></span> 刪除
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">{!! $lives->appends(['q' => $q])->render() !!}</div>
    @else
        <br>
        <p class="text-center">無資料</p>
    @endif
    <div class="sub-content">
        <form action="/admin/life" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h4 class="ribbon" id="add">新增相片：</h4>
            <div class="form-group">
                <select class="form-control" name="unit">
                    <option value="">單位名稱</option>
                    @foreach (['通信系統指揮部', '部本部', '通資綜合科', '通資安全科', '電子系統科', '左營通信隊', '左營通信隊 - 修護區隊', '左營通信隊 - 作業區隊', '左營通信隊 - 龍泉發射台', '臺北通信隊', '臺北通信隊 - 修護區隊', '臺北通信隊 - 作業區隊', '臺北通信隊 - AOC 區隊', '臺北通信隊 - 基隆區隊', '臺北通信隊 - 淡水發射臺', '臺北通信隊 - 八里發射臺', '臺北通信隊 - 三芝天線場', '臺北通信隊 - 綠坵山發射臺', '蘇澳通信隊', '蘇澳通信隊 - 修護區隊', '蘇澳通信隊 - 作業區隊', '蘇澳通信隊 - 花蓮區隊', '馬公通信隊', '馬公通信隊 - 修護區隊', '馬公通信隊 - 作業區隊', '馬公通信隊 - 菜園發射臺'] as $unit)
                        <option value="{{ $unit }}"{{ old('unit') === $unit ? ' selected' : ''}}>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">標題：</span>
                    <input type="text" class="form-control" name="subject" value="{{ old('subject') }}">
                </div>
            </div>
            <div class="form-group text-right">
                <input type="file" name="file" id="file" class="inputfile">
                <label for="file" class="btn btn-default"><i class="glyphicon glyphicon-paperclip"></i> <span>上傳檔案</span></label>
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> 送出資料</button>
            </div>
        </form>
    </div>
@endsection