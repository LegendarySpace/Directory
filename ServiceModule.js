/* App Module */
var app = angular.module("Directory", ["ngAnimate", "ngRoute"]);

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

app.controller("FrameController", function ($scope, $http, PageData) {
    $scope.login = {display: false};

});

app.controller("LoginController", function ($scope, $http, PageData) {
    $scope.login.content = null;
    $scope.register = {display: false, type:null};

    $scope.getModal = function () {
        $scope.login.content = {};
        let url = PageData.getServer + 'purpose=form&context=';
        url += ($scope.register.display)? 'register':'login';
        $http.jsonp(url).then(function (response) {
            $scope.login.content = response.data;
        }, function (response) {
            $scope.login.content = (!$scope.register.display)?{username: '', password: ''}
            :{main:{user:'',pass:'',type:null},tower:{},company:{}};
        });
        if($scope.register.display) {
            $scope.register.tower = $scope.login.content.tower;
            $scope.register.company = $scope.login.content.company;
            $scope.login.content = $scope.login.main;
        }
    };


// GET content for login
    $scope.getModal();
});

app.controller("FormController", function($scope, $http, PageData) {
    $scope.form = PageData.getForm();
    // if form.empty() close modal
    if ($scope.form.content.empty()) $scope.form.display = false;
    $scope.sendForm = function() {
        let url = PageData.getServer + 'purpose=create&item=' + $scope.form.type;
        $http.post(url, JSON.stringify($scope.form.content)).then(function (response) {
            // submitted successfully
            $scope.form.display = false;
        }, function (response) {
            // failed to submit
        });
    };
});
