app.controller('PeriodsController', function($scope, $http, Notification, PeriodsService){
    $scope.page.title = "Periodos";
    
    $scope.periods = [];
    $scope.period = {
        id: 0,
        start: null,
        end: null
    }

    /**
     * Formatea la fecha
     * @param {*} date 
     */
    var cutDate = function(date){
        var date = new Date(date);
        
        var month = (date.getMonth() + 1);
        if( month < 10 )
            month = "0"+month;

        var day = (date.getDate());
        if( day < 10 )
            day = "0"+day;

        var newDate = date.getFullYear() +"/"+ month +"/"+ day; //month starts in 0 end in 11
        return newDate;
    }

    /**
     * Obtiene periodos registrados
     */
    var getPeriods = function(){

        $scope.periods = [];
        $scope.showUpdateForm = false;
        $scope.loading.status = true;
        $scope.loading.message = "Obteniendo registros";

        PeriodsService.getPeriods(
            function(success){
                if( success.status == NO_CONTENT ){
                    //Notification.primary("no hay registros");
                    $scope.loading.message = "No hay registros";
                }
                else
                    $scope.periods = success.data;

                $scope.loading.status = false;
            },
            function(error){
                Notification.error("Error al obtener periodos: "+error.data);
                $scope.loading.status = false;
                $scope.loading.message = "Error: "+error.data;
            }
        );
    }

    /**
     * Agrega un periodo
     * @param {*} period 
     */
    $scope.addPeriod = function(period){

        //Validacion
        if( period.end <= period.start ){
            Notification.warning("Fecha de termino debe ser posterior a la de inicio");
            return;
        }

        Notification("Procesando...");
        //Cambiando formato
        // period.start = cutDate(period.start);
        // period.end = cutDate(period.end);
        
        
        PeriodsService.addPeriod(period, 
            function(success){
                Notification.success("Registrado con exito");
                getPeriods();
            },
            function(error){
                Notification.error("Error: "+error.data);
            }
        );
    }


    $scope.editPeriod = function(period){
        $scope.period.id = period.id;
        //FIXME: El dia se le resta -1, debe ser igual
        $scope.period.start = new Date(period.start);
        $scope.period.end = new Date(period.end);

        $scope.showUpdateForm = true;
    }


    $scope.updatePeriod = function(period){

        //Validacion
        if( period.end <= period.start ){
            Notification.warning("Fecha de termino debe ser posterior a la de inicio");
            return;
        }

        Notification("Procesando...");
        //Cambiando formato
        period.start = cutDate(period.start);
        period.end = cutDate(period.end);
        
        
        PeriodsService.updatePeriod(period, 
            function(success){
                Notification.success("Actualizado con exito");
                getPeriods();
            },
            function(error){
                Notification.error("Error: "+error.data);
            }
        );
    }


    

    $scope.deletePeriod = function(period_id){

        Notification("Procesando...");
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-period-'+period_id);
        
        PeriodsService.deletePeriod(period_id,
            function(success){
                Notification.success("Eliminado con exito");
                getPeriods();
            },
            function(error){
                Notification.success("Error: "+error.data);
                $scope.disableButtons(false, '.opt-period-'+period_id);
            }
        );
    }

    /**
     * 
     * @param {*} period_id 
     */
    $scope.disablePeriod = function(period_id){
        $scope.disableButtons(true, '.opt-period-'+period_id);
        Notification("Procesando...");

        PeriodsService.changeStatus(period_id, DISABLED,
            function(success){
                Notification.success("Deshabilitado con exito");
                getPeriods();
            },
            function(error){
                Notification.error("Error al Deshabilitar periodo: "+error.data);
                $scope.disableButtons(false, '.opt-period-'+period_id);
            }
        );
    }

    /**
     * 
     * @param {*} period_id 
     */
    $scope.enablePeriod = function(period_id){
        $scope.disableButtons(true, '.opt-period-'+period_id);
        Notification("Procesando...");

        PeriodsService.changeStatus(period_id, ENABLED,
            function(success){
                Notification.success("habilitado con exito");
                getPeriods();
            },
            function(error){
                Notification.error("Error al habilitar periodo: "+error.data);
                $scope.disableButtons(false, '.opt-period-'+period_id);
            }
        );
    }

    //Obtiene todos por default
    getPeriods();

});