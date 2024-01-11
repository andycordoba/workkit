<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->middleware('auth');

Route::middleware('auth')->group(function() { 

  Route::get('/', function () {
    return Inertia::render('Home');
  });


  Route::get('/users', function () {
    return Inertia::render('Users/Index', [
      'users' => User::query()
        ->when(Request::input('search'), function($query, $search) {
          $query->where('name', 'like', "%{$search}%");
        })
        ->paginate(10)
        ->withQueryString()
        ->through(fn($user) => [
          'id' => $user->id,
          'name' => $user->name,
          'can' => [
            'edit' => Auth::user()->can('edit', $user)
          ]
        ]),

        'filters' => Request::only(['search']),
        'can' => [
          'createUser' => Auth::user()->can('create', User::class)
        ]
    ]);
  });

  Route::get('/users/create', function () {
    return Inertia::render('Users/Create');
  })->can('create', 'App\Models\User');

  Route::post('/users', function () {
    //Validate the request
    $attributes = Request::validate([
      'name' => 'required',
      'email' => ['required','email'],
      'password' => 'required',
    ]);

    //create tehe user
    User::create($attributes); 

    //redirect
    return redirect('/users'); 
  });

  Route::get('/settings', function () {
    return Inertia::render('Settings');
  });

});
