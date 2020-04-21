
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