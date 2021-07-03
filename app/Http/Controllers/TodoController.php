<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    use SoftDeletes;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
 
        $todos = Todo::where('user_id',Auth::id())->whereNull('is_completed')->get();
        return response()->json(['errors'=>false,'list'=>$todos]);
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
        $input     = $request->only('list')['list'];
        $rules = [
            'title' => 'string|required',
            'description'    => 'string|nullable'
        ];
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()],422);
        }
       
        $new = new Todo();
        $new->title = $input['title'];
        $new->description = $input['description'];
        $new->user_id = Auth::id();
        $res = $new->save();
        return response()->json(['success' => $res, 'list' => @$new],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = Todo::where('user_id',Auth::id())->where('id',$id)->get();
        return response()->json(['errors'=>false,'list'=>$todo]);
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
        $input     = $request->only('list')['list'];
        $rules = [
            'title' => 'string',
            'description'    => 'string|nullable',
            'is_completed'=> 'integer|nullable',
        ];
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()],422);
        }
        $update = Todo::where('id',$id)->where('user_id',Auth::id())->first();
        if(empty($update)){
            //Better not tell it doesnt exists or already deleted
            return response()->json(['success' => true, 'list' => []],200);
        }
        if(!empty($input['title'])) $update->title = $input['title'];
        if(!is_null($input['description'])) $update->description = $input['description'];
        if(isset($input['is_completed']) && $input['is_completed']) $update->is_completed = 1;
        $res = $update->save();
        return response()->json(['success' => $res, 'list' => @$update],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Todo::where('user_id',Auth::id())->where('id', $id )->first();
        if(empty($id)){
            //Better not tell it doesnt exists or already deleted
            return response()->json(['success' => true, 'list' => []],200);
        }
        $id->delete();
        return response()->json(['success' => true, 'list' => $id]);
    }
}
