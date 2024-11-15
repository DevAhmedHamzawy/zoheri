<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_discount'])->only(['create', 'store']);
        $this->middleware(['permission:edit_discount'])->only(['edit', 'update']);
        $this->middleware(['permission:view_discount'])->only(['index']);
        $this->middleware(['permission:delete_discount'])->only(['delete']);
        $this->middleware(['permission:active_discount'])->only(['discounts.active']);
        $this->middleware(['permission:active_discount'])->only(['discounts.deactive']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $discounts = Discount::all();

            return DataTables::of($discounts)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = '';
                if(auth()->user()->can('edit_discount')){
                    $btn .= '<a href="'.route("discounts.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_discount')){
                    $btn .= '&nbsp;<a href="'.route("discounts.delete", [$row->id]).'" class="delete btn btn-danger btn-sm" onclick="return confirm(`هل انت متاكد انك تريد الحذف ؟`);">حذف</a>';
                }
                if(auth()->user()->can('active_discount')){
                    if($row->active == 0){
                        $btn .= '&nbsp;<a href="'.route("discounts.active", [$row->id]).'" class="active btn btn-primary btn-sm">تفعيل</a>';
                    }else{
                        $btn .= '&nbsp;<a href="'.route("discounts.deactive", [$row->id]).'" class="deactive btn btn-danger btn-sm">تعطيل</a>';
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
            ->rawColumns(['actions','blank','status'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('discounts.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {

            $discounts = Discount::onlyTrashed()->get();

            return DataTables::of($discounts)->addIndexColumn()
            ->addColumn('actionone', function($row){
                if(auth()->user()->can('restore_discount')){
                    $btn = '<a href="'.route("discounts.restore", [$row->id]).'" class="edit btn btn-primary btn-sm">استعادة</a>';return $btn;
                }
            })
            ->rawColumns(['actionone'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('discounts.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('discounts.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), ['amount' => 'required']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        if(Discount::whereSort($request->sort)->whereAmount($request->amount)->exists())
            return redirect()->back()->withErrors($validator)->with(['status' => 'قيمة الخصم موجودة من قبل', 'alert' => 'alert-danger']);

        $request->merge(['active' => 1]);

        $discount = Discount::create($request->all());

        activity()->log('قام '.auth()->user()->name.'باضافة خصم جديد'.$discount->name);

        return redirect('discounts')->with(['message' => 'تم إضافة خصم جديد بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        return view('discounts.edit', ['discount' => $discount]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discount $discount)
    {

        $validator = Validator::make($request->all(), ['amount' => 'required']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $getDiscount = Discount::whereSort($request->sort)->whereAmount($request->amount)->first();
        if($getDiscount != null && $getDiscount->id !== $discount->id)
            return redirect()->back()->withErrors($validator)->with(['status' => 'قيمة الخصم موجودة من قبل', 'alert' => 'alert-danger']);

        $discount->update($request->all());

        activity()->log('قام '.auth()->user()->name.'بالتعديل على الخصم'.$discount->name);

        return redirect('discounts')->with(['message' => 'تم التعديل على بيانات الخصم']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف الخصم'.$discount->name);

        return redirect('discounts')->with(['message' => 'تم حذف الخصم']);
    }

    public function active(Discount $discount)
    {
        $discount->update(['active' => 1]);
        return redirect()->back()->with(['message' => 'تم التفعيل']);
    }

    public function deactive(Discount $discount)
    {
        $discount->update(['active' => 0]);

        return redirect()->back()->with(['message' => 'تم التعطيل']);
    }

    public function restore($id)
    {
        Discount::withTrashed()->find($id)->restore();

        return redirect()->back()->with(['message' => 'تم الإستعادة']);
    }

    public function discountAmount($discount_sort)
    {
        $arr = ['نسبة','مبلغ'];
        $discountAmounts = Discount::whereSort($arr[$discount_sort])->whereActive(1)->get();

        return $discountAmounts;
    }
}
