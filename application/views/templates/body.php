
<!--suppress HtmlUnknownAttribute -->
<div class="content" style='background-image: url("assets/images/{{background}}")'>
    <div class="splash text" ng-hide="selection.length > 0">
        <div ng-repeat="(type, text) in splash">
            <span ng-hide="edit.target === type" ng-switch="type">
                <span ng-switch-when="message" class="title">{{ text }}</span>
                <span ng-switch-when="name" class="title">{{ text }}</span>
                <span ng-switch-default>{{ text }}</span>

                <?php /* TODO edit button visibility should be controlled by PHP */ ?>
                <span ng-show="splash.admin" ng-click="edit.target = type; edit.value = text">&#9998;</span>
            </span>
            <span ng-hide="edit.target !== type" ng-switch="type">
                <input type="email" name="{{type}}" ng-model="edit.value" ng-switch-when="email">
                <input type="tel" name="{{type}}" ng-model="edit.value" ng-switch-when="phone">
                <input type="text" name="{{type}}" ng-model="edit.value" ng-switch-default>
                <input type="button" name="accept" ng-click="applyEdit(type)" value="Accept">
                <input type="button" name="cancel" ng-click="edit.target = null" value="Cancel">
            </span>
        </div>
    </div>
    <div id="Display" ng-hide="selection.length === 0">
        <div id="Selected" class="tileContainer">
            <div ng-repeat="tile in selection" class="displayTile" ng-selected="currentSection === tile.section" ng-click="selectedClick(tile)">
                <h3>{{tile.section}}</h3>
                <h4 ng-hide="tile.choice === ''">{{tile.choice}}</h4>
            </div>
        </div>
        <div class="tileGroup tileContainer" ng-hide="sDisplay !== 'Tiles'">
            <?php // Figure out how to sort by selected values in angularjs ?>
            <div ng-repeat="tile in tiles" class="displayTile" ng-selected="currentChoice.name === tile.name" ng-click="chooseTile(tile)">
                <h3>{{tile.name}}</h3>
                <h4>{{tile.aux}}</h4>
            </div>
                <?php /* TODO edit button visibility should be controlled by PHP and based on login credentials */ ?>
            <div class="displayTile" ng-click="newTile(currentSection)">
                <img src="<?php echo site_url(); ?>assets/images/plus.png" />
            </div>
        </div>
        <div class="bubble" ng-hide="sDisplay !== 'Bubble'">
                <?php /* TODO edit button visibility should be controlled by PHP and based on login credentials */ ?>
            <div ng-repeat="(x,y) in currentChoice" ng-switch="x">
                <span ng-switch-default>{{x | capitalize}}: {{y}}</span>
            </div>
            <div><span class="link" ng-click="linkPage(currentSection)">Go To {{currentSection}} Page</span></div>
        </div>
    </div>
    <div id="footer" class="tileContainer">
        <?php /* Should only display when not at top (use array that's filtered by selection.each(x.section != item)) */ ?>
        <div ng-repeat="section in sections" class="displayTile" ng-hide="currentSection === section" ng-click="footerClick(section)">
            <h2>{{section}}</h2>
        </div>
    </div>
</div>
