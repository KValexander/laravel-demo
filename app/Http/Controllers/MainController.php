<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    // Функция вывода данных на главную страницу
    public function main_page(Request $request) {
    	// Такие проверки приходится писать в каждом контроллере из-за отсутствия middleware
    	// где я мог бы это единоразово прописать и не нагромождать контроллеры лишним кодом
    	// Если пользователь авторизован
    	if(Auth::check()) {
    		// Получение данных пользователя
    		$user = Auth::user();
    		// Получение роли
    		$role = $user->role;
    	// Если пользователь не авторизован
    	} else $role = "guest";

    	// Получение последних 4 решённых заявок
    	$app = DB::table("applications")
    		->where("status", "=", "Решена")
    		->orderBy("created_at", "DESC")
    		->limit(4)
    		->get();

    	// Составление объекта
    	$data = (object)[
    		"applications" => $app
    	];

    	// Возвращение данных
    	return view("index", ["data" => $data, "role" => $role]);
    }
}
