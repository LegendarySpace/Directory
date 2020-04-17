
app.controller("LandingController", function($scope, $http, $location, PageData) {
    // !IMPORTANT! Use &amp; instead of & to avoid &sect miscall
    // TODO Set Mime type correctly
    $http.jsonp(PageData.getServer + 'purpose=splash&amp;page=landing')
        .then(function (response) {
            // response.data = [splash[], sections[], admin]
            $scope.sections = response.data.sections;
            $scope.splash = response.data.splash;
            $scope.admin = response.data.admin;
            // query content and set background url to img
        }, function (response) {
            $scope.sections = ["Tower", "Event"];
            $scope.splash = {message:"Welcome Message", sub:"Sub Message"};
            $scope.admin = true;
            // Execute on error
        });
    $scope.background = 'Images/tower.jpg';
    $scope.selection = []; // Tiles in Selected [{section, choice}]
    $scope.currentSection = null; // Section to display [null, Tower, Event+]
    $scope.tiles = []; // Items in tile section [{},{}]
    $scope.currentChoice = {}; // Details in bubble
    $scope.sDisplay = null; // DisplayArea [Null, Tiles, Bubble]
    $scope.edit = {target:null, value: null}; // Editing details

    $scope.loadTiles = function(section) {
        // Retrieve tile data
        $scope.tiles = [];
        $http.jsonp(PageData.getServer + 'purpose=tiles&amp;section=' + section)
            .then(function (response) {
                $scope.tiles = response.data;
            }, function (response) {
                switch (section) {
                    case 'Tower':
                        $scope.tiles = [{name:'Forum 900', aux:'900 2nd Ave'},{name:'AT&T', aux:'901 Marquette Ave'},
                            {name:'121 Tower', aux:'121 S 8th St'},{name:'Wayne Tower', aux:'84th Gotham Ave'},{name:'Metropolitan Tower', aux:'145 Metropolis Circle'}];
                        break;
                    case 'Event':
                        $scope.tiles = [{name:'ThermoDynamic', aux:'Dynamic'},{name:'Photo Op with Bruce Wayne', aux:'WayneTech'}];
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
    }; // click function from Selected
    $scope.chooseTile = function(tile) {

        // update selected
        let index = $scope.selection.findIndex(value => value.section === $scope.currentSection);
        if(index > -1) $scope.selection[index].choice = tile.name;

        // Generate URL to retrieve bubble data
        let purpose = "purpose=bubble";
        let section = "section=" + $scope.selection[index].section;
        let name = "name=" + tile.name;
        let aux = "aux=" + tile.aux;
        let url = PageData.getServer + purpose + '&amp;' + section + '&amp;' + name + '&amp;' + aux;
        url = encodeURI(url); // Sanitize String
        // Get bubble data
        $http.jsonp(url).then(function (response) {
            $scope.currentChoice = response.data;
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
        switch (section) {
            case "Tower":
                // Store relevant data
                PageData.setTower({name: $scope.currentChoice.name, aux: $scope.currentChoice.location || $scope.currentChoice.aux});

                // Use $location to open new page
                $location.path('/tower');
                $location.apply();
                break;
            case "Event":
                // Store relevant data
                PageData.setEvent({name: $scope.currentChoice.name, aux: $scope.currentChoice.host || $scope.currentChoice.aux});

                // Use $location to open new page
                $location.path('/event');
                $location.apply();
                break;
        }
    };
    $scope.addTile = function (section) {
        // TODO Create new item
    };
    $scope.applyEdit = function (type) {
        // apply the change
        if($scope.splash[type] !== $scope.edit.value) {
            $scope.splash[type] = $scope.edit.value;
            // TODO Send update to server

            // hide the edit menu
            $scope.edit.target = null;
        }
    };
});