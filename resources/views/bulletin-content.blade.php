@extends('layouts.app')

@section('title', '網頁公告')

@section('content')
    @include('common.errors')
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        <li class="active"><a href="/bulletin">網頁公告</a></li>
    </ol>
    <p>
        <small>
            <span class="glyphicon glyphicon-time"></span>
            {{ $bulletin->created_at->toDateString() }}／
            <span class="glyphicon glyphicon-user"></span>
            {{ $bulletin->user->unit }} {{ $bulletin->user->name }}／
            <span class="glyphicon glyphicon-comment"></span>
            {{ $comments->count() }} 個回覆
        </small>
    </p>
    <h2>主旨：{{ $bulletin->subject }}</h2>
    <p class="text-indent-2">
        內容摘要：<br>
        {!! nl2br(e($bulletin->body)) !!}
    </p>
    @if ($bulletin->fileentry_id !== 0)
        <p>
            附件：
            <a href="/fileentry/{{ $bulletin->fileentry->filename }}">
                <span class="glyphicon glyphicon-paperclip"></span>
                {{ $bulletin->fileentry->original_filename }}
            </a>
        </p>
    @endif
    <div class="sub-content">
        <div class="up-arrow"></div>
        <form action="/bulletin/{{ $bulletin->id }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <p>回覆：</p>
            <div class="form-group">
                <textarea class="form-control" name="comment" rows="5">{{old('comment')}}</textarea>
            </div>
            <div class="form-group text-right">
                <input type="file" name="file" id="file" class="inputfile">
                <label for="file" class="btn btn-default"><i class="glyphicon glyphicon-paperclip"></i> <span>上傳檔案</span></label>
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-comment"></span> 送出回覆</button>
            </div>
        </form>
        @foreach($comments as $comment)
            <hr>
            <p>
                <small>
                    <span class="glyphicon glyphicon-time"></span>
                    {{ $comment->created_at->toDateString() }}／
                    <span class="glyphicon glyphicon-user"></span>
                    {{ $comment->user->unit }} {{ $comment->user->name }}
                </small>
            </p>
            <p>{!! nl2br(e($comment->body)) !!}</p>
            @if ($comment->fileentry_id !== 0)
                <p>
                    <a href="/fileentry/{{ $comment->fileentry->filename }}">
                        <span class="glyphicon glyphicon-paperclip"></span>
                        {{ $comment->fileentry->original_filename }}
                    </a>
                </p>
            @endif
            @if ($comment->user_id === Auth::id())
                <form action="/bulletin/{{ $comment->id }}" method="POST" class="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" id="delete-comment-{{ $comment->id }}" class="btn btn-danger">
                        <span class="glyphicon glyphicon-trash"></span> 刪除
                    </button>
                </form>
            @endif
        @endforeach
    </div>
@endsection
