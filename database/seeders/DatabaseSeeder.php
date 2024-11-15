<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create();

        $role = Role::create(['name' => 'admin', 'active' => 1]);

        Permission::create(['name' => 'add_user', 'display_name' => 'إضافة مستخدم', 'group_name' => 'المستخدمين']);
        $role->givePermissionTo('add_user');
        Permission::create(['name' => 'edit_user', 'display_name' => 'تعديل مستخدم', 'group_name' => 'المستخدمين']);
        $role->givePermissionTo('edit_user');
        Permission::create(['name' => 'delete_user', 'display_name' => 'حذف مستخدم', 'group_name' => 'المستخدمين']);
        $role->givePermissionTo('delete_user');
        Permission::create(['name' => 'view_user', 'display_name' => 'عرض مستخدم', 'group_name' => 'المستخدمين']);
        $role->givePermissionTo('view_user');
        Permission::create(['name' => 'active_user', 'display_name' => 'تفعيل مستخدم', 'group_name' => 'المستخدمين']);
        $role->givePermissionTo('active_user');
        Permission::create(['name' => 'restore_user', 'display_name' => 'استعادة مستخدم', 'group_name' => 'المستخدمين']);
        $role->givePermissionTo('restore_user');
        Permission::create(['name' => 'add_role', 'display_name' => 'إضافة منصب', 'group_name' => 'المناصب']);
        $role->givePermissionTo('add_role');
        Permission::create(['name' => 'edit_role', 'display_name' => 'تعديل منصب', 'group_name' => 'المناصب']);
        $role->givePermissionTo('edit_role');
        Permission::create(['name' => 'delete_role', 'display_name' => 'حذف منصب', 'group_name' => 'المناصب']);
        $role->givePermissionTo('delete_role');
        Permission::create(['name' => 'view_role', 'display_name' => 'عرض منصب', 'group_name' => 'المناصب']);
        $role->givePermissionTo('view_role');
        Permission::create(['name' => 'active_role', 'display_name' => 'تفعيل منصب', 'group_name' => 'المناصب']);
        $role->givePermissionTo('active_role');
        Permission::create(['name' => 'restore_role', 'display_name' => 'استعادة منصب', 'group_name' => 'المناصب']);
        $role->givePermissionTo('restore_role');
        Permission::create(['name' => 'add_term', 'display_name' => 'إضافة بند', 'group_name' => 'البنود']);
        $role->givePermissionTo('add_term');
        Permission::create(['name' => 'edit_term', 'display_name' => 'تعديل بند', 'group_name' => 'البنود']);
        $role->givePermissionTo('edit_term');
        Permission::create(['name' => 'delete_term', 'display_name' => 'حذف بند', 'group_name' => 'البنود']);
        $role->givePermissionTo('delete_term');
        Permission::create(['name' => 'view_term', 'display_name' => 'عرض بند', 'group_name' => 'البنود']);
        $role->givePermissionTo('view_term');
        Permission::create(['name' => 'active_term', 'display_name' => 'تفعيل بند', 'group_name' => 'البنود']);
        $role->givePermissionTo('active_term');
        Permission::create(['name' => 'restore_term', 'display_name' => 'استعادة بند', 'group_name' => 'البنود']);
        $role->givePermissionTo('restore_term');
        Permission::create(['name' => 'add_branch', 'display_name' => 'إضافة فرع', 'group_name' => 'الفروع']);
        $role->givePermissionTo('add_branch');
        Permission::create(['name' => 'edit_branch', 'display_name' => 'تعديل فرع', 'group_name' => 'الفروع']);
        $role->givePermissionTo('edit_branch');
        Permission::create(['name' => 'delete_branch', 'display_name' => 'حذف فرع', 'group_name' => 'الفروع']);
        $role->givePermissionTo('delete_branch');
        Permission::create(['name' => 'view_branch', 'display_name' => 'عرض فرع', 'group_name' => 'الفروع']);
        $role->givePermissionTo('view_branch');
        Permission::create(['name' => 'active_branch', 'display_name' => 'تفعيل فرع', 'group_name' => 'الفروع']);
        $role->givePermissionTo('active_branch');
        Permission::create(['name' => 'restore_branch', 'display_name' => 'استعادة فرع', 'group_name' => 'الفروع']);
        $role->givePermissionTo('restore_branch');
        Permission::create(['name' => 'edit_code', 'display_name' => 'تعديل كود تسجيل المنشأة', 'group_name' => 'كود تسجيل المنشأة']);
        $role->givePermissionTo('edit_code');
        Permission::create(['name' => 'add_hurdle', 'display_name' => 'إضافة حاجز', 'group_name' => 'الحاجز']);
        $role->givePermissionTo('add_hurdle');
        Permission::create(['name' => 'edit_hurdle', 'display_name' => 'تعديل حاجز', 'group_name' => 'الحاجز']);
        $role->givePermissionTo('edit_hurdle');
        Permission::create(['name' => 'delete_hurdle', 'display_name' => 'حذف حاجز', 'group_name' => 'الحاجز']);
        $role->givePermissionTo('delete_hurdle');
        Permission::create(['name' => 'view_hurdle', 'display_name' => 'عرض حاجز', 'group_name' => 'الحاجز']);
        $role->givePermissionTo('view_hurdle');
        Permission::create(['name' => 'active_hurdle', 'display_name' => 'تفعيل حاجز', 'group_name' => 'الحاجز']);
        $role->givePermissionTo('active_hurdle');
        Permission::create(['name' => 'restore_hurdle', 'display_name' => 'استعادة حاجز', 'group_name' => 'الحاجز']);
        $role->givePermissionTo('restore_hurdle');
        Permission::create(['name' => 'add_vehicle', 'display_name' => 'إضافة مركبة', 'group_name' => 'المركبات']);
        $role->givePermissionTo('add_vehicle');
        Permission::create(['name' => 'edit_vehicle', 'display_name' => 'تعديل مركبة', 'group_name' => 'المركبات']);
        $role->givePermissionTo('edit_vehicle');
        Permission::create(['name' => 'delete_vehicle', 'display_name' => 'حذف مركبة', 'group_name' => 'المركبات']);
        $role->givePermissionTo('delete_vehicle');
        Permission::create(['name' => 'view_vehicle', 'display_name' => 'عرض مركبة', 'group_name' => 'المركبات']);
        $role->givePermissionTo('view_vehicle');
        Permission::create(['name' => 'active_vehicle', 'display_name' => 'تفعيل مركبة', 'group_name' => 'المركبات']);
        $role->givePermissionTo('active_vehicle');
        Permission::create(['name' => 'restore_vehicle', 'display_name' => 'استعادة مركبة', 'group_name' => 'المركبات']);
        $role->givePermissionTo('restore_vehicle');
        Permission::create(['name' => 'add_model', 'display_name' => 'إضافة طراز', 'group_name' => 'الطراز']);
        $role->givePermissionTo('add_model');
        Permission::create(['name' => 'edit_model', 'display_name' => 'تعديل طراز', 'group_name' => 'الطراز']);
        $role->givePermissionTo('edit_model');
        Permission::create(['name' => 'delete_model', 'display_name' => 'حذف طراز', 'group_name' => 'الطراز']);
        $role->givePermissionTo('delete_model');
        Permission::create(['name' => 'view_model', 'display_name' => 'عرض طراز', 'group_name' => 'الطراز']);
        $role->givePermissionTo('view_model');
        Permission::create(['name' => 'active_model', 'display_name' => 'تفعيل طراز', 'group_name' => 'الطراز']);
        $role->givePermissionTo('active_model');
        Permission::create(['name' => 'restore_model', 'display_name' => 'استعادة طراز', 'group_name' => 'الطراز']);
        $role->givePermissionTo('restore_model');
        Permission::create(['name' => 'add_expense', 'display_name' => 'إضافة مصروفات', 'group_name' => 'المصروفات']);
        $role->givePermissionTo('add_expense');
        Permission::create(['name' => 'edit_expense', 'display_name' => 'تعديل مصروفات', 'group_name' => 'المصروفات']);
        $role->givePermissionTo('edit_expense');
        Permission::create(['name' => 'delete_expense', 'display_name' => 'حذف مصروفات', 'group_name' => 'المصروفات']);
        $role->givePermissionTo('delete_expense');
        Permission::create(['name' => 'view_expense', 'display_name' => 'عرض مصروفات', 'group_name' => 'المصروفات']);
        $role->givePermissionTo('view_expense');
        Permission::create(['name' => 'active_expense', 'display_name' => 'تفعيل مصروفات', 'group_name' => 'المصروفات']);
        $role->givePermissionTo('active_expense');
        Permission::create(['name' => 'restore_expense', 'display_name' => 'استعادة مصروفات', 'group_name' => 'المصروفات']);
        $role->givePermissionTo('restore_expense');
        Permission::create(['name' => 'add_product', 'display_name' => 'إضافة منتج', 'group_name' => 'المنتجات']);
        $role->givePermissionTo('add_product');
        Permission::create(['name' => 'edit_product', 'display_name' => 'تعديل منتج', 'group_name' => 'المنتجات']);
        $role->givePermissionTo('edit_product');
        Permission::create(['name' => 'delete_product', 'display_name' => 'حذف منتج', 'group_name' => 'المنتجات']);
        $role->givePermissionTo('delete_product');
        Permission::create(['name' => 'view_product', 'display_name' => 'عرض منتج', 'group_name' => 'المنتجات']);
        $role->givePermissionTo('view_product');
        Permission::create(['name' => 'active_product', 'display_name' => 'تفعيل منتج', 'group_name' => 'المنتجات']);
        $role->givePermissionTo('active_product');
        Permission::create(['name' => 'restore_product', 'display_name' => 'استعادة منتج', 'group_name' => 'المنتجات']);
        $role->givePermissionTo('restore_product');
        Permission::create(['name' => 'add_discount', 'display_name' => 'إضافة خصم', 'group_name' => 'الخصومات']);
        $role->givePermissionTo('add_discount');
        Permission::create(['name' => 'edit_discount', 'display_name' => 'تعديل خصم', 'group_name' => 'الخصومات']);
        $role->givePermissionTo('edit_discount');
        Permission::create(['name' => 'delete_discount', 'display_name' => 'حذف خصم', 'group_name' => 'الخصومات']);
        $role->givePermissionTo('delete_discount');
        Permission::create(['name' => 'view_discount', 'display_name' => 'عرض خصم', 'group_name' => 'الخصومات']);
        $role->givePermissionTo('view_discount');
        Permission::create(['name' => 'active_discount', 'display_name' => 'تفعيل خصم', 'group_name' => 'الخصومات']);
        $role->givePermissionTo('active_discount');
        Permission::create(['name' => 'restore_discount', 'display_name' => 'استعادة خصم', 'group_name' => 'الخصومات']);
        $role->givePermissionTo('restore_discount');
        Permission::create(['name' => 'edit_settings', 'display_name' => 'تعديل الضريبة', 'group_name' => 'الضريبة']);
        $role->givePermissionTo('edit_settings');
        Permission::create(['name' => 'add_selling', 'display_name' => 'إضافة طلب', 'group_name' => 'الطلبات']);
        $role->givePermissionTo('add_selling');
        Permission::create(['name' => 'edit_selling', 'display_name' => 'تعديل طلب', 'group_name' => 'الطلبات']);
        $role->givePermissionTo('edit_selling');
        Permission::create(['name' => 'delete_selling', 'display_name' => 'حذف طلب', 'group_name' => 'الطلبات']);
        $role->givePermissionTo('delete_selling');
        Permission::create(['name' => 'view_selling', 'display_name' => 'عرض طلب', 'group_name' => 'الطلبات']);
        $role->givePermissionTo('view_selling');
        Permission::create(['name' => 'show_selling', 'display_name' => 'مشاهدة الطلب', 'group_name' => 'الطلبات']);
        $role->givePermissionTo('show_selling');
        Permission::create(['name' => 'show_invoice', 'display_name' => 'عرض الفاتورة', 'group_name' => 'الطلبات']);
        $role->givePermissionTo('show_invoice');



        $user = \App\Models\User::latest()->first();
        $user->assignRole($role);

        Setting::create(['code' => 1111, 'vat' => 0.25]);

    }
}
