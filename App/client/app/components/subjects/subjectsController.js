app.controller('SubjectsController', function($scope, $http,Notification, SubjectsService){
    $scope.newSubject = {
        show: false,
        status: false
    };

    $scope.subjects = [];
    $scope.status = "Cargando usuarios..."

    $scope.errorSnack = function(errorMessage){
        Notification.error( errorMessage );
    }

    $scope.getStatus = function(status){
        if( status == 0 )
            return "CERO";
        else if( status == 1 )
            return "UNO";
        else if( status == 2 )
            return "DOS";
    }
    $scope.getSubjects = function(){
        SubjectsService.getSubjects(
            function(success){
                if( success.status == NO_CONTENT ){
                    Notification.primary('No hay materias registradas');
                }
                else{
                    Notification.success('Datos obtenidos');
                    $scope.subjects = success.data;
                }
                    
            },
            function( error ){
                $scope.errorSnack("Error al obtener materia");
            });
    }
    $scope.add = function(subject){
        //Se pone en cargando
        $scope.newSubject.status = true

        //Se hace peticion
        SubjectsService.addSubject(subject, 
            function(success){
                Notification.success('Datos obtenidos');
                $scope.getSubjects();
            }, 
            function(error){
                $scope.errorSnack("Error al registrar materia: "+error.data.message);
            });
    }

    
    $scope.deleteSubject = function(subject_id){
        SubjectsService.deleteSubject(subject_id,
            function(success){
                $scope.getSubjects();
            },
            function(error){
                $scope.errorSnack("Error al eliminar materia");
            });
    }

    //Se carguen datos al iniciar pagina
    $scope.getSubjects();

});