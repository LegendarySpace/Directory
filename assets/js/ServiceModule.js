
/* Add abstract class here so I can use inheritance later */
/* App Module */
var app = angular.module("Directory", ["ngAnimate"]);

/* Filters */
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
	function serverURL (address) {
		// Site url is already injected
		let url = 'api/';
		url += address;
		return url;
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
