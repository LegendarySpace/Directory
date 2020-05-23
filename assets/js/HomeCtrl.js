(function() {
	'use strict';

	angular
		.module('directory')
		.controller('HomeCtrl', HomeCtrl);

	HomeCtrl.$inject = ['api'];
	function HomeCtrl(api) {
		var vm = this;
		vm.form = {
			display: false,
			grade: null,
			id: null
		};

		activate();

		// Definitions

		function activate() {
			// Set caller then load page
			api.setCaller();
			api.splash().then(function(response) {

				vm.splash = api.page.splash;
				vm.pills = api.page.pills;
				vm.tiles = api.page.tiles;
				vm.bubble = api.page.bubble;
				vm.edit = edit;
				vm.pillclick = click_pill;
				vm.tileclick = click_tile;
				vm.current = api.page.current;
				vm.display = api.page.display;
				vm.background = api.page.background;
			});
		}

		function edit(grade = null, id = null) {
			api.edit(grade, id);
		}

		function click_pill(pill) {
			// pill should by used to load tile
			api.pill(pill);
		}

		function click_tile(tile) {
			// call tile_click from factory
			api.tile(tile);
		}
	}
})();
