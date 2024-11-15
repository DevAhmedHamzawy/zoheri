@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-header">
                   عرض المستخدم
                </h3>

                <div class="card-body">
                    إسم المستخدم :- {{ $user->name }}
                    <br>
                    البريد الإلكترونى :- {{ $user->email }}
                    <br>
                    الحالة :- {{ $user->active ? 'مفعل' : 'غير مفعل' }}
                    <br>
                    الدور :- {{ $user->roles->pluck('name')[0] }}
                    <br>
                    الصلاحيات :-
                    @foreach ($user->roles[0]->permissions as $permission)
                        {{ $permission->display_name }} &nbsp; - &nbsp;
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



