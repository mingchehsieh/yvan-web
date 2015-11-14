<?php

namespace App\Http\Controllers;

use Auth;
use Storage;
use Validator;
use App\Knowledge;
use App\Fileentry;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class KnowledgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        $t = $request->input('t');
        if ($q !== null) {
            $knowledges = Knowledge::join('users', 'knowledges.user_id', '=', 'users.id')
                ->select('knowledges.*', 'users.unit', 'users.name')
                ->whereIn('type', $t !== null ? $t : ['通信類', '資訊類', '其他類'])
                ->Where(function ($query) use ($q) {
                    $query->where('subject', 'LIKE', '%' . $q . '%')
                    ->orwhere('unit', 'LIKE', '%' . $q . '%')
                    ->orwhere('name', 'LIKE', '%' . $q . '%');
                })
                ->orderBy('knowledges.created_at', 'desc')
                ->paginate(10);
        } else {
            $knowledges = Knowledge::orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $knowledges->setPath('knowledge');
        return view('knowledge', ['knowledges' => $knowledges, 'q' => $q, 't' => $t]);
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
            'type' => $request->input('type'),
            'subject' => $request->input('subject'),
            'file' => $request->file('file')
        ], [
            'type' => 'required',
            'subject' => 'required|max:255',
            'file' => 'required',
        ])->setAttributeNames([
            'type' => '類型',
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
        $knowledge = new Knowledge();
        $knowledge->user_id = Auth::id();
        $knowledge->type = $request->input('type');
        $knowledge->subject = $request->input('subject');
        if ($file !== null) $knowledge->fileentry_id = $entry->id;
        $knowledge->save();

        return redirect('knowledge');
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
        $knowledge = Knowledge::find($id);
        if ($knowledge->fileentry_id !== 0) {
            if (Storage::exists('uploads/' . $knowledge->fileentry->filename))
                Storage::delete('uploads/' . $knowledge->fileentry->filename);
            $knowledge->fileentry->delete();
        }
        $knowledge->delete();
        return redirect('knowledge');
    }
}
