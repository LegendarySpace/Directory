
app.controller("LandingController", function ($scope, PageData) {
    $scope.tileSection = [
        "Tower",
        "Event"
    ];
    $scope.towers = [
        {name : "Forum 900", address : "900 2nd Ave S"},
        {name : "AT&T", address : "901 Marquette Ave S"},
        {name : "Forum 920", address : "920 2nd Ave S"},
    ];
    $scope.activities = [
        {name : "ThermoDynamic", company : "Dynamic", tower : "Forum 900", details : "A BBQ hosted by Dynamic"}
    ];
    $scope.selectedTower = null;
    $scope.selectedEvent = null;
    
    $scope.towerDisplayButton = function (tower) {
        // Filter companies by tower
        if(tower === $scope.selectedTower) {
            $scope.selectedTower = null;
        }
        else {
            $scope.selectedTower = tower;
            PageData.setTower = tower;
        }
    };
    $scope.eventDisplayButton = function (anEvent) {
        if(anEvent === $scope.selectedEvent) {
            $scope.selectedEvent = null;
        }
        else {
            $scope.selectedEvent = anEvent;
        }
        // Display employee info
    };
});