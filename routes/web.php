<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\EmployeeController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui estão as rotas definidas para sua aplicação.
|
*/

// Rotas para usuários não autenticados (página de login e registro)
Route::middleware(['guest'])->group(function () {
    // Rota de login inicial personalizada
    Route::view('/', 'login')->name('login');
//deletar depois
    Route::view('/teste', 'teste')->name('teste');

    // Rota para a página de login (opcional, redireciona para '/')
    Route::view('/login', 'login');

    // Ação para efetuar login (com lógica do UserController)
    Route::post('/login', [UserController::class, 'login'])->name('login.submit');

    // Página de registro
    Route::view('/register', 'register')->name('register');

    // Ação para efetuar o registro (com lógica do UserController)
    Route::post('/register', [UserController::class, 'register'])->name('register.submit');

});

// Rotas protegidas com middleware `auth` (acessíveis apenas por usuários autenticados)
Route::middleware(['auth'])->group(function () {
    // Página Home (apenas para usuários autenticados)
    Route::get('/home', [ProductController::class, 'index'])->name('home');

    // Rotas para pedidos
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/teste/formulario', [OrderController::class, 'testeFormulario'])->name('orders.testeFormulario');

    // Rotas para gerenciamento de produtos
    //Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // Rotas para o carrinho de compras
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');

    // Rota de logout para deslogar o usuário autenticado
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Rota para atualizar os dados do usuário
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update');

    // Rota para salvar o pedido e redirecionar para a tela de resumo
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');

    // Rota para exibir o resumo do pedido
    Route::get('/orders/summary/{order}', [OrderController::class, 'showSummary'])->name('orders.summary');
    // Rota para acompanhamento de pedidos
    Route::get('/orders', [OrderController::class, 'userOrders'])->name('orders.user')->middleware('auth');

    // Rota para a página "Minha Conta"
    Route::get('/account', [UserController::class, 'showAccount'])->name('account.show');

    // Rota para processar a atualização dos dados
    Route::post('/account/update-profile', [UserController::class, 'updateProfile'])->name('account.updateProfile')->middleware('auth');
    // Rota para processar a atualização da senha
    Route::post('/account/update-password', [UserController::class, 'updatePassword'])->name('account.updatePassword')->middleware('auth');

    Route::post('/user/updateAddressPhone', [UserController::class, 'updateAddressPhone'])->name('user.updateAddressPhone');
    // Programa de pontos
    Route::post('/account/redeem-loyalty-coupon', [UserController::class, 'redeemLoyaltyCoupon'])->name('account.redeemLoyaltyCoupon');
    // Tela de avaliações
    Route::get('/reviews/report', [OrderController::class, 'reviewReport'])->name('reviews.report');
    // Rota avaliações
    Route::post('/orders/{order}/rate', [OrderController::class, 'rate'])->name('orders.rate');

});

// Rotas protegidas com middleware `auth` e `isAdmin` (acessíveis apenas por administradores)
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    // Rotas para o gerenciamento de estoque
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock/add/{product}', [StockController::class, 'addStock'])->name('stock.add');
    Route::post('/stock/remove/{product}', [StockController::class, 'removeStock'])->name('stock.remove');
    Route::get('/stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
    Route::delete('/stock/{id}', [StockController::class, 'destroy'])->name('stock.destroy');
    // Rotas para atributos
    Route::get('/attributes/create', [AttributeController::class, 'create'])->name('attributes.create');
    Route::post('/attributes', [AttributeController::class, 'store'])->name('attributes.store');
    // Rota Atualiza status
    Route::put('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.updateOrderStatus');
    // Rota para relatório de vendas
    Route::get('/report/sales', [ReportController::class, 'showReport'])->name('report.sales');
    // Rota para coupons
    Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');
    // Rota cadastro de empregados
    Route::resource('employees', EmployeeController::class);
    // Listar funcionários
     Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    
    // Formulário para criar um novo funcionário
     Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
 
    // Armazenar um novo funcionário
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
 
    // Formulário para editar um funcionário
    Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
 
    // Atualizar os dados do funcionário
    Route::put('/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
 
    // Remover um funcionário
     Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');





});
