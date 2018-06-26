angular.module("AuthModule", ['LocalStorageModule'])

    .constant('USER_ROLES', {
        admin:  'administrator',
        mod:    'moderator',
        basic:  'basic'
    })

    .constant('STORAGE', {
        user: 'PAR_USER',
        token: 'PAR_TOKEN'
    })

    //Para cambiar tipo de session, es decir, por defecto usa LocalStorage, se puede cambiar a sessionStorage
    .config(function(localStorageServiceProvider){
        //Al cerrar navegador, se cierra sesión
        // localStorageServiceProvider.setStorageType('sessionStorage');
    })

    .factory('AuthFactory', function($http, USER_ROLES, STORAGE, localStorageService){

        //TODO: separar cada tipo y unificarlo en un sólo objeto
        var authentication = {

        };
        var session = {

        };
        var factory = {};

        return{
            setSession: function(data){
                this.setToken( data.token );
                this.setUser( data.user );
            },

            setToken: function(token){
                localStorageService.set(STORAGE.token, token);
            },

            getToken: function(){
                //Si hay un token
                if( localStorageService.get(STORAGE.token) )
                    return localStorageService.get(STORAGE.token);
                else
                    return null;
            },

            setUser: function(user){
                localStorageService.set(STORAGE.user, JSON.stringify(user));
            },

        
            getUser: function(){
                if( localStorageService.get(STORAGE.user) !== undefined ){
                    var data = localStorageService.get(STORAGE.user);
                    return  JSON.parse(data);
                }
                return null;
            },

            removeSession: function(){
                alert("Se esta cerrando sesión");
                localStorageService.remove(STORAGE.user);
                localStorageService.remove(STORAGE.token);
            },

            isAuthenticated: function () {
                var auth = this.getUser();
                //Si no es null o vacío
                return auth != null &&
                    auth !== "" &&
                    auth !== undefined;
            },

            isStudent: function(){
                var data = this.getUser();
                if( data != null )
                    return data.role === USER_ROLES.basic;
                return false;
            },

            isStaff: function(){
                var data = this.getUser();
                if( data != null ){
                    return data.role === USER_ROLES.mod ||
                        data.role === USER_ROLES.admin;
                }
                return false;
            }

        };

    });
