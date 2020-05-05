<!DOCTYPE html>
<!--suppress HtmlUnknownAttribute -->
<html>
    <head>
        <title>Faux Directory</title>
        <link rel="stylesheet" type="text/css" href="DirectoryCSS.css">
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-animate.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>

        <script src="ServiceModule.js"></script>
        <script src="LandingCtrl.js"></script>
        <script src="TowerCtrl.js"></script>
        <script src="CompanyCtrl.js"></script>
    </head>

    <body id="frame" ng-app="Directory">
        <div id="header">
            <!-- TODO Switch to nav bar -->
            <!-- contains centered logo and right aligned sign in button/user -->
            <div>
                <!-- TODO PHP will handle login -->
                <!-- TODO include login and registration modals in php -->
                <input ng-if="!user.display" type="button" id="login" value="Log In" ng-click="login.display = true" />
                <span ng-if="user.display" ng-click="">{{user.name}}<img src="Images/FoxLogo.png" /></span>
                <!-- TODO allow logout on user click -->
            </div>
            <div style="background-image: url(Images/FoxFLogo.png)"></div>
        </div>
        <div class="container">
