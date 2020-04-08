
app.controller("DirectoryController", function ($scope, PageData) {
    $scope.tileSection = [
        "Tower",
        "Company",
        "Event"
    ];
    $scope.towers = [
        {name : "Forum 900", address : "900 2nd Ave S"},
        {name : "AT&T", address : "901 Marquette Ave S"},
        {name : "Forum 920", address : "920 2nd Ave S"},
    ];
    $scope.companies = [
        {name : "Dynamic", tower : "Forum 900", suite : "1560"},
        {name : "Static", tower : "Forum 900", suite : "650"},
        {name : "Foshay", tower : "Forum 900", suite : "1500"},
        {name : "Not mine", tower : "AT&T", suite : "1360"},
        {name : "Unconcerned", tower : "AT&T", suite : "1950"},
        {name : "ATT", tower : "AT&T", suite : "350"},
        {name : "Google", tower : "Forum 920", suite : "1890"},
        {name : "Amazon", tower : "Forum 920", suite : "290"},
        {name : "SpaceX", tower : "Forum 920", suite : "670"}
    ];
    $scope.activities = [
        {name : "ThermoDynamic", company : "Dynamic", tower : "Forum 900", details : "A BBQ hosted by Dynamic"},
        {name : "", company : "", tower : "", details : ""},
    ];
    $scope.selectedTower = null;
    $scope.selectedCompany = null;
    $scope.selectedEvent = null;
    
    $scope.towerDisplayButton = function (tower) {
        // Filter companies by tower
        if(tower === $scope.selectedTower) {
            $scope.selectedTower = null;
        }
        else $scope.selectedTower = tower;
        // Toggle button
        // Untoggle other buttons
        // if selected set selectedTower to this
        // otherwise set selectedTower to none
    };
    $scope.companyDisplayButton = function (company) {
        if(company === $scope.selectedCompany) {
            $scope.selectedCompany = null;
            PageData.setCompany = company;
            // $window.location.url('../CompanyPage.html');
        }
        else $scope.selectedCompany = company;
        // Open company page
    };
    $scope.eventDisplayButton = function (anEvent) {
        if(anEvent === $scope.selectedEvent) {
            $scope.selectedEvent = null;
        }
        else $scope.selectedEvent = anEvent;
        // Display employee info
    };
});