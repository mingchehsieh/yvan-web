<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    $rotas = App\Rota::select('title', 'd' . date('j') . ' as user')->where('date', date('Y/n'))->get();
    return view('welcome', ['rotas' => $rotas]);
});

Route::get('life/{unit?}', function ($unit = null) {
    if ($unit === '通指部') {
        $lives = App\Life::whereIn('unit', ['通信系統指揮部', '部本部', '通資綜合科', '通資安全科', '電子系統科'])->orderBy('created_at', 'desc')->paginate(9);
    } elseif ($unit !== null) {
        $lives = App\Life::where('unit', 'like', '%' . $unit . '%')->orderBy('created_at', 'desc')->paginate(9);
    } else {
        $lives = [];
    }
    return view('life', ['unit' => $unit, 'lives' => $lives]);
});

Route::get('bulletin', 'BulletinController@index');
Route::get('bulletin/{id}', 'BulletinController@show');
Route::post('bulletin/{id}', 'BulletinController@store');
Route::delete('bulletin/{id}', 'BulletinController@destroy');

Route::get('line', 'LineController@index');
Route::post('line', 'LineController@store');
Route::delete('line/{id}', 'LineController@destroy');

Route::get('knowledge', 'KnowledgeController@index');
Route::post('knowledge', 'KnowledgeController@store');
Route::delete('knowledge/{id}', 'KnowledgeController@destroy');

Route::get('rota', 'RotaController@show');



Route::get('admin/news', function () {
    return view('admin.news');
});
Route::post('admin/news', function () {
    $news = App\Config::find('news');
    $news->config = Request::input('body');
    $news->save();
    return view('welcome');
});

Route::get('admin/life', function () {
    return view('admin.life');
});

//生活花絮
Route::get('admin/life', 'LifeController@admin');
Route::get('admin/life/{id}/edit', 'LifeController@edit');
Route::post('admin/life/{id}', 'LifeController@update');
Route::post('admin/life', 'LifeController@store');
Route::delete('admin/life/{id}', 'LifeController@destroy');

//當值人員
Route::get('admin/rota', function () {
    return redirect('admin/rota/' . date('Y') . '/' . date('n'));
});
Route::get('admin/rota/{year}/{month}', 'RotaController@edit');
Route::post('admin/rota/{year}/{month}', 'RotaController@update');
Route::post('admin/rota/{year}/{month}/add', function ($year, $month) {
    if (Request::get('user') !== null) {
        $user = App\User::find(Request::get('user'));
        if (!in_array(Request::get('title'), explode(",", $user->title))) {
            $user_titles_array = explode(",", $user->title);
            array_push($user_titles_array, Request::get('title'));
            $user->title = implode(",", $user_titles_array);
            $user->save();
        }
        return back();

    } else {
        $users = App\User::all();
        if (Request::get('unit') !== null) $users->where('unit', Request::get('unit'));
        if (Request::get('rank') !== null) $users->where('rank', Request::get('rank'));
        return back()->withInput();
    }
});
Route::delete('admin/rota/{year}/{month}/{id}/{rota}', 'RotaController@destroy');


//宣教專區
Route::get('admin/guide', 'GuideController@admin');
Route::get('admin/guide_my', 'GuideController@adminMy');
Route::post('admin/guide', 'GuideController@store');
Route::get('admin/guide/{id}', 'GuideController@show');
Route::get('admin/guide/{id}/edit', 'GuideController@edit');
Route::post('admin/guide/{id}', 'GuideController@update');
Route::delete('admin/guide/{id}', 'GuideController@destroy');




Route::get('fileentry/{filename}', function ($filename) {
    $entry = App\Fileentry::where('filename', $filename)->firstOrFail();
    $file = Storage::get('uploads/' . $entry->filename);
    return response($file, 200)
        ->header('Content-Type', $entry->mime)
        ->header('Content-Disposition', 'attachment; filename="' . $entry->original_filename . '"');
});


// 認證
Route::get('auth/login', function () {
    return redirect('/');
});
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// 註冊
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
