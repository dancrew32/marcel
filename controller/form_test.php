<?
class controller_form_test extends controller_base {
	function index() {
		$this->form = new form;

		# Start the form
		$this->form->open('#', 'post')

		->fieldset('Inputs')

		# Add straight to the form
		->add('date', new field('date', []))
		->add('datetime', new field('datetime', []))
		->add('datetime-local', new field('datetime-local', []))
		->add('file', new field('file', []))
		->add('hidden', new field('hidden', []))
		->add('image', new field('image', []))
		->add('password', new field('password', []))
		->add('email', new field('email', []))
		->add('month', new field('month', []))
		->add('number', new field('number', []))
		->add('range', new field('range', []))
		->add('reset', new field('reset', ['class' => 'btn']))
		->add('search', new field('search', []))
		->add('tel', new field('tel', []))
		->add('time', new field('time', []))
		->add('url', new field('url', [
				'prepend' => '&nbsp;http://&nbsp;', 
				'class' => ['input-medium']
			])
		)
		->add('Typeahead', new field('typeahead', [
				'data-source'    => '["one", "two", "three", "four", "five", "six", "seven", "eight"]',
				'data-items'     => 5,
				'placeholder'    => 'e.g. "one" or "three"',
				'data-minLength' => 2,
			])
		)
		->add('textarea', new field('textarea', []))

		->fieldset('Groups')

		# Group some radios!
		->group([ 'label' => 'Radio Groups' ],
			new field('radio', [
				'name' => 'foo',
				'value' => 'a',
				'label' => 'Label 1',
				'inline' => true,
			]),
			new field('radio', [
				'name' => 'foo',
				'value' => 'a',
				'checked' => true,
				'label' => 'Label 2',
				'inline' => true,
			]),
			new field('radio', [
				'name' => 'foo',
				'value' => 'a',
				'label' => 'Label 3',
				'inline' => true,
			])
		)


		# Checkbox
		->group([ 'label' => 'Checkboxes' ], 
			new field('checkbox', [
				'name' => 'tel-test[]',
				'checked' => true,
				'value' => 'wat',
				'label' => 'item 1',
				'inline' => true,
			]),
			new field('checkbox', [
				'name' => 'tel-test[]',
				'checked' => false,
				'value' => 'thing',
				'label' => 'item 2',
				'inline' => true,
			]),
			new field('checkbox', [
				'name' => 'tel-test[]',
				'checked' => true,
				'value' => 'stuff',
				'label' => 'item 3',
				'inline' => true,
			])
		)

		->fieldset('Selects')

		# Select
		->add('Select', new field('select', [
			'name' => 'test-select', 
			'value' => 'b',
			'options' => [
					'a' => 'A',
					'b' => 'B', 
					'c' => 'C',
				]
			])
		)

		# Multi-selects
		->add('Multi-Select', new field('select_multiple', [
				'name' => 'test-multi-select', 
				'value' => ['b', 'c'], 
				'options' => [
					'a' => 'A', 
					'b' => 'B', 
					'c' => 'C',
				]
			])
		)

		# Actions
		->actions(
			new field('submit', ['text' => 'Save', 'class' => ['btn', 'btn-primary']]),
			new field('button', ['type' => 'reset', 'text' => 'Reset', 'class' => 'btn'])
		);	

	}


	function modal() { }
	function popover() { }
}
