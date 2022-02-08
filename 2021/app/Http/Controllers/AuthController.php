<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Функция регистрации
    public function register(Request $request) {
    	// Валидация
    	$validator = Validator::make($request->all(), [
    		"login" => "required|unique:users,login"
    	]);
    	// В случае наличия ошибок валидации
    	if($validator->fails()) {
    		return redirect()->route("main_page")->withErrors("Такой логин уже есть", "message");
    		// return redirect()->route("main_page")->withErrors($validator, "register");
    	}

    	// Вставка данных в базу
    	DB::table("users")->insert([
    		"fio" => $request->input("fio"),
    		"email" => $request->input("email"),
    		"login" => $request->input("login"),
    		"password" => bcrypt($request->input("password")),
    		"role" => "user"
    	]);

    	// Перенаправление на главную страницу с сообщением об успешной регистрации
    	return redirect()->route("main_page")->withErrors("Вы зарегистрировались", "message");

    }

    // Функция авторизации
    public function login(Request $request) {
    	// Авторизация работает даже без переключения провайдера
    	// в файле config/auth.php на database

    	// Получение данных
    	$login = $request->input("login");
    	$password = $request->input("password");
    	// Проверка авторизации
    	if(Auth::attempt(["login" => $login, "password" => $password], true)) {
    		// В случае успеха перенаправление на страницу личного кабинет
    		return redirect()->route("user_page");
    	} else {
    		// В случае неудачи перенаправление на главную страницу с сообщением об ошибке
    		return redirect()->route("main_page")->withErrors("Ошибка логина или пароля", "message");
    	}
    }

    // Функция выхода из авторизации
    public function logout(Request $request) {
    	// Если пользователь авторизован
    	if(Auth::check()) {
    		// Выход из авторизации
    		Auth::logout();
    		// Перенаправление на главную страницу
    		return redirect()->route("main_page");
    	// Если пользователь не авторизован
    	} else {
    		// Перенаправление на главную страницу с сообщением об ошибке
    		return redirect()->route("main_page")->withErrors("Вы не авторизованы", "message");
    	}
    }
}
