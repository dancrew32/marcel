<style>
	body {-webkit-transform:translate3d(0,0,0);}
	#score {
		-webkit-transform-style: preserve-3d;
		-webkit-transition: all 5s ease;
		-webkit-backface-visibility: hidden;
		-webkit-transform: rotateX(23deg) rotateY(10deg) translateZ(-100px);
	}
	.loaded #score {
		//-webkit-transform: rotateX(0) rotateY(0) translateZ(0);
	}
</style>
<div class="container" ng-controller="<?= $ctrl ?>">

	<div class="row">
		<div class="span12">
			<canvas id="score" width="501" height="500"></canvas>
		</div>
	</div>

	<div class="row">
		<div class="span12">

			<div class="major">        
				<div id="c_major" data-relative_minor="a_minor">C Major</div>
				<div id="g_major" data-relative_minor="e_minor">G Major</div>
				<div id="d_major" data-relative_minor="b_minor">D Minor</div>
				<div id="a_major" data-relative_minor="f_sharp_minor">A Major</div>
				<div id="e_major" data-relative_minor="c_sharp_minor">E Major</div>
				<div id="b_major" data-relative_minor="g_sharp_minor">B Major</div>
				<div class="same">
					<div id="f_sharp_major" data-relative_minor="d_sharp_minor">F<sup>#</sup> Major</div>
					<div id="g_flat_major" data-relative_minor="e_flat_minor">G<sup>b</sup> Major</div>
				</div>
				<div id="d_flat_major" data-relative_minor="b_flat_minor">D<sup>b</sup> Major</div>
				<div id="a_flat_major" data-relative_minor="f_minor">A<sup>b</sup> Major</div>
				<div id="e_flat_major" data-relative_minor="c_minor">E<sup>b</sup> Major</div>
				<div id="b_flat_major" data-relative_minor="g_minor">B<sup>b</sup> Major</div>
				<div id="f_major" data-relative_minor="d_minor">F Major</div>
			</div>

			<div class="minor">
				<div id="a_minor" data-relative_major="c_major">a Minor</div>
				<div id="e_minor" data-relative_major="g_major">e Minor</div>
				<div id="b_minor" data-relative_major="d_major">b Minor</div>
				<div id="f_sharp_minor" data-relative_major="">f<sup>#</sup> Minor</div>
				<div id="c_sharp_minor" data-relative_major="">c<sup>#</sup> Minor</div>
				<div id="g_sharp_minor" data-relative_major="">g<sup>#</sup> Minor</div>
				<div class="same">
					<div id="d_sharp_minor" data-relative_major="">d<sup>#</sup> Minor</div>
					<div id="e_flat_minor" data-relative_major="">e<sup>b</sup> Minor</div>
				</div>
				<div id="b_flat_minor" data-relative_major="">b<sup>b</sup> Minor</div>
				<div id="f_minor" data-relative_major="">f Minor</div>
				<div id="c_minor" data-relative_major="">c Minor</div>
				<div id="g_minor" data-relative_major="">g Minor</div>
				<div id="d_minor" data-relative_major="">d Minor</div>
			</div>

		</div>
	</div>

</div>
