
app.controller("TowerController", function ($scope, PageData) {
    $scope.tileSection = [
        "Company",
        "Event",
        "Employee"
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
        {name : "ThermoDynamic", company : "Dynamic", tower : "Forum 900", details : "A BBQ hosted by Dynamic"}
    ];
    $scope.employees = [
        {name : "Brit", title : "Manager", company : "Dynamic"},
        {name : "Kyle", title : "Worker Bee", company : "Dynamic"},
        {name : "Steve", title : "Worker Bee", company : "Dynamic"},
        {name : "Jessica", title : "Worker Bee", company : "Dynamic"},
        {name : "Eric", title : "Worker Bee", company : "Dynamic"},
        {name : "Ali", title : "Worker Bee", company : "Dynamic"},
        {name : "Jamal", title : "Worker Bee", company : "Dynamic"},
        {name : "Kareem", title : "Worker Bee", company : "Dynamic"}
    ];
    
    $scope.tower = {name : "Forum 900", address : "900 2nd Ave S", accountID : 849897, img : 'Images/Tower2.jpg'};
    $scope.selectedCompany = null;
    $scope.selectedEvent = null;
    $scope.selectedEmployee = null;
    
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
    $scope.employeeDisplayButton = function (employee) {
        if(employee === $scope.selectedEmployee) {
            $scope.selectedEmployee = null;
        }
        else $scope.selectedEmployee = employee;
        // Display employee info
    };
});