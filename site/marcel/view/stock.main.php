<div class="media row">
	<? foreach ($stocks as $stock): ?>
		<div class="media-body span4">
			<h4 class="media-heading">
				<?= $stock->Name ?>
			</h4>
			<ul>
				<? /*
					what buyers are willing to pay for it.
				*/ ?>
				<li>
					Bid: <?= $stock->BidRealtime ?>
				</li>

				<? /*
					what sellers are willing to take for it.
				*/ ?>
				<li>
					Ask: <?= $stock->AskRealtime ?> 
				</li>

				<li>
					Change: <?= $stock->ChangeRealtime ?> (<?= $stock->ChangeinPercent ?>)
				</li>
				<li>
					Day High: <?= $stock->DaysHigh ?>
				</li>
				<li>
					Day Low: <?= $stock->DaysLow ?>
				</li>

				<li>
					PE: <?= $stock->PERatio ?>
				</li>
			</ul>
			<? #pp($stock) ?>
		</div>
	<? endforeach ?>
</div>
