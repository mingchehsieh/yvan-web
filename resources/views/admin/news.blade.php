@extends('layouts.app')

@section('title', '最新消息')

@section('content')
    <script src="/ckeditor/ckeditor.js" type="text/javascript"><!--mce:2--></script>
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        <li>後臺管理</li>
        <li class="active">最新消息</li>
    </ol>
    <form action="/admin/news" method="POST">
        {{ csrf_field() }}
        <textarea name="body" class="ckeditor">{!! App\Config::find('news')->config !!}</textarea>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary col-xs-12"><span class="glyphicon glyphicon-ok"></span> 送出</button>
            </div>
        </div>
    </form>
@endsection
