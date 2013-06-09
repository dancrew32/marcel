<? if ($items_count): ?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>
					Name	
				</th>
				<th>
					Qty.	
				</th>
				<th>
					Total	
				</th>
				<th>
					Actions
				</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($items as $item): ?>
				<tr>
					<td>
						<?= $item->product->name ?>
					</td>
					<td>
						<?= $item->quantity ?>
					</td>
					<td>
						$<?= number_format($item->product->price * $item->quantity, 2) ?>
					</td>
					<td>
						<?= html::btn($item->product->add_url(), '', 'plus-sign') ?>
						<?= html::btn($item->product->remove_url(), '', 'remove-sign') ?>
					</td>
				</tr>
			<? endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td></td>
				<td>$<?= number_format($shipping, 2) ?></td>
				<td></td>
			</tr>
			<tr>
				<td colspan=2>
					Grand	
					Total
				</td>
				<td>$<?= $grand_total ?></td>
				<td></td>
			</tr>
		</tfoot>
	</table>
<? else: ?>
	<p class="lead">
		No products in your <?= strtolower(Cart::NAME) ?>.
		<? if (auth::can(['product'])): ?>
			<br>
			<?= html::btn(route::get('Product Home'), 'Add Some Products', 'plus') ?>
		<? endif ?>
	</p>
<? endif ?>
