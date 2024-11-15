<?php

namespace App\Http\Controllers;

use App\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ModelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_model'])->only(['create', 'store']);
        $this->middleware(['permission:edit_model'])->only(['edit', 'update']);
        $this->middleware(['permission:view_model'])->only(['index']);
        $this->middleware(['permission:delete_model'])->only(['delete']);
        $this->middleware(['permission:active_model'])->only(['model.active']);
        $this->middleware(['permission:deactive_model'])->only(['model.deactive']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $models = Model::all();

            return DataTables::of($models)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = '';
                if(auth()->user()->can('edit_model')){
                    $btn .= '<a href="'.route("models.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_model')){
                    $btn .= '&nbsp;<a href="'.route("models.delete", [$row->id]).'" class="delete btn btn-danger btn-sm" onclick="return confirm(`هل انت متاكد انك تريد الحذف ؟`);">حذف</a>';
                }
                if(auth()->user()->can('active_model')){
                    if($row->active == 0){
                        $btn .= '&nbsp;<a href="'.route("models.active", [$row->id]).'" class="active btn btn-primary btn-sm">تفعيل</a>';
                    }else{
                        $btn .= '&nbsp;<a href="'.route("models.deactive", [$row->id]).'" class="deactive btn btn-danger btn-sm">تعطيل</a>';
                    }
                }
                return $btn;
            })
            ->addColumn('status', function($row){
                return $row->active == 1 ? 'مفعل' : 'غير مفعل';
            })
            ->addColumn('blank', function($row){
                return '  ';
            })
            ->rawColumns(['actions','status','blank'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('models.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {

            $models = Model::onlyTrashed()->get();

            return DataTables::of($models)->addIndexColumn()
            ->addColumn('actionone', function($row){
                if(auth()->user()->can('restore_model')){
                    $btn = '<a href="'.route("models.restore", [$row->id]).'" class="edit btn btn-primary btn-sm">استعادة</a>';return $btn;
                }
            })
            ->rawColumns(['actionone'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('models.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('models.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(is_numeric($request->name)){
            return redirect()->back()->withErrors(['name' => 'الطراز يجب ان يكون حروف وارقام'])->with(['message' => 'الطراز يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        if(is_numeric($request->type)){
            return redirect()->back()->withErrors(['type' => 'رمز الطراز يجب ان يكون حروف وارقام'])->with(['message' => 'رمز الطراز يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        $validator = Validator::make($request->all(), ['name' => 'required|unique:models,name', 'type' => 'required|unique:models,type']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['active' => 1]);

        $model = Model::create($request->all());

        activity()->log('قام '.auth()->user()->name.'باضافة طراز جديد'.$model->name);

        return redirect('models')->with(['message' => 'تم إضافة طراز جديد بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function show(Model $model)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function edit(Model $model)
    {
        return view('models.edit', ['model' => $model]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $model)
    {
        if(is_numeric($request->name)){
            return redirect()->back()->withErrors(['name' => 'الطراز يجب ان يكون حروف وارقام'])->with(['message' => 'الطراز يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        if(is_numeric($request->type)){
            return redirect()->back()->withErrors(['type' => 'رمز الطراز يجب ان يكون حروف وارقام'])->with(['message' => 'رمز الطراز يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        $validator = Validator::make($request->all(), ['name' => 'required|unique:models,name,'.$model->id.',id', 'type' => 'required|unique:models,type,'.$model->id.',id']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $model->update($request->all());

        activity()->log('قام '.auth()->user()->name.'بالتعديل على الطراز'.$model->name);

        return redirect('models')->with(['message' => 'تم التعديل على بيانات الطراز']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $model)
    {
        $model->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف الطراز'.$model->name);

        return redirect('models')->with(['message' => 'تم حذف الطراز']);
    }

    public function active(Model $model)
    {
        $model->update(['active' => 1]);
        return redirect()->back()->with(['message' => 'تم التفعيل']);
    }

    public function deactive(Model $model)
    {
        $model->update(['active' => 0]);

        return redirect()->back()->with(['message' => 'تم التعطيل']);
    }

    public function restore($id)
    {
        Model::withTrashed()->find($id)->restore();

        return redirect()->back()->with(['message' => 'تم الإستعادة']);
    }
}
