
app.controller("CompanyController", function ($scope, PageData) {
    $scope.company = PageData.getCompany();
    $scope.token = PageData.getToken();
    $scope.tileSection = [
        "Event",
        "Employee"
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
    
    /*$scope.company = {
        name : "Dynamic",
        slogan : "Work your butt off",
        tower : "Forum 900",
        address : "900 2nd Ave S.",
        suite : [1500, 1645],
        reception : 1500,
        phone : "6124862416",
        email : "abc@dynamic.com",
        accountID : 13579,
        img : "Images/DynamicLogo.jpg"
    };*/
    $scope.selectedEvent = null;
    $scope.selectedEmployee = null;
    
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
    $scope.employeeDisplayButton = function (employee) {
        if(employee === $scope.selectedEmployee) {
            $scope.selectedEmployee = null;
        }
        else {
            $scope.selectedEmployee = employee;
            PageData.setEmployee(employee);
        }
        // Display employee info
    };
    $scope.editBar = function (edit) {
        if(edit === $scope.slideEditBar) {
            $scope.slideEditBar = null;
        }
        else $scope.slideEditBar = edit;
    };
    $scope.displaySuite = function () {
        return $scope.company.suite.join(', ');
    };
    
    $scope.token = {
        accountID : null,
        permissions : []
    };
});