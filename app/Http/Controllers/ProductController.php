<?php

namespace App\Http\Controllers;

use App\Models\Hurdle;
use App\Models\Item;
use App\Models\Model;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $products = Product::with(['vehicle','hurdle','model'])->get();

            return DataTables::of($products)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = "";
                if(auth()->user()->can('edit_product')){
                    $btn .= '<a href="'.route("products.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_product')){
                    $btn .= '&nbsp;<a href="'.route("products.delete", [$row->id]).'" class="delete btn btn-danger btn-sm" onclick="return confirm(`هل انت متاكد انك تريد الحذف ؟`);">حذف</a>';
                }
                if(auth()->user()->can('active_product')){
                    if($row->active == 0){
                        $btn .= '&nbsp;<a href="'.route("products.active", [$row->id]).'" class="active btn btn-primary btn-sm">تفعيل</a>';
                    }else{
                        $btn .= '&nbsp;<a href="'.route("products.deactive", [$row->id]).'" class="deactive btn btn-danger btn-sm">تعطيل</a>';
                    }
                }
                return $btn;
            })
            ->addColumn('status', function($row){
                return $row->active == 1 ? 'مفعل' : 'غير مفعل';
            })
            ->rawColumns(['actions','status'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('products.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {

            $users = Product::onlyTrashed()->with(['vehicle','hurdle','model'])->get();

            return DataTables::of($users)->addIndexColumn()
            ->addColumn('actionone', function($row){
                if(auth()->user()->can('restore_product')){
                    $btn = '<a href="'.route("products.restore", [$row->id]).'" class="edit btn btn-primary btn-sm">استعادة</a>';return $btn;
                }
            })
            ->rawColumns(['actionone'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('products.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.add', ['vehicles' => Vehicle::whereActive(1)->get(), 'models' => Model::whereActive(1)->get(), 'hurdles' => Hurdle::whereActive(1)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(Product::whereVehicleId($request->vehicle_id)->where('hurdle_id', $request->hurdle_id)->where('model_id', $request->model_id)->exists())
        {
            return redirect()->back()->withErrors('المنتج موجود من قبل')->with(['message' => 'المنتج موجود من قبل', 'alert' => 'alert-danger']);
        }

        $validator = Validator::make($request->all(), ['vehicle_id' => 'required|numeric', 'hurdle_id' => 'required|numeric', 'model_id' => 'required|numeric', 'price' => 'required']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['active' => 1]);

        $expense =  Product::create($request->all());

        activity()->log('قام '.auth()->user()->name.'باضافة تسعير منتج جديد');

        return redirect('products')->with(['message' => 'تم إضافة تسعير منتج جديد بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit', ['vehicles' => Vehicle::whereActive(1)->get(), 'models' => Model::whereActive(1)->get(), 'hurdles' => Hurdle::whereActive(1)->get(), 'product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        if(Product::whereVehicleId($request->vehicle_id)->where('hurdle_id', $request->hurdle_id)->where('model_id', $request->model_id)->exists())
        {
            if($request->price === $product->price){
                return redirect()->back()->withErrors('المنتج موجود من قبل')->with(['message' => 'المنتج موجود من قبل', 'alert' => 'alert-danger']);
            }
        }

        $validator = Validator::make($request->all(), ['vehicle_id' => 'required|numeric', 'hurdle_id' => 'required|numeric', 'model_id' => 'required|numeric', 'price' => 'required']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $product->update($request->all());

        activity()->log('قام '.auth()->user()->name.'بالتعديل على تسعير المنتج');

        return redirect('products')->with(['message' => 'تم التعديل على بيانات تسعير المنتج']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف تسعير المنتج');

        return redirect('products')->with(['message' => 'تم حذف تسعير المنتج']);
    }

    public function active(Product $product)
    {
        $product->update(['active' => 1]);
        return redirect()->back()->with(['message' => 'تم التفعيل']);
    }

    public function deactive(Product $product)
    {
        $product->update(['active' => 0]);

        return redirect()->back()->with(['message' => 'تم التعطيل']);
    }

    public function restore($id)
    {
        Product::withTrashed()->find($id)->restore();

        return redirect()->back()->with(['message' => 'تم الإستعادة']);
    }

    public function getData(Request $request)
    {
        $vehicle_id = $request->vehicle_id;
        $hurdle_id = $request->hurdle_id;
        $model_id = $request->model_id;
        $qty = $request->qty;

        $product = Product::whereVehicleId($vehicle_id)->where('hurdle_id', $hurdle_id)->where('model_id', $model_id)->with(['vehicle', 'hurdle' , 'model'])->first();

        if($product == null) return null;
        if($product->active == 0) return null;

        $product->price_qty = $product->price * $qty;

        return $product;
    }

    public function getProductCode(Request $request, $vehicle_id, $hurdle_id, $model_id)
    {
        $request['vehicle_id'] = $vehicle_id;
        $request['hurdle_id'] = $hurdle_id;
        $request['model_id'] = $model_id;

        $product = $this->getData($request);
        $code = Setting::whereId(1)->first()->code;

        $noOfProductsMade = Item::whereProductId($product->id)->count();
        $productCode = '0000'.$noOfProductsMade+1;

        return ['product_code' => 'KSA '.$code.' '.$product->hurdle->slogan.' '.$product->model->type.' '.$productCode, 'product_id' => $product->id];
    }
}
