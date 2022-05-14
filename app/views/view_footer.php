
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

</script>

</div>
</body>
</html>