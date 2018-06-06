var app = angular.module("Desktop", ['ngRoute', 'ui-notification', 'LocalStorageModule']);



app.run(function($rootScope, $window, $http, localStorageService){

    $rootScope.student = {};
    $rootScope.user = {};
    $rootScope.token = {};
    $rootScope.period = {
        data: null,
        message: ""
    };
    $rootScope.loading = {
        status: false,
        message: ""
    };
    
    //Verifica la sesion
    (function(){
        if( localStorageService.get('user') ){
            $rootScope.loading.status = true;

            var data = localStorageService.get('user');
            data = JSON.parse( data );
            //Se verifica rol y se redirecciona
            if( data.user.role !== 'basic' )
                $window.location.href = "/dashboard";
            else{
                //Obtiene datos de estudiante
                $rootScope.user = data.user;
                $rootScope.token = data.token;
                $http({
                    method: 'GET',
                    url: "http://api.ronintopics.com/index.php/users/"+$rootScope.user.id+"/student"
                }).then(function(success){
                    $rootScope.student = success.data;
                    console.log("Estudiante cargado");
                }, function(error){
                    localStorageService.remove('user')
                    $window.location.href = "/";
                });

                //Obteniendo periodo actual
                $http({
                    method: 'GET',
                    url: "http://api.ronintopics.com/index.php/periods/current"
                }).then(function(success){
                    if( success.status == NO_CONTENT ){
                        $rootScope.loading.status = false;
                        $rootScope.period.message = "No hay un periodo actual disponible";
                        console.log("Periodo no encontrado");
                    }
                    else{
                        $rootScope.period.data = success.data;
                        console.log("Periodo cargado");
                    }

                    $rootScope.loading.status = false;
                }, function(error){
                    $rootScope.loading.status = false;
                    
                });
            }
        }
        else
            $window.location.href = "/";
    })();

    $rootScope.signOut = function(){
        localStorageService.remove('user');
        $window.location.href = "/";
    }

    // $rootScope.setActiveMenu = function(event){
    //     $(event.currentEvent).
    // }

});