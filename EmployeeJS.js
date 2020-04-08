
app.controller("DirectoryController", function ($scope) {
    $scope.company = {
        name : "Dynamic",
        slogan : "Work your butt off",
        tower : "Forum 900",
        address : "900 2nd Ave S.",
        suite : [1500, 1645],
        reception : 1500,
        phone : "6124862416",
        email : "abc@dynamic.com"
    };
    $scope.employees = [
        {name : "Brit", title : "Manager", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "Kyle", title : "Worker Bee", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "Steve", title : "Worker Bee", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "Jessica", title : "Worker Bee", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "Eric", title : "Worker Bee", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "Ali", title : "Worker Bee", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "Jamal", title : "Worker Bee", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"},
        {name : "Kareem", title : "Worker Bee", company : "Dynamic"},
        {name : "", title : "", company : "Dynamic"}
    ];
    $scope.suiteString = "";
    $scope.selectedEmployee = {name : "", title : "", company : "Dynamic"};
    
    $scope.employeeDisplayButton = function (employee) {
        // Display employee info
        $scope.selectedEmployee = employee;
    };
    $scope.displaySuites = function (display) {
        // Format the suites to a sing line of text and return that
        $scope.suiteString = display;
    }
    
    // $scope.displaySuites();
});