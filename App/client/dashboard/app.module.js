angular.module("Dashboard", ['ngRoute', 'ui-notification', 'LocalStorageModule', 'HostModule', 'AuthModule']);

angular.module("Dashboard")
    //Cuando iniciar el modulo
    .run(function($rootScope, $window, AuthFactory, UtilsFactory){
        
        $rootScope.session = {};
        $rootScope.token = {};

        //-----------VARIABLES GLOBALES
        $rootScope.page = {
            title: "PAGE TITLE"
        };

        //-STATUS VARIABLES
        $rootScope.alert = {
            type: "",
            status: false,
            message: "",
        };


        //-----------FUNCIONES GLOBALES
        /**
         * Funcion para habilitar/deshabilitar los botones de un elemento para que no se presionen de nuevo
         * @param {bool} disabled TRUE para deshabilitar, FALSE para habilitar
         * @param {String} btnClass clase de referencia de botones a deshabilitar/habilitar
         */
        $rootScope.disableButtons = function(disabled, btnClass){
            $(btnClass).each(function(){
                //console.log("Elemento: "+$(this).text() );
                $(this).prop('disabled', disabled);
            });
        };

        $rootScope.signOut = function(){
            AuthFactory.removeSession();
            $window.location.href = "/";
        };

        //Verifica el rol, si es administrador retorna true
        $rootScope.isAdmin = function(){
            return UtilsFactory.isAdmin();
        };


        //Verifica la sesion
        (function(){
            if( AuthFactory.isAuthenticated() ){
                if( !AuthFactory.isStaff() )
                    $window.location.href = "/";
                // Se obtiene informacion del usuario
                else
                    $rootScope.session = AuthFactory.getUser();
            }
            else{
                $window.location.href = "/";
            }
        })();


        
        
    })

    .factory('UtilsFactory', function(AuthFactory, $window){
        
        return {
            isAdmin: function(){
                return AuthFactory.getUser().role === 'administrator';
            },

            //Si no es administrador, redirecciona (utilizado en routes)
            onlyAdmin_Redirect: function(){
                if( !this.isAdmin() )
                    $window.location = "#!/";
                
            }
        };            

    });