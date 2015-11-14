<?php

namespace App\Http\Controllers;

use Auth;
use Storage;
use Validator;
use App\Bulletin;
use App\Fileentry;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BulletinController extends Controller
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
            $bulletins = Bulletin::join('users', 'bulletins.user_id', '=', 'users.id')
                ->select('bulletins.*', 'users.unit', 'users.name')
                ->where('subject', '<>', '')
                ->Where(function ($query) use ($q) {
                $query->where('subject', 'LIKE', '%'.$q.'%')
                    ->orwhere('unit', 'LIKE', '%'.$q.'%')
                    ->orwhere('name', 'LIKE', '%'.$q.'%');
                })
                ->orderBy('bulletins.created_at', 'desc')
                ->paginate(10);
        } else {
            $bulletins = Bulletin::where('subject', '<>', '')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $bulletins->setPath('bulletin');
        return view('bulletin', ['bulletins' => $bulletins, 'q' => $q]);
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
    public function store(Request $request, $id)
    {
        $v = Validator::make([
            'comment' => $request->input('comment')
        ], [
            'comment' => 'required|max:255'
        ])->setAttributeNames([
            'comment' => '回覆內容'
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
        $bulletin = new Bulletin();
        $bulletin->parent_id = $id;
        $bulletin->user_id = Auth::id();
        $bulletin->body = $request->input('comment');
        if ($file !== null) $bulletin->fileentry_id = $entry->id;
        $bulletin->save();

        return redirect('bulletin/'.$id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bulletin = Bulletin::join('users', 'bulletins.user_id', '=', 'users.id')
            ->select('bulletins.*', 'users.unit', 'users.name')
            ->find($id);
        $comments = Bulletin::where('parent_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('bulletin-content', ['bulletin' => $bulletin, 'comments' => $comments]);
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
        $bulletin = Bulletin::find($id);
        if ($bulletin->parent_id === 0) return abort(500);
        if ($bulletin->fileentry_id !== 0) {
            if (Storage::exists('uploads/' . $bulletin->fileentry->filename))
                Storage::delete('uploads/' . $bulletin->fileentry->filename);
            $bulletin->fileentry->delete();
        }
        $redirect = 'bulletin/' . $bulletin->parent_id;
        $bulletin->delete();
        return redirect($redirect);
    }
}
