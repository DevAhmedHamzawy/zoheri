<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:add_role'])->only(['create', 'store']);
        $this->middleware(['permission:edit_role'])->only(['edit', 'update']);
        $this->middleware(['permission:view_role'])->only(['index']);
        $this->middleware(['permission:delete_role'])->only(['delete']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $roles = Role::all();

            return DataTables::of($roles)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = '';
                if(auth()->user()->can('edit_role')){
                    $btn .= '<a href="'.route("roles.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_role')){
                    $btn .= '&nbsp;<a href="'.route("roles.delete", [$row->id]).'" class="delete btn btn-danger btn-sm" onclick="return confirm(`هل انت متاكد انك تريد الحذف ؟`);">حذف</a>';
                }
                if(auth()->user()->can('active_role')){
                    if($row->active == 0){
                        $btn .= '&nbsp;<a href="'.route("roles.active", [$row->id]).'" class="active btn btn-primary btn-sm">تفعيل</a>';
                    }else{
                        $btn .= '&nbsp;<a href="'.route("roles.deactive", [$row->id]).'" class="deactive btn btn-danger btn-sm">تعطيل</a>';
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

        return view('roles.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {

            $users = Role::onlyTrashed()->get();

            return DataTables::of($users)->addIndexColumn()
            ->addColumn('actionone', function($row){
                if(auth()->user()->can('restore_role')){
                    $btn = '<a href="'.route("roles.restore", [$row->id]).'" class="edit btn btn-primary btn-sm">استعادة</a>';return $btn;
                }
            })
            ->rawColumns(['actionone'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('roles.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get()->groupby('group_name');

        return view('roles.add')->withPermissions($permissions);
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

        $validator = Validator::make($request->all(), ['name' => 'required|unique:roles,name', 'permissions' => 'required']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['active' => 1]);

        $role = Role::create($request->except('permissions'));

        if($request->has('permissions')){
            foreach($request->permissions as $permission){
                $role->givePermissionTo($permission);
            }
        }


        activity()->log('قام '.auth()->user()->name.'باضافة دور جديد'.$role->name);

        return redirect('roles')->with(['message' => 'تم إضافة دور جديد بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::get()->groupby('group_name');

        return view('roles.edit', ['role' => $role, 'permissions' => $permissions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {

        if(is_numeric($request->name)){
            return redirect()->back()->withErrors(['name' => 'الاسم يجب ان يكون حروف وارقام'])->with(['message' => 'الاسم يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
        }

        $validator = Validator::make($request->all(), ['name' => 'required|unique:roles,name,'.$role->id.',id', 'permissions' => 'required']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $role->update($request->all());

        //foreach($request->permissions as $permission){
            if($request->has('permissions')){
                $role->syncPermissions($request->permissions);
            }
        //}

        activity()->log('قام '.auth()->user()->name.'بالتعديل على الدور'.$role->name);

        return redirect('roles')->with(['message' => 'تم التعديل على بيانات المستخدم']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $checkRole = Role::withCount('users')->find($role->id);

        if(!$checkRole->users_count){
            $role->delete();

            activity()->log('قام '.auth()->user()->name.'بحذف الدور'.$role->name);

            return redirect('roles')->with(['message' => 'تم حذف المنصب']);
        }else{
            return redirect('roles')->with(['message' => 'لا يمكن حذف المنصب']);
        }
    }

    public function active(Role $role)
    {
        $role->update(['active' => 1]);
        return redirect()->back()->with(['message' => 'تم التفعيل']);
    }

    public function deactive(Role $role)
    {
        $role->update(['active' => 0]);

        return redirect()->back()->with(['message' => 'تم التعطيل']);
    }

    public function restore($id)
    {
        Role::withTrashed()->find($id)->restore();

        return redirect()->back()->with(['message' => 'تم الإستعادة']);
    }
}
