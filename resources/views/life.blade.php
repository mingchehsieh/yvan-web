@extends('layouts.app')

@section('title', '生活花絮')

@section('content')
    @include('common.errors')
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        @if (isset($unit))
            <li><a href="/life">生活花絮</a></li>
            <li class="active">{{ $unit }}</li>
        @else
            <li class="active">生活花絮</li>
        @endif
    </ol>
    @if (isset($unit))
        @foreach ($lives as $life)
            <div class="col-xs-4">
                <a href="/images/uploads/{{ $life->fileentry->filename }}" target="_blank">
                    <img src="/images/uploads/{{ $life->fileentry->filename }}" alt="{{ $life->subject }}" class="img-thumbnail">
                    <p class="text-center">{{ $life->subject }}</p>
                </a>
            </div>
        @endforeach
        <div class="row"><div class="col-xs-12 text-center">{!! $lives->render() !!}</div></div>
    @else
        <div class="row">
            <div class="col-xs-1"></div>
            @if (($life = App\Life::whereIn('unit', ['通信系統指揮部', '部本部', '通資綜合科', '通資安全科', '電子系統科'])->orderBy('created_at', 'desc')->first()) !== null)
                <div class="col-xs-2">
                    <a href="/life/通指部">
                        <img src="/images/uploads/{{ $life->fileentry->filename }}" alt="通指部" class="img-thumbnail">
                        <p class="text-center">通指部</p>
                    </a>
                </div>
            @endif
            @foreach (['左營通信隊', '臺北通信隊', '蘇澳通信隊', '馬公通信隊'] as $ounit)
                @if (($life = App\Life::where('unit', 'like', '%' . $ounit . '%')->orderBy('created_at', 'desc')->first()) !== null)
                    <div class="col-xs-2">
                        <a href="/life/{{ $ounit }}">
                            <img src="/images/uploads/{{ $life->fileentry->filename }}" alt="{{ $ounit }}" class="img-thumbnail">
                            <p class="text-center">{{ $ounit }}</p>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
@endsection