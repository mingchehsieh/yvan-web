@extends('layouts.app')

@section('title', '線傳系統')

@section('content')
    @include('common.errors')
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        <li class="active">線傳系統</li>
    </ol>
    <form action="/line" method="GET">
        <div class="row">
            <div class="col-xs-10">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">搜尋：</span>
                    <input type="text" class="form-control" name="q" value="{{ $q }}">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">送出</button>
                        <a href="/line" class="btn btn-default">清除</a>
                    </span>
                </div>
            </div>
            <div class="col-xs-2">
                <a href="#add" class="btn btn-default btn-sm btn-block">上傳資料</a>
            </div>
        </div>
    </form>
    @if ($lines->count() !== 0)
        <table class="table table-striped max-last2-td">
            <thead>
                <tr>
                    <th>上傳日期</th>
                    <th>單位</th>
                    <th>姓名</th>
                    <th>檔案描述</th>
                    <th></th>
                </tr>
            </thead>
          <tbody>
                @foreach ($lines as $line)
                    <tr>
                        <td>{{ $line->created_at->toDateString() }}</td>
                        <td>{{ $line->user->unit }}</td>
                        <td>{{ $line->user->name }}</td>
                        <td>
                            <a href="/fileentry/{{ $line->fileentry->filename }}">
                                {{ $line->subject }}
                            </a>
                        </td>
                        <td>
                            @if ($line->user_id === Auth::id())
                                <form action="/line/{{ $line->id }}" method="POST" class="delete-form">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" id="delete-line-{{ $line->id }}" class="btn btn-danger btn-xs">
                                        <span class="glyphicon glyphicon-trash"></span> 刪除
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">{!! $lines->appends(['q' => $q])->render() !!}</div>
    @else
        <br>
        <p class="text-center">無資料</p>
    @endif
    <div class="sub-content">
        <form action="/line" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h4 class="ribbon" id="add">上傳資料：</h4>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">檔案描述：</span>
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
