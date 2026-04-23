<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $database = [
        'ok' => false,
        'connection' => config('database.default'),
        'host' => config('database.connections.'.config('database.default').'.host'),
        'name' => config('database.connections.'.config('database.default').'.database'),
        'message' => null,
    ];

    $users = collect();
    $userCount = 0;

    try {
        DB::connection()->getPdo();

        $userCount = User::query()->count();
        $users = User::query()
            ->latest('id')
            ->limit(8)
            ->get(['id', 'name', 'email', 'created_at']);

        $database['ok'] = true;
    } catch (Throwable $exception) {
        $database['message'] = $exception->getMessage();
    }

    return view('welcome', [
        'database' => $database,
        'users' => $users,
        'userCount' => $userCount,
    ]);
});
