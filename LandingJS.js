
app.controller("LandingController", function($scope, $http, $location, $sce, PageData) {
    $http.jsonp(PageData.getServer + '?purpose=landing')
        .then(function (response) {
            var data = response.data;
            $scope.sections = data.sections;
            PageData.setToken(data.token);
        }, function (response) {
            $scope.sections = ["Tower", "Event"];
            $scope.token = null;
            // Execute on error
        });
    $scope.selection = []; // Tiles in Selected [{section, choice}]
    $scope.currentSection = null; // Section to display [null, Tower, Event+]
    $scope.tiles = []; // Items in tile section [{},{}]
    $scope.currentChoice = {}; // Details in bubble
    $scope.sDisplay = null; // DisplayArea [Null, Tiles, Bubble]

    $scope.loadTiles = function(section) {
        // Retrieve tile data
        $scope.tiles = [];
        $http.jsonp(PageData.getServer + 'purpose=landingBtn&section=' + section)
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
    };
    $scope.selectedClick = function(tile) {
        // if already in section don't reload tiles
        if($scope.currentSection !== tile.section) {
            $scope.currentSection = tile.section;

            $scope.loadTiles(tile.section);
        }
        $scope.currentChoice = {name: tile.choice};

        // if not in tiles switch to tiles
        if($scope.sDisplay !== "Tiles") {
            $scope.sDisplay = "Tiles";
        } else {
            // is in tiles remove tile from selection
            var index = $scope.selection.findIndex((value) => value.section === $scope.currentSection);
            if(index > -1) $scope.selection.splice(index,1);
            $scope.currentSection = '';
            $scope.currentChoice = {};

            // switch to null
            $scope.sDisplay = null;
        }
    }; // click function from Selected
    $scope.chooseTile = function(tile) {

        // update selected
        var index = $scope.selection.findIndex((value) => value.section === $scope.currentSection);
        if(index > -1) {
            $scope.selection[index].choice = tile.name;
        }

        // Generate URL to retrieve bubble data
        var purpose = "purpose=LandingBubble";
        var section = "section=" + $scope.selection[index].section;
        var name = "name=" + tile.name;
        var aux = "aux=" + tile.aux;
        var url = PageData.getServer+'?'+section+'&'+purpose+'&'+name+'&'+aux;
        url = encodeURI(url); // Sanitize String
        // Get bubble data
        $http.jsonp(url).then(function (response) {
            $scope.currentChoice = response.data;
        }, function (response) {
            $scope.currentChoice = {name: tile.name};
        });

        // Display bubble
        $scope.sDisplay = "Bubble";
    };  // click function from Tiles
    $scope.footerClick = function(section) {
        // Should not be currently displayed
        if($scope.currentSection === section) { return; }
        // if selection contains section
        var index = $scope.selection.findIndex((value) => value.section === section);
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
    };   // click function from footer
    $scope.linkPage = function(section, choice) {
        switch (section) {
            case "Tower":
                // Store relevant data
                PageData.setTower({name: choice});

                // Use $location to open new page
                $location.url('/Tower');
                break;
            case "Event":
                // Store relevant data
                PageData.setEvent({name: choice});

                // Use $location to open new page
                $location.url('/Event');
                break;
        }
    };
    $scope.editBar = function (edit) {
        if(edit === $scope.slideEditBar) {
            $scope.slideEditBar = null;
        }
        else $scope.slideEditBar = edit;
    };
});