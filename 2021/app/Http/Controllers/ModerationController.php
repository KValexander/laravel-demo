<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModerationController extends Controller
{
    // Функция вывода данных на страницу модерации
    public function admin_page(Request $request) {
    	// Если пользователь авторизован
    	if(Auth::check()) {
    		// Получение данных пользователя
    		$user = Auth::user();
    		// Получение роли пользователя
    		$role = $user->role;
    		// Получение id пользователя
    		$user_id = $user->id;
    		// Если пользователь не является администратором
    		if($role != "admin") {
	    		// Перенаправление на главную страницу с сообщением об ошибке
	    		return redirect()->route("main_page")->withErrors("Отказ в правах доступа", "message");
    		}
    	// Если пользователь не авторизован
    	} else {
    		// Перенаправление на главную страницу с сообщением об ошибке
    		return redirect()->route("main_page")->withErrors("Вы не авторизованы", "message");
    	}

    	// Получение новых заявок
    	$app_new = DB::table("applications")->where("status", "=", "Новая")->orderBy("created_at", "DESC")->get();
    	// Получение решённых заявок
    	$app_resolved = DB::table("applications")->where("status", "=", "Решена")->orderBy("created_at", "DESC")->get();
    	// Получение отклонённых заявок
    	$app_rejected = DB::table("applications")->where("status", "=", "Отклонена")->orderBy("created_at", "DESC")->get();

    	// Составление объекта
    	$data = (object)[
    		"app_new" => $app_new,
    		"app_resolved" => $app_resolved,
    		"app_rejected" => $app_rejected,
    	];

    	// Возращение данных
    	return view("admin", ["data" => $data, "role" => $role]);
    }

    // Функция решения заявки
    public function app_resolve(Request $request) {
        // Если пользователь не авторизован
        if(!Auth::check())
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect()->route("main_page")->withErrors("Вы не авторизованы", "message");
        // Получение данных пользователя
        $user = Auth::user();
        // Если пользователь не администратор
        if($user->role != "admin")
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect()->route("main_page")->withErrors("Отказ в правах доступа", "message");

    	// Валидация
    	$validator = Validator::make($request->all(), [
    		"image" => "required|mimes:jpeg,jpg,bmp,png|max:10240"
    	]);
    	// В случае наличия ошибок валидации
    	if($validator->fails()) {
    		return redirect()->route("admin_page")->withErrors("Файл должен быть изображением с расширениями jpg, jpeg, png, bmp и весить не более 10мб", "message");
    	}

        // Название изображения
        $image_name = "1_". time() ."_". rand() .".". $request->file("image")->extension();
        // Перемещение изображение на сервер
        $request->file("image")->move(public_path("images/after/"), $image_name);
        // Путь до изображения для добавлени в базу
        $path = "images/after/". $image_name;

        // Обновление данных в базе
        DB::table("applications")->where("application_id", "=", $request->input("app_id"))->update([
        	"status" => "Решена",
        	"path_to_image_after" => $path
        ]);

        // Перенправлание на страницу модерации с сообщением об успешном изменении статуса заявки
        return redirect()->route("admin_page")->withErrors("Статус заявки изменён на \"Решена\"", "message");
    }

    // Функция отклонения заявки
    public function app_reject(Request $request) {
        // Если пользователь не авторизован
        if(!Auth::check())
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect()->route("main_page")->withErrors("Вы не авторизованы", "message");
        // Получение данных пользователя
        $user = Auth::user();
        // Если пользователь не администратор
        if($user->role != "admin")
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect()->route("main_page")->withErrors("Отказ в правах доступа", "message");

        // Обновление данных в базе
        DB::table("applications")->where("application_id", "=", $request->input("app_id"))->update([
        	"status" => "Отклонена",
        	"rejection_reason" => $request->input("rejection_reason")
        ]);

        // Перенправлание на страницу модерации с сообщением об успешном изменении статуса заявки
        return redirect()->route("admin_page")->withErrors("Статус заявки изменён на \"Отклонена\"");
    }

    // Функция вывода данных на страницу категорий
    public function category_page(Request $request) {
    	// Если пользователь авторизован
    	if(Auth::check()) {
    		// Получение данных пользователя
    		$user = Auth::user();
    		// Получение роли пользователя
    		$role = $user->role;
    		// Получение id пользователя
    		$user_id = $user->id;
    		// Если пользователь не является администратором
    		if($role != "admin") {
	    		// Перенаправление на главную страницу с сообщением об ошибке
	    		return redirect()->route("main_page")->withErrors("Отказ в правах доступа", "message");
    		}
    	// Если пользователь не авторизован
    	} else {
    		// Перенаправление на главную страницу с сообщением об ошибке
    		return redirect()->route("main_page")->withErrors("Вы не авторизованы", "message");
    	}

    	// Получение всех категорий
    	$cat = DB::table("category")->get();

    	// Составление объекта
    	$data = (object)[
    		"category" => $cat
    	];

    	// Возвращение данных
    	return view("category", ["data" => $data, "role" => $role]);
    }

    // Функция добавления категории
    public function category_add(Request $request) {
        // Если пользователь не авторизован
        if(!Auth::check())
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect()->route("main_page")->withErrors("Вы не авторизованы", "message");
        // Получение данных пользователя
        $user = Auth::user();
        // Если пользователь не администратор
        if($user->role != "admin")
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect()->route("main_page")->withErrors("Отказ в правах доступа", "message");
        
        // Валидация
        $validator = Validator::make($request->all(), [
        	"category" => "required|string"
        ]);
    	// В случае наличия ошибок валидации
    	if($validator->fails()) {
        // Перенаправление на страницу категорий с сообщением об ошибке
    		return redirect()->route("category_page")->withErrors("Введите название категории", "message");
    	}
        // Добавление категории
        DB::table("category")->insert([
        	"category" => $request->input("category")
        ]);

        // Перенаправление на страницу категорий с сообщением об успешном добавлением категории
        return redirect()->route("category_page")->withErrors("Категория добавлена", "message");
    }

    // Функция удаления категории
    public function category_delete(Request $request) {
        // Если пользователь не авторизован
        if(!Auth::check())
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect()->route("main_page")->withErrors("Вы не авторизованы", "message");
        // Получение данных пользователя
        $user = Auth::user();
        // Если пользователь не администратор
        if($user->role != "admin")
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect()->route("main_page")->withErrors("Отказ в правах доступа", "message");

        // Получение id категории
        $cat_id = $request->input("cat_id");
        // Получение категории
        $cat = DB::table("category")->where("category_id", "=", $cat_id)->first();
        // Удаление всех заявок с этой категорией
        $app = DB::table("applications")->where("category", "=", $cat->category)->delete();
        // Удаление категории
        $cat = DB::table("category")->where("category_id", "=", $cat_id)->delete();

        // Перенаправление на страницу категорий с сообщением об успешном удалении категории
        return redirect()->route("category_page")->withErrors("Категория удалена", "message");
    }
}
