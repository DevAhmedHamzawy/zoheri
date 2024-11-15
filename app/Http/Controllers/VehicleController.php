<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class VehicleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:add_vehicle'])->only(['create', 'store']);
        $this->middleware(['permission:edit_vehicle'])->only(['edit', 'update']);
        $this->middleware(['permission:view_vehicle'])->only(['index']);
        $this->middleware(['permission:delete_vehicle'])->only(['delete']);
        $this->middleware(['permission:active_vehicle'])->only(['vehicle.active']);
        $this->middleware(['permission:deactive_vehicle'])->only(['vehicle.deactive']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $vehicles = Vehicle::all();

            return DataTables::of($vehicles)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = "";
                if(auth()->user()->can('edit_vehicle')){
                    $btn .= '<a href="'.route("vehicles.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_vehicle')){
                    $btn .= '&nbsp;<a href="'.route("vehicles.delete", [$row->id]).'" class="delete btn btn-danger btn-sm" onclick="return confirm(`هل انت متاكد انك تريد الحذف ؟`);">حذف</a>';
                }
                if(auth()->user()->can('active_vehicle')){
                    if($row->active == 0){
                        $btn .= '&nbsp;<a href="'.route("vehicles.active", [$row->id]).'" class="active btn btn-primary btn-sm">تفعيل</a>';
                    }else{
                        $btn .= '&nbsp;<a href="'.route("vehicles.deactive", [$row->id]).'" class="deactive btn btn-danger btn-sm">تعطيل</a>';
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

        return view('vehicles.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {

            $vehicles = Vehicle::onlyTrashed()->get();

            return DataTables::of($vehicles)->addIndexColumn()
            ->addColumn('actionone', function($row){
                if(auth()->user()->can('restore_vehicle')){
                    $btn = '<a href="'.route("vehicles.restore", [$row->id]).'" class="edit btn btn-primary btn-sm">استعادة</a>';return $btn;
                }
            })
            ->rawColumns(['actionone'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('vehicles.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vehicles.add');
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

        $validator = Validator::make($request->all(), ['name' => 'required|unique:vehicles,name']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['active' => 1]);

        $vehicle = Vehicle::create($request->all());

        activity()->log('قام '.auth()->user()->name.'باضافة مركبة جديدة'.$vehicle->name);

        return redirect('vehicles')->with(['message' => 'تم إضافة مركبة جديدة بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', ['vehicle' => $vehicle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        if(is_numeric($request->name)){
            return redirect()->back()->withErrors(['name' => 'الاسم يجب ان يكون حروف وارقام'])->with(['message' => 'الاسم يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        $validator = Validator::make($request->all(), ['name' => 'required|unique:vehicles,name,'.$vehicle->id.',id']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $vehicle->update($request->all());

        activity()->log('قام '.auth()->user()->name.'بالتعديل على المركبة'.$vehicle->name);

        return redirect('vehicles')->with(['message' => 'تم التعديل على بيانات المركبة']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف المركبة'.$vehicle->name);

        return redirect('vehicles')->with(['message' => 'تم حذف المركبة']);
    }

    public function active(Vehicle $vehicle)
    {
        $vehicle->update(['active' => 1]);
        return redirect()->back()->with(['message' => 'تم التفعيل']);
    }

    public function deactive(Vehicle $vehicle)
    {
        $vehicle->update(['active' => 0]);

        return redirect()->back()->with(['message' => 'تم التعطيل']);
    }

    public function restore($id)
    {
        Vehicle::withTrashed()->find($id)->restore();

        return redirect()->back()->with(['message' => 'تم الإستعادة']);
    }
}
