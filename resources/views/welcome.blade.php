<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>首頁 - 海軍通信系統指揮部</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/default.css">
</head>
<body>
    <header id="header">
        <h1><img src="/images/logo.gif" width="80"> 海軍通信系統指揮部</h1>
    </header>

    <nav id="nav">
        <ul>
            <li><a href="/">首頁</a></li><li><a href="#">本部沿革</a></li><li><a href="#">本部各隊臺</a></li><li><a href="#">我們的大家長</a></li><li><a href="/life">生活花絮</a></li><li><a href="#">網網相連</a></li>
        </ul>
    </nav>

    <aside id="aside1">
        <div id="user-login">
            @if (Auth::guest())
                <form action="/auth/login" method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">帳號</span>
                        <input type="text" name="uid" class="form-control" value="{{ old('uid') }}">
                    </div>

                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">密碼</span>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="/auth/register" class="btn btn-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 註冊</a>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> 登入</button>
                        </div>
                    </div>
                </form>
            @else
                <p>
                    {{ Auth::user()->name }}
                    {{ in_array(Auth::user()->rank, ['上兵', '一兵', '二兵']) ? '' : '長官' }}您好<br>
                    歡迎您的登入
                </p>
                <a href="/auth/logout" class="btn btn-default"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> 登出</a>
            @endif
        </div>
        <div id="useful-links">
            @include('common.sub-menu')
        </div>
    </aside>

    <aside id="aside2">
        <div id="daily-life" class="has-title"><div>生活花絮</div>
        <div>
            @if (($life = App\Life::orderByRaw('rand()')->first()) !== null)
                <a href="/images/uploads/{{ $life->fileentry->filename }}" target="_blank">
                    <img src="/images/uploads/{{ $life->fileentry->filename }}" alt="{{ $life->subject }}" width="100%" class="img-thumbnail">
                    <p class="text-center">{{ $life->subject }}</p>
                </a>
            @else
                無資料
            @endif
        </div>
        </div>
        <div>
            @foreach ($rotas as $rota)
                {{ $rota->title }}：{{ $rota->user or '未設定' }}<br>
            @endforeach
            <a href="/rota">當月輪值表</a>
        </div>
        <div id="schedule" class="has-title">
            <div>行事曆</div>
            <div>
                <ul>
                    <li>2015/8/12（三）
                        <ul>
                            <li>行事曆項目 1</li>
                        </ul>
                    </li>
                    <li>2015/8/13（四）
                        <ul>
                            <li>行事曆項目 2</li>
                            <li>行事曆項目 3</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div>
            今日瀏覽人數：1<br>
            昨日瀏覽人數：2<br>
            本月瀏覽人數：3<br>
            本站瀏覽人數：4
        </div>
    </aside>

    <section id="section">
        @if(App\Config::where('item', 'news')->first() !== null)
            <div>
                {!! App\Config::where('item', 'news')->first()->config !!}
            </div>
        @endif
        @if (Auth::check() && ($guides = App\Guide::whereIn('unit', ['通信系統指揮部', explode(" - ", Auth::user()->unit)[0], Auth::user()->unit])->where('term', '>=', date('Y-m-d'))->get())->count() !== 0)
            <div>
                <table class="table table-striped max-last2-td">
                    <thead>
                        <tr>
                            <th>日期</th>
                            <th>宣教</th>
                            <th>承辦單位</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($guides as $guide)
                                <tr>
                                    <td>{{ $guide->created_at->toDateString() }}</td>
                                    <td>
                                        <a href="/admin/guide/{{ $guide->id }}" target="_blank"{{ $guide->effects()->where('user_id', Auth::id())->count() !== 0 ? '' : ' class=text-warning' }}>
                                            {{ $guide->subject }}
                                        </a>
                                    </td>
                                    <td>{{ $guide->user->unit }}</td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <footer id="footer">
    </footer>
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
</body>
</html>