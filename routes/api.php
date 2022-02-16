<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoHuController;
use App\Http\Controllers\BotXuLiController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Api Home
Route::get('/get-hu', [NoHuController::class, 'Get_Hu']);
Route::get('/load-hu', [NoHuController::class, 'Load_Hu']);

//+ Api Cron Game
Route::get('/cron/get-giao-dich', [BotXuLiController::class, 'SaveGiaoDich']);
Route::get('/cron/xu-li-giao-dich', [BotXuLiController::class, 'TraThuongGiaoDich']);
Route::get('/cron/xu-li-giao-dich-loi', [BotXuLiController::class, 'TraThuongGiaoDichError']);
//+ kich hoạt sdt bao tri to active 
Route::get('/cron/kich-hoat-sdt', [BotXuLiController::class, 'GetKichHoatSdt']);
//+ upate status lôi quá 2 phút từ 2 lên 4
Route::get('/cron/update-status-error', [BotXuLiController::class, 'updateStatusError']);
Route::get('/cron/doanh-thu-ngay', [BotXuLiController::class, 'getDoanhThuNgay']);
Route::get('/cron/rut-tien', [BotXuLiController::class, 'getTranferMoney']);

//+ Api Cron Trả thưởng tuần
Route::get('/cron/get-tra-thuong-tuan', [BotXuLiController::class, 'GetTraThuongTuan']);
Route::get('/cron/xu-li-thuong-tuan', [BotXuLiController::class, 'XuLiTraThuongTuan']);
Route::get('/cron/xu-li-thuong-tuan-loi', [BotXuLiController::class, 'XuLiTraThuongTuanLoi']);

//+ Api Cron Nổ Hũ
Route::get('/cron/xu-li-no-hu', [BotXuLiController::class, 'XuLiNoHuu']);
Route::get('/cron/xu-li-no-hu-loi', [BotXuLiController::class, 'XuLiNoHuuLoi']);

//version
Route::get('/info/version', [AdminController::class, 'ViewVersion']);