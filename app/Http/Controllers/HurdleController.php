<?php

namespace App\Http\Controllers;

use App\Models\Hurdle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class HurdleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:add_hurdle'])->only(['create', 'store']);
        $this->middleware(['permission:edit_hurdle'])->only(['edit', 'update']);
        $this->middleware(['permission:view_hurdle'])->only(['index']);
        $this->middleware(['permission:delete_hurdle'])->only(['delete']);
        $this->middleware(['permission:active_hurdle'])->only(['hurdle.active']);
        $this->middleware(['permission:active_hurdle'])->only(['hurdle.deactive']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $hurdles = Hurdle::all();

            return DataTables::of($hurdles)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = '';
                if(auth()->user()->can('edit_hurdle')){
                    $btn .= '&nbsp;<a href="'.route("hurdles.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_hurdle')){
                    $btn .= '&nbsp;<a href="'.route("hurdles.delete", [$row->id]).'" class="delete btn btn-danger btn-sm" onclick="return confirm(`هل انت متاكد انك تريد الحذف ؟`);">حذف</a>';
                }
                if(auth()->user()->can('active_hurdle')){
                    if($row->active == 0){
                        $btn .= '&nbsp;<a href="'.route("hurdles.active", [$row->id]).'" class="active btn btn-primary btn-sm">تفعيل</a>';
                    }else{
                        $btn .= '&nbsp;<a href="'.route("hurdles.deactive", [$row->id]).'" class="deactive btn btn-danger btn-sm">تعطيل</a>';
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

        return view('hurdles.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {

            $hurdles = Hurdle::onlyTrashed()->get();

            return DataTables::of($hurdles)->addIndexColumn()
            ->addColumn('actionone', function($row){
                if(auth()->user()->can('restore_hurdle')){
                    $btn = '<a href="'.route("hurdles.restore", [$row->id]).'" class="edit btn btn-primary btn-sm">استعادة</a>';return $btn;
                }
            })
            ->rawColumns(['actionone'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('hurdles.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hurdles.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(is_numeric($request->type)){
            return redirect()->back()->withErrors(['type' => 'النوع يجب ان يكون حروف وارقام'])->with(['message' => 'الاسم يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        if(is_numeric($request->slogan)){
            return redirect()->back()->withErrors(['slogan' => 'الرمز يجب ان يكون حروف وارقام'])->with(['message' => 'الاسم يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        $validator = Validator::make($request->all(), ['type' => 'required|unique:hurdles,type', 'slogan' => 'required|unique:hurdles,slogan']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['active' => 1]);

        $hurdle = Hurdle::create($request->all());

        activity()->log('قام '.auth()->user()->name.'باضافة حاجز جديد'.$hurdle->name);

        return redirect('hurdles')->with(['message' => 'تم إضافة حاجز جديد بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hurdle  $hurdle
     * @return \Illuminate\Http\Response
     */
    public function show(Hurdle $hurdle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hurdle  $hurdle
     * @return \Illuminate\Http\Response
     */
    public function edit(Hurdle $hurdle)
    {
        return view('hurdles.edit', ['hurdle' => $hurdle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hurdle  $hurdle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hurdle $hurdle)
    {
        if(is_numeric($request->type)){
            return redirect()->back()->withErrors(['type' => 'النوع يجب ان يكون حروف وارقام'])->with(['message' => 'الاسم يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        if(is_numeric($request->slogan)){
            return redirect()->back()->withErrors(['slogan' => 'الرمز يجب ان يكون حروف وارقام'])->with(['message' => 'الاسم يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        $validator = Validator::make($request->all(), ['type' => 'required|unique:hurdles,type,'.$hurdle->id.',id', 'slogan' => 'required|unique:hurdles,slogan,'.$hurdle->id.',id']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $hurdle->update($request->all());

        activity()->log('قام '.auth()->user()->name.'بالتعديل على الحاجز'.$hurdle->name);

        return redirect('hurdles')->with(['message' => 'تم التعديل على بيانات الحاجز']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hurdle  $hurdle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hurdle $hurdle)
    {
        $hurdle->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف الحاجز'.$hurdle->name);

        return redirect('hurdles')->with(['message' => 'تم حذف الحاجز']);
    }

    public function active(Hurdle $hurdle)
    {
        $hurdle->update(['active' => 1]);
        return redirect()->back()->with(['message' => 'تم التفعيل']);
    }

    public function deactive(Hurdle $hurdle)
    {
        $hurdle->update(['active' => 0]);

        return redirect()->back()->with(['message' => 'تم التعطيل']);
    }

    public function restore($id)
    {
        Hurdle::withTrashed()->find($id)->restore();

        return redirect()->back()->with(['message' => 'تم الإستعادة']);
    }
}
