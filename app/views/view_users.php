<?php
use app\controllers\ContollerOperators;
use app\controllers\ContollerUsers;

$operators_class = new ContollerOperators;
$operators = $operators_class::get();
$operators_html = '';
foreach($operators as $item) {
	$operators_html .= <<<HERE
	<option value="{$item->id}">{$item->name}</option>
HERE;
}

$users = new ContollerUsers;
$arr = $users->get();

?>

<div class="container-lg">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-8">
						<h2>Пользователи</h2>
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
						<th>ФИО</th>
						<th>Телефон</th>
						<th>Оператор</th>
						<th class="text-center">Действия</th>
					</tr>
				</thead>
				<tbody id="tBody">
				<?php foreach($arr as $user) { ?>
					<tr>
						<td><input type="text" disabled value="<?=$user->name?>" data-id="<?=$user->id?>" /></td>
						<td><input type="text" disabled value="<?=$user->phone?>" data-id="<?=$user->id?>" /></td>
						<td>
							<select disabled data-id="<?=$user->id?>">
								<?php
									foreach($operators as $item) {
										$selected = $item->id == $user->operator_id ? 'selected' : '';
										echo '<option value="'.$item->id.'" '.$selected.'>'.$item->name.'</option>';
									}
								?>
							</select>
						</td>
						<td class="text-center">
							<a href="javascript:void(0)" id="edit<?=$user->id?>" style="cursor: pointer" title="Edit" onclick="setEditedRow(<?=$user->id?>,'edit')"><i class="fa fa-edit"></i></a>
							
							<a href="javascript:void(0)" id="save<?=$user->id?>" style="cursor: pointer;" class="d-none" title="сохранить" onclick="setEditedRow(<?=$user->id?>, 'save')"><i class="fa fa-save""></i></a>

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

var editedItem = {id: 0, name: '', phone: 0}
var newItem = null

// добавление новой строки (Оператора)
function setAddRow(){
	if(newItem === null){
		var lastElement = document.querySelector('table tbody tr:last-child');
		var row = htmlToElement(`
			<tr>
				<td><input type="text" data-id="new" onchange="newItem.name = this.value"></td>
				<td><input type="text" data-id="new" onchange="newItem.phone = this.value"></td>
				<td class="text-center">
					<select data-id="new">
						<?=$operators_html?>
					</select>
				<td class="text-center">
					<a style="cursor: pointer;" title="сохранить" onclick="add()"><i class="fa fa-save"></i></a>
					<a style="cursor: pointer;" title="отменить" onclick="this.parentElement.parentElement.remove(); newItem = null"><i class="fa fa-trash"></i></a>
				</td>
			</tr>
		`)
		lastElement.after(row)
		newItem = {name: '', phone: 0, operator_id: 0}
	} else {
		alert('Сначала сохраните ранеее созданную строку')
	}
}

// включаем режим редактировани или сохранения
function setEditedRow(id, ds){
	editedArr = document.querySelectorAll(`*[data-id="${id}"]`)
	Object.assign(editedItem, {id: id, name: editedArr[0].value, phone: editedArr[1].value, operator_id: editedArr[2].value})
	if(ds == 'save') { save(); }
	
	document.getElementById(`edit${id}`).classList.toggle('d-none')
	document.getElementById(`save${id}`).classList.toggle('d-none')
	var rows = document.querySelectorAll(`.table *[data-id="${id}"]`)
	rows.forEach(el => {
		el.disabled = (el.dataset.id === id.toString() ? (ds === 'edit' ? false : true) : true)
	})
}

// обновление таблицы
async function load() {
	var tmp_el = null
	// tBody.innerHTML = ''
	
	const params = { method: "POST", headers: { "Content-Type": "application/json" }, body: null }
	let response = await fetch("/app/api/get_users", params)
	let result = await response.json();
	
	if(result.status == 'success'){
		newBody = document.createElement('tbody')
		let selected = newBody.querySelectorAll('select')
		var row = null
		
		var i = 0
		result.answer.forEach(el => {
			let row = htmlToElement(`
				<tr>
					<td><input type="text" value="${el.name}" disabled data-id="${el.id}"></td>
					<td><input type="text" value="${el.phone}" disabled data-id="${el.id}"></td>
					<td>
						<select disabled data-id="${el.id}">
							<?=$operators_html?>
						</select>
					<td class="text-center">
						<a href="javascript:void(0)" id="edit${el.id}" style="cursor: pointer;" title="редактировать" onclick="setEditedRow(${el.id},'edit')"><i class="fa fa-edit"></i></a>
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
}

async function add(){
	newItem = document.querySelectorAll(`*[data-id="new"]`)
	Object.assign(newItem, {name: newItem[0].value, phone: newItem[1].value, operator_id: newItem[2].value})
	
	const params = { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify( newItem )}
	let response = await fetch("/app/api/add_users", params)
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

	editedArr = document.querySelectorAll(`*[data-id="${editedItem.id}"]`)
	Object.assign(editedItem, {id: editedItem.id, name: editedArr[0].value, phone: editedArr[1].value, operator_id: editedArr[2].value})
	
	console.log('editedItem',editedItem)

	const params = { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify( editedItem )}
	let response = await fetch("/app/api/save_users", params)
	let result = await response.json();
	
	if(result.status == 'success'){
	console.log('status', 'success. ' + result.description)
	load()
	} else if(result.status == 'error'){
		editedArr = document.querySelectorAll(`*[data-id="${oldVal.id}"]`)
		Object.assign(editedArr = oldVal)
		editedArr[0].value = oldVal.name
		editedArr[1].value = oldVal.phone
		editedArr[2].value = oldVal.operator_id
		console.log('status', 'error. ' + result.description)
	}
}

</script>