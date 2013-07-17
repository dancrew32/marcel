<li class="dropdown<? echoif($in_section, ' active') ?>">
	<a href="#" 
		class="dropdown-toggle" 
		data-toggle="dropdown">
		Tests
		<b class="caret"></b>
	</a>
	<ul class="dropdown-menu">
		<li>
			<a href="<?= route::get('Message Home') ?>">Message</a>
		</li>
		<li>
			<a href="<?= route::get('Mustache Home') ?>">Mustache</a>
		</li>
		<li>
			<a href="<?= route::get('Markdown Home') ?>">Markdown</a>
		</li>
		<li>
			<a href="<?= route::get('Filter Home') ?>">Filters</a>
		</li>
		<? if (auth::can(['phone'])): ?>
			<li>
				<a href="<?= route::get('Phone Home') ?>">Call Phone</a>
			</li>
		<? endif ?>
		<? if (auth::can(['ocr'])): ?>
			<li>
				<a href="<?= route::get('OCR Home') ?>">OCR</a>
			</li>
		<? endif ?>
		<? if (auth::can(['stock'])): ?>
			<li>
				<a href="<?= route::get('Stock Home', ['symbols' => 'Z,AMD,ZNGA,SQNM']) ?>">Stocks</a>
			</li>
		<? endif ?>

	</ul>
</li>
