<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function __construct()
    {
        if(request()->segment(1) == 'code'){
            $this->middleware(['permission:edit_code'])->only(['edit', 'update']);
        }else{
            $this->middleware(['permission:edit_settings'])->only(['edit', 'update']);
        }
    }
    public function edit()
    {
        if(request()->segment(1) == 'code'){
            return view('code.edit', ['settings' => Setting::whereId(1)->first()]);
        }else if(request()->segment(1) == 'settings'){
            return view('settings.edit', ['settings' => Setting::whereId(1)->first()]);
        }
    }

    public function update(Request $request, Setting $setting)
    {
        if(request()->type == 'code'){

            /*if(is_numeric($request->code)){
                return redirect()->back()->withErrors(['code' => 'الكود يجب ان يكون حروف وارقام'])->with(['message' => 'الكود يجب ان يكون حروف وارقام', 'alert' => 'alert-danger']);
            }*/

            $validator = Validator::make($request->all(), ['code' => 'required']);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
            }

            Setting::whereId(1)->update(['code' => $request->code]);

            $setting = $setting::whereId(1)->first();

            activity()->log('قام '.auth()->user()->name.'بالتعديل على الكود'.$setting->code);

            return redirect()->back()->with(['message' => 'تم التعديل على كود تسجيل المنشأة']);

        }else if(request()->type == 'settings'){

            $validator = Validator::make($request->all(), ['vat' => 'required|numeric']);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'alert' => 'alert-danger']);
            }

            Setting::whereId(1)->update(['vat' => $request->vat]);

            $setting = $setting::whereId(1)->first();

            activity()->log('قام '.auth()->user()->name.'بالتعديل على الاعدادت');

            return redirect()->back()->with(['message' => 'تم التعديل على الإعدادات']);
        }
    }
}
