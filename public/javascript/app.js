$(document).on('change', '#good_category', function(){
	let $field = $(this);
	let $form = $field.closest('form');
	let data = {};
	data[$field.attr('name')] = $field.val();
	let $subcategory = $('#good_subcategory');

		$.post($form.attr('action'), data).then(function (data){
		let $input = $(data).find('#good_subcategory')
		$subcategory.replaceWith($input);

		$subcategory.val($subcategory.attr('name'));
	})
})