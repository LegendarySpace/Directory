
// app.controller(Name, function(){});
// ABOVE sets controller for the app and should be called during constructor
(function() {
	'use strict';
	angular
		.module('directory', [])
		.factory('api', api);

	api.$inject = ['$http', '$location'];

	function api($http, $location) {
		let page = {
			splash: [],
			grades: [],
			pills: [],
			tiles: [],
			bubble: [], // use array to preserve references
			current: null, // will hold pill
			display: 'splash',
			background: null
		};
		let caller = {grade: null, id: null};

		return {
			page: page,
			edit: edit,
			splash: get_splash,
			pill: pill_click,
			tile: tile_click,
			bubble: load_bubble,
			setCaller: setCaller
		};

		function setCaller() {
            // get the path as array
            // remove leading empty value
            let path = $location.path().split('/').splice(1);
			caller.grade = path[0];
            caller.id = path[1];
		}

		function edit(grade, id) {
			// if grade invalid: edit this obj
			// call load_form
			if (!grade) {
				load_form(caller.grade, caller.id);
			} else {
				load_form(grade, id);
			}
		}

		function get_splash() {
			let url = (caller.id) ? caller.grade + "/splash/" + caller.id : "splash";
			url = encodeURI(url); // Sanitize the string

			let success = function (response) {
				page.splash = response.data.splash;
				page.grades = response.data.sections; // TODO change to grade in controller
				page.pills = page.grades.reduce((result, value) => {
					let temp = {};
					temp.grade = value;
					temp.tile = {name: null, id: null};
					result.push(temp);
					return result;
				}, []);
				page.background = image(response.data.image);
			};

			return resources(url).then(success);
		}

		function pill_click(pill) {
			// pill struct {grade: item=>{id, name}}
			// there's only one value in pill but this gives me the key easily
			// if this is the current pill, clear the item and return to splash
			// else set current to pill and load tiles
			if (pill === page.current) {
				page.display = 'splash';
				// noinspection JSUnfilteredForInLoop
				pill.tile = null;
				return;
			} else page.current = pill;

			// load tiles
			let url = pill.grade + '/tiles';
			url = encodeURI(url);

			function success(response) {
				page.tiles.length = 0;
				page.tiles.push(response.data);
				page.display = 'tiles';
				pill.tile = null;
			}

			return resources(url).then(success);
		}

		function tile_click(tile) {
			// set pill data using current then load bubble data
			page.current.tile = tile;
			load_bubble(page.current.grade, tile.id);
		}

		function load_bubble(grade, id) {
			if (!id || !grade) return false; // failure
			let url = grade + "/bubble/" + id;
			url = encodeURI(url); // Sanitize

			// bubble is a single item array for reference preservation
			page.bubble.length = 0;

			function success(response) {
				page.bubble.push(response.data);
				page.display = 'bubble';
			}

			return resources(url).then(success);
		}

		function load_form(grade, id) {
			// TODO all of this should be in form controller.

			// grade and id of form to load
			// use api/grade/item to get base form data, create form, then display it

			// if grade and id are valid: load obj
			// else grade valid or id invalid: create obj
			if(!grade || !id) {
				//
			}
			else {
				//
			}

			// use api/grade/bubble/id to load data
			// on submit reload display to reflect changes
		}

		// Handles resource gathering
		function resources(path, data = null) {
			// Site url is already injected
			let url = 'api/';
			url += path;
			if (empty(data)) return $http.jsonp(url);
			else return $http.post(url, data);
		}

		function image(path) {
			let url = 'assets/images/';
			url += path;
			return url;
		}

	}
})();
