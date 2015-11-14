@extends('layouts.app')

@section('title', '宣導專區')

@section('additionalStyle')
    <link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection

@section('additionalScript')
    <script src="/js/moment.min.js"></script>
    <script src="/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.datepicker').datetimepicker({format:'YYYY-MM-DD',dayViewHeaderFormat:'YYYY MMMM'});
        });
    </script>
@endsection

@section('content')
    @include('common.errors')
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        <li>後臺管理</li>
        <li><a href="/admin/guide">宣導專區</a></li>
        <li class="active">修改</li>
    </ol>
    <table class="table table-striped max-last2-td">
        <thead>
            <tr>
                <th>上傳日期</th>
                <th>受宣教單位</th>
                <th>主旨</th>
                <th>宣導期限</th>
            </tr>
        </thead>
      <tbody>
            <tr>
                <td>{{ $guide->created_at->toDateString() }}</td>
                <td>{{ $guide->unit }}</td>
                <td>
                    <a href="/fileentry/{{ $guide->fileentry->filename }}">
                        {{ $guide->subject }}
                    </a>
                </td>
                <td>{{ $guide->term }}</td>
            </tr>
        </tbody>
    </table>
    <div class="sub-content">
        <div class="up-arrow"></div>
        <form action="/admin/guide/{{ $guide->id }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            {{ csrf_field() }}
            <p>修改：</p>
            <div class="form-group">
                <label for="unit" class="col-xs-3 control-label">受宣教單位：</label>
                <div class="col-xs-9">
                    <select name="unit" id="unit" class="form-control">
                        @foreach (['通信系統指揮部', '部本部', '通資綜合科', '通資安全科', '電子系統科', '左營通信隊', '左營通信隊 - 修護區隊', '左營通信隊 - 作業區隊', '左營通信隊 - 龍泉發射台', '臺北通信隊', '臺北通信隊 - 修護區隊', '臺北通信隊 - 作業區隊', '臺北通信隊 - AOC 區隊', '臺北通信隊 - 基隆區隊', '臺北通信隊 - 淡水發射臺', '臺北通信隊 - 八里發射臺', '臺北通信隊 - 三芝天線場', '臺北通信隊 - 綠坵山發射臺', '蘇澳通信隊', '蘇澳通信隊 - 修護區隊', '蘇澳通信隊 - 作業區隊', '蘇澳通信隊 - 花蓮區隊', '馬公通信隊', '馬公通信隊 - 修護區隊', '馬公通信隊 - 作業區隊', '馬公通信隊 - 菜園發射臺'] as $unit)
                            <option value="{{ $unit }}"{{ $guide->unit === $unit ? ' selected' : ''}}>{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="subject" class="col-xs-3 control-label">主旨：</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" name="subject" id="subject" value="{{ $guide->subject }}">
                </div>
            </div>
            <div class="form-group">
                <label for="term" class="col-xs-3 control-label">宣導期限：</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control datepicker" name="term" id="term" value="{{ $guide->term }}" placeholder="{{ $guide->term }}">
                </div>
            </div>
            <div class="form-group text-right">
                <div class="col-xs-12">
                    <input type="file" name="file" id="file" class="inputfile">
                    <label for="file" class="btn btn-default"><i class="glyphicon glyphicon-paperclip"></i> <span>選擇檔案</span></label>
                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> 送出資料</button>
                </div>
            </div>
        </form>
    </div>
@endsection
