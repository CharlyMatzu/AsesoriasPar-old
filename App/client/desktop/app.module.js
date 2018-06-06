var app = angular.module("Desktop", ['ngRoute', 'ui-notification', 'LocalStorageModule']);



app.run(function($rootScope, $window, $http, localStorageService){

    $rootScope.student = {};
    $rootScope.user = {};
    $rootScope.token = {};
    
    //Verifica la sesion
    (function(){
        if( localStorageService.get('user') ){
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
                }, function(error){
                    localStorageService.remove('user')
                    $window.location.href = "/";
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

});