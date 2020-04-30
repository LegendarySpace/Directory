/* App Module */
var app = angular.module("Directory", ["ngAnimate", "ngRoute"]);

/* Route Provider */
app.config(function($routeProvider){
    $routeProvider
    .when("/", {
        templateUrl : 'MainDisplay.html',
        controller : 'LandingCtrl'
    })
    .when("/tower", {
        // Only switch controller, pages had only minor differences
        templateUrl : 'MainDisplay.html',
        controller : 'TowerCtrl'
    })
    .when("/company", {
        // TODO Setup company controller to function on landing page also
        templateUrl : "MainDisplay.html",
        controller : "CompanyCtrl"
    })
    .otherwise({
        redirectTo : '/'
    });
});

/* Filters */
/* legacy filters. no longer have access to info
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
}); */
// new filter that accepts tower and company, filtering first by tower then company
app.filter('tandc', function () {
    return function (inputArray, tower, company) {
        let filtered = [];
        inputArray.forEach(function(item) {
            // if tower is set check for tower
            // if company is set check for company
        });
    }
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
    var form = null;
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
    function getForm (contents) {
        if (contents) {form = contents;}
        return form;
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
        getServer : serverURL,
        getForm : getForm
    }
});

app.controller("FrameCtrl", function ($scope, $http, PageData) {
    $scope.login = {display: false};
    $scope.user = {display: false, name: null, img: "Images/fox.jpg"};
});
