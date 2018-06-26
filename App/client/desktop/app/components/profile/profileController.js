angular.module("Desktop").controller('ProfileController', function($scope, ProfileService){

    //Menu general
    $scope.menu.title = 'profile';
    //Titulo
    $scope.page.title = 'Perfil de usuario';

    // $scope.loading = true;


    (function(){
        if( $scope.student == null ){
            alert("No hay un usuario asignado, se cerrara sesión");
            $scope.signOut();
        }

        $scope.loading = false;
    })();

});