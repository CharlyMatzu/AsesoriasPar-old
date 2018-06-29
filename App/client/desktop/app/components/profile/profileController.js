angular.module("Desktop").controller('ProfileController', function($scope, ProfileService, Notification){

    //Menu general
    $scope.menu.title = 'profile';
    //Titulo
    $scope.page.title = 'Perfil de usuario';

    $scope.loadingData = false;
    $scope.loadingPass = false;


    $scope.updateStudentData = function(student){
        $scope.loadingData = true;

        if( !student.facebook )
            student.facebook = "";

        ProfileService.updateStudent(student)
            .then(function(success){
                Notification.success("Actualizado con éxito");
                $scope.loadingData = false;
            }, function(error){
                Notification.error("Ocurrió un error al actualizar: "+error.data);
                $scope.loadingData = false;
            });        
    }

    $scope.updateUserPassword = function(pass){
        $scope.loadingPass = true;

        ProfileService.updatePassword($scope.student.user_id, pass)
            .then(function(success){
                Notification.success("Contraseña actualizada");
                $scope.loadingPass = false;
                //Limpiando
                pass.old = "";
                pass.new = "";
            }, function(error){
                Notification.error("Ocurrió un error: "+error.data);
                $scope.loadingPass = false;
            });
    };


    (function(){
        if( $scope.student == null ){
            alert("No hay un usuario asignado, se cerrara sesión");
            $scope.signOut();
        }
    })();

});