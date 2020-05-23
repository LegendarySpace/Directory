<!DOCTYPE html>
<!--suppress HtmlUnknownAttribute -->
<html>
    <head>
        <?php /* TODO Get from php */ ?>
        <title>Faux Directory</title>
		<link rel="stylesheet" type="text/css" href="assets/css/DirectoryCSS.css">
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-animate.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>

        <script src="assets/js/DAController.js"></script>
        <script src="assets/js/<?php echo $title; ?>Ctrl.js"></script>
    </head>

    <body ng-app="directory">
        <div id="header">
            <?php /* TODO Switch to nav bar */
            /* contains logo and right aligned sign in button/user */ ?>
            <div>
                <?php /* TODO PHP will handle login */
                /* TODO include login and registration modals in php */
                /* TODO Use frame controller to control login button and form display - Suggested rename of controller to header*/
				/* TODO Use login status to determine if login button should be shown or user credentials */
                /* <input ng-if="!user.display" type="button" id="login" value="Log In" ng-click="login.display = true" />
                <span ng-if="user.display" ng-click="">{{user.name}}<img src="Images/FoxLogo.png" /></span> */
                ?>
            </div>
            <div style="background-image: url(assets/images/FoxFLogo.png) "></div>
        </div>
        <div ng-controller="<?php echo $title; ?>Ctrl as vm" class="container">
            <?php /* TODO Insert login modal */ ?>
