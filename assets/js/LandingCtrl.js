
app.controller("LandingCtrl", function($scope, $http, $location, PageData) {
    $http.jsonp(PageData.getServer('splash'))
        .then(function (response) {
            // response.data = [splash[], sections[], admin]
            $scope.sections = response.data.sections;
            $scope.splash = response.data.splash;
            $scope.splash.admin = response.data.admin;
            // query content and set background url to img
        }, function (response) {
            $scope.sections = ["Tower", "Event"];
            $scope.splash = {message:"Welcome Message", sub:"Sub Message"};
            $scope.splash.admin = false;
            // Execute on error
        });
    $scope.background = 'Tower.jpg';
    $scope.form = {display: false, content: null, type: null};
    $scope.selection = []; // Tiles in Selected [{section, choice}]
    $scope.currentSection = null; // Section to display [null, Tower, Event+]
    $scope.tiles = []; // Items in tile section [{},{}]
    $scope.currentChoice = {}; // Details in bubble
    $scope.sDisplay = null; // DisplayArea [Null, Tiles, Bubble]
    $scope.edit = {target:null, value: null}; // Editing details

    $scope.loadTiles = function(section) {
        // Retrieve tile data
        // TODO after looking up a tile set store it so the call isn't repeated incessantly
        // if(!$scope.tile[section]) server call
        // else $scope.tiles = $scope.tile[section]
        $scope.tiles = [];
        let url = PageData.getServer(section.toLowerCase() + '/tiles');
        $http.jsonp(url).then(function (response) {
                $scope.tiles = response.data.tiles;
            }, function (response) {
                switch (section) {
                    case 'Tower':
                        $scope.tiles = [{name:'Forum 900', aux:'900 2nd Ave'},{name:'AT&T', aux:'901 Marquette Ave'},
                            {name:'121 Tower', aux:'121 S 8th St'},{name:'Wayne Tower', aux:'84th Gotham Ave'}];
                        break;
                    case 'Event':
                        $scope.tiles = [{name:'ThermoDynamic', aux:'Dynamic'}];
                        break;
                }
            });
    }; // Handles retrieval of tile data
    $scope.selectedClick = function(tile) {
        // if not in tiles switch to tiles
        if($scope.sDisplay !== "Tiles") {
            $scope.sDisplay = "Tiles";
        } else if($scope.currentSection === tile.section) {
            // is in tiles and tile is current section remove tile from selection
            let index = $scope.selection.findIndex(value => value.section === $scope.currentSection);
            if(index > -1) $scope.selection.splice(index,1);
            $scope.currentSection = '';
            $scope.currentChoice = {};

            // switch to null
            $scope.sDisplay = null;
            return;
        }

        // if already in section don't reload tiles
        if($scope.currentSection !== tile.section) {
            $scope.currentSection = tile.section;

            $scope.loadTiles(tile.section);
        }
        $scope.currentChoice = {name: tile.choice};
        tile.choice = null;
    }; // click function from Selected
    $scope.chooseTile = function(tile) {
        // update selected
        let index = $scope.selection.findIndex(value => value.section === $scope.currentSection);
        if(index > -1) $scope.selection[index].choice = tile.name;

        // Generate URL to retrieve bubble data
        let url = PageData.getServer($scope.currentSection.toLowerCase() + '/bubble/' + tile.id);
        url = encodeURI(url); // Sanitize String
        // Get bubble data
        $http.jsonp(url).then(function (response) {
            $scope.currentChoice = response.data.bubble;
        }, function (response) {
            $scope.currentChoice = {'name': tile.name, 'aux': tile.aux};
        });

        // Display bubble
        $scope.sDisplay = "Bubble";
    };  // click function from Tiles
    $scope.footerClick = function(section) {
        // Should not be currently displayed if true
        if($scope.currentSection === section) { return; }
        // if selection contains section
        let index = $scope.selection.findIndex((value) => value.section === section);
        if(index < 0) {
            // Not in selection - add it
            $scope.selection.push({'section': section, choice: ''});
        } else {
            // Is in selection - clear it's choice
            $scope.selection[index].choice = '';
        }
        // Update $scope.tiles with current section
        $scope.loadTiles(section);
        $scope.sDisplay = "Tiles";
        $scope.currentSection = section;
    };  // click function from footer
    $scope.linkPage = function(section, choice) {
    	$location.path(section.toLowerCase() + '/' + $scope.currentChoice.id);
        /*switch (section) {
            case "Tower":
                // Store relevant data
                PageData.setTower($scope.currentChoice.id);

                // Use $location to open new page
                $location.path('/tower');
                break;
            case "Event":
                // Store relevant data
                PageData.setEvent($scope.currentChoice.id);

                // Use $location to open new page
                $location.path('/event');
                break;
        }*/
    };
    $scope.newTile = function (section) {
        // TODO Create new item
		/* ** Display new item form, pass in need variables **
        // GET content for form data
        $scope.form.content = null;
        $scope.form.type = section;
        let url = PageData.getServer + 'purpose=form&item='+ section;
        $http.jsonp(url).then(function (response) {
            $scope.form.content = response.data;
        }, function (response) {
            $scope.form.content = {};
        });
        // if not empty open form
        if(!$scope.form.content.empty()) {
            PageData.getForm($scope.form);
            $scope.form.display = true;
        }
		 */
    };
    $scope.addItem = function () {
        // TODO POST data to server depending on type
		/* ** TODO Complete overhaul **
        let url = PageData.getServer + 'item=' + $scope.form.context;
        $http.post(url, JSON.stringify($scope.form.content))
            .then(function (response) {

            }, function (response) {

            });
		 */
    };
    $scope.applyEdit = function (type) {
        // apply the change if different
		/* ** TODO Complete Overhaul **
        if($scope.splash[type] !== $scope.edit.value) {
            $scope.splash[type] = $scope.edit.value;
            // TODO Send update to server

            // hide the edit menu
            $scope.edit.target = null;
        }
		 */
    };
});