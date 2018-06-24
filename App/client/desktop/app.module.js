angular.module("Desktop", ['ngRoute', 'ui-notification', 'HostModule', 'AuthModule'])


    .run(function($rootScope, $window, $http, RequestFactory, AuthFactory, STATUS){

        $rootScope.student = null;
        $rootScope.user = null;
        $rootScope.token = null;
        $rootScope.period = null;

        $rootScope.loading = {
            status: false,
            message: ""
        };
        

        $rootScope.signOut = function(){
            AuthFactory.removeSession();
            $window.location.href = "/";
        };


        $rootScope.getCurrentPeriod = function(dataCallback){
            //Si ya hay datos, se llama al callback
            if( $rootScope.period != null ){
                dataCallback();
                return;
            }

            var promise = RequestFactory.makeTokenRequest(
                'GET',
                "/periods/current",
                null,
                AuthFactory.getToken()
            );

            promise.then(
                function(success){
                    if( success.status !== STATUS.NO_CONTENT ){
                        $rootScope.period = success.data;
                        dataCallback();
                    }
                    else
                        $rootScope.period = null;
                },
                function(error){
                    $rootScope.period = null;
                });
        };




        // //TODO: debe verificarse esto antes de cargar el sitio
        var getStudent = function(user_id, successCallback){
            var promise = RequestFactory.makeTokenRequest(
                'GET',
                "/users/"+user_id+"/student",
                null,
                AuthFactory.getToken()
            );

            promise.then(
                function(success){
                    //Se asigna estudiante
                    $rootScope.student = success.data;
                    successCallback();
                },
                function(error){
                    console.log("Error: "+error.data);
                    $rootScope.signOut();
                }
            );
        };



        //TODO: si dicho usuario esta deshabilitado, no debe poder hacerse nada y debe sacarse
        $rootScope.getUser = function(successCallback){
            // //Se obtiene usuario guardado
            var user = AuthFactory.getUser();

            //Se hace peticion para obtener usuario actualizado (en caso de tener cambios)
            var promise = RequestFactory.makeTokenRequest(
                'GET',
                "/users/"+user.id,
                null,
                AuthFactory.getToken(),
            );
            
            promise.then(
                function(success){
                    AuthFactory.setUser( success.data );
                    $rootScope.user = success.data;

                    //Se obtiene estudiante
                    getStudent( user.id, successCallback );
                },
                function(error){
                    console.log("Error: "+error.data);
                    $rootScope.signOut();
                }
            );
        };


        $rootScope.loadData = function(callback){
            $rootScope.getCurrentPeriod(
                function(){
                    $rootScope.getUser(
                        function(){
                            callback();
                        }
                    );
                }
            )
        };

        

        (function(){
            $rootScope.loadData(
                function(){
                    //TODO: quitar loader
                }
            );
        })();


        });
        // end run
