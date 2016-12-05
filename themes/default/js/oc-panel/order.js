$(function(){
	var divContents = $("#print").html();
    window.document.write('<html><body>');
    window.document.write(divContents);
    window.document.write('</body></html>');
    window.print();
});