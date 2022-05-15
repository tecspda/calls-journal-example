<?php
use app\controllers\ContollerOperators;
use app\controllers\ContollerUsers;
use app\controllers\ContollerCalls;

// заполняем select operators
$operators_class = new ContollerOperators;
$operators = $operators_class::get();
$operators_html = '';
foreach($operators as $item) {
	$operators_html .= <<<HERE
	<option value="{$item->id}">{$item->name}</option>
HERE;
}


// заполняем select user
$users_class = new ContollerUsers;
$users = $users_class::get();
$users_html = '';
foreach($users as $item) {
	$users_html .= <<<HERE
	<option value="{$item->id}">{$item->name}</option>
HERE;
}


$calls = new ContollerCalls;
$arr = $calls->get();

?>

	<div class="table-responsive1">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-8">
						<h2>Журнал звонков</h2>
						<div class="input-group mb-3 w-75">
						  <input id="search_text" class="input mr-1" id="inputGroupSelect02" placeholder="Имя, тел. или оператор" />
						  <div class="input-group-append">
							<button class="input-group-text mr-1" for="inputGroupSelect02" onclick="load()">Найти</button>
							<button class="input-group-text" for="inputGroupSelect02" onclick="search_text.value = ''; load()">Показать все</button>
						  </div>
						</div>
						
					</div>
					<div class="col-sm-4 text-right">
						<button type="button" class="btn btn-info add-new" onclick="load()"><i class="fa fa-refresh"></i> Обновить</button>
						<button type="button" class="btn btn-info add-new" onclick="setAddRow()"><i class="fa fa-plus"></i> Добавить</button>
					</div>
				</div>
			</div>
			<table class="table table-bordered w-100">
				<thead>
					<tr>
						<th style="max-width: 150px;">Пользователь</th>
						<th style="max-width: 150px;">Телефон</th>
						<th>Тип</th>
						<th>Дата</th>
						<th>Длительность,<br>сек.</th>
						<th>Цена</th>
						<th>Оператор<br>пользователя</th>
						<th class="text-center">Действия</th>
					</tr>
				</thead>
				<tbody id="tBody">
				<?php foreach($arr as $call) { ?>
					<tr>
						<td style="max-width: 50px;"><?=$call->user_name?></td>
						<td style="max-width: 50px;"><?=$call->phone?></td>
						<td style="max-width: 50px; color: red"><?=($call->type == 0 ? '<span class="out">исх.</span>' : '<span class="in">вх.</span>')?></td>
						<td><?=(date('d.m.Y H:i:s', strtotime($call->date_time)))?></td>
						<td style="max-width: 100px;"><?=$call->call_duration?></td>
						<td style="max-width: 100px; text-align: right"><?=$call->price?></td>
						<td>
							<input type="text" disabled value="<?=$call->operator_name?>" data-id="<?=$call->id?>" />
						</td>

						<td class="text-center">
							<a href="javascript:void(0)" style="cursor: pointer" title="Edit" onclick="alert('Данная фунция продемонстрирована в разделах Пользователи, Операторы')"><i class="fa fa-edit"></i></a>
							
							
						
						</td>
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4"><b>Итого</b>:</td>
						<td id="totalDurringHtml"></td>
						<td id="totalPriceHtml"></td>
						<td></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>

<style>
.table input[disabled]{
	border: 0;
}
</style>

<script>

var editedItem = {id: 0, phone: '', type: 0, date: '', call_durring: 0, operator_id: 0}
var newItem = null
var calls = <?=(json_encode($arr))?>

// добавление новой строки (Оператора)
function setAddRow(){
	if(newItem === null){
		var lastElement = document.querySelector('table tbody tr:last-child');
		var row = htmlToElement(`
			<tr>
				<td style="max-width: 150px;">
					<select data-id="new">
						<?=$users_html?>
					</select>
				</td>
				<td><input style="max-width: 150px;" type="tel" data-id="new"></td>
				<td>
					<select data-id="new">
						<option value="0" selected="selected">Исх.</option>
						<option value="1" selected="selected">Вх.</option>
					</select>
				</td>
				<td><input style="max-width: 200px;" class="my-calendar" type="text" data-id="new"></td>
				<td><input style="max-width: 50px;" type="text" data-id="new" onkeydown="validNumber()"></td>
				<td></td>
				<td class="text-center">
					<select data-id="new">
						<?=$operators_html?>
					</select>
				</td>
				<td class="text-center">
					<a style="cursor: pointer;" title="сохранить" onclick="add()"><i class="fa fa-save"></i></a>
					<a style="cursor: pointer;" title="отменить" onclick="this.parentElement.parentElement.remove(); newItem = null"><i class="fa fa-trash"></i></a>
				</td>
			</tr>
		`)
		lastElement.after(row)
		newItem = {name: '', phone: 0, operator_id: 0}
		
		updatePhoneFormat()
		
	} else {
		alert('Сначала сохраните ранеее созданную строку')
	}
	
	$(function() {
	  $('.my-calendar').daterangepicker({
		timePicker: true,
		singleDatePicker: true,
		showDropdowns: true,
		minYear: 2022,
		locale: {
		  format: 'DD.MM.YYYY HH:mm'
		}
	  });
	});
}


// обновление таблицы
async function load() {
	var tmp_el = null
	// tBody.innerHTML = ''
	
	const params = { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({search_name: search_text.value}) }
	let response = await fetch("/app/api/get_calls", params)
	let result = await response.json();
	
	if(result.status == 'success'){
		calls = result.answer
		
		newBody = document.createElement('tbody')
		let selected = newBody.querySelectorAll('select')
		var row = null
		
		var i = 0
		result.answer.forEach(el => {
			let row = htmlToElement(`
				<tr>
					<td style="max-width: 100px;">${el.user_name}</td>
					<td style="max-width: 100px;">${el.phone}</td>
					<td style="max-width: 50px;">${el.type == 0 ? '<span class="out">исх.</span>' : '<span class="in">вх.</span>'}</td>
					<td>${el.date_time}</td>
					<td>${el.call_duration}</td>
					<td style="max-width: 100px;">${el.price}</td>

					<td>
						<select disabled data-id="${el.id}">
							<?=$operators_html?>
						</select>
					</td>
					<td class="text-center">
						<a href="javascript:void(0)" id="edit${el.id}" style="cursor: pointer;" title="редактировать" onclick="alert('Данная фунция продемонстрирована в разделах Пользователи, Операторы')"><i class="fa fa-edit"></i></a>
						<a href="javascript:void(0)" id="save${el.id}" style="cursor: pointer;" class="d-none" title="сохранить" onclick="save()"><i class="fa fa-save""></i></a>
					</td>
				</tr>
			`)
			row.querySelector(`option[value="${el.operator_id}"]`).setAttribute('selected', 'selected')
			
			console.log("row.querySelector('select')", row.querySelector('select'))
			newBody.appendChild(row)
			i++
		})
	}
	tBody.innerHTML = newBody.innerHTML
	console.log('Пользователи обновлены')
	newItem = null
	total()
}

async function add(){
	newItem_ = document.querySelectorAll(`*[data-id="new"]`)
	let param = {
		user_id: newItem_[0].value, 
		phone: newItem_[1].value, 
		type: newItem_[2].value, 
		date_time: newItem_[3].value, 
		call_duration: newItem_[4].value, 
		operator_id: newItem_[5].value
	}
	
	const params = { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify( param )}
	let response = await fetch("/app/api/add_calls", params)
	let result = await response.json();
	
	if(result.status == 'success'){
		load()
		newItem = null
	} else {
		console.log('status', 'error. ')
	}
}

function total() {
	totalDurringHtml.innerHTML = calls.sum('call_duration')
	totalPriceHtml.innerHTML = calls.sum('price')
	
}


function validNumber(){
	// Разрешаем: backspace, delete, tab и escape
	if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
		// Разрешаем: Ctrl+A
		(event.keyCode == 65 && event.ctrlKey === true) ||
		// Разрешаем: home, end, влево, вправо
		(event.keyCode >= 35 && event.keyCode <= 39)) {
		
		// Ничего не делаем
		return;
	} else {
		// Запрещаем все, кроме цифр на основной клавиатуре, а так же Num-клавиатуре
		if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
			event.preventDefault();
		}
	}
}

document.addEventListener('DOMContentLoaded', function(e) {
  total()
}, true);

</script>

<style>

#tBody > tr > td:nth-child(3) {
	text-align: center;
}

#tBody > tr > td:nth-child(5),
#tBody > tr > td:nth-child(6),
#tBody > tr > td:nth-child(7) {
	max-width: 100px;
}

#tBody > tr > td:nth-child(5),
#tBody > tr > td:nth-child(6) {
	text-align: right;
}

#tBody .in {
	color: green;
}

#tBody .out {
	color: red;
}

table thead th {
	vertical-align: middle !important;
	text-align: center !important;
	background-color: #c9ff7a;
}

table tfoot td {
	text-align: center;
	background-color: #c9ff7a;
	font-weight: bold;
}

</style>
