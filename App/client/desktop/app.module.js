angular.module("Desktop", ['ngRoute', 'ui-notification', 'HostModule', 'AuthModule', 'ngAnimate'])


    .run(function($rootScope, $window, $http, RequestFactory, AuthFactory, STATUS, $q, $timeout){

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
        };

        $rootScope.setUser = function(user){
            $rootScope.user = user;
            AuthFactory.setUser( user );
        };

        $rootScope.setStudent = function(student){
            $rootScope.student = student;
        };
        

        $rootScope.signOut = function(){
            // alert("Se esta cerrando sesion");
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
                    console.log(error.data);
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
        };



        //TODO: si dicho usuario esta deshabilitado, no debe poder hacerse nada y debe sacarse
        $rootScope.getUser = function(){
            // //Se obtiene usuario guardado
            var user = AuthFactory.getUser();

            if( user == null || user.role !== 'basic' ){
                alert("Usuario no es estudiante o no esta autenticado");
                $rootScope.signOut();
            }
            else{
                // //Se hace petición para obtener usuario actualizado (en caso de tener cambios)
                var promise = RequestFactory.makeTokenRequest(
                    'GET',
                    "/users/"+user.id,
                    null,
                    AuthFactory.getToken(),
                );
                return promise;
            }

            
        };

        $rootScope.storageCurrentUrl = function(){
            
            // var url = $window.location.href;
            // //Si la ruta es loading, se pone otra por defecto
            // if( url.search('#!/loading') !== -1 )
            //     url = "#!/escritorio";

            // $rootScope.currentLoc = url;
            $rootScope.currentLoc = "#!/escritorio";
        };

        
        (function(){
            //Almacena la ruta actual con la que se accedio para posteriormente redireccionar
            $rootScope.storageCurrentUrl();
            //Para que inicie en dicho directorio
            $window.location.href = "#!/loading";
        })();

        
        //TODO: siempre debe hacer peticiones al servidor para obtener un nuevo token y verificar la sesion


    })
    // end run


    //Primer controlador en iniciar
    .controller('InitController', function($scope, $window, AuthFactory){
        $scope.setLoading(true);

        (function(){
            $scope.getUser()
                //Promesa de usuario
                .then(function(success){ 

                        //TODO: verificar que esta activo
                        var user = success.data;
                        if( user.status === 'ACTIVE' ){
                            $scope.setUser(user);
                            return $scope.getStudent( user.id );
                        }
                        else
                            $scope.signOut();

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
                    //Se refirecciona a ruta con la que se inicio
                    $window.location = $scope.currentLoc;
                });
        })();
        
    });
