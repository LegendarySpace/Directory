/* App Module */
var app = angular.module("DirectoryMain", ["ngAnimate"]);

/* Filters */
app.filter('filterByTower', function () {
    return function (x, selected) {
        if (selected === null) { return x; }
        var filtered = [];
        x.forEach(function (company) {
            if (company.tower === selected.name) { filtered.push(company); }
        });
        return filtered;
    };
});
app.filter('filterByCompany', function () {
    return function (x, selected) {
        if (selected === null) { return x; }
        var filtered = [];
        x.forEach(function (employee) {
            if (employee.company === selected.name) { filtered.push(employee); }
        });
        return filtered;
    };
});

/* Services */
app.factory('PageData', function () {
    var savedTower = {};
    var savedCompany = {};
    var savedEmployee = {};
    //var userToken = { level : null, permissions : []};
    function setTower (tower) {
        savedTower = tower;
    }
    function setCompany (company) {
        savedCompany = company;
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
    function getEmployee () {
        return savedEmployee;
    }
    return {
        setTower : setTower,
        setCompany : setCompany,
        setEmployee : setEmployee,
        getTower : getTower,
        getCompany : getCompany,
        getEmployee : getEmployee
    }
});