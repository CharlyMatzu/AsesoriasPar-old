angular.module("Desktop").controller('ProfileController', function($scope, $http, $routeParams, $window, $location){

    //Menu general
    $scope.menu.title = 'profile';
    //Titulo
    $scope.page.title = 'Perfil de usuario';

    console.log( "Usuario: " + $scope.student );

});