<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Функция вывода данных в личный кабинет
    public function user_page(Request $request) {
    	// Если пользователь авторизован
    	if(Auth::check()) {
    		// Получение данных пользователя
    		$user = Auth::user();
    		// Получение роли пользователя
    		$role = $user->role;
    		// Получение id пользователя
    		$user_id = $user->id;
    	// Если пользователь не авторизован
    	} else {
    		// Перенаправление на главную страницу с сообщением об ошибке
    		return redirect()->route("main_page")->withErrors("Вы не авторизованы", "message");
    	}

    	// Получение всех заявок пользователя
    	$app = DB::table("applications")
    		->where("user_id", "=", $user_id)
    		->get();
    	// Получение всех категорий
    	$cat = DB::table("category")->get();

    	// Составление объекта
    	$data = (object)[
    		"applications" => $app,
    		"category" => $cat,
    	];

    	// Возвращение данных
    	return view("user", ["data" => $data, "role" => $role]);
    }

    // Функция добавления заявки
    public function app_add(Request $request) {
        // Если пользователь не авторизован
        if(!Auth::check())
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect()->route("main_page")->withErrors("Вы не авторизованы", "message");

    	// Валидация
    	$validator = Validator::make($request->all(), [
    		"image" => "required|mimes:jpeg,jpg,bmp,png|max:10240"
    	]);
    	// В случае наличия ошибок валидации
    	if($validator->fails()) {
    		return redirect()->route()->withErrors("Файл должен быть изображением с расширениями jpg, jpeg, png, bmp и весить не более 10мб", "message");
    	}

        // Название изображения
        $image_name = "1_". time() ."_". rand() .".". $request->file("image")->extension();
        // Перемещение изображение на сервер
        $request->file("image")->move(public_path("images/before/"), $image_name);
        // Путь до изображения для добавлени в базу
        $path = "images/before/". $image_name;

        // Добавление данных в базу
        DB::table("applications")->insert([
            "user_id" => Auth::id(),
            "title" => $request->input("title"),
            "description" => $request->input("description"),
            "category" => $request->input("category"),
            "path_to_image_before" => $path,
            "status" => "Новая"
        ]);

        // Перенаправление на страницу личного кабинет с сообщением об успешном добавлении заявки
        return redirect()->route("user_page")->withErrors("Заявка добавлена", "message"); 
    }

    // Функция удаления заявки
    public function app_delete(Request $request) {
        // Если пользователь не авторизован
        if(!Auth::check())
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect()->route("main_page")->withErrors("Вы не авторизованы", "message");

        // Получение id заявки
        $app_id = $request->input("app_id");
        // Получение заявки
        $app = DB::table("applications")->where("application_id", "=", $app_id)->first();

        // Если заявка не новая или пользователь не является автором заявки
        if($app->status != "Новая" || $app->user_id != Auth::id()) {
            // Перенправление на страницу личного кабинета с сообщением об ошибке
            return redirect()->route("user_page")->withErrors("Удалить можно только заявку со статутом \"Новая\"", "message");
        }

        // Удаление изображения
        unlink(public_path($app->path_to_image_before));
        // Удаление заявки
        DB::table("applications")->where("application_id", "=", $app_id)->delete();

        // Перенаправление на страницу личного кабинета с сообщением об успешном удалении заявки
        return redirect()->route("user_page")->withErrors("Заявка удалена", "message");
    }
}
