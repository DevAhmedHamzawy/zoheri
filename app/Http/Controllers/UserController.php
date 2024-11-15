<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:add_user'])->only(['create', 'store']);
        $this->middleware(['permission:edit_user'])->only(['edit', 'update']);
        $this->middleware(['permission:view_user'])->only(['index']);
        $this->middleware(['permission:delete_user'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $users = User::all();

            return DataTables::of($users)->addIndexColumn()
            ->addColumn('actions', function($row){
                $btn = "";
                if(auth()->user()->can('edit_user')){
                    $btn .= '<a href="'.route("users.edit", [$row->id]).'" class="edit btn btn-primary btn-sm">تعديل</a>';
                }
                if(auth()->user()->can('delete_user')){
                    $btn .= '&nbsp;<a href="'.route("users.delete", [$row->id]).'" class="delete btn btn-danger btn-sm" onclick="return confirm(`هل انت متاكد انك تريد الحذف ؟`);">حذف</a>';
                }
                $btn .= '&nbsp;<a href="'.route("users.show", [$row->id]).'" class="active btn btn-primary btn-sm">عرض</a>';
                if(auth()->user()->can('active_user')){
                    if($row->active == 0){
                        $btn .= '&nbsp;<a href="'.route("users.active", [$row->id]).'" class="active btn btn-primary btn-sm">تفعيل</a>';
                    }else{
                        $btn .= '&nbsp;<a href="'.route("users.deactive", [$row->id]).'" class="deactive btn btn-danger btn-sm">تعطيل</a>';
                    }
                }
                return $btn;
            })
            ->addColumn('status', function($row){
                return $row->active == 0 ? 'غير مفعل' : 'مغعل';
            })
            ->addColumn('blank', function($row){
                return '  ';
            })
            ->rawColumns(['actions','status','blank'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('users.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {

            $users = User::onlyTrashed()->get();

            return DataTables::of($users)->addIndexColumn()
            ->addColumn('actionone', function($row){
                if(auth()->user()->can('restore_user')){
                    $btn = '<a href="'.route("users.restore", [$row->id]).'" class="edit btn btn-primary btn-sm">استعادة</a>';return $btn;
                }
            })
            ->rawColumns(['actionone'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('users.trash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.add')->withRoles($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), ['name' => 'required', 'email' => 'required|email|unique:users,email', 'active' => 'required', 'role' => 'required', 'password' => 'required|min:8|max:25']);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        $request->merge(['password' => bcrypt($request->password)]);
        $user = User::create($request->except('role'));
        $user->assignRole($request->role);

        activity()->log('قام '.auth()->user()->name.'باضافة مستخدم جديد'.$user->name);

        return redirect('users')->with(['message' => 'تم إضافة مستخدم جديد بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user, 'roles' => Role::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), ['name' => 'required', 'email' => 'required|email|unique:users,email,'.$user->id.',id', 'active' => 'required', 'role' => 'required']);

        if($request->password != null)
        {
            $validator = Validator::make($request->all(), ['password' => 'sometimes|min:8|max:25']);
        }

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
        }

        if($request->password != null){
            $request->merge(['password' => bcrypt($request->password)]);
        }else{
            $request->merge(['password' => $user->password]);
        }

        $user->update($request->except('role'));
        $user->syncRoles($request->role);

        activity()->log('قام '.auth()->user()->name.'بالتعديل على المستخدم'.$user->name);

        return redirect('users')->with(['message' => 'تم التعديل على بيانات المستخدم']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف المستخدم'.$user->name);

        return redirect('users')->with(['message' => 'تم حذف المستخدم']);
    }

    public function active(User $user)
    {
        $user->update(['active' => 1]);
        return redirect()->back()->with(['message' => 'تم التفعيل']);
    }

    public function deactive(User $user)
    {
        $user->update(['active' => 0]);

        return redirect()->back()->with(['message' => 'تم التعطيل']);
    }

    public function restore($id)
    {
        User::withTrashed()->find($id)->restore();

        return redirect()->back()->with(['message' => 'تم الإستعادة']);
    }
}
