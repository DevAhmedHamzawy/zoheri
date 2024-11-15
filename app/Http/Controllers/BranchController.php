<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_branch'])->only(['create', 'store']);
        $this->middleware(['permission:edit_branch'])->only(['edit', 'update']);
        $this->middleware(['permission:view_branch'])->only(['index']);
        $this->middleware(['permission:delete_branch'])->only(['delete']);
        $this->middleware(['permission:active_branch'])->only(['branches.active']);
        $this->middleware(['permission:active_branch'])->only(['branches.deactive']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $branches = Branch::all();

            return DataTables::of($branches)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = '';
                if(auth()->user()->can('edit_branch')){
                    $btn .= '<a href="'.route("branches.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_branch')){
                    $btn .= '&nbsp;<a href="'.route("branches.delete", [$row->id]).'" class="delete btn btn-danger btn-sm" onclick="return confirm(`هل انت متاكد انك تريد الحذف ؟`);">حذف</a>';
                }
                if(auth()->user()->can('active_branch')){
                    if($row->active == 0){
                        $btn .= '&nbsp;<a href="'.route("branches.active", [$row->id]).'" class="active btn btn-primary btn-sm">تفعيل</a>';
                    }else{
                        $btn .= '&nbsp;<a href="'.route("branches.deactive", [$row->id]).'" class="deactive btn btn-danger btn-sm">تعطيل</a>';
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

        return view('branches.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {

            $users = Branch::onlyTrashed()->get();

            return DataTables::of($users)->addIndexColumn()
            ->addColumn('actionone', function($row){
                if(auth()->user()->can('restore_branch')){
                    $btn = '<a href="'.route("branches.restore", [$row->id]).'" class="edit btn btn-primary btn-sm">استعادة</a>';return $btn;
                }
            })
            ->rawColumns(['actionone'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('branches.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('branches.add');
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

        $validator = Validator::make($request->all(), ['name' => 'required|unique:branches,name', 'telephone' => 'required|regex:/(050)[0-9]{7}/|max:10|unique:branches,telephone']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['active' => 1]);

        $branch = Branch::create($request->all());

        activity()->log('قام '.auth()->user()->name.'باضافة فرع جديد'.$branch->name);

        return redirect('branches')->with(['message' => 'تم إضافة فرع جديد بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        return view('branches.edit', ['branch' => $branch]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {

        if(is_numeric($request->name)){
            return redirect()->back()->withErrors(['name' => 'الاسم يجب ان يكون حروف وارقام'])->with(['message' => 'الاسم يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        $validator = Validator::make($request->all(), ['name' => 'required|unique:branches,name,'.$branch->id.',id', 'telephone' => 'required|regex:/(050)[0-9]{7}/|max:10|unique:branches,telephone,'.$branch->id.',id']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $branch->update($request->all());

        activity()->log('قام '.auth()->user()->name.'بالتعديل على الفرع'.$branch->name);

        return redirect('branches')->with(['message' => 'تم التعديل على بيانات الفرع']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {

        $branch->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف الفرع'.$branch->name);

        return redirect('branches')->with(['message' => 'تم حذف الفرع']);

    }

    public function active(Branch $branch)
    {
        $branch->update(['active' => 1]);
        return redirect()->back()->with(['message' => 'تم التفعيل']);
    }

    public function deactive(Branch $branch)
    {
        $branch->update(['active' => 0]);

        return redirect()->back()->with(['message' => 'تم التعطيل']);
    }

    public function restore($id)
    {
        Branch::withTrashed()->find($id)->restore();

        return redirect()->back()->with(['message' => 'تم الإستعادة']);
    }
}
