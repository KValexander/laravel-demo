<!-- Подключение шаблона -->
@extends("shablon")

<!-- Подключение контентной части к шаблону -->
@section("content")
		
	<div class="heading">Добавить категорию</div>
	<!-- Добавление категории -->
	<form action="/category/add" method="GET">
		<!-- Название категории -->
		<input type="text" placeholder="Название категории" name="category">
		<!-- Кнопка отправления данных формы серверу -->
		<input type="submit" value="Добавить категорию">
	</form>

	<div class="heading">Удалить категорию</div>
	<!-- Удаление категории -->
	<form action="/category/delete" method="GET">
		<!-- Список существующих категорий -->
		<select name="cat_id">
			<!-- Вывод пунктов списка категорий -->
			<!-- Если категорий нет -->
			@if(count($data->category) == 0)
				<option checked disabled>Категории отсутствуют</option>
			<!-- Если категории есть -->
			@else
				<!-- Вывод категорий -->
				@foreach($data->category as $val)
					<option value="{{ $val->category_id }}">{{ $val->category }}</option>
				@endforeach
			@endif
		</select>
		<!-- Кнопка отправления данных формы серверу -->
		<input type="submit" value="Удалить категорию">
	</form>

@endsection