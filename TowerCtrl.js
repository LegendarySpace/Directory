
app.controller("TowerCtrl", function ($scope, $http, $location, PageData) {
    // On failure to load should return to main page
    $scope.tower = PageData.getTower();
    if($scope.tower.name === '') $location.path('/');
    $http.jsonp(PageData.getServer + "purpose=splash&page=tower&name="+$scope.tower.name+"&aux="+$scope.tower.aux)
        .then(function (response) {
            // response.data = [splash[], sections[], admin]
            $scope.sections = response.data.sections;
            $scope.splash = response.data.splash;
            $scope.splash.admin = response.data.splash;
            $scope.background = ($scope.splash.img)? 'Images/'+$scope.splash.img: 'Images/Tower1.jpg';
        }, function (response) {
            $scope.sections = ["Company", "Event", "Employee"];
            $scope.splash = {name:$scope.tower.name, location:$scope.tower.aux};
            $scope.splash.admin = false;
            $scope.background = ($scope.splash.img)? 'Images/'+$scope.splash.img: 'Images/Tower1.jpg';
        });
    $scope.form = {display: false, content: null, type: null};
    $scope.selection = []; // Tiles in Selected [{section, choice}]
    $scope.currentSection = null; // Section to display [null, Tower, Event+]
    $scope.tiles = []; // Items in tile section [{},{}]
    $scope.currentChoice = {}; // Details in bubble
    $scope.sDisplay = null; // DisplayArea [Null, Tiles, Bubble]
    $scope.edit = {target:null, value: null}; // Editing details

    $scope.loadTiles = function(section) {
        // Retrieve tile data
        $scope.tiles = [];
        let url = PageData.getServer + "?purpose=tiles&section=" + section.toLowerCase() + "&tname=" +
            $scope.tower.name + "&taux" + $scope.tower.aux;
        $http.jsonp(url).then(function (response) {
            $scope.tiles = response.data.tiles;
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
        let index = $scope.selection.findIndex( value => value.section === $scope.currentSection);
        if(index > -1) $scope.selection[index].choice = tile.name;

        // Generate URL to retrieve bubble data
        let purpose = "purpose=bubble";
        let section = "section=" + $scope.selection[index].section.toLowerCase();
        let name = "name=" + tile.name;
        let aux = "aux=" + tile.aux;
        let tname = "tname=" + $scope.tower.name;
        let taux = "taux=" + $scope.tower.aux;
        let url = PageData.getServer + purpose + "&" + section + "&" + name + "&" + aux + "&" + tname + "&" + taux;
        url = encodeURI(url); // Sanitize String
        // Get bubble data
        $http.jsonp(url).then(function (response) {
            $scope.currentChoice = response.data.bubble;
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
                PageData.setCompany({name: $scope.currentChoice.name, aux: $scope.currentChoice.reception || $scope.currentChoice.aux});

                // Use $location to open new page
                $location.path('/company');
                break;
            case "Event":
                PageData.setEvent({name: $scope.currentChoice.name, aux: $scope.currentChoice.host || $scope.currentChoice.aux});

                $location.path('/event');
                break;
            case "Employee":
                PageData.setEmployee({name: $scope.currentChoice.name, aux: $scope.currentChoice.title || $scope.currentChoice.aux});
                // TODO Send additional data to load the company page

                $location.path('/company#employee');
                break;
        }
    };
    $scope.newTile = function (section) {
        // TODO Create new item
        // GET content for form data
        $scope.form.content = null;
        $scope.form.type = section;
        let url = PageData.getServer() + 'purpose=form&item='+ section;
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
    };
    $scope.addItem = function () {
        // TODO POST data to server depending on type
        let url = PageData.getServer() + 'item=' + $scope.form.context;
        $http.post(url, JSON.stringify($scope.form.content))
            .then(function (response) {

            }, function (response) {

            });
    };
    $scope.applyEdit = function (type) {
        // apply the change if different
        if($scope.splash[type] !== $scope.edit.value) {
            $scope.splash[type] = $scope.edit.value;
            // TODO Send update to server

            // hide the edit menu
            $scope.edit.target = null;
        }
    };
});