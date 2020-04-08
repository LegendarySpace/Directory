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
        templateUrl : 'TowerPage.html',
        controller : 'TowerController'
    })
    .when("/company", {
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
        var filtered = [];
        x.forEach(function (company) {
            if (company.tower === selected.name) { filtered.push(company); }
        });
        return filtered;
    };
});
app.filter('filterByCompany', function () {
    return function (x, selected) {
        if (selected === null || !angular.isDefined(selected) || x.length < 1) { return x; }
        var filtered = [];
        x.forEach(function (employee) {
            if (employee.company === selected.name) { filtered.push(employee); }
        });
        return filtered;
    };
});
app.filter('excludeHidden', function () {
    return function (x) {
        if (x.length < 1) { return x; }
        var filtered = [];
        for (key in x) {
            if (key !== 'accountID' && key !== 'img') { filtered.push(x); }
        }
        return filtered;
    };
});
app.filter('capitalize', function() {
    return function(input) {
      return (angular.isString(input) && input.length > 0) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : input;
    }
});

/* Services */
app.factory('PageData', function () {
    var savedTower = {};
    var savedCompany = {};
    var savedEmployee = {};
    var savedEvent = {};
    var serverURL = 'https://script.google.com/macros/s/AKfycbydsK0d07yyGWJ4D7P49IEhAXkDxVwsz9cTSDfG_vA1aTcoyr8/exec';
    var savedToken = {};
    // var userToken = { level : null, permissions : []};
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
    function setToken (token) {
        savedToken = token
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
    function getToken() {
        return savedToken;
    }
    function loadP(page) {
        // Contains GET request for pages
        switch(page) {
            case 'landing':
                break;
            case 'tower':
                break;
            case 'company':
                break;
        }
    }
    return {
        setTower : setTower,
        setCompany : setCompany,
        setEvent : setEvent,
        setEmployee : setEmployee,
        setToken : setToken,
        getTower : getTower,
        getCompany : getCompany,
        getEvent : getEvent,
        getEmployee : getEmployee,
        getToken : getToken,
        getServer : serverURL,
        loadP : loadP
    }
});

app.controller("FrameController", function ($scope) {
    // TODO GET to start a session
});