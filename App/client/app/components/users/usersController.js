app.controller('UsersController', function($scope, UsersServices){
    $scope.loading = true;
    $scope.users = [];

    //Cargando controllador, obtiene usuarios
    $scope.users = UsersServices.getAll();


});