<?php

namespace App\Http\Controllers;

use Auth;
use Storage;
use Validator;
use App\Life;
use App\Fileentry;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LifeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = $request->input('q');
        if ($q !== null) {
            $lives = Life::join('users', 'lives.user_id', '=', 'users.id')
                ->select('lives.*', 'users.unit', 'users.name')
                ->where('subject', 'LIKE', '%' . $q . '%')
                ->orwhere('unit', 'LIKE', '%' . $q . '%')
                ->orderBy('lives.created_at', 'desc')
                ->paginate(10);
        } else {
            $lives = Life::orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $lives->setPath('life');
        return view('life', ['lives' => $lives, 'q' => $q]);
    }

    public function admin(Request $request)
    {
        $q = $request->input('q');
        if ($q !== null) {
            $lives = Life::where('subject', 'LIKE', '%' . $q . '%')
                ->orwhere('unit', 'LIKE', '%' . $q . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $lives = Life::orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $lives->setPath('life');
        return view('admin/life', ['lives' => $lives, 'q' => $q]);
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
            'subject' => $request->input('subject'),
            'file' => $request->file('file')
        ], [
            'unit' => 'required',
            'subject' => 'required|max:255',
            'file' => 'required',
        ])->setAttributeNames([
            'unit' => '單位名稱',
            'subject' => '檔案描述',
            'file' => '上傳檔案'
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }
        $file = $request->file('file');
        if ($file !== null) {
            $extension = $file->getClientOriginalExtension();
            $request->file('file')
                ->move(public_path('images/uploads/'), $file->getFilename().'.'.$extension);
            $entry = new Fileentry();
            $entry->user_id = Auth::id();
            $entry->mime = $file->getClientMimeType();
            $entry->original_filename = $file->getClientOriginalName();
            $entry->filename = $file->getFilename().'.'.$extension;
            $entry->save();
        }
        $life = new Life();
        $life->user_id = Auth::id();
        $life->unit = $request->input('unit');
        $life->subject = $request->input('subject');
        if ($file !== null) $life->fileentry_id = $entry->id;
        $life->save();

        return redirect('admin/life');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $life = Life::find($id);
        return view('admin/life-edit', ['life' => $life]);
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
            'subject' => $request->input('subject'),
        ], [
            'unit' => 'required',
            'subject' => 'required|max:255',
        ])->setAttributeNames([
            'unit' => '單位名稱',
            'subject' => '檔案描述',
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }
        $life = Life::find($id);
        $file = $request->file('file');
        if ($file !== null) {
            if ($life->fileentry_id !== 0) {
                unlink(public_path('images/uploads/') . $life->fileentry->filename);
                $life->fileentry->delete();
            }
            $extension = $file->getClientOriginalExtension();
            $request->file('file')
                ->move(public_path('images/uploads/'), $file->getFilename().'.'.$extension);
            $entry = new Fileentry();
            $entry->user_id = Auth::id();
            $entry->mime = $file->getClientMimeType();
            $entry->original_filename = $file->getClientOriginalName();
            $entry->filename = $file->getFilename().'.'.$extension;
            $entry->save();
        }
        $life->edit_user_id = Auth::id();
        $life->unit = $request->input('unit');
        $life->subject = $request->input('subject');
        if ($file !== null) $life->fileentry_id = $entry->id;
        $life->save();

        return redirect('admin/life');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $life = Life::find($id);
        if ($life->fileentry_id !== 0) {
            unlink(public_path('images/uploads/') . $life->fileentry->filename);
            $life->fileentry->delete();
        }
        $life->delete();
        return redirect('admin/life');
    }
}
