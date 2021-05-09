<?php

use Illuminate\Support\Facades\Route;

// Подключение контроллеров
use App\Http\Controllers\MainController; // Главный контроллер
use App\Http\Controllers\AuthController; // Контроллер авторизации
use App\Http\Controllers\UserController; // Контроллер работы с пользователем
use App\Http\Controllers\ModerationController; // Контроллер модерации

// Возможности обычного пользователя
// ==========================================
// Главная страница
Route::get("/", [MainController::class, "main_page"])->name("main_page");
// Регистрация
Route::post("/register", [AuthController::class, "register"])->name("register");
// Авторизация
Route::get("/login", [AuthController::class, "login"])->name("login");

// Возможности авторизованного пользователя
// ==========================================
// Личный кабинет
Route::get("/user", [UserController::class, "user_page"])->name("user_page");
// Добавить заявку
Route::post("/app/add", [UserController::class, "app_add"])->name("app_add");
// Удалить заявку
Route::get("/app/delete", [UserController::class, "app_delete"])->name("app_delete");
// Выход из авторизации
Route::get("/logout", [AuthController::class, "logout"])->name("logout");

// Возможности администратора
// ==========================================
// Страница модерации
Route::get("/admin", [ModerationController::class, "admin_page"])->name("admin_page");
// Решить заявку
Route::post("/app/resolve", [ModerationController::class, "app_resolve"])->name("app_resolve");
// Отказать заявке
Route::get("/app/reject", [ModerationController::class, "app_reject"])->name("app_reject");
// Страница категорий
Route::get("/category", [ModerationController::class, "category_page"])->name("category_page");
// Добавление категории
Route::get("/category/add", [ModerationController::class, "category_add"])->name("category_add");
// Удаление категории
Route::get("/category/delete", [ModerationController::class, "category_delete"])->name("category_delete");
