<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Demo</title>

	<!-- Подключение стилей -->
	<link rel="stylesheet" href="{{ asset('public/css/style.css') }}">

	<!-- Точка для подключения скриптов -->
	@yield("script")

</head>
<body>

	<!-- Logo сайта -->
	<div class="logo">
		<div class="content">
			<div class="img">
				<img src="{{ asset('public/logo/logo.png') }}" alt="">
			</div>
			<div class="text">
				<h1>Сделаем лучше вместе!</h1>
				<h3>Тут какой-то текст как дополнение</h3>
			</div>
		</div>
	</div>

	<!-- Меню сайта -->
	<header>
		<div class="content">
			<nav>
				<!-- Если переменная роли существует -->
				@if(isset($role))
					<!-- Если пользователь гость -->
					@if($role == "guest")
						<a href="/#login">Войти</a> | <!-- Переместится к форме авторизации --> 
						<a href="/#register">Регистрация</a> <!-- Переместится к форме регистрации --> 
					@endif
					<!-- Если пользователь авторизован -->
					@if($role != "guest")
						<a href="/">Главная</a> | <!-- Главная страница --> 
						<!-- Если пользователь администратор -->
						@if($role == "admin")
							<a href="/admin">Заявки</a> | <!-- Страница модерирования -->
						@endif
						<a href="/user">Личный кабинет</a> | <!-- Страница личного кабинет --> 
						<a href="/logout">Выйти</a> <!-- Выход из авторизации --> 
					@endif
				@endif
			</nav>
		</div>
	</header>

	<!-- Вывод сообщения -->
	<div class="message">{{ $errors->message->first() }}</div>

	<!-- Контентная часть сайта -->
	<main>
		<div class="content">
			<!-- Точка для подключения контента -->
			@yield("content")
		</div>
	</main>

	<!-- Подвал сайта -->
	<footer>
		<div class="content"></div>
	</footer>

</body>
</html>