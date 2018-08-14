var config = {
	map: {
		"*": { 
			'ajaxscroll': 'Lof_AjaxScroll/js/script',
		}
	},
	shim: {
		'Lof_AjaxScroll/js/script': {
			'deps': ['jquery']
		},
		'ajaxscroll': {
			'deps': ['jquery']
		}
	}
};