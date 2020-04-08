
app.controller("TowerController", function ($scope, PageData) {
    $scope.tower = PageData.getTower();
    //$scope.tower = { name: "Forum 900", address: "Where I'm at", accountID: "13579", img: "Images/tower1.jpg"};
    $scope.token = PageData.getToken();
    $scope.tileSection = [
        "Company",
        "Event",
        "Employee"
    ];
    $scope.companies = [
        {
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
        },
        {
            name : "Static",
            slogan : "Work your butt off",
            tower : "Forum 900",
            address : "900 2nd Ave S.",
            suite : "650",
            reception : 1500,
            phone : "6124862416",
            email : "abc@dynamic.com",
            accountID : 13579,
            img : "Images/DynamicLogo.jpg"
        },
        {
            name : "Foshay",
            slogan : "Work your butt off",
            tower : "Forum 900",
            address : "900 2nd Ave S.",
            suite : "1500",
            reception : 1500,
            phone : "6124862416",
            email : "abc@dynamic.com",
            accountID : 13579,
            img : "Images/DynamicLogo.jpg"
        },
        {
            name : "Not mine",
            slogan : "Work your butt off",
            tower : "AT&T",
            address : "900 2nd Ave S.",
            suite : "1360",
            reception : 1500,
            phone : "6124862416",
            email : "abc@dynamic.com",
            accountID : 13579,
            img : "Images/DynamicLogo.jpg"
        },
        {
            name : "Unconcerned",
            slogan : "Work your butt off",
            tower : "AT&T",
            address : "900 2nd Ave S.",
            suite : "1950",
            reception : 1500,
            phone : "6124862416",
            email : "abc@dynamic.com",
            accountID : 13579,
            img : "Images/DynamicLogo.jpg"
        },
        {
            name : "ATT",
            slogan : "Work your butt off",
            tower : "AT&T",
            address : "900 2nd Ave S.",
            suite : "350",
            reception : 1500,
            phone : "6124862416",
            email : "abc@dynamic.com",
            accountID : 13579,
            img : "Images/DynamicLogo.jpg"
        },
        {
            name : "Google",
            slogan : "Work your butt off",
            tower : "Forum 920",
            address : "900 2nd Ave S.",
            suite : "1890",
            reception : 1500,
            phone : "6124862416",
            email : "abc@dynamic.com",
            accountID : 13579,
            img : "Images/DynamicLogo.jpg"
        },
        {
            name : "Amazon",
            slogan : "Work your butt off",
            tower : "Forum 920",
            address : "900 2nd Ave S.",
            suite : "290",
            reception : 1500,
            phone : "6124862416",
            email : "abc@dynamic.com",
            accountID : 13579,
            img : "Images/DynamicLogo.jpg"
        },
        {
            name : "SpaceX",
            slogan : "Work your butt off",
            tower : "Forum 920",
            address : "900 2nd Ave S.",
            suite : "670",
            reception : 1500,
            phone : "6124862416",
            email : "abc@dynamic.com",
            accountID : 13579,
            img : "Images/DynamicLogo.jpg"
        }
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
    
    $scope.selectedCompany = null;
    $scope.selectedEvent = null;
    $scope.selectedEmployee = null;

    $scope.companyDisplayButton = function (company) {
        if(company === $scope.selectedCompany) {
            $scope.selectedCompany = null;
        }
        else {
            $scope.selectedCompany = company;
            PageData.setCompany(company);
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
    };
    $scope.employeeDisplayButton = function (employee) {
        if(employee === $scope.selectedEmployee) {
            $scope.selectedEmployee = null;
        }
        else {
            $scope.selectedEmployee = employee;
            PageData.setEmployee(employee);
        }
    };
    $scope.editBar = function (edit) {
        if(edit === $scope.slideEditBar) {
            $scope.slideEditBar = null;
        }
        else $scope.slideEditBar = edit;
    };
});