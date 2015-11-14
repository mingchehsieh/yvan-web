<ul>
    @if (Auth::check())
        <li><strong>本部專用系統</strong>
            <ul>
                <li><a href="#">通況管理系統</a></li>
                <li><a href="/bulletin">網頁公告</a></li>
                <li><a href="/line">線傳系統</a></li>
                <li><a href="/knowledge">知識庫</a></li>
                <li><a href="/guide">宣教專區</a></li>
            </ul>
        </li>
        <hr>
        <li><strong>後臺管理</strong>
            <ul>
                <li><a href="/admin/news">最新消息</a></li>
                <li><a href="/admin/life">生活花絮</a></li>
                <li><a href="/admin/rota">當值人員</a></li>
                <li><a href="/admin/bulletin">網頁公告</a></li>
                <li><a href="/admin/line">線傳系統</a></li>
                <li><a href="/admin/knowledge">知識庫</a></li>
                <li><a href="/admin/guide">宣教專區</a></li>
                <li><a href="/admin/user">人員管理</a></li>
            </ul>
        </li>
        <hr>
    @endif
    <li><strong>常用連結</strong>
        <ul>
            <li><a href="#">國軍電子郵件</a></li>
            <li><a href="#">電話查詢</a></li>
        </ul>
    </li>
</ul>