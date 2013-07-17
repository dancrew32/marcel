<div class="row">
	<div class="span12">
		<h1>Filters</h1>
	</div>
</div>
<div class="row">

	<div class="span4">
		<h2>Passing</h2>
		<? foreach ($pass as $k => $v): ?>
		<h3>
			<?= $k ?>
			<code><?= filter($v['value'], $k) ? "is {$k}" : "is not {$k}" ?></code>
		</h3>
			<pre><?= h('<?= filter('.$v['display'].', "'.$k.'")
	? "is '.$k.'" 
	: "is not '.$k.'" ?>') ?></pre>
		<? endforeach ?>
	</div>

	<div class="span4">
		<h2>Failing</h2>
		<? foreach ($fail as $k => $v): ?>
		<h3>
			<?= $k ?>
			<code><?= filter($v['value'], $k) ? "is {$k}" : "is not {$k}" ?></code>
		</h3>
			<pre><?= h('<?= filter('.$v['display'].', "'.$k.'")
	? "is '.$k.'" 
	: "is not '.$k.'" ?>') ?></pre>
		<? endforeach ?>
	</div>

	<div class="span4">
		<h2>Options</h2>
		<h3>
			alnum,min,max
		</h3>
		<? pp(filter('abcdef123456', 'alnum,min:1,max:7')) ?>

		<h3>
			array,keys
		</h3>
		<code>['a' => 1, 'b' => 2, 3 => 'foo']</code>
		<code>'array,keys:int'</code>
		<? pp(filter(['a' => 1, 'b' => 2, 3 => 'foo'], 'array,keys:int')) ?>
	</div>

</div>
