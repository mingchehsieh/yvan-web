<!DOCTYPE html>
<html lang="zh-TW" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') - 海軍通信系統指揮部</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/default2.css" rel="stylesheet">
    @yield('additionalStyle')
    <style type="text/css">
        .max-last-td td:not(:last-child) {width: 1px; word-break: keep-all; white-space: nowrap;}
        .max-last-td td:last-child {word-break: break-all;}
        .max-last2-td td:not(:nth-last-child(2)) {width: 1px; word-break: keep-all; white-space: nowrap;}
        .max-last2-td td:nth-last-child(2) {word-break: break-all;}
        .max-last3-td td:not(:nth-last-child(3)) {width: 1px; word-break: keep-all; white-space: nowrap;}
        .max-last3-td td:nth-last-child(3) {word-break: break-all;}
    </style>
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

    <section id="section">
        <div>
            @yield('content')
        </div>
    </section>

    <footer id="footer">
    </footer>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    @yield('additionalScript')
    <script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>
    <script>
        var inputs = document.querySelectorAll( '.inputfile' );
        Array.prototype.forEach.call( inputs, function( input )
        {
            var label    = input.nextElementSibling,
                labelVal = label.innerHTML;

            input.addEventListener( 'change', function( e )
            {
                var fileName = e.target.value.split( '\\' ).pop();

                if( fileName )
                    label.querySelector( 'span' ).innerHTML = fileName;
                else
                    label.innerHTML = labelVal;
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.delete-form').submit(function(event) {
                return confirm("確定刪除該筆資料嗎？");
            });
        });
    </script>
</body>
</html>