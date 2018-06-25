angular.module("Desktop").controller('DesktopController', function($scope, $http, $routeParams, $window, $location){



    $scope.showSubmenu = false;
    $scope.showAdvisories = false;
    $scope.showAlumns = false;
    $scope.showSubjects = false;
    $scope.showSchedule = false;
    $scope.menu.title = 'desktop';
    $scope.submenu = '';
    $scope.page.title = 'Escritorio';


    var checkRouteParam = function(){
        $scope.showSubmenu = true;

        //Chequeo de ruta para mostrar datos
        var submenu = $routeParams.route;
        $scope.submenu = submenu;

        switch( submenu ){
            case 'asesorias': $scope.showAdvisories = true; break;
            case 'alumnos': $scope.showAlumns = true; break;
            case 'materias': $scope.showSubjects = true; break;
            case 'horario': $scope.showSchedule = true; break;

            default: $window.location = "#!/escritorio/asesorias";
        }
    };


    (function(){
        $scope.loadData(
            function(){
                // console.log("Datos cargados");
            }
        );
        // $scope.$watch('period', function(){
        //     console.log("Cambio valor");
        // });
        checkRouteParam();
        
    })();

});