<?php

namespace App\Http\Controllers;

use Auth;
use Storage;
use Validator;
use App\Guide;
use App\EffectOfGuide;
use App\Fileentry;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GuideController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin(Request $request)
    {
        $q = $request->input('q');
        if ($q !== null) {
            $guides = Guide::where('subject', 'LIKE', '%' . $q . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $guides = Guide::orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $guides->setPath('guide');
        return view('admin.guide', ['guides' => $guides, 'q' => $q]);
    }
    public function adminMy(Request $request)
    {
        $q = $request->input('q');
        if ($q !== null) {
            $guides = Guide::where('user_id', Auth::id())
                ->where('subject', 'LIKE', '%' . $q . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $guides = Guide::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $guides->setPath('guide_my');
        return view('admin.guide-my', ['guides' => $guides, 'q' => $q]);
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
        $v = Validator::make([
            'unit' => $request->input('unit'),
            'term' => $request->input('term'),
            'subject' => $request->input('subject'),
            'file' => $request->file('file')
        ], [
            'unit' => 'required',
            'term' => 'date',
            'subject' => 'required|max:255',
            'file' => 'required',
        ])->setAttributeNames([
            'unit' => '受宣教單位',
            'term' => '宣導期限',
            'subject' => '主旨',
            'file' => '上傳檔案'
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }
        $file = $request->file('file');
        if ($file !== null) {
            $extension = $file->getClientOriginalExtension();
            $request->file('file')
                ->move(storage_path('app/uploads/'), $file->getFilename().'.'.$extension);
            $entry = new Fileentry();
            $entry->user_id = Auth::id();
            $entry->mime = $file->getClientMimeType();
            $entry->original_filename = $file->getClientOriginalName();
            $entry->filename = $file->getFilename().'.'.$extension;
            $entry->save();
        }
        $guide = new Guide();
        $guide->user_id = Auth::id();
        $guide->unit = $request->input('unit');
        $guide->term = $request->input('term') === '' ? date('Y-m-d', strtotime('+1 month')) : $request->input('term');
        $guide->subject = $request->input('subject');
        if ($file !== null) $guide->fileentry_id = $entry->id;
        $guide->save();
        if (in_array($request->input('unit'), ['通信系統指揮部', explode(" - ", Auth::user()->unit)[0], Auth::user()->unit])) {
            $effect = EffectOfGuide::firstOrCreate(['user_id' => Auth::id(), 'guide_id' => $guide->id]);
            $effect->date = date('Y-m-d H:i:s');
            $effect->save();
        }
        return redirect('/admin/guide');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $guide = Guide::find($id);
        if (in_array($guide->unit, ['通信系統指揮部', explode(" - ", Auth::user()->unit)[0], Auth::user()->unit])) {
            $effect = EffectOfGuide::firstOrCreate(['user_id' => Auth::id(), 'guide_id' => $guide->id]);
            $effect->date = date('Y-m-d H:i:s');
            $effect->save();
        }
        $entry = Fileentry::find($guide->fileentry_id);
        $file = Storage::get('uploads/' . $entry->filename);
        return response($file, 200)
            ->header('Content-Type', $entry->mime)
            ->header('Content-Disposition', 'filename="' . $entry->original_filename . '"');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $guide = Guide::find($id);
        return view('admin/guide-edit', ['guide' => $guide]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $v = Validator::make([
            'unit' => $request->input('unit'),
            'term' => $request->input('term'),
            'subject' => $request->input('subject')
        ], [
            'unit' => 'required',
            'term' => 'date',
            'subject' => 'required|max:255'
        ])->setAttributeNames([
            'unit' => '受宣教單位',
            'term' => '宣導期限',
            'subject' => '主旨'
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }
        $guide = Guide::find($id);
        $file = $request->file('file');
        if ($file !== null) {
            if ($guide->fileentry_id !== 0) {
                if (Storage::exists('uploads/' . $guide->fileentry->filename))
                    Storage::delete('uploads/' . $guide->fileentry->filename);
                $guide->fileentry->delete();
            }
            $extension = $file->getClientOriginalExtension();
            $request->file('file')
                ->move(storage_path('app/uploads/'), $file->getFilename().'.'.$extension);
            $entry = new Fileentry();
            $entry->user_id = Auth::id();
            $entry->mime = $file->getClientMimeType();
            $entry->original_filename = $file->getClientOriginalName();
            $entry->filename = $file->getFilename().'.'.$extension;
            $entry->save();
        }
        $guide->edit_user_id = Auth::id();
        $guide->unit = $request->input('unit');
        $guide->term = $request->input('term') === '' ? date('Y-m-d', strtotime('+1 month')) : $request->input('term');
        $guide->subject = $request->input('subject');
        if ($file !== null) $guide->fileentry_id = $entry->id;
        $guide->save();
        $guide->effects()->delete();
        return redirect('/admin/guide');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $guide = Guide::find($id);
        if ($guide->fileentry_id !== 0) {
            if (Storage::exists('uploads/' . $guide->fileentry->filename))
                Storage::delete('uploads/' . $guide->fileentry->filename);
            $guide->fileentry->delete();
        }
        $guide->effects()->delete();
        $guide->delete();
        return back();
    }
}
