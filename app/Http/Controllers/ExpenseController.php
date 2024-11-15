<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Term;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ExpenseController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:add_expense'])->only(['create', 'store']);
        $this->middleware(['permission:edit_expense'])->only(['edit', 'update']);
        $this->middleware(['permission:view_expense'])->only(['index']);
        $this->middleware(['permission:delete_expense'])->only(['delete']);
        $this->middleware(['permission:active_expense'])->only(['expenses.active']);
        $this->middleware(['permission:active_expense'])->only(['expenses.deactive']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $expenses = Expense::with('term')->get();

            return DataTables::of($expenses)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = '';
                if(auth()->user()->can('edit_expense')){
                    $btn .= '<a href="'.route("expenses.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_expense')){
                    $btn .= '&nbsp;<a href="'.route("expenses.delete", [$row->id]).'" class="delete btn btn-danger btn-sm" onclick="return confirm(`هل انت متاكد انك تريد الحذف ؟`);">حذف</a>';
                }
                if(auth()->user()->can('active_expense')){
                    if($row->active == 0){
                        $btn .= '&nbsp;<a href="'.route("expenses.active", [$row->id]).'" class="active btn btn-primary btn-sm">تفعيل</a>';
                    }else{
                        $btn .= '&nbsp;<a href="'.route("expenses.deactive", [$row->id]).'" class="deactive btn btn-danger btn-sm">تعطيل</a>';
                    }
                }
                return $btn;
            })
            ->addColumn('status', function($row){
                return $row->active == 1 ? 'مفعل' : 'غير مفعل';
            })
            ->addColumn('invoice', function($row){
                return '<!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal-'.$row->id.'">
                  عرض الفاتورة
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal-'.$row->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">عرض الفاتورة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <img width="460" height="460" src='.$row->img_path.' />
                      </div>
                    </div>
                  </div>
                </div>';
            })

            ->rawColumns(['actions','active','status','invoice'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('expenses.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {

            $expenses = Expense::onlyTrashed()->with('term')->get();

            return DataTables::of($expenses)->addIndexColumn()
            ->addColumn('actionone', function($row){
                if(auth()->user()->can('restore_expense')){
                    $btn = '<a href="'.route("expenses.restore", [$row->id]).'" class="edit btn btn-primary btn-sm">استعادة</a>';return $btn;
                }
            })
            ->addColumn('invoice', function($row){
                return '<!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal-'.$row->id.'">
                  عرض الفاتورة
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal-'.$row->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">عرض الفاتورة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <img width="460" height="460" src='.$row->img_path.' />
                      </div>
                    </div>
                  </div>
                </div>';
            })
            ->rawColumns(['actionone','invoice'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('expenses.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expenses.add', ['terms' => Term::whereActive(1)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['term_id' => 'required|numeric', 'cost' => 'required', 'date' => 'required|date' , 'main_image' => 'mimes:jpeg,jpg,png,gif|required|max:10000']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['active' => 1]);

        $request->merge(['image' => Upload::uploadImage($request->main_image, 'expenses' , rand(00000,99999))]);

        $expense =  Expense::create($request->except('main_image'));

        activity()->log('قام '.auth()->user()->name.'باضافة مصروف جديد');

        return redirect('expenses')->with(['message' => 'تم إضافة مصروف جديد بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', ['expense' => $expense, 'terms' => Term::whereActive(1)->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {

        if($request->has('main_image'))
        {
            $validator = Validator::make($request->all(), ['main_image' => 'mimes:jpeg,jpg,png,gif|required|max:10000']);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
            }

            $request->merge(['image' => Upload::uploadImage($request->main_image, 'expenses' , rand(00000,99999))]);
        }

        $validator = Validator::make($request->all(), ['term_id' => 'required|numeric', 'cost' => 'required', 'date' => 'required|date']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $expense->update($request->except('main_image'));

        activity()->log('قام '.auth()->user()->name.'بالتعديل على المصروف');

        return redirect('expenses')->with(['message' => 'تم التعديل على بيانات المصروف']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف المصروف');

        return redirect('expenses')->with(['message' => 'تم حذف المصروف']);
    }

    public function active(Expense $expense)
    {
        $expense->update(['active' => 1]);
        return redirect()->back()->with(['message' => 'تم التفعيل']);
    }

    public function deactive(Expense $expense)
    {
        $expense->update(['active' => 0]);

        return redirect()->back()->with(['message' => 'تم التعطيل']);
    }

    public function restore($id)
    {
        Expense::withTrashed()->find($id)->restore();

        return redirect()->back()->with(['message' => 'تم الإستعادة']);
    }
}
