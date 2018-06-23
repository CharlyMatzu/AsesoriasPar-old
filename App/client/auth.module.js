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
        //Al cerrar navegador, se cierra sesion
        localStorageServiceProvider.setStorageType('sessionStorage');
    })

    .factory('AuthFactory', function($http, USER_ROLES, STORAGE, localStorageService){

        // var isSupported = function(){
        //     if(localStorageService.isSupported) {
        //         //...
        //     }
        // };

        return{
            setSession: function(data){
                this.setToken( data.token );
                this.setUser( data.user );
            },

            setToken: function(token){
                localStorageService.set(STORAGE.token, token);
            },

            getToken: function(){
                if( localStorageService.get(STORAGE.token) != undefined ){
                    var token = localStorageService.get(STORAGE.token);
                    return token;
                }
                return null;
            },

            setUser: function(user){
                localStorageService.set(STORAGE.user, JSON.stringify(user));
            },

        
            getUser: function(){
                if( localStorageService.get(STORAGE.user) != undefined ){
                    var data = localStorageService.get(STORAGE.user);
                    user = JSON.parse(data);
                    return user;
                }
                return null;
            },

            removeSession: function(){
                localStorageService.remove(STORAGE.user);
                localStorageService.remove(STORAGE.token);
            },

            isAuthenticated: function () {
                var auth = this.getUser();
                //Si no es null o vacio
                if( auth != null && 
                    auth != "" && 
                    auth != undefined )
                    return true;
                else
                    return false;
            },

            isStudent: function(){
                var data = this.getUser();
                if( data != null ){
                    if( data.role === USER_ROLES.basic )
                        return true;
                    else
                        return false;
                }
                return false;
            },

            isStaff: function(){
                var data = this.getUser();
                if( data != null ){
                    if( data.role === USER_ROLES.mod || 
                        data.role === USER_ROLES.admin )
                        return true;
                    else
                        return false;
                }
                return false;
            },

        };

    });
