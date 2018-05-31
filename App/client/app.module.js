var app = angular.module("AsesoriasPar", ['ngRoute', 'ui-notification']);

    app.run(function($rootScope){
        //Se define variable global
        $rootScope.page = {
            title: "PAGE TITLE"
        }
    });

    // .controller('MainController', function($scope, $http){
    //     $scope.page.title = "MAIN";
    // });

    //-------------Angular Notifications
    // app .config(function(NotificationProvider) {
    //     NotificationProvider.setOptions({
    //         delay: 2000,
    //         startTop: 20,
    //         startRight: 10,
    //         verticalSpacing: 20,
    //         horizontalSpacing: 20,
    //         positionX: 'left',
    //         positionY: 'bottom'
    //     });
    // });