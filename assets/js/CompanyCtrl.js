
(function() {
    'use strict';
    
    angular
        .module('directory')
        .controller('CompanyCtrl', CompanyCtrl);
    
    CompanyCtrl.$inject = ['api'];
    function CompanyCtrl(api) {
        var vm = this;
        
        activate();
        
        // Definitions
        
        function activate() {
            api.setCaller();
            api.splash().then(function() {
                
                vm.splash = api.page.splash;
                vm.pills = api.page.pills;
                vm.tiles = api.page.tiles;
                vm.bubble = api.page.bubble;
                vm.edit = edit;
                vm.pillclick = click_pill;
                vm.tileclick = click_tile;
                vm.current = api.page.current;
                vm.display = api.page.display;
                vm.background = api.page.background;
                vm.form = null; // TODO move form data to api
            })
        }
        
        function edit(grade = null, id = null) {
            api.edit(grade, id);
        }
        
        function click_pill(pill) {
            api.pill(pill);
        }
        
        function click_tile(tile) {
            api.tile(tile);
        }
    }
})();

