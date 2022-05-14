<?php
use app\controllers\ContollerOperators;

$operators = new ContollerOperators;
$arr = $operators->get();

?>

<div class="container-lg">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-8">
						<h2>Операторы</h2>
					</div>
					<div class="col-sm-4 text-right">
						<button type="button" class="btn btn-info add-new" onclick="load()"><i class="fa fa-refresh"></i> Обновить</button>
						<button type="button" class="btn btn-info add-new" onclick="setAddRow()"><i class="fa fa-plus"></i> Добавить</button>
					</div>
				</div>
			</div>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Название</th>
						<th>Цена за минуту</th>
						<th class="text-center">Действия</th>
					</tr>
				</thead>
				<tbody id="tBody">
				<?php foreach($arr as $operator) { ?>
					<tr>
						<td><input type="text" disabled value="<?=$operator->name?>" data-id="<?=$operator->id?>" /></td>
						<td><input type="text" disabled value="<?=$operator->price_per_minute?>" data-id="<?=$operator->id?>" /></td>
						<td class="text-center">
							<a href="javascript:void(0)" id="edit<?=$operator->id?>" style="cursor: pointer" title="Edit" onclick="setEditedRow(<?=$operator->id?>,'edit')"><i class="fa fa-edit"></i></a>
							
							<a href="javascript:void(0)" id="save<?=$operator->id?>" style="cursor: pointer;" class="d-none" title="сохранить" onclick="setEditedRow(<?=$operator->id?>, 'save')"><i class="fa fa-save""></i></a>

						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<style>
.table input[disabled]{
	border: 0;
}
</style>

<script>

var editedItem = {id: 0, name: '', price_per_minute: 0}
var newItem = null

// добавление новой строки (Оператора)
function setAddRow(){
	if(newItem === null){
		var lastElement = document.querySelector('table tbody tr:last-child');
		var row = htmlToElement(`
			<tr>
				<td><input type="text" data-id="new" onchange="newItem.name = this.value"></td>
				<td><input type="text" data-id="new" onchange="newItem.price_per_minute = this.value"></td>
				<td class="text-center">
					<a style="cursor: pointer;" title="сохранить" onclick="add()"><i class="fa fa-save"></i></a>
					<a style="cursor: pointer;" title="отменить" onclick="this.parentElement.parentElement.remove(); newItem = null"><i class="fa fa-trash"></i></a>
				</td>
			</tr>
		`)
		lastElement.after(row)
		newItem = {name: '', price_per_minute: 0}
	} else {
		alert('Сначала сохраните ранеее созданную строку')
	}
}

// включаем режим редактировани или сохранения
function setEditedRow(id, ds){
	editedArr = document.querySelectorAll(`input[data-id="${id}"]`)
	Object.assign(editedItem, {id: id, name: editedArr[0].value, price_per_minute: editedArr[1].value})
	if(ds == 'save') { save(); }
	
	document.getElementById(`edit${id}`).classList.toggle('d-none')
	document.getElementById(`save${id}`).classList.toggle('d-none')
	var rows = document.querySelectorAll(`.table input[data-id="${id}"]`)
	rows.forEach(el => {
		el.disabled = (el.dataset.id === id.toString() ? (ds === 'edit' ? false : true) : true)
	})
}

// обновление таблицы
async function load() {
	const params = { method: "POST", headers: { "Content-Type": "application/json" }, body: null }
	let response = await fetch("/app/api/get_operators", params)
	let result = await response.json();
	
	if(result.status == 'success'){
		newBody = document.createElement('tbody')
		result.answer.forEach(el => {
			let row = htmlToElement(`
				<tr>
					<td><input type="text" value="${el.name}" disabled data-id="${el.id}"></td>
					<td><input type="text" value="${el.price_per_minute}" disabled data-id="${el.id}"></td>
					<td class="text-center">
						<a href="javascript:void(0)" id="edit${el.id}" style="cursor: pointer" title="Edit" onclick="setEditedRow(${el.id},'edit')"><i class="fa fa-edit"></i></a>
						
						<a href="javascript:void(0)" id="save${el.id}" style="cursor: pointer;" class="d-none" title="сохранить" onclick="setEditedRow(${el.id}, 'save')"><i class="fa fa-save""></i></a>
					</td>
				</tr>
			`)
			newBody.appendChild(row)
		})
		tBody.innerHTML = newBody.innerHTML
		console.log('Операторыы обновлены')
	}
	newItem = null	
}

async function add(){
	const params = { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify( newItem )}
	let response = await fetch("/app/api/add_operators", params)
	let result = await response.json();
	
	if(result.status == 'success'){
		load()
		newItem = null
	} else {
		console.log('status', 'error. ')
	}
}

async function save(){
	const oldVal = editedItem
	console.log('editedItem',editedItem)

	const params = { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify( editedItem )}
	let response = await fetch("/app/api/save_operators", params)
	let result = await response.json();
	
	if(result.status == 'success'){
	console.log('status', 'success. ' + result.description)
	} else if(result.status == 'error'){
		editedArr = document.querySelectorAll(`input[data-id="${oldVal.id}"]`)
		Object.assign(editedArr = oldVal)
		editedArr[0].value = oldVal.name
		editedArr[1].value = oldVal.price_per_minute
		console.log('status', 'error. ' + result.description)
	}
}

</script>