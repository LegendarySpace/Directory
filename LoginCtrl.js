
app.controller("LoginCtrl", function ($scope, $http, PageData) {
    $scope.login.content = null;
    $scope.register = {display: false, type:null};

    $scope.getModal = function () {
        $scope.login.content = {};
        let setReg = function () {
            $scope.register.tower = $scope.login.content.tower;
            $scope.register.company = $scope.login.content.company;
            $scope.login.content = $scope.login.content.main;
        };
        let url = PageData.getServer + 'purpose=form&context=';
        url += ($scope.register.display)? 'register':'login';
        $http.jsonp(url).then(function (response) {
            $scope.login.content = response.data;
            if($scope.register.display) setReg();
        }, function (response) {
            $scope.login.content = (!$scope.register.display)?{username: '', password: ''}
                :{main:{user:'',pass:''},tower:{},company:{}};
            if($scope.register.display) setReg();
        });
    };

    $scope.attemptLogin = function () {
        if(!$scope.login.content.username || !$scope.login.content.password) return;
        let url = PageData.getServer + 'purpose=login';
        $http.post(url, JSON.stringify($scope.login.content)).then(function (response) {
            // Create welcome message
            $scope.welcome = "Welcome " + response.data.name;
            $scope.user.name = response.data.name;
            $scope.user.display = true;
        }, function (response) {
            // Display Error message
            $scope.welcome = response.data;
        });
    };

    $scope.attemptRegUser = function () {
        let url = PageData.getServer + 'purpose=create&item=user';
        $http.post(url, JSON.stringify($scope.login.content)).then(function (response) {
            // Create a message welcoming new user then register account type
            $scope.welcome = "Welcome " + response.data.name;
            if ($scope.register.type === 'tower') {
                // Register new tower
                $scope.attemptRegTower(response.data.id);
            } else if ($scope.register.type === 'company') {
                // Register new company
                $scope.attemptRegCompany(response.data.id);
            }
        }, function (response) {
            // Alert that user registration failed
        });
    };

    $scope.attemptRegTower = function (id) {
        let url = PageData.getServer + 'purpose=create&item=tower&id=' + id;
        $http.post(url, JSON.stringify($scope.login.tower)).then(function (response) {
            // Acknowledge creation of new tower
            $scope.welcome += "/n Tower successfully registered"
        }, function (response) {
            // Alert that tower reg failed
        });
    };

    $scope.attemptRegCompany = function (id) {
        let url = PageData.getServer + 'purpose=create&item=company&id=' + id;
        $http.post(url, JSON.stringify($scope.login.company)).then(function (response) {
            // Acknowledge creation of new tower
            $scope.welcome += "/n Company successfully registered"
        }, function (response) {
            // Alert that tower reg failed
        });
    };

    $scope.submitForm = function () {
        if(!$scope.register.display) {
            // Normal login
            $scope.attemptLogin();
        } else {
            // Register new user
            $scope.attemptRegUser();
        }
        alert($scope.message);
    }


// GET content for login
    $scope.getModal();
});