<?
class controller_form_test extends controller_base {
	function index() {
		$this->form = new form;

		# Start the form
		$this->form->open('#', 'post');

		# Fieldset
		$this->form->head('Field test');

		# Inputs 
		$this->form->group('Inputs',
			new field('text', [
				'name' => 'test-text',
				'placeholder' => 'Text Input',
			]),
			new field('email', [
				'name' => 'test-email',
				'placeholder' => 'Email Test',
			])
		);

		# Add test
		$this->form->add('Test', new field('tel', [
			'name' => 'tel-test'
		]));

		$this->form->head('Field test 2');

		# Add test
		$this->form->add('Test', new field('tel', [
			'name' => 'tel-test'
		]));

		# Date test
		$this->form->add('Test', new field('month', [
			'name' => 'tel-test'
		]));

		# Checkbox
		$this->form->add('Test', new field('checkbox', [
			'name' => 'tel-test',
			'checked' => true,
		]));

		# Selects
		$this->form->group('Selects', 
			new field('select', [
			'name' => 'test-select', 
			'value' => 'b',
			'options' => [
					'a' => 'A',
					'b' => 'B', 
					'c' => 'C',
				]
			]),
			new field('select_multiple', [
				'name' => 'test-multi-select', 
				'value' => ['b', 'c'], 
				'options' => [
					'a' => 'A', 
					'b' => 'B', 
					'c' => 'C',
				]
			])
		);

		# Actions
		$this->form->actions(
			new field('submit', ['text' => 'Save'])
		);	

	}
}
