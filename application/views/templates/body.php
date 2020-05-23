
<?php // Can use ng-init to set id and pass to ajs controller ?>
<!--suppress HtmlUnknownAttribute -->
<div class="content" style='background-image: url("{{vm.background}}")'>
    <div id="splash" class="splash text" ng-hide="">
        <?php /* TODO edit button visibility should be controlled by PHP */ ?><span ng-click="">&#9998;</span>
        <div ng-repeat="(label, text) in vm.splash">
            <span ng-switch="label">
                <span ng-switch-when="message" class="title">{{ text }}</span>
                <span ng-switch-when="name" class="title">{{ text }}</span>
                <span ng-switch-default>{{ text }}</span>
            </span>
        </div>
    </div>
    <div id="display">
        <div id="pillbar" class="tileContainer">
            <div ng-repeat="pill in vm.pills" class="displayTile" ng-selected="pill === vm.current" ng-click="vm.pillclick(pill)">
                <h3>{{pill.grade}}</h3>
                <h4 ng-hide="!pill.tile.name">{{pill.tile.name}}</h4>
            </div>
        </div>
        <div class="tileGroup tileContainer" ng-hide="vm.display !== 'tiles'">
            <?php // Write filter to sort by selected values in angularjs ?>
            <div ng-repeat="tile in vm.tiles" class="displayTile" ng-selected="vm.current.tile === tile" ng-click="vm.tileclick(tile)">
                <h3>{{tile.name}}</h3>
                <h4>{{tile.aux}}</h4>
            </div>
            
            <?php /* TODO edit button visibility should be controlled by PHP and based on login credentials */ ?>
            <div class="displayTile" ng-click="vm.edit(vm.current.grade)">
                <img src="assets/images/plus.png" />
            </div>
        </div>
        <div class="bubble" ng-hide="vm.display !== 'bubble'">
            <?php /* TODO edit button visibility should be controlled by PHP and based on login credentials */ ?>
            <div ng-repeat="(label,text) in vm.bubble" ng-switch="x">
                <span ng-switch-default>{{label | capitalize}}: {{text}}</span>
            </div>
            <?php /* TODO link to "{{vm.current.grade}}/{{vm.current.tile.id}}" */ ?>
            <div><span class="link">Go To {{vm.current.grade}} Page</span></div>
        </div>
    </div>
</div>
