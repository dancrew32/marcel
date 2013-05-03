<div class="row">
	<? /*
	<div class="span4">
		<h2>
			Send Email
		</h2>
	</div>
	<div class="span4">
		<h2>
			Send Text
		</h2>
	</div>
	*/ ?>
	<div class="span8">
		<h1>
			Chat
		</h1>
		<div id="messages" class="well word-wrap"></div>
		<?= r('message', 'chat_form') ?>
	</div>
	<div class="span4">
		<h2>
			Roster	
		</h2>
		<ul class="nav nav-tabs nav-stacked">
			<li>
				<a href="#" id="notifications-enable">Enable Notifications</a>
			</li>
		</ul>
	</div>
</div>
