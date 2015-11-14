<?php

namespace App\Http\Controllers;

use Auth;
use Storage;
use Validator;
use App\Line;
use App\Fileentry;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        if ($q !== null) {
            $lines = Line::join('users', 'lines.user_id', '=', 'users.id')
                ->select('lines.*', 'users.unit', 'users.name')
                ->where('subject', 'LIKE', '%' . $q . '%')
                ->orwhere('unit', 'LIKE', '%' . $q . '%')
                ->orwhere('name', 'LIKE', '%' . $q . '%')
                ->orderBy('lines.created_at', 'desc')
                ->paginate(10);
        } else {
            $lines = Line::orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $lines->setPath('line');
        return view('line', ['lines' => $lines, 'q' => $q]);
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
            'subject' => $request->input('subject'),
            'file' => $request->file('file')
        ], [
            'subject' => 'required|max:255',
            'file' => 'required',
        ])->setAttributeNames([
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
                ->move(storage_path('app/uploads/'), $file->getFilename().'.'.$extension);
            $entry = new Fileentry();
            $entry->user_id = Auth::id();
            $entry->mime = $file->getClientMimeType();
            $entry->original_filename = $file->getClientOriginalName();
            $entry->filename = $file->getFilename().'.'.$extension;
            $entry->save();
        }
        $line = new Line();
        $line->user_id = Auth::id();
        $line->subject = $request->input('subject');
        if ($file !== null) $line->fileentry_id = $entry->id;
        $line->save();

        return redirect('line');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $line = Line::find($id);
        if ($line->fileentry_id !== 0) {
            if (Storage::exists('uploads/' . $line->fileentry->filename))
                Storage::delete('uploads/' . $line->fileentry->filename);
            $line->fileentry->delete();
        }
        $line->delete();
        return redirect('line');
    }
}
