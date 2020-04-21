
app.controller("LoginController", function ($scope, $http, PageData) {
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


// GET content for login
    $scope.getModal();
});