<?php

use App\Http\Controllers\AksesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\EkspedisiController;
use App\Http\Controllers\KartuAksesController;
use App\Http\Controllers\MapMenuRoleController;
use App\Http\Controllers\MapSubmenuMenuController;
use App\Http\Controllers\MapUserRoleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaketKeluarController;
use App\Http\Controllers\PaketKeluarHistController;
use App\Http\Controllers\PaketMasukController;
use App\Http\Controllers\PaketMasukHistController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubmenuController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\TamuHadirController;
use App\Http\Controllers\TamuHadirHistController;
use App\Http\Controllers\UserController;

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


// auth
Route::get('/', [AuthController::class, 'login']);
Route::get('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/akses', [AuthController::class, 'akses']);
Route::get('/auth/pilih_role', [AuthController::class, 'pilih_role']);
Route::get('/auth/blocked', [AuthController::class, 'blocked']);
Route::post('/auth/proses_login', [AuthController::class, 'proses_login']);
Route::get('/auth/logout', [AuthController::class, 'logout']);
Route::post('auth/password', [App\Http\Controllers\AuthController::class, 'changePassword']);
 
Route::group(['middleware' => ['isloggedin']], function () {
    //auth
    Route::get('/auth/getTerminal', [AuthController::class, 'getTerminal']);
    Route::put('/auth/change_session', [AuthController::class, 'change_session']);
    Route::put('/auth/change_pwd', [AuthController::class, 'change_pwd']);

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);      

    // divisi
    Route::get('/divisi', [DivisiController::class, 'index']);
    Route::get('/divisi/getDivisi', [DivisiController::class, 'getDivisi']);
    Route::get('/divisi/getListDivisi', [DivisiController::class, 'getListDivisi']);
    Route::post('/divisi/store', [DivisiController::class, 'store']);
    Route::put('/divisi/update/{id_divisi}', [DivisiController::class, 'update']);
    Route::delete('/divisi/delete/{id_divisi}', [DivisiController::class, 'delete']);


    // user
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/getUser', [UserController::class, 'getUser']);
    Route::get('/user/getListUserAktif', [UserController::class, 'getListUserAktif']);
    Route::post('/user/store', [UserController::class, 'store']);
    Route::put('/user/update/{nipp}', [UserController::class, 'update']);
    Route::delete('/user/delete/{nipp}', [UserController::class, 'delete']);
    Route::get('/user/getDivisiByNipp/{nipp}', [UserController::class, 'getDivisiByNipp']);

    // role
    Route::get('/role', [RoleController::class, 'index']);
    Route::get('/role/getRole', [RoleController::class, 'getRole']);
    Route::post('/role/store', [RoleController::class, 'store']);
    Route::put('/role/update/{id_role}', [RoleController::class, 'update']);
    Route::delete('/role/delete/{id_role}', [RoleController::class, 'delete']);

    //akses
    Route::get('/akses/getAkses', [AksesController::class, 'getAkses']);
    Route::put('/akses/update', [AksesController::class, 'update']);

    // menu
    Route::get('/menu', [MenuController::class, 'index']);
    Route::get('/menu/getMenu', [MenuController::class, 'getMenu']);
    Route::get('/menu/getMaxUrutanMenu', [MenuController::class, 'getMaxUrutanMenu']);
    Route::post('/menu/store', [MenuController::class, 'store']);
    Route::put('/menu/update/{id_menu}', [MenuController::class, 'update']);
    Route::delete('/menu/delete/{id_menu}', [MenuController::class, 'delete']);

    // submenu
    Route::get('/submenu', [SubmenuController::class, 'index']);
    Route::get('/submenu/getSubmenu', [SubmenuController::class, 'getSubmenu']);
    Route::get('/submenu/getMaxUrutanSubmenu', [SubmenuController::class, 'getMaxUrutanSubmenu']);
    Route::post('/submenu/store', [SubmenuController::class, 'store']);
    Route::put('/submenu/update/{id_submenu}', [SubmenuController::class, 'update']);
    Route::delete('/submenu/delete/{id_submenu}', [SubmenuController::class, 'delete']);

    // user-role
    Route::get('/mapuserrole', [MapUserRoleController::class, 'index']);
    Route::get('/mapuserrole/getUserRole', [MapUserRoleController::class, 'getUserRole']);
    Route::post('/mapuserrole/store', [MapUserRoleController::class, 'store']);
    Route::put('/mapuserrole/update/{id_user_role}', [MapUserRoleController::class, 'update']);
    Route::delete('/mapuserrole/delete/{id_user_role}', [MapUserRoleController::class, 'delete']);

    // menu-role
    Route::get('/mapmenurole', [MapMenuRoleController::class, 'index']);
    Route::get('/mapmenurole/getMenuRole', [MapMenuRoleController::class, 'getMenuRole']);
    Route::post('/mapmenurole/store', [MapMenuRoleController::class, 'store']);
    Route::put('/mapmenurole/update/{id_menu_role}', [MapMenuRoleController::class, 'update']);
    Route::delete('/mapmenurole/delete/{id_menu_role}', [MapMenuRoleController::class, 'delete']);

    // menu-submenu
    Route::get('/mapsubmenumenu', [MapSubmenuMenuController::class, 'index']);
    Route::get('/mapsubmenumenu/getSubmenuMenu', [MapSubmenuMenuController::class, 'getSubmenuMenu']);
    Route::post('/mapsubmenumenu/store', [MapSubmenuMenuController::class, 'store']);
    Route::put('/mapsubmenumenu/update/{id_submenu_menu}', [MapSubmenuMenuController::class, 'update']);
    Route::delete('/mapsubmenumenu/delete/{id_submenu_menu}', [MapSubmenuMenuController::class, 'delete']);

    // tamu
    Route::get('/ekspedisi', [EkspedisiController::class, 'index']);
    Route::get('/ekspedisi/getEkspedisi', [EkspedisiController::class, 'getEkspedisi']);
    Route::get('/ekspedisi/getInfoEkspedisi', [EkspedisiController::class, 'getInfoEkspedisi']);
    Route::post('/ekspedisi/store', [EkspedisiController::class, 'store']);
    Route::put('/ekspedisi/update/{id_ekspedisi}', [EkspedisiController::class, 'update']);
    Route::delete('/ekspedisi/delete/{id_ekspedisi}', [EkspedisiController::class, 'delete']);

    // tamu
    Route::get('/tamu', [TamuController::class, 'index']);
    Route::get('/tamu/getTamu', [TamuController::class, 'getTamu']);
    Route::get('/tamu/getListTamu', [TamuController::class, 'getListTamu']);
    Route::post('/tamu/store', [TamuController::class, 'store']);
    Route::put('/tamu/update/{id_tamu}', [TamuController::class, 'update']);
    Route::delete('/tamu/delete/{id_tamu}', [TamuController::class, 'delete']);

    // kartu akses
    Route::get('/kartuakses', [KartuAksesController::class, 'index']);
    Route::get('/kartuakses/getKartuAkses', [KartuAksesController::class, 'getKartuAkses']);
    Route::get('/kartuakses/getListKartuAksesTersedia', [KartuAksesController::class, 'getListKartuAksesTersedia']);
    Route::post('/kartuakses/store', [KartuAksesController::class, 'store']);
    Route::put('/kartuakses/update/{id_kartuakses}', [KartuAksesController::class, 'update']);
    Route::delete('/kartuakses/delete/{id_kartuakses}', [KartuAksesController::class, 'delete']);

    // paket masuk
    Route::get('/paketmasuk', [PaketMasukController::class, 'index']);
    Route::get('/paketmasuk/getPaketMasuk', [PaketMasukController::class, 'getPaketMasuk']);
    Route::post('/paketmasuk/store', [PaketMasukController::class, 'store']);
    Route::put('/paketmasuk/serahterima/{id_paketmasuk}', [PaketMasukController::class, 'serahterima']);
    Route::delete('/paketmasuk/delete/{id_paketmasuk}', [PaketMasukController::class, 'delete']);

    // paket keluar
    Route::get('/paketkeluar', [PaketKeluarController::class, 'index']);
    Route::get('/paketkeluar/getPaketKeluar', [PaketKeluarController::class, 'getPaketKeluar']);
    Route::post('/paketkeluar/store', [PaketKeluarController::class, 'store']);
    Route::put('/paketkeluar/serahterima/{id_paketkeluar}', [PaketKeluarController::class, 'serahterima']);
    Route::delete('/paketkeluar/delete/{id_paketkeluar}', [PaketKeluarController::class, 'delete']);

    // histori paket masuk
    Route::get('/paketmasukhist', [PaketMasukHistController::class, 'index']);
    Route::get('/paketmasukhist/getPaketMasuk', [PaketMasukHistController::class, 'getPaketMasuk']);

    // histori paket keluar
    Route::get('/paketkeluarhist', [PaketKeluarHistController::class, 'index']);
    Route::get('/paketkeluarhist/getPaketKeluar', [PaketKeluarHistController::class, 'getPaketKeluar']);

    // tamu hadir
    Route::get('/tamuhadir', [TamuHadirController::class, 'index']);
    Route::get('/tamuhadir/getTamuHadir', [TamuHadirController::class, 'getTamuHadir']);
    Route::post('/tamuhadir/store', [TamuHadirController::class, 'store']);
    Route::put('/tamuhadir/serahterima/{id_tamuhadir}', [TamuHadirController::class, 'serahterima']);
    Route::delete('/tamuhadir/delete/{id_tamuhadir}', [TamuHadirController::class, 'delete']);

    // histori tamu hadir
    Route::get('/tamuhadirhist', [TamuHadirHistController::class, 'index']);
    Route::get('/tamuhadirhist/getTamuHadir', [TamuHadirHistController::class, 'getTamuHadir']);
});

// Tambahkan route baru
Route::get('/get-visitor-stats', [App\Http\Controllers\TamuHadirHistController::class, 'getVisitorStats']);
Route::get('/get-paket-stats', [App\Http\Controllers\PaketMasukHistController::class, 'getPaketStats']);
Route::get('/get-paketkeluar-stats', [App\Http\Controllers\PaketKeluarHistController::class, 'getPaketStats']);

Route::get('/get-divisi-by-nipp/{nipp}', 'UserController@getDivisiByNipp');
Route::get('/get-user-divisi/{nipp}', 'UserController@getUserDivisi');

Route::get('/search-users', 'UserController@searchUsers');
Route::get('/search-users/{nipp}', 'UserController@getUserDetail');

Route::get('/get-users-by-name', 'App\Http\Controllers\UserController@searchByName');

// Route untuk paket keluar
Route::group(['prefix' => 'paketkeluar'], function () {
    Route::get('/', 'App\Http\Controllers\PaketKeluarController@index');
    Route::post('/', 'App\Http\Controllers\PaketKeluarController@store');
    Route::post('/serahterima/{id}', 'App\Http\Controllers\PaketKeluarController@serahterima');
    Route::delete('/{id}', 'App\Http\Controllers\PaketKeluarController@delete');
});
