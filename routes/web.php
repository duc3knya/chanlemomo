<?php

use App\Http\Controllers\AccountLevelMoneyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'maintenance_system'], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/load-data', [HomeController::class, 'getDataAfterLoad'])
        ->name('home.get_data_after_load');
    Route::post('/realtime-attendance', [HomeController::class, 'realTimeAttendance'])
        ->name('home.attendance.realtime');
    Route::post('/attendance-session', [HomeController::class, 'attendanceSession'])->name('home.attendance_session');
    Route::post('/attendance-date', [HomeController::class, 'attendanceDate'])->name('home.attendance_date');
});
Route::get('/bao-tri', function() {
    $repo    = new \App\Http\Repositories\AttendanceSessionRepository();
    $setting = $repo->getSettingWebsite();
    return view('pages.maintenance_system', compact('setting'));
})->name('bao_tri');
Route::get('/admin/login', [AdminController::class, 'Login'])->name('login')->middleware('guest');
Route::post('/admin/login-action', [AdminController::class, 'LoginAction'])->name('admin_login_action')->middleware('guest');

Route::group(['prefix' => '/admin', 'middleware' => 'auth'],function(){
    Route::get('/logout', [AdminController::class, 'Logout'])->name('admin_logout');
    Route::get('/', [AdminController::class, 'index'])->name('admin_home');
    Route::get('/lich-su-choi/{slug}', [AdminController::class, 'LichSuChoi'])->name('admin_lichsuchoi');
    Route::get('/setting', [AdminController::class, 'EditSetting'])->name('admin_setting');
    Route::post('/setting-action', [AdminController::class, 'EditSettingAction'])->name('admin_setting_action');
    Route::get('/level-money-sdt', [AccountLevelMoneyController::class, 'index'])->name('admin_level_money');
    Route::post('/level-money-sdt-add', [AccountLevelMoneyController::class, 'store'])->name('admin_level_money.store');
    Route::post('/level-money-sdt-edit', [AccountLevelMoneyController::class, 'edit'])->name('admin_level_money.edit');
    Route::post('/level-money-sdt-update', [AccountLevelMoneyController::class, 'update'])->name('admin_level_money.update');
    Route::post('/level-money-sdt-delete', [AccountLevelMoneyController::class, 'delete'])->name('admin_level_money.delete');
    Route::get('/quanlysdt', [AdminController::class, 'QuanlySDT'])->name('admin_quanlysdt');
    Route::post('/quanlysdt/set-status', [AdminController::class, 'SetStatusSDT'])->name('admin.setstatus');
    Route::get('/quanlysdt/delete/id-{id}', [AdminController::class, 'DeteleSDT'])->name('admin_quanlysdt_delete');
    Route::get('/quanlysdt/edit/id-{id}', [AdminController::class, 'EditSDT'])->name('admin_quanlysdt_edit');
    Route::post('/quanlysdt/edit/action', [AdminController::class, 'EditSDTAction'])->name('admin_quanlysdt_edit_action');
    Route::get('/quanlysdt/add', [AdminController::class, 'AddSDT'])->name('admin_quanlysdt_add');
    Route::post('/quanlysdt/add-action', [AdminController::class, 'AddSDTAction'])->name('admin_quanlysdt_add_action');
    Route::get('/setting/chan-le', [AdminController::class, 'SettingChanLe'])->name('admin_setting_chanle');
    Route::post('/setting/chan-le-action', [AdminController::class, 'SettingChanLeAction'])->name('admin_setting_chanle_action');
    Route::get('/setting/tai-xiu', [AdminController::class, 'SettingTaiXiu'])->name('admin_setting_taixiu');
    Route::post('/setting/tai-xiu-action', [AdminController::class, 'SettingTaiXiuAction'])->name('admin_setting_taixiu_action');
    Route::get('/setting/chan-le-2', [AdminController::class, 'SettingChanLe2'])->name('admin_setting_chanle2');
    Route::post('/setting/chan-le-2-action', [AdminController::class, 'SettingChanLeAction2'])->name('admin_setting_chanle_action2');
    Route::get('/setting/gap-3', [AdminController::class, 'SettingGap3'])->name('admin_setting_gap3');
    Route::post('/setting/gap-3-action', [AdminController::class, 'SettingGap3Action'])->name('admin_setting_gap3_action');
    Route::get('/setting/tong-3-so', [AdminController::class, 'SettingTong3So'])->name('admin_setting_tong3so');
    Route::post('/setting/tong-3-so-action', [AdminController::class, 'SettingTong3SoAction'])->name('admin_setting_tong3so_action');
    Route::get('/setting/1-phan-3', [AdminController::class, 'Setting1Phan3'])->name('admin_setting_1phan3');
    Route::post('/setting/1-phan-3-action', [AdminController::class, 'Setting1Phan3Action'])->name('admin_setting_1phan3_action');
    Route::get('/setting/no-hu', [AdminController::class, 'SettingNoHu'])->name('admin_setting_nohu');
    Route::post('/setting/no-hu-action', [AdminController::class, 'SettingNoHuAction'])->name('admin_setting_nohu_action');
    Route::get('/setting/thuong-tuan', [AdminController::class, 'SettingThuongTuan'])->name('admin_setting_thuongtuan');
    Route::post('/setting/thuong-tuan-action', [AdminController::class, 'SettingThuongTuanAction'])
        ->name('admin_setting_thuongtuan_action');
    Route::get('/setting/diem-danh', [AdminController::class, 'SettingAttendance'])->name('admin_setting_diemdanh');
    Route::post('/setting/diem-danh-action', [AdminController::class, 'SettingAttendanceAction'])
        ->name('admin_setting_diemdanh_action');
    Route::get('/setting/attendance-date', [AdminController::class, 'SettingAttendanceDate'])->name('admin_setting_attendance_date');
    Route::post('/setting/attendance-date-add', [AdminController::class, 'SettingAttendanceDateAdd'])
        ->name('admin_setting_attendance_date_add');
    Route::post('/setting/attendance-date-update', [AdminController::class, 'SettingAttendanceDateUpdate'])
        ->name('admin_setting_attendance_date_update');
    Route::post('/setting/attendance-date-delete', [AdminController::class, 'SettingAttendanceDateDelete'])
        ->name('admin_setting_attendance_date_delete');
    Route::get('/setting/doi-mat-khau', [AdminController::class, 'DoiMatKhau'])->name('admin_doi_mat_khau');
    Route::post('/setting/doi-mat-khau-action', [AdminController::class, 'DoiMatKhauAction'])->name('admin_doi_mat_khau_action');
    Route::get('/setting/update', [AdminController::class, 'Update'])->name('admin_update');
    Route::get('/setting/config-message', [AdminController::class, 'ConfigMessage'])->name('admin_config_message');
    Route::post('/setting/config-message-action', [AdminController::class, 'ConfigMessageAction'])->name('admin_config_message_action');

    Route::get('/lich-su-bank', [AdminController::class, 'LichSuBankView'])->name('admin.lichsubank.view');

    Route::get('/update-view', [AdminController::class, 'UpdateView'])->name('admin.update.view');
    Route::get('/update', [AdminController::class, 'Update'])->name('admin.update');
});