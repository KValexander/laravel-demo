<!-- Подключение шаблона -->
@extends("shablon")

<!-- Подключение скриптов к шаблону -->
@section("script")
<script>
	// Функция открытия старого изображения
	function image_enter(e) {
		document.querySelector("#" + e.target.id + " div.before").style.display = "block";
		document.querySelector("#" + e.target.id + " div.after").style.display = "none";
	}

	// Функция скрытия старого изображения
	function image_leave(e) {
		document.querySelector("#" + e.target.id + " div.before").style.display = "none";
		document.querySelector("#" + e.target.id + " div.after").style.display = "block";
	}

	// Запрос на регистрацию
	function register() {
		// Получение данных формы
		let form = document.forms[0];
		// Переменные обработки ошибок
		let validator = {};
		let error = '';
		// Переменная с элементами формы для заполнения ошибок валидации
		let p_err = document.querySelectorAll("p.error");

		// Регулярные выражения для проверки валидации
		let fio_reg = /^[а-яА-ЯёЁ\-\ ]+$/;
		let login_reg = /^[a-zA-Z]+$/;
		let email_reg = /@/;

		// Валидация поля ФИО
		if(!fio_reg.test(form.elements["fio"].value)) {
			error = "ФИО должен содержать только кириллические буквы, дефис и пробелы";
			validator.fio = error;
		}

		// Валидация поля Логин
		if(!login_reg.test(form.elements["login"].value)) {
			error = "Логин должен содержать только латиницу";
			validator.login = error;
		}

		// Валидация поля Email
		if(!email_reg.test(form.elements["email"].value)) {
			error = "Email должно содержать валидный email формат";
			validator.email = error;
		}

		// Валидация поля Пароль
		if(form.elements["password"].value == "" ) {
			error = "Поле Пароль должно быть заполнено";
			validator.password = error;
		}

		// Подтверждение пароля
		if(form.elements["password_check"].value == "") {
			error = "Поле Подтверждение пароля должно быть заполнено";
			validator.password_check = error;
		}

		// Валидация совпадения полей Пароль и Подтверждение пароля
		if(form.elements["password"].value != form.elements["password_check"].value) {
			error = "Пароли не совпадают";
			validator.password_match = error;
		}

		// Валидация Согласия на обработку данных
		if(!document.querySelector("input[name=privacy]").checked) {
			error = "Согласие обязательно";
			validator.privacy = error;
		}

		// Проверка наличия ошибок в валидации
		if(Object.keys(validator).length != 0) {
			// Вывод ошибок валидации
			for (let i = 0; i < p_err.length; i++) {
				// Проверка на пустоту валидации
				if(validator[p_err[i].id] == undefined) validator[p_err[i].id] = "";
				// Добавление сообщения об ошибке
				p_err[i].innerHTML = validator[p_err[i].id];
				// Проверка на null
				if (document.querySelector("form#reg input[name="+ p_err[i].id +"]") == null)
					continue;
				// Добавление или удаление класса ошибки
				if(p_err[i].innerHTML != "")
					document.querySelector("form#reg input[name="+ p_err[i].id +"]").classList.add('error');
				else
					document.querySelector("form#reg input[name="+ p_err[i].id +"]").classList.remove('error');
			}
			// Отмена отправки данных серверу
			return false;
		}

		// Отправка данных серверу
		return true;
	}

</script>
@endsection

<!-- Подключение контентной части к шаблону -->
@section("content")

	<div class="heading">Последние решённые проблемы</div>
	<!-- Количество решённых заявок -->
	<nav class="count">
		<!-- Вывод количества решённых заявок -->
		<p>Количество решённых заявок: <b>{{ $data->count }}</b></p>
	</nav>
	<!-- Последние решённый проблемы -->
	<div class="container">
		<!-- Если решённых заявок нет -->
		@if(count($data->applications) == 0)
			<h3>Решённые заявки отсутствуют</h3>
		<!-- Если решённые заявки есть -->
		@else
			<!-- Вывод решённых заявок -->
			@foreach($data->applications as $val)
				<div class="wrap">
					<div class="image" onmouseenter="image_enter(event)" onmouseleave="image_leave(event)" id="image_{{ $val->application_id }}">
						<div class="before" style="display: none"><img src="{{ asset('public/'. $val->path_to_image_before) }}" /></div>
						<div class="after"><img src="{{ asset('public/'. $val->path_to_image_after) }}" /></div>
					</div>
					<h3>{{ $val->title }}</h3>
					<p class="justify">Категория заявки: <b>{{ $val->category }}</b></p>
					<p class="date">{{ $val->created_at }}</p>
				</div>
			@endforeach
		@endif
	</div>

	<div class="heading" id="register">Регистрация</div>

	<!-- Форма регистрации, метод POST -->
	<form action="/register" method="POST" onsubmit="return register();" id="reg">

		<!-- Токен формы -->
		{{ csrf_field() }}

		<!-- ФИО -->
		<p class="error" id="fio"></p>
		<input type="text" placeholder="ФИО" name="fio">

		<!-- Логин -->
		<p class="error" id="login"></p>
		<input type="text" placeholder="Логин" name="login">

		<!-- Email -->
		<p class="error" id="email"></p>
		<input type="text" placeholder="Email" name="email">

		<!-- Пароль -->
		<p class="error" id="password_match"></p>
		<p class="error" id="password"></p>
		<input type="password" placeholder="Пароль" name="password">

		<!-- Подтверждение пароля -->
		<p class="error" id="password_check"></p>
		<input type="password" placeholder="Повтор пароля" name="password_check">

		<!-- Согласие на обработку персональных данных -->
		<div class="left">
			<p class="error" id="privacy"></p>
			<input type="checkbox" name="privacy"> Согласие на обработку персональных данных
		</div>

		<!-- Отправка данных файлу регистрации -->
		<input type="submit" value="Зарегистрироваться">
	</form>

	<div class="heading" id="login">Вход</div>

	<!-- Форма авторизации, метод GET -->
	<form action="/login" method="GET">

		<!-- Логин -->
		<input type="text" placeholder="Логин" name="login">

		<!-- Пароль -->
		<input type="password" placeholder="Пароль" name="password">

		<!-- Кнопка входа -->
		<input type="submit" value="Войти">
		
	</form>

@endsection