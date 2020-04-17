/* App Module */
var app = angular.module("Frame", ["ngAnimate", "ngRoute"]);

/* Route Provider */
app.config(function($routeProvider){
    $routeProvider
    .when("/", {
        templateUrl : 'LandingPage.html',
        controller : 'LandingController'
    })
    .when("/tower", {
        // Only switch controller, pages had only minor differences
        templateUrl : 'LandingPage.html',
        controller : 'TowerController'
    })
    .when("/company", {
        // TODO Setup company controller to function on landing page also
        templateUrl : "CompanyPage.html",
        controller : "CompanyController"
    })
    .otherwise({
        redirectTo : '/'
    });
});

/* Filters */
app.filter('filterByTower', function () {
    return function (x, selected) {
        if (selected === null || !angular.isDefined(selected) || x.length < 1) { return x; }
        let filtered = [];
        x.forEach(function (company) {
            if (company.tower === selected.name) { filtered.push(company); }
        });
        return filtered;
    };
});
app.filter('filterByCompany', function () {
    return function (x, selected) {
        if (selected === null || !angular.isDefined(selected) || x.length < 1) { return x; }
        let filtered = [];
        x.forEach(function (employee) {
            if (employee.company === selected.name) { filtered.push(employee); }
        });
        return filtered;
    };
});
app.filter('excludeHidden', function () {
    return function (x) {
        if (x.length < 1) { return x; }
        let filtered = [];
        for (key in x) {
            if (key !== 'accountID' && key !== 'img') { filtered.push(x); }
        }
        return filtered;
    };
});
app.filter('capitalize', function() {
    return function(input) {
        if (!angular.isString(input)) return input;
        let red = function (res, cur) {
            let temp = cur.charAt(0).toUpperCase() + cur.substr(1).toLowerCase();
            return (!res)? res = temp: res += ' ' + temp;
        };
      return input.split(" ").reduce(red, "");
    }
});

/* Services */
app.factory('PageData', function () {
    var savedTower = {};
    var savedCompany = {};
    var savedEmployee = {};
    var savedEvent = {};
    var serverURL = 'Responder.php?';
    function setTower (tower) {
        savedTower = tower;
    }
    function setCompany (company) {
        savedCompany = company;
    }
    function setEvent (event) {
        savedEvent = event;
    }
    function setEmployee (employee) {
        savedEmployee = employee;
    }
    function getTower () {
        return savedTower;
    }
    function getCompany () {
        return savedCompany;
    }
    function getEvent () {
        return savedEvent;
    }
    function getEmployee () {
        return savedEmployee;
    }
    return {
        setTower : setTower,
        setCompany : setCompany,
        setEvent : setEvent,
        setEmployee : setEmployee,
        getTower : getTower,
        getCompany : getCompany,
        getEvent : getEvent,
        getEmployee : getEmployee,
        getServer : serverURL
    }
});

app.controller("FrameController", function ($scope) {
    
});