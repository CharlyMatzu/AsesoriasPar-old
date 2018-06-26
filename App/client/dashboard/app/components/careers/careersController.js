angular.module("Dashboard").controller('CareersController', function($scope, $http, Notification, CareerService){
    $scope.page.title = "Carreras";
    
    $scope.careers = [];
    $scope.career = {
        id: null,
        name: null,
        short_name: null
    }

    
    /**
     * Obtiene carreras registrados
     */
    $scope.getCareers = function(){

        $scope.showUpdateForm = false;
        $scope.loading.status = true;
        $scope.loading.message = "Obteniendo registros";

        CareerService.getCareers()
            .then(function(success){
                if( success.status == NO_CONTENT ){
                    //Notification.primary("no hay registros");
                    $scope.loading.message = "No hay registros";
                }
                else
                    $scope.careers = success.data;

                $scope.loading.status = false;
            },
            function(error){
                Notification.error("Error al obtener carreras: "+error.data);
                $scope.loading.status = false;
                $scope.loading.message = "Error: "+error.data;
            }
        );
    }

    /**
     * Agrega un carrera
     * @param {*} career 
     */
    $scope.addCareer = function(career){
        Notification("Procesando...");
        
        CareerService.addCareer(career)
            .then(function(success){
                Notification.success("Registrado con exito");
                $scope.getCareers();
            },
            function(error){
                Notification.error("Error: "+error.data);
            }
        );
    }


    $scope.editCareer = function(career){
        $scope.career = career
        $scope.showUpdateForm = true;
    }


    $scope.updateCareer = function(career){
        Notification("Procesando...");
        
        CareerService.updateCareer(career)
            .then(function(success){
                Notification.success("Actualizado con exito");
                $scope.getCareers();
            },
            function(error){
                Notification.error("Error: "+error.data);
            }
        );
    }


    

    $scope.deleteCareer = function(career_id){

        Notification("Procesando...");
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-career-'+career_id);
        
        CareerService.deleteCareer(career_id)
            .then(function(success){
                Notification.success("Eliminado con exito");
                $scope.getCareers();
            },
            function(error){
                Notification.success("Error: "+error.data);
                $scope.disableButtons(false, '.opt-career-'+career_id);
            }
        );
    }

    /**
     * 
     * @param {*} career_id 
     */
    $scope.disableCareer = function(career_id){
        $scope.disableButtons(true, '.opt-career-'+career_id);
        Notification("Procesando...");

        CareerService.changeStatus(career_id, DISABLED)
            .then(function(success){
                Notification.success("Deshabilitado con exito");
                $scope.getCareers();
            },
            function(error){
                Notification.error("Error al Deshabilitar carrera: "+error.data);
                $scope.disableButtons(false, '.opt-career-'+career_id);
            }
        );
    }

    /**
     * 
     * @param {*} career_id 
     */
    $scope.enableCareer = function(career_id){
        $scope.disableButtons(true, '.opt-career-'+career_id);
        Notification("Procesando...");

        CareerService.changeStatus(career_id, ACTIVE,
            function(success){
                Notification.success("habilitado con exito");
                $scope.getCareers();
            },
            function(error){
                Notification.error("Error al habilitar carrera: "+error.data);
                $scope.disableButtons(false, '.opt-career-'+career_id);
            }
        );
    }


    //Obtiene todos por default
    $scope.getCareers();

});