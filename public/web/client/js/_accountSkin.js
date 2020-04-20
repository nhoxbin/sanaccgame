$.get('load/skin', function (data) {
	$('input[data-filter="tim-theo-trang-phuc"]').typeahead({
		source: data,
	    updater: function (item) {
	    	skin_str = item;
	        return item;
	    }
	});
}, "json");
$.get('load/champion', function (data) {
	$('input[data-filter="tim-theo-tuong"]').typeahead({
		source: data,
	    updater: function (item) {
	    	champ_str = item;
	        return item;
	    }
	});
	$('input[data-filter="tim-theo-thong-thao"]').typeahead({
		source: data,
	    updater: function (item) {
	    	master_str = item;
	        return item;
	    }
	});
}, "json");
