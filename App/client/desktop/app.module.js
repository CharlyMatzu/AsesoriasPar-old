var app = angular.module("Desktop", ['ngRoute', 'ui-notification', 'HostModule', 'AuthModule']);



app.run(function($rootScope, $window, $http, RequestFactory, AuthFactory, STATUS){

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
    
    

    $rootScope.signOut = function(){
        AuthFactory.removeSession();
        $window.location.href = "/";
    };


    $rootScope.getCurrentPeriod = function(){
        RequestFactory.makeTokenRequest(
            'GET',
            "/periods/current",
            null,
            AuthFactory.getToken(),
            function(success){
                if( success.status !== STATUS.NO_CONTENT )
                    $rootScope.period = success.data;
                else
                    console.log("No hay periodo actual disponible");
            },
            function(error){
                console.log("Error: "+error.data);
            }
        );
    };

    $rootScope.getStudent = function(user_id){
        RequestFactory.makeTokenRequest(
            'GET',
            "/users/"+user_id+"/student",
            null,
            AuthFactory.getToken(),
            function(success){
                //Se asigna estudiante
                $rootScope.student = success.data;
                //Se obtiene periodo actual
                $rootScope.getCurrentPeriod();
            },
            function(error){
                console.log("Error: "+error.data);
                $rootScope.signOut();
            }
        );
    };



    //TODO: si dicho usuario esta deshabilitado, no debe poder hacerse nada y debe sacarse
    $rootScope.getUser = function(){
        // //Se obtiene usuario guardado
        var user = AuthFactory.getUser();

        //Se hace peticion para obtener usuario actualizado (en caso de tener cambios)
        RequestFactory.makeTokenRequest(
            'GET',
            "/users/"+user.id,
            null,
            AuthFactory.getToken(),
            function(success){
                AuthFactory.setUser( success.data );
                $rootScope.user = success.data;

                //Se obtiene estudiante
                $rootScope.getStudent( user.id );
            },
            function(error){
                console.log("Error: "+error.data);
                $rootScope.signOut();
            }
        );
    };

    

    (function(){
        $rootScope.getUser();
    })();

})
// end run
