angular.module("Desktop", ['ngRoute', 'ui-notification', 'HostModule', 'AuthModule', 'ngAnimate'])


    .run(function($rootScope, $window, $http, RequestFactory, AuthFactory, STATUS, $q, $timeout, $window){

        $rootScope.loadingState = true;
        $rootScope.student = null;
        $rootScope.user = null;
        $rootScope.period = null;
        $rootScope.menu = {
            title: 'TITLE'
        };
        $rootScope.page = {
            title: "PAGE TITLE"
        };

        
        $rootScope.setLoading = function(state){
            $rootScope.loadingState = state;
        }

        $rootScope.setUser = function(user){
            $rootScope.user = user;
            AuthFactory.setUser( user );
        }

        $rootScope.setStudent = function(student){
            $rootScope.student = student;
        }
        

        $rootScope.signOut = function(){
            AuthFactory.removeSession();
            $window.location.href = "/";
        };


        $rootScope.getCurrentPeriod = function(){
            var promise = RequestFactory.makeTokenRequest(
                'GET',
                "/periods/current",
                null,
                AuthFactory.getToken()
            );

            promise.then(
                function(success){
                    if( success.status != STATUS.NO_CONTENT )
                        $rootScope.period = success.data;
                }, function(error){
                    console.log(erro.data);
                });

            return promise;
        };




        // //TODO: debe verificarse esto antes de cargar el sitio
        $rootScope.getStudent = function(user_id){

            var promise = RequestFactory.makeTokenRequest(
                'GET',
                "/users/"+user_id+"/student",
                null,
                AuthFactory.getToken()
            );
            return promise;

            // promise.then(
            //     function(success){
            //         //Se asigna estudiante
            //         $rootScope.student = success.data;
            //         successCallback();
            //     },
            //     function(error){
            //         alert("Error: "+error.data);
            //         $rootScope.signOut();
            //     }
            // );
        };



        //TODO: si dicho usuario esta deshabilitado, no debe poder hacerse nada y debe sacarse
        $rootScope.getUser = function(){
            // //Se obtiene usuario guardado
            var user = AuthFactory.getUser();

            if( user == null || user.role !== 'basic' ){
                alert("Ocurrio un error");
                $rootScope.signOut();
            }
            else{
                // //Se hace peticion para obtener usuario actualizado (en caso de tener cambios)
                var promise = RequestFactory.makeTokenRequest(
                    'GET',
                    "/users/"+user.id,
                    null,
                    AuthFactory.getToken(),
                );
                return promise;
            }
            
            // promise.then(
            //     function(success){
            //         AuthFactory.setUser( success.data );
            //         $rootScope.user = success.data;
            //     },
            //     function(error){
            //         console.log("Error: "+error.data);
            //         $rootScope.signOut();
            //     }
            // );

            
        };


        // $scope.loadData = function(){
            // $rootScope.getUser()
            //     //Promesa de usuario
            //     .then(function(success){ 
            //         var user = success.data;
            //         $rootScope.user = user;
            //         AuthFactory.setUser( user );
            //         return $rootScope.getStudent( user.id );
            //     }, function(error){
                    
            //     })
                
            //     //Promesa de estudiante
            //     .then(function(success){ 
            //         var student = success.data;
            //         $rootScope.student = student;
            //     }, function(error){
            //         alert("No existe estudiante asociado");
            //         $rootScope.signOut();
            //     })

            //     // //Promesa de periodo
            //     // .then(function(success){ 
            //     //     if( success.status !== STATUS.NO_CONTENT )
            //     //         $rootScope.period = success.data;
            //     //     else
            //     //         $rootScope.period = null;

            //     // }, function(error){
                    
            //     // })
                
            //     .catch(function(){ console.log("Fallo algo"); })
            //     .finally(function(){
            //         $rootScope.loadingState = false;
            //     });
        // }

        
        (function(){
            //Para que inicie en dicho directorio
            //TODO: tomar directorio actual para redireccionar una vez termine
            $window.location = "#!/loading";
        })();

        
        //TODO: siempre debe hacer peticiones al servidor para obtener un nuevo token y verificar la sesion


    })
    // end run


    //Primer controlador en iniciar
    .controller('InitController', function($scope, $window, AuthFactory){

        (function(){
            $scope.getUser()
                //Promesa de usuario
                .then(function(success){ 

                        var user = success.data;
                        $scope.setUser(user);
                        return $scope.getStudent( user.id );

                    }, function(error){
                })
            //Promesa de estudiante
                .then(function(success){ 

                    var student = success.data;
                    $scope.setStudent(student);

                }, function(error){

                    console.log(error);
                    alert("No existe estudiante asociado");
                    $scope.signOut();
                })
                .finally(function(){
                    $window.location = "#!/escritorio";
                });
        })();
        
    });
