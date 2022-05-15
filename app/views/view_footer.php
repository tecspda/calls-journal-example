
<script src="/assets/js/imask.min.js"></script>

<script>
// создаем DOM элемент из HTML
function htmlToElement(html) {
  var template = document.createElement('template');
  html = html.trim(); // Never return a text node of whitespace as the result
  template.innerHTML = html;
  return template.content.firstChild;
}

// суммируем колонку массива
Array.prototype.sum = function(columnName){
  var s = 0;
  for (i = 0; i < this.length; i++){
    s += Number(this[i][columnName])
  }
  return s.toFixed(2)
}

function inputPhoneFormat(el) {
  var maskOptions = {
    mask: '+7(000)000-00-00',
    lazy: false,
  };
  new IMask(el, maskOptions);
}

function updatePhoneFormat() {
  var phone_inputs = document.querySelectorAll('input[type="tel"]');
  var maskOptions = {
    mask: '+7 (000) 000-00-00',
    lazy: false,
  };
  phone_inputs.forEach((el) => {
    new IMask(el, maskOptions);
  });

}

</script>

</div>

<div class="my-5">&nbsp;</div>

</body>
</html>