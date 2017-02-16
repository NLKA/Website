$(document).ready(
	function () {
	}
);

$('#anfordern').click(
	function () {
		var r = confirm("Möchtest du uns heute Abend ab 21 Uhr anrufen? Wenn du auf Okay klickst, werden wir da sein und auf deinen Anruf warten");
		if (r == true) {
    		alert("Alles klar, wir werden auf deinen Anruf heute Abend warten. Bis bald")
    		$('#topBar').slideToggle();
		}
	}
);