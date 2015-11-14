@extends('layouts.app')

@section('title', '註冊')

@section('content')
    @include('common.errors')

    <form action="/auth/register" method="POST" class="form-horizontal">
        {{ csrf_field() }}
        <h2 class="text-center" style="margin-top:10px">註冊</h2>
        <hr>

        <div class="form-group">
            <label class="control-label col-xs-4" for="uid">帳號（身分證字號）：</label>
            <div class="col-xs-6">
                <input type="text" name="uid" class="form-control" value="{{ old('uid') }}">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4" for="name">中文姓名：</label>
            <div class="col-xs-6">
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4" for="password">登入密碼：</label>
            <div class="col-xs-6">
                <input type="password" name="password" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4" for="password_confirmation">確認密碼：</label>
            <div class="col-xs-6">
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4" for="unit">所屬單位：</label>
            <div class="col-xs-6">
                <select class="form-control" name="unit">
                    <option value=""></option>
                    @foreach(['通信系統指揮部', '部本部', '通資綜合科', '通資安全科', '電子系統科', '左營通信隊', '左營通信隊 - 修護區隊', '左營通信隊 - 作業區隊', '左營通信隊 - 龍泉發射台', '臺北通信隊', '臺北通信隊 - 修護區隊', '臺北通信隊 - 作業區隊', '臺北通信隊 - AOC 區隊', '臺北通信隊 - 基隆區隊', '臺北通信隊 - 淡水發射臺', '臺北通信隊 - 八里發射臺', '臺北通信隊 - 三芝天線場', '臺北通信隊 - 綠坵山發射臺', '蘇澳通信隊', '蘇澳通信隊 - 修護區隊', '蘇澳通信隊 - 作業區隊', '蘇澳通信隊 - 花蓮區隊', '馬公通信隊', '馬公通信隊 - 修護區隊', '馬公通信隊 - 作業區隊', '馬公通信隊 - 菜園發射臺'] as $unit)
                    <option value="{{ $unit }}"{{ old('unit') == $unit ? ' selected' : '' }}>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4" for="rank">階級：</label>
            <div class="col-xs-6">
                <select class="form-control" name="rank">
                    <option value=""></option>
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
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4" for="title">職稱：</label>
            <div class="col-xs-6">
                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4">役別：</label>
            <div class="col-xs-6">
                <label class="radio-inline">
                    <input type="radio" name="serviceType" value="志願役"{{ old('serviceType') == '志願役' ? ' checked' : '' }}> 志願役
                </label>
                <label class="radio-inline">
                    <input type="radio" name="serviceType" value="義務役"{{ old('serviceType') == '義務役' ? ' checked' : '' }}> 義務役
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4"></label>
            <div class="col-xs-6">
                <button type="submit" class="btn btn-primary col-xs-12">確認送出</button>
            </div>
        </div>
    </form>
@endsection
