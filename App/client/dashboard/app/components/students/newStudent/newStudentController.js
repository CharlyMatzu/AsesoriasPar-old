angular.module("Dashboard").controller('NewStudentController', function($scope, NewStudentService, CareerService, Notification, STATUS, $window){
    
    
    $scope.page.title = "Estudiantes > Nuevo";
    $scope.loading = true;
    $scope.enabledCareers = [];


    /**
     * 
     * @param {*} student 
     * @return bool
     */
    var validate = function(student){

        if( student.career == null ){
            Notification.warning('Debe seleccionar una carrera');
            return false;
        }

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
        $scope.loading = true;

        //Peticion
        NewStudentService.addStudent(student)
            .then(function(success){
                Notification.success("Registrado");
                $scope.alert.type = 'success';
                $scope.alert.message = "Se ha registrado estudiante correctamente"
                $scope.loading = false;
                $window.location = "#!/estudiantes";
            },
            function (error){
                if( error.status === STATUS.CONFLICT )
                    $scope.alert.type = 'warning';
                else
                    $scope.alert.type = 'error';

                $scope.alert.message = error.data;
                $scope.loading = false;
                
            }
        );
    };


    (function(){
        // $scope.loading = true;
        //Se cargan carreras
        CareerService.getCareers()
            .then(function(success){

                //Si hay carreras
                if( success.status === STATUS.OK ){
                    var elementos = success.data;
                    //Se buscan las carreras activas
                    elementos.forEach(carrera => {
                        //Si la carrera esta activa se agrega a arreglo
                        if( carrera.status === 'ACTIVE' )
                            $scope.enabledCareers.push( carrera );
                    });
                }

                if( success.status === STATUS.NO_CONTENT || 
                    $scope.enabledCareers.length == 0 ){
                    alert("No hay carreras disponibles, se redireccionará");
                    //Redireccion a carreras
                    $window.location = "#!/carreras";
                    return;
                }

                $scope.loading = false;

            }, function(error){
                Notification.error("Ocurrio un error al cargar carreras: "+error.data);
                // $scope.loading = false;
            }); 
    })();


});