@extends('layouts.app')

@section('title', '生活花絮')

@section('content')
    @include('common.errors')
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        <li>後臺管理</li>
        <li><a href="/admin/life">生活花絮</a></li>
        <li class="active">修改</li>
    </ol>
    <table class="table table-striped max-last2-td">
        <thead>
            <tr>
                <th>上傳日期</th>
                <th>單位</th>
                <th>標題</th>
                <th>圖片</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $life->created_at->toDateString() }}</td>
                <td>{{ $life->unit }}</td>
                <td>{{ $life->subject }}</td>
                <td>
                    <a href="/images/uploads/{{ $life->fileentry->filename }}" target="_blank">
                        <img src="/images/uploads/{{ $life->fileentry->filename }}" width="100">
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="sub-content">
        <div class="up-arrow"></div>
        <form action="/admin/life/{{ $life->id }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <p>修改：</p>
            <div class="form-group">
                <select class="form-control" name="unit">
                    <option value="">單位名稱</option>
                    @foreach (['通信系統指揮部', '部本部', '通資綜合科', '通資安全科', '電子系統科', '左營通信隊', '左營通信隊 - 修護區隊', '左營通信隊 - 作業區隊', '左營通信隊 - 龍泉發射台', '臺北通信隊', '臺北通信隊 - 修護區隊', '臺北通信隊 - 作業區隊', '臺北通信隊 - AOC 區隊', '臺北通信隊 - 基隆區隊', '臺北通信隊 - 淡水發射臺', '臺北通信隊 - 八里發射臺', '臺北通信隊 - 三芝天線場', '臺北通信隊 - 綠坵山發射臺', '蘇澳通信隊', '蘇澳通信隊 - 修護區隊', '蘇澳通信隊 - 作業區隊', '蘇澳通信隊 - 花蓮區隊', '馬公通信隊', '馬公通信隊 - 修護區隊', '馬公通信隊 - 作業區隊', '馬公通信隊 - 菜園發射臺'] as $unit)
                        <option value="{{ $unit }}"{{ $life->unit === $unit ? ' selected' : ''}}>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">標題：</span>
                    <input type="text" class="form-control" name="subject" value="{{ $life->subject }}">
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