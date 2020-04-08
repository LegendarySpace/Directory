
app.controller("LandingController", function ($scope, $http, PageData) {
    var URL = 'https://script.google.com/macros/s/AKfycbxvRxfFEaShT19EbpwtRgBi1VKcFH01LRTjR-aLvH0OBeqjADU/exec';
    $http.get(URL + '?purpose=landing')
        .then(function (response) {
            var data = response.data;
            $scope.tileSection = data.tileSection;
            $scope.towers = data.towers;
            $scope.token = data.token;
        }, function (response) {
            $scope.tileSection = [
                "Tower",
                "Event"
            ];
            $scope.token = PageData.getToken();
        });
    $scope.towers = [
        {name : "Forum 900", address : "900 2nd Ave S", accountID : 13579, img : "Images/Tower1.jpg"},
        {name : "AT&T", address : "901 Marquette Ave S", accountID : 13579, img : "Images/Tower2.jpg"},
        {name : "Forum 920", address : "920 2nd Ave S", accountID : 13579, img : "Images/Tower3.jpg"}
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
            PageData.setTower(tower);
        }
    };
    $scope.eventDisplayButton = function (anEvent) {
        if(anEvent === $scope.selectedEvent) {
            $scope.selectedEvent = null;
        }
        else {
            $scope.selectedEvent = anEvent;
            PageData.setEvent(anEvent);
        }
        // Display employee info
    };
    $scope.editBar = function (edit) {
        if(edit === $scope.slideEditBar) {
            $scope.slideEditBar = null;
        }
        else $scope.slideEditBar = edit;
    };
});