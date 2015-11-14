<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\User;
use App\Rota;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $date = date('Y') . '/' . date('n');
        $rotas = Rota::where('date', $date)->get();
        $title1 = $rotas->where('title', '駐部官')->first();
        $title2 = $rotas->where('title', '理事官')->first();
        $title3 = $rotas->where('title', '值日官')->first();
        return view('rota', ['year' => date('Y'), 'month' => date('n'), 'title1' => $title1, 'title2' => $title2, 'title3' => $title3]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($year, $month)
    {
        $title1 = Rota::where('date', $year . '/' . $month)->where('title', '駐部官')->first();
        $title2 = Rota::where('date', $year . '/' . $month)->where('title', '理事官')->first();
        $title3 = Rota::where('date', $year . '/' . $month)->where('title', '值日官')->first();
        return view('admin.rota', ['year' => $year, 'month' => $month, 'title1' => $title1, 'title2' => $title2, 'title3' => $title3]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $year, $month)
    {
        $date = $year . '/' . $month;
        $rota1 = Rota::firstOrCreate(['date' => $date, 'title' => '駐部官']);
        $rota2 = Rota::firstOrCreate(['date' => $date, 'title' => '理事官']);
        $rota3 = Rota::firstOrCreate(['date' => $date, 'title' => '值日官']);
        $rota1->date = $date;
        $rota2->date = $date;
        $rota3->date = $date;
        $rota1->title = '駐部官';
        $rota2->title = '理事官';
        $rota3->title = '值日官';
        for ($i = 1; $i <= 31; $i ++) {
            if ($request->input('rota-title1-' . $i) !== null) $rota1->{'d' . $i} = $request->input('rota-title1-' . $i);
            if ($request->input('rota-title2-' . $i) !== null) $rota2->{'d' . $i} = $request->input('rota-title2-' . $i);
            if ($request->input('rota-title3-' . $i) !== null) $rota3->{'d' . $i} = $request->input('rota-title3-' . $i);
        }
        $rota1->save();
        $rota2->save();
        $rota3->save();
        return back()->with('success', '已於 '. date('Y-m-d H:i') . ' 更新當值表。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($year, $month, $id, $title)
    {
        $user = User::find($id);
        $user_titles = explode(",", $user->title);
        foreach ($user_titles as $key => $value) {
            if ($value === $title) {
                unset($user_titles[$key]);
            }
        }
        $user->title = implode(",", $user_titles);
        $user->save();
        return redirect('/admin/rota/' . $year . '/' .$month);
    }
}
