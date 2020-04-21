
app.controller("TowerCtrl", function ($scope, $http, $location, PageData) {
    // On failure to load should return to main page
    $scope.tower = PageData.getTower();
    if($scope.tower.name === '') $scope.tower = {name: 'Anonymous Tower', aux: 'an address'};
    // tower: { name, aux }
    // !IMPORTANT! Use &amp; instead of & to avoid &sect miscall
    $http.jsonp(PageData.getServer + "?purpose=splash&amp;page=tower&amp;name="+
        $scope.tower.name+"&amp;aux="+$scope.tower.aux)
        .then(function (response) {
            // response.data = [splash[], sections[], admin]
            $scope.sections = response.data.sections;
            $scope.splash = response.data.splash;
            $scope.admin = response.data.splash;
            // get img also !Currently not implemented! TODO
        }, function (response) {
            $scope.sections = ["Company", "Event", "Employee"];
            $scope.splash = {name:$scope.tower.name, location:$scope.tower.aux};
            $scope.admin = false;
        });
    $scope.background = 'Images/'+$scope.splash.img || 'Images/Tower1.jpg';
    $scope.selection = []; // Tiles in Selected [{section, choice}]
    $scope.currentSection = null; // Section to display [null, Company, Event, Employee]
    $scope.tiles = []; // Items in tile section [{},{}]
    $scope.currentChoice = {}; // Details in bubble
    $scope.sDisplay = null; // DisplayArea [Null, Tiles, Bubble]

    $scope.loadTiles = function(section) {
        // Retrieve tile data
        $scope.tiles = [];
        let url = PageData.getServer() + "?purpose=tiles&amp;section=" + section + "&amp;tname=" +
            $scope.tower.name + "&amp;taux" + $scope.tower.aux;
        $http.jsonp(url).then(function (response) {
            $scope.tiles = response.data;
        }, function (response) {
            switch (section) {
                case 'Company':
                    $scope.tiles = [{name:'Dynamic', aux:'1500'},{name:'Static', aux:'650'}, {name:'Tesla', aux:'1485'}];
                    break;
                case 'Event':
                    $scope.tiles = [{name:'ThermoDynamic', aux:'Dynamic'}];
                    break;
                case 'Employee':
                    $scope.tiles = [{name:'Maxi Volv', aux:'CEO of Dynamic'}];
                    break;
            }
        });
    }; // Handles retrieval of tile data
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
        } else if($scope.currentSection === tile.section) {
            // is in tiles and tile is current section remove tile from selection
            let index = $scope.selection.findIndex(value => value.section === $scope.currentSection);
            if(index > -1) $scope.selection.splice(index,1);
            $scope.currentSection = '';
            $scope.currentChoice = {};

            // switch to null
            $scope.sDisplay = null;
        }
    }; // click function from Selected
    $scope.chooseTile = function(tile) {
        // update selected
        let index = $scope.selection.findIndex( value => value.section === $scope.currentSection);
        if(index > -1) $scope.selection[index].choice = tile.name;

        // Generate URL to retrieve bubble data
        let purpose = "purpose=bubble";
        let section = "section=" + $scope.selection[index].section;
        let name = "name=" + tile.name;
        let aux = "aux=" + tile.aux;
        let tname = "tname=" + $scope.tower.name;
        let taux = "taux=" + $scope.tower.aux;
        let url = "?" + purpose + "&amp;" + section + "&amp;" + name + "&amp;" + aux + "&amp;" +
            tname + "&amp;" + taux;
        url = encodeURI(url); // Sanitize String
        // Get bubble data
        $http.jsonp(url).then(function (response) {
            $scope.currentChoice = response.data;
        }, function (response) {
            $scope.currentChoice = {'Name': tile.name, 'Other': tile.aux};
        });

        // Display bubble
        $scope.sDisplay = "Bubble";
    }; // click function from Tiles
    $scope.footerClick = function(section) {
        // Should not be displayed if true
        if($scope.currentSection === section) { return; }
        // if selection contains section
        let index = $scope.selection.findIndex( value => value.section === section);
        if(index < 0) {
            // Not in selection - add it
            $scope.selection.push({'section': section, choice: ''})
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
        switch(section) {
            case "Company":
                // Store relevant data
                PageData.setCompany({name: choice, aux: $scope.currentChoice.Reception});

                // Use $location to open new page
                $location.path('/company');
                $location.apply();
                break;
            case "Event":
                PageData.setEvent({name: choice, aux: $scope.currentChoice.Host});

                $location.path('/event');
                $location.apply();
                break;
            case "Employee":
                PageData.setEmployee({name: choice, aux: $scope.currentChoice.Title});
                // TODO Send additional data to load the company page

                $location.path('/company#employee');
                $location.apply();
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