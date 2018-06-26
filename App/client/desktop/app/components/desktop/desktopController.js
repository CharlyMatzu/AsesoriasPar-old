angular.module("Desktop").controller('DesktopController', function($scope, $http, $routeParams, $window, $location){

    $scope.showSubmenu = true;
    
    $scope.showAdvisories = false;
    $scope.showStudents = false;
    $scope.showSubjects = false;
    $scope.showSchedule = false;
    $scope.menu.title = 'desktop';
    $scope.page.title = 'Escritorio';
    $scope.submenu = '';


    var checkRouteParam = function(){
        //Chequeo de ruta para mostrar datos
        // console.log("RUTA: "+$routeParams.route);
        
        //FIXME: arreglar
        //Si existe un valor de parametro
        if( $routeParams.route ){

            var submenu = $routeParams.route;
            //Para activar clase de submenus
            $scope.submenu = submenu;

            //Si no hay periodo no permite redireccionar
            if( !$scope.period ){
                $window.location = "#!/escritorio";
                return;
            }
                
            switch( submenu ){
                case 'asesorias': $scope.showAdvisories = true; break;
                case 'alumnos': $scope.showStudents = true; break;
                case 'materias': $scope.showSubjects = true; break;
                case 'horario': $scope.showSchedule = true; break;
    
                default: $window.location = "#!/escritorio/asesorias"; break;
            }
        }
        else{
            if( $scope.period )
                $window.location = "#!/escritorio/asesorias";
        }
        
    };


    (function(){
        //Si hay periodo, se va directo
        if( $routeParams.period )
            checkRouteParam();
        //si no hay periodo, hace petición
        //TODO: debe hacerlo una vez hasta refrescar página
        else
            $scope.getCurrentPeriod()
                .then(function(success){
                    checkRouteParam();
                }, function(error){
                    console.log("Ocurrio un error");
                })
                .finally(function(){
                    //Desactiva loader general
                    $scope.setLoading(false);
                });
    })();



});