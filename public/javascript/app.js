$(document).on('change', '#good_category', function(){
	let $field = $(this);
	let $form = $field.closest('form');
	let data = {};
	data[$field.attr('name')] = $field.val();
	let $subcategory = $('#good_subcategory');

	$.post($form.attr('action'), data).then(function (data){
		let $input = $(data).find('#good_subcategory');
		$subcategory.replaceWith($input);

		$subcategory.val($subcategory.attr('name'));
	})
});

$(document).on('change', '#search_category', function(){
	let $field = $(this);
	let $form = $field.closest('form');
	let data = {};
	data[$field.attr('name')] = $field.val();
	let $subcategory = $('#search_subcategory');

	$.post($form.attr('action'), data).then(function (data){
		let $input = $(data).find('#search_subcategory');
		$subcategory.replaceWith($input);

		$subcategory.val($subcategory.attr('name'));
	})
});

$(document).on('change', '#good_imageFile', function() {
	alert("Your file has been succesfully uploaded");
});