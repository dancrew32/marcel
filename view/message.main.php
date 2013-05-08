<? app::asset('class/json', 'js') ?>
<? app::asset('class/mustache', 'js') ?>
<? app::asset('class/websocket', 'js') ?>
<? app::asset('view/message.main', 'js') ?>
<div class="row">
	<div class="span8">
		<h1>
			Chat
		</h1>
		<div id="messages" class="well word-wrap scrolling">
			<?= r('message', 'message') ?>
		</div>
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
