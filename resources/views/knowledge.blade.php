@extends('layouts.app')

@section('title', '知識庫')

@section('content')
    @include('common.errors')
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        <li class="active">知識庫</li>
    </ol>
    <form action="/knowledge" method="GET">
        <div class="row">
            <div class="col-xs-10">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">搜尋：</span>
                    <input type="text" class="form-control" name="q" value="{{ $q }}">
                    @foreach (['通信類', '資訊類', '其他類'] as $type)
                        <span class="input-group-addon">
                            @if ($t !== null)
                                <input type="checkbox" name="t[]" value="{{ $type }}"{{ in_array($type, $t) ? ' checked' : ''}}>
                            @else
                                <input type="checkbox" name="t[]" value="{{ $type }}">
                            @endif
                            {{ $type }}
                        </span>
                    @endforeach
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">送出</button>
                        <a href="/knowledge" class="btn btn-default">清除</a>
                    </span>
                </div>
            </div>
            <div class="col-xs-2">
                <a href="#add" class="btn btn-default btn-sm btn-block">上傳資料</a>
            </div>
        </div>
    </form>
    <br>
    @if ($knowledges->count() !== 0)
        <table class="table table-striped max-last2-td">
            <thead>
                <tr>
                    <th>上傳日期</th>
                    <th>單位</th>
                    <th>姓名</th>
                    <th>類別</th>
                    <th>檔案描述</th>
                    <th></th>
                </tr>
            </thead>
          <tbody>
                @foreach ($knowledges as $knowledge)
                    <tr>
                        <td>{{ $knowledge->created_at->toDateString() }}</td>
                        <td>{{ $knowledge->user->unit }}</td>
                        <td>{{ $knowledge->user->name }}</td>
                        <td>{{ $knowledge->type }}</td>
                        <td>
                            <a href="/fileentry/{{ $knowledge->fileentry->filename }}">
                                {{ $knowledge->subject }}
                            </a>
                        </td>
                        <td>
                            @if ($knowledge->user_id === Auth::id())
                                <form action="/knowledge/{{ $knowledge->id }}" method="POST" class="delete-form">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" id="delete-knowledge-{{ $knowledge->id }}" class="btn btn-danger btn-xs">
                                        <span class="glyphicon glyphicon-trash"></span> 刪除
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">{!! $knowledges->appends(['q' => $q])->render() !!}</div>
    @else
        <br>
        <p class="text-center">無資料</p>
    @endif
    <div class="sub-content">
        <form action="/knowledge" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h4 class="ribbon" id="add">上傳資料：</h4>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">檔案描述：</span>
                    <input type="text" class="form-control" name="subject" value="{{ old('subject') }}">
                    <span class="input-group-addon">
                        <label class="radio-inline"><input type="radio" name="type" value="通信類"> 通信類</label>
                    </span>
                    <span class="input-group-addon">
                        <label class="radio-inline"><input type="radio" name="type" value="資訊類"> 資訊類</label>
                    </span>
                    <span class="input-group-addon">
                        <label class="radio-inline"><input type="radio" name="type" value="其他類"> 其他類</label>
                    </span>
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
