angular.module("Dashboard").controller('PeriodsController', function($scope,  Notification, PeriodsService, STATUS){
    
    $scope.page.title = "Periodos";
    $scope.showNewPeriod = false;
    $scope.showUpdatePeriod = false;
    $scope.periods = [];
    $scope.loading = true;
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
    };

    /**
     * Obtiene periodos registrados
     */
    $scope.getPeriods = function(){

        $scope.showUpdatePeriod = false;
        $scope.showNewPeriod = false;
        $scope.loading = true;

        PeriodsService.getPeriods()
            .then(function(success){

                if( success.status == STATUS.NO_CONTENT )
                    $scope.periods = [];
                else
                    $scope.periods = success.data;

                $scope.loading = false;

            },
            function(error){
                Notification.error("Error al obtener periodos: "+error.data);
                $scope.loading = false;
            });
    };

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
        
        
        PeriodsService.addPeriod(period)
            .then(function(success){
                Notification.success("Registrado con exito");
                $scope.getPeriods();
            },
            function(error){
                Notification.error("Error: "+error.data);
            })
            .finally(function(){
                $scope.showNewPeriod = false;
            });
            
    };


    $scope.editPeriod = function(period){
        $scope.period.id = period.id;

        //FIXME: El dia se le resta -1, debe ser igual
        $scope.period.start = new Date( period.start );
        $scope.period.end = new Date( period.end );

        $scope.showUpdatePeriod = true;
    };


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
        
        
        PeriodsService.updatePeriod(period)
            .then(function(success){
                Notification.success("Actualizado con exito");
                $scope.getPeriods();
            },
            function(error){
                Notification.error("Error: "+error.data);
            })
            .finally(function(){
                $scope.showUpdatePeriod = false;
            });
    };


    

    $scope.deletePeriod = function(period_id){

        Notification("Procesando...");
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-period-'+period_id);
        
        PeriodsService.deletePeriod(period_id)
            .then(function(success){
                Notification.success("Eliminado con exito");
                $scope.getPeriods();
            },
            function(error){
                Notification.success("Error: "+error.data);
                $scope.disableButtons(false, '.opt-period-'+period_id);
            }
        );
    };

    /**
     * 
     * @param {*} period_id 
     */
    $scope.disablePeriod = function(period_id){
        $scope.disableButtons(true, '.opt-period-'+period_id);
        Notification("Procesando...");

        PeriodsService.changeStatus(period_id, DISABLED)
            .then(function(success){
                Notification.success("Deshabilitado con exito");
                $scope.getPeriods();
            },
            function(error){
                Notification.error("Error al Deshabilitar periodo: "+error.data);
                $scope.disableButtons(false, '.opt-period-'+period_id);
            }
        );
    };

    /**
     * 
     * @param {*} period_id 
     */
    $scope.enablePeriod = function(period_id){
        $scope.disableButtons(true, '.opt-period-'+period_id);
        Notification("Procesando...");

        PeriodsService.changeStatus(period_id, ACTIVE)
            .then(function(success){
                Notification.success("habilitado con exito");
                getPeriods();
            },
            function(error){
                Notification.error("Error al habilitar periodo: "+error.data);
                $scope.disableButtons(false, '.opt-period-'+period_id);
            }
        );
    };

    (function(){    
        //Obtiene todos por default
        $scope.getPeriods();
    })();

});