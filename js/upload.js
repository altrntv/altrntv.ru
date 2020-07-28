$(".file").change(function() {
    var file = this.files[0];
    document.getElementById("file-1").textContent=file.name;
    document.getElementById("file-1").title=file.name;
    var reader = new FileReader();
    reader.onload = (function(theFile) {
        return function(e) {
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" title="', escape(theFile.name), '" src="', e.target.result, '" />'].join('');
          	$("#output").html('');
            document.getElementById('output').insertBefore(span, null);
        };
    })(file);
    reader.readAsDataURL(file);
});