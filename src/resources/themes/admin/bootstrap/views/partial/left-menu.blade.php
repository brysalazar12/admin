            <div class="collapse navbar-collapse navbar-ex1-collapse">
					<!--{!! Menu::get('leftMenu')->asUl(['class'=>'nav navbar-nav side-nav']) !!}-->
					<ul class="nav navbar-nav side-nav">
					@include(config('laravel-menu.views.bootstrap-items'), array('items' => Menu::get('leftMenu')->roots()))
					</ul>
            </div>