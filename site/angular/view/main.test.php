<div class="container" ng-controller="<?= $ctrl ?>">

	<div class="row">
		<div class="span4">
			<?= $form ?>
			<span>Hi {{name | currency}}</span>
			<button ng-click="foo()">Click Plz</button>
		</div>

		<div class="span4">
			<button class="btn" ng-click="isCollapsed = !isCollapsed">Toggle collapse</button>
			<div collapse="isCollapsed">
				<div class="well">Some content</div> 
			</div>
			
			<button class="btn btn-primary" 
				ng-click="modalOpen()" 
				tooltip="Don't be scared!" 
				tooltip-placement="right">Open Dialog</button>
		</div>
		
		<div class="span4">
			<datepicker ng-model="dt" show-weeks="showWeeks" starting-day="1" date-disabled="disabled(date, mode)" min="minDate" max="'2015-06-22'"></datepicker>
			<pre>Selected date is: <em>{{dt | date:'fullDate' }}</em></pre>
			<button class="btn btn-small btn-inverse" ng-click="today()">Today</button>
			<button class="btn btn-small btn-success" ng-click="toggleWeeks()">Toggle Weeks</button>
			<button class="btn btn-small btn-danger" ng-click="clear()">Clear</button>
			<button class="btn btn-small" ng-click="toggleMin()">After today restriction</button>
		</div>

	</div>

	<div class="row">
		<div class="span4">
			<div class="well well-small">
				<pagination num-pages="noOfPages" current-page="currentPage"></pagination>
			</div>
		</div>
		<div class="span4">
			<alert ng-repeat="alert in alerts" type="alert.type" close="closeAlert($index)">{{alert.msg}}</alert>
			<button ng-click="addAlert()" class="btn">Add Alert</button>
		</div>
		<div class="span4">
			<button type="button" 
				class="btn" 
				ng-model="singleModel" 
				ng-change="singleModelChange()"
				btn-checkbox btn-checkbox-true="1" 
				btn-checkbox-false="0">Toggle</button>
		</div>
	</div>

</div>
