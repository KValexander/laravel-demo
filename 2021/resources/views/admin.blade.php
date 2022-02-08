<!-- Подключение шаблона -->
@extends("shablon")

<!-- Подключение скриптов к шаблону -->
@section("script")
<script>
	// Функция вывода полей в зависимости от выбора действия для заявки
	function change_app(e) {
		// Переменная вывода данных
		let out;
		// Проверка выбора пункта из списка
		if(e.target.value == "Решена") {
			// Запись данных в переменную
			out = `
				<form enctype="multipart/form-data" action="/app/resolve" method="POST" onsubmit="return validation_app()">
					{{ csrf_field() }}
					<p><b>Доказательство решения проблемы:</b></p>
					<input type="hidden" value="${e.target.value}" name="change">
					<input type="hidden" value="${e.target.id}" name="app_id">
					<input type="file" name="image"> <br>
					<input type="submit" value="Отправить">
				</form>
			`;
		} else if (e.target.value == "Отклонена") {
			// Запись данных в переменную
			out = `
				<form action="/app/reject" method="GET" onsubmit="return validation_app()">
					<input type="hidden" value="${e.target.value}" name="change">
					<input type="hidden" value="${e.target.id}" name="app_id">
					<p class="error" id="rejection_reason"></p>
					<textarea name="rejection_reason" placeholder="Причина отказа"></textarea>
					<input type="submit" value="Отправить">
				</form>
			`;
		}
		// Вывод данных в форму
		document.querySelector("#change_"+e.target.id).innerHTML = out;
	}

	// Функция валидация данных формы перед отправкой серверу
	function validation_app() {
		// Получение данных формы
		let form = document.forms[0];
		// Переменные обработки ошибок
		let validator = {};
		let error = '';
		// Переменная с элементами формы для заполнения ошибок валидации
		let p_err = document.querySelectorAll("p.error");

		// Валидация поля причин отказа выполнения заявки на пустоту
		if(form.elements['rejection_reason'].value == "") {
			error = "Причина отказа должна быть заполнена";
			validator.rejection_reason = error;
		}

		// Валидация поля причин отказа выполнения заявки на количество символов
		if(form.elements['rejection_reason'].value.length > 500) {
			error = "Причина отказа не должна превышать 500 символов";
			validator.rejection_reason = error;
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
</script>
@endsection

<!-- Подключение контентной части к шаблону -->
@section("content")

	<div class="heading">Новые заявки</div>

	<!-- Меню для перехода на страницу создания и удаления категорий -->
	<nav class="category"> <a href="/category">Категории</a> </nav>

	<!-- Новые заявки -->
	<div class="container">
		<!-- Если новых заявок нет -->
		@if(count($data->app_new) == 0)
			<h3>Заявки отсутствуют</h3>
		<!-- Если новые заявки есть -->
		@else
			<!-- Вывод новых заявок -->
			@foreach($data->app_new as $val)
				<div class="wrap">
					<div class="image">
						<div class="before"><img src="{{ asset('public/'. $val->path_to_image_before) }}" /></div>
					</div>
					<h3>{{ $val->title }}</h3>
					<p id="status">Статус заявки: <b>{{ $val->status }}</b></p>
					<p class="justify">Категория заявки: <b>{{ $val->category }}</b></p>
					<h4>Описание:</h4>
					<p class="justify">{{ $val->description }}</p>
					<p>
						<select onchange="change_app(event)" id="{{ $val->application_id }}">
							<option disabled selected>Выберите тип действий</option>
							<option value="Решена">Решить</option>
							<option value="Отклонена">Отклонить</option>
						</select>
					</p>
					<div id="change_{{ $val->application_id }}"></div>
					<p class="date">{{ $val->created_at }}</p>
				</div>
			@endforeach
		@endif
	</div>

	<div class="heading">Решённые заявки</div>
	<!-- Решённые заявки -->
	<div class="container">
		<!-- Если решённых заявок нет -->
		@if(count($data->app_resolved) == 0)
			<h3>Заявки отсутствуют</h3>
		<!-- Если решённых заявок есть -->
		@else
			<!-- Вывод решённых заявок -->
			@foreach($data->app_resolved as $val)
				<div class="wrap">
					<div class="image">
						<div class="before"><img src="{{ asset('public/'. $val->path_to_image_after) }}" /></div>
					</div>
					<h3>{{ $val->title }}</h3>
					<p id="status">Статус заявки: <b>{{ $val->status }}</b></p>
					<p class="justify">Категория заявки: <b>{{ $val->category }}</b></p>
					<h4>Описание:</h4>
					<p class="justify">{{ $val->description }}</p>
					<p class="date">{{ $val->created_at }}</p>
				</div>
			@endforeach
		@endif
	</div>

	<div class="heading">Отклонённые заявки</div>
	<!-- Отклонённые заявки -->
	<div class="container">
		<!-- Если отклонённых заявок нет -->
		@if(count($data->app_rejected) == 0)
			<h3>Заявки отсутствуют</h3>
		<!-- Если отклонённых заявок есть -->
		@else
			<!-- Вывод отклонённых заявок -->
			@foreach($data->app_rejected as $val)
				<div class="wrap">
					<div class="image">
						<div class="before"><img src="{{ asset('public/'. $val->path_to_image_before) }}" /></div>
					</div>
					<h3>{{ $val->title }}</h3>
					<p id="status">Статус заявки: <b>{{ $val->status }}</b></p>
					<p class="justify">Категория заявки: <b>{{ $val->category }}</b></p>
					<h4>Описание:</h4>
					<p class="justify">{{ $val->description }}</p>
					<h4>Причина отказа:</h4>
					<p class="justify">{{ $val->rejection_reason }}</p>
					<p class="date">{{ $val->created_at }}</p>
				</div>
			@endforeach
		@endif
	</div>

@endsection