<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Hurdle;
use App\Models\Item;
use App\Models\Model;
use App\Models\Selling;
use App\Models\Setting;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SellingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_selling'])->only(['create', 'store']);
        $this->middleware(['permission:edit_selling'])->only(['edit', 'update']);
        $this->middleware(['permission:view_selling'])->only(['index']);
        $this->middleware(['permission:delete_selling'])->only(['delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $sellings = Selling::all();

            return DataTables::of($sellings)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = "";
                if(auth()->user()->can('show_selling')){
                    $btn .= '<a href="'.route("sellings.show", [$row->id]).'" class="edit btn btn-primary btn-sm">عرض الطلب</a>';
                }
                if(auth()->user()->can('show_invoice')){
                    $btn .= '&nbsp;<a href="'.route("sellings.invoice", [$row->id]).'" class="edit btn btn-primary btn-sm">عرض الفاتورة</a>';
                }
                if(auth()->user()->can('show_invoice')){
                    $btn .= '&nbsp;<a href="'.route("sellings.unique_card", [$row->id]).'" class="edit btn btn-primary btn-sm">عرض البطاقة</a>';
                }
                if(auth()->user()->can('edit_selling')){
                    $btn .= '&nbsp;<a href="'.route("sellings.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_selling')){
                    $btn .= '&nbsp;<a href="'.route("sellings.delete", [$row->id]).'" class="edit btn btn-primary btn-sm">حذف</a>';
                }
                return $btn;
            })
            ->rawColumns(['actions'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('sellings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sellings.add', ['branches' => Branch::whereActive(1)->get(), 'vehicles' => Vehicle::whereActive(1)->get(), 'hurdles' => Hurdle::whereActive(1)->get(), 'models' => Model::whereActive(1)->get(), 'settings' => Setting::whereId(1)->first()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(!$request->has('subtotal') || $request->subtotal == 0){
            return redirect()->back()->withInput()->withErrors(['name' => 'برجاء اضافة منتجات'])->with(['message' => 'برجاء اضافة منتجات', 'alert' => 'alert-danger']);
        }

        $selling = Selling::create($request->only('branch_id','client_name', 'client_tax_no', 'client_telephone' , 'subtotal', 'vat', 'total', 'notes'));

        for ($i=0; $i < count($request->product_ids) ; $i++) {

            $request->discount_sorts[$i] == 'نسبة' ? $specialChar = "%"  :  $specialChar = "ر.س" ;

            $discount_amount = str_replace($specialChar , '' , $request->discount_amounts[$i]);

            Item::create([
             'selling_id' => $selling->id,
             'product_id' => $request->product_ids[$i],
             'product_code' => $request->product_codes[$i],
             'vin' => $request->vins[$i],
             'product_price' => $request->unit_prices[$i],
             'qty' => $request->qtys[$i],
             'discount_sort' => $request->discount_sorts[$i] == 'نوع الخصم' ? null : $request->discount_sorts[$i],
             'discount' => $discount_amount,
             'sub_total' => $request->prices_after_discount[$i],
             'vat' => $request->vats_to_pay[$i],
             'total' => $request->total_prices[$i]
            ]);
        }

        return redirect('sellings')->with(['message' => 'تم إضافة طلب جديد بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Selling  $selling
     * @return \Illuminate\Http\Response
     */
    public function show(Selling $selling)
    {
        return view('sellings.show', ['selling' => $selling]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Selling  $selling
     * @return \Illuminate\Http\Response
     */
    public function edit(Selling $selling)
    {
        return view('sellings.edit', ['selling' => $selling, 'branches' => Branch::whereActive(1)->get(), 'vehicles' => Vehicle::whereActive(1)->get(), 'hurdles' => Hurdle::whereActive(1)->get(), 'models' => Model::whereActive(1)->get(), 'settings' => Setting::whereId(1)->first()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Selling  $selling
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Selling $selling)
    {

        if(!$request->product_ids){
            return redirect()->back()->withInput()->withErrors(['name' => 'برجاء اضافة منتجات'])->with(['message' => 'برجاء اضافة منتجات', 'alert' => 'alert-danger']);
        }

        $selling->update($request->only('branch_id','client_name', 'client_tax_no', 'client_telephone' , 'subtotal', 'vat', 'total' , 'notes'));

        $selling->items()->delete();

        for ($i=0; $i < count($request->product_ids) ; $i++) {
            $request->discount_sorts[$i] == 'نسبة' ? $specialChar = "%"  :  $specialChar = "ر.س" ;

            $discount_amount = str_replace($specialChar , '' , $request->discount_amounts[$i]);

            Item::create([
             'selling_id' => $selling->id,
             'product_id' => $request->product_ids[$i],
             'product_code' => $request->product_codes[$i],
             'vin' => $request->vins[$i],
             'product_price' => $request->unit_prices[$i],
             'qty' => $request->qtys[$i],
             'discount_sort' => $request->discount_sorts[$i] == 'نوع الخصم' ? null : $request->discount_sorts[$i],
             'discount' => $discount_amount,
             'sub_total' => $request->prices_after_discount[$i],
             'vat' => $request->vats_to_pay[$i],
             'total' => $request->total_prices[$i]
            ]);
        }

        return redirect('sellings')->with(['message' => 'تم التعديل على الطلب بنجاح']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Selling  $selling
     * @return \Illuminate\Http\Response
     */
    public function destroy(Selling $selling)
    {
        $selling->delete();

        $selling->items()->delete();

        return redirect('sellings')->with(['message' => 'تم حذف الطلب بنجاح']);
    }

    public function showInvoice(Selling $selling)
    {
        return view('sellings.invoice', ['selling' => $selling]);
    }

    public function showUniqueCard(Selling $selling)
    {
        $selling = Selling::whereId($selling->id)->with('items', 'items.product.model', 'items.product.hurdle')->first();

        return view('sellings.unique_card', ['selling' => $selling]);
    }
}
