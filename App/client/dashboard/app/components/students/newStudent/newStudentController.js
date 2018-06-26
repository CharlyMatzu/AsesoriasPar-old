angular.module("Dashboard").controller('NewUserController', function($scope, $http, $timeout, NewUserService, Notification){
    
    $scope.page.title = "Estudiantes > Nuevo";
    $scope.loading = false;


    /**
     * 
     * @param {*} student 
     * @return bool
     */
    var validate = function(student){
        if( student.pass != student.pass2 ){
            Notification.warning('Contraseñas no coinciden');
            return false;
        }
            
        return true;
    };

    /**
     * 
     * @param {*} student 
     */
    $scope.addStudent = function(student){
        if( !validate(student) )
            return;

        
        //Para quitar alerts actuales
        $scope.alert.type = '';
        //Se pone en cargando
        $scope.loading.status = true;

        //Peticion
        NewUserService.addStudent(student,
            function(success){
                $scope.alert.type = 'success';
                $scope.alert.message = "Se ha registrado estudiante correctamente"
                $scope.loading.status = false;
            },
            function (error){
                if( error.status == CONFLICT )
                    $scope.alert.type = 'warning';
                else
                    $scope.alert.type = 'error';

                $scope.alert.message = error.data;
                $scope.loading.status = false;
                
            }
        );
    };

    (function(){
        
    })();


});