<!-- Подключение шаблона -->
@extends("shablon")

<!-- Подключение скриптов к шаблону -->
@section("script")
<script>
	// Функция валидации данных формы
	function app_add() {
		// Получение данных формы
		let form = document.forms[0];
		// Переменные обработки ошибок
		let validator = {};
		let error = '';
		// Переменная с элементами формы для заполнения ошибок валидации
		let p_err = document.querySelectorAll("p.error");

		// Проверка поля Название заявки
		if(form.elements["title"].value == "") {
			error = "Введите Название заявки";
			validator.title = error;
		}

		// Проверка поля Название заявки по символам
		if(form.elements["title"].value.length >= 50) {
			error = "Название не должно превышать 50 символов";
			validator.title = error;
		}

		// Проверка поля Описание заявки
		if(form.elements["description"].value == "") {
			error = "Описание должно быть заполнено";
			validator.description = error;
		}

		// Проверка поля Описание заявки по символам
		if(form.elements["description"].value.length >= 500) {
			error = "Описание не должно превышать 500 символов";
			validator.description = error;
		}

		// Проверка списка Категория заявки
		if(form.elements["category"].value == "") {
			error = "Категория должна быть выбрана";
			validator.category = error;
		}

		// Проверка наличия ошибок в валидации
		if(Object.keys(validator).length != 0) {
			// Вывод ошибок валидации
			for (let i = 0; i < p_err.length; i++) {
				// Проверка на пустоту валидации
				if(validator[p_err[i].id] == undefined) validator[p_err[i].id] = "";
				// Добавление сообщения об ошибке
				p_err[i].innerHTML = validator[p_err[i].id];
			}
			// Отмена отправки данных серверу
			return false;
		}

		// Отправка данных серверу
		return true;
	}

	// Функция фильтрации заявок по статусу
	function app_filtration(status) {
		// Получение всех заявок
		let app = document.querySelectorAll(".wrap");

		// Если заявки отсутствуют
		if(app.length == 0) return;

		// Если переданный статус отсутствует
		if(status == undefined) {
			// Отображение всех заявок
			for(let i = 0; i < app.length; i++) {
				// Отображение заявки
				app[i].style.display = "block";
			}
			// Очищение блока сообщения
			document.querySelector("#mess").innerHTML = "";
			// Завершение выполнения функции
			return;
		}

		// Получение текста статуса у всех заявок
		let stat = document.querySelectorAll(".wrap #status b");
		// Фильтрация по статусу
		for(let i = 0; i < stat.length; i++) {
			// Проверка на статус
			if(stat[i].innerHTML == status) {
				// Отображение нужного блока
				app[i].style.display = "block";
				// Переход на следующую итерацию цикла
				continue;
			}
			// Скрытие заявки в случае несоответствия фильтрации
			app[i].style.display = "none";
		}

		// Счётчик
		let count = 0;
		// Смотрим сколько блоков скрыто
		for(let i = 0; i < app.length; i++) {
			if(app[i].style.display == "none") {
				count++;
			}
		}

		// Если все блоки скрыты, значит фильтрация не нашла заявок
		if(app.length == count) {
			// Выводим сообщение об отсутствии заявок фильтрации
			document.querySelector("#mess").innerHTML = "<h3 id='filtr'>Фильтрация ничего не нашла</h3>";
		// В ином случае удалем сообщение
		} else {
			document.querySelector("#mess").innerHTML = "";
		}
	}

	// Функция вывода диалогового окна на проверку действительно ли пользователь хочет удалить заявку
	function app_delete() {
		// Переменная выбора
		let result = confirm("Вы действительно хотите удалить заявку?");
		// Возвращение результата
		return result;
	}
</script>
@endsection

<!-- Подключение контентной части к шаблону -->
@section("content")

	<div class="heading">Ваши заявки</div>
	<!-- Вызов фильтрации заявок по статусу -->
	<nav class="filtration">
		<a onclick="app_filtration('Новая')">Новые</a> |
		<a onclick="app_filtration('Решена')">Решённые</a> |
		<a onclick="app_filtration('Отклонена')">Отклонённые</a> |
		<a onclick="app_filtration()">Все</a>
	</nav>
	<!-- Заявки пользователя -->
	<div class="container">
		<!-- Если заявок нет -->
		@if(count($data->applications) == 0)
			<h3>Заявки отсутствуют</h3>
		<!-- Если заявки есть -->
		@else
			<!-- Вывод заявок -->
			@foreach($data->applications as $val)
				<div class="wrap">
					<h3>{{ $val->title }}</h3>
					<p id="status">Статус заявки: <b>{{ $val->status }}</b></p>
					<p class="justify">Категория заявки: <b>{{ $val->category }}</b></p>
					<h4>Описание:</h4>
					<p class="justify">{{ $val->description }}</p>
					<p class="del"><a href="/app/delete?app_id={{ $val->application_id }}" onclick="return app_delete()">Удалить заявку</a></p>
					<p class="date">{{ $val->created_at }}</p>
				</div>
			@endforeach
		@endif
		<!-- Блок вывода сообщения об отсутствии заявок фильтрации -->
		<div id="mess"></div>
	</div>

	<div class="heading">Создать заявку</div>

	<!-- Форма создания заявки -->
	<form enctype="multipart/form-data" action="/app/add" method="POST" onsubmit="return app_add();">
		
		<!-- Токен формы -->
		{{ csrf_field() }}

		<!-- Название заявки -->
		<p class="error" id="title"></p>
		<input type="text" placeholder="Название" name="title">

		<!-- Описание заявки -->
		<p class="error" id="description"></p>
		<textarea name="description" placeholder="Описание"></textarea>

		<!-- Категория заявки -->
		<p class="error" id="category"></p>
		<select name="category">
			<!-- Будет выводится с помощью php из базы-->
			<option value="" disabled selected>Категория заявки</option>
			<!-- Если категорий есть -->
			@if(count($data->category) != 0)
				<!-- Вывод категорий -->
				@foreach($data->category as $val)
					<option value="{{ $val->category }}">{{ $val->category }}</option>
				@endforeach
			@endif
		</select>

		<!-- Фотография заявки -->
		<div class="left">
			<p>Фотография проблемы</p>
			<input type="file" name="image">
		</div>

		<!-- Кнопка отправки данных скрипту -->
		<input type="submit" value="Создать заявку">
	</form>

@endsection