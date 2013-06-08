<li class="dropdown<? echoif($in_section, ' active') ?>">
	<a href="#" 
		class="dropdown-toggle" 
		data-toggle="dropdown">
		Tests
		<b class="caret"></b>
	</a>
	<ul class="dropdown-menu">
		<li>
			<a href="<?= app::get_path('Message Home') ?>">Message</a>
		</li>
		<li>
			<a href="<?= app::get_path('Mustache Home') ?>">Mustache</a>
		</li>
		<li>
			<a href="<?= app::get_path('Markdown Home') ?>">Markdown</a>
		</li>
		<? if (auth::can(['phone'])): ?>
			<li>
				<a href="<?= app::get_path('Phone Home') ?>">Call Phone</a>
			</li>
		<? endif ?>
		<? if (auth::can(['ocr'])): ?>
			<li>
				<a href="<?= app::get_path('OCR Home') ?>">OCR</a>
			</li>
		<? endif ?>
		<? if (auth::can(['stock'])): ?>
			<li>
				<a href="<?= app::get_path('Stock Home') ?>">Stocks</a>
			</li>
		<? endif ?>
	</ul>
</li>
