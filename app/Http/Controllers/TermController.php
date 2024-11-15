<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TermController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_term'])->only(['create', 'store']);
        $this->middleware(['permission:edit_term'])->only(['edit', 'update']);
        $this->middleware(['permission:view_term'])->only(['index']);
        $this->middleware(['permission:delete_term'])->only(['delete']);
        $this->middleware(['permission:active_term'])->only(['terms.active']);
        $this->middleware(['permission:active_term'])->only(['terms.deactive']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $terms = Term::all();

            return DataTables::of($terms)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = '';
                if(auth()->user()->can('edit_term')){
                    $btn .= '<a href="'.route("terms.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_term')){
                    $btn .= '&nbsp;<a href="'.route("terms.delete", [$row->id]).'" class="delete btn btn-danger btn-sm" onclick="return confirm(`هل انت متاكد انك تريد الحذف ؟`);">حذف</a>';
                }
                if(auth()->user()->can('active_term')){
                    if($row->active == 0){
                        $btn .= '&nbsp;<a href="'.route("terms.active", [$row->id]).'" class="active btn btn-primary btn-sm">تفعيل</a>';
                    }else{
                        $btn .= '&nbsp;<a href="'.route("terms.deactive", [$row->id]).'" class="deactive btn btn-danger btn-sm">تعطيل</a>';
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

        return view('terms.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {

            $terms = Term::onlyTrashed()->get();

            return DataTables::of($terms)->addIndexColumn()
            ->addColumn('actionone', function($row){
                if(auth()->user()->can('restore_term')){
                    $btn = '<a href="'.route("terms.restore", [$row->id]).'" class="edit btn btn-primary btn-sm">استعادة</a>';return $btn;
                }
            })
            ->rawColumns(['actionone'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('terms.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('terms.add');
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
            return redirect()->back()->withErrors(['name' => 'الاسم يجب ان يكون حروف وارقام'])->with(['message' => 'الاسم يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        $validator = Validator::make($request->all(), ['name' => 'required|unique:terms,name']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['active' => 1]);

        $term = Term::create($request->all());

        activity()->log('قام '.auth()->user()->name.'باضافة بند جديد'.$term->name);

        return redirect('terms')->with(['message' => 'تم إضافة بند جديد بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function show(Term $term)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function edit(Term $term)
    {
        return view('terms.edit', ['term' => $term]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Term $term)
    {
        if(is_numeric($request->name)){
            return redirect()->back()->withErrors(['name' => 'الاسم يجب ان يكون حروف وارقام'])->with(['message' => 'الاسم يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        $validator = Validator::make($request->all(), ['name' => 'required|unique:terms,name,'.$term->id.',id']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $term->update($request->all());

        activity()->log('قام '.auth()->user()->name.'بالتعديل على البند'.$term->name);

        return redirect('terms')->with(['message' => 'تم التعديل على بيانات البند']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $term)
    {
        $term->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف البند'.$term->name);

        return redirect('terms')->with(['message' => 'تم حذف البند']);
    }

    public function active(Term $term)
    {
        $term->update(['active' => 1]);
        return redirect()->back()->with(['message' => 'تم التفعيل']);
    }

    public function deactive(Term $term)
    {
        $term->update(['active' => 0]);

        return redirect()->back()->with(['message' => 'تم التعطيل']);
    }

    public function restore($id)
    {
        Term::withTrashed()->find($id)->restore();

        return redirect()->back()->with(['message' => 'تم الإستعادة']);
    }
}
