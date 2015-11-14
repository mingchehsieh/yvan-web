@extends('layouts.app')

@section('title', '網頁公告')

@section('content')
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        <li class="active">網頁公告</li>
    </ol>
    <form action="/bulletin" method="GET">
        <div class="row">
            <div class="col-xs-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">搜尋：</span>
                    <input type="text" class="form-control" name="q" value="{{ $q }}">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">送出</button>
                        <a href="/bulletin" class="btn btn-default">清除</a>
                    </span>
                </div>
            </div>
        </div>
    </form>
    @if ($bulletins->count() !== 0)
        <table class="table table-striped max-last-td">
            <thead>
                <tr>
                    <th>上傳日期</th>
                    <th>上傳單位</th>
                    <th>承辦人</th>
                    <th>主旨</th>
                </tr>
            </thead>
          <tbody>
                @foreach ($bulletins as $bulletin)
                    <tr>
                        <td>{{ $bulletin->created_at->toDateString() }}</td>
                        <td>{{ $bulletin->user->unit }}</td>
                        <td>{{ $bulletin->user->name }}</td>
                        <td>
                            <a href="/bulletin/{{ $bulletin->id }}"{{ $bulletin->created_at->diffInDays(Carbon\Carbon::now()) > 7 ? ' class=text-warning' : ''}}>
                                @if ($bulletin->fileentry_id !== 0)
                                    <span class="glyphicon glyphicon-paperclip"></span>
                                @endif
                                {{ $bulletin->subject }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">{!! $bulletins->appends(['q' => $q])->render() !!}</div>
    @else
        <br>
        <p class="text-center">無資料</p>
    @endif
@endsection
