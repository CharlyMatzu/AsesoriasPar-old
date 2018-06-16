angular.module("AuthModule", ['LocalStorageModule'])

    .constant('USER_ROLES', {
        admin: 'administrator',
        mod: 'moderator',
        basic: 'basic'
    })

    .constant('STORAGE', {
        user: 'PAR_USER',
        student: 'PAR_STUDENT',
    })

    .config(function(localStorageServiceProvider){
        // localStorageServiceProvider.setStorageType('sessionStorage');
    })

    .factory('AuthFactory', function($http, USER_ROLES, STORAGE, localStorageService){

        var isSupported = function(){
            // if(localStorageService.isSupported) {
            //     //...
            // }
        };

        return{
            setUser: function(user){
                localStorageService.set(STORAGE.user, JSON.stringify(user));
            },

            removeUser: function(){
                localStorageService.remove(STORAGE.user);
            },

            isAuthenticated: function () {
                var auth = localStorageService.get(STORAGE.user);
                if( auth ){
                    //Si no es null o vacio
                    if( auth != null && auth != "" )
                        return true;
                    else
                        return false;
                }
                else
                    return false;
            },

            // isAuthorized: function ( authorizedRoles ) {
            //     if (!angular.isArray(authorizedRoles)) {
            //         authorizedRoles = [authorizedRoles];
            //     }
            //     return (authService.isAuthenticated() &&
            //         a
            // },

            isStudent: function(){
                var data = this.getData();
                if( data != null ){
                    if( data.role === USER_ROLES.basic )
                        return true;
                    else
                        return false;
                }
                return false;
            },

            isStaff: function(){
                var data = this.getData();
                if( data != null ){
                    if( data.role === USER_ROLES.mod || 
                        data.role === USER_ROLES.admin )
                        return true;
                    else
                        return false;
                }
                return false;
            },

            getToken: function(){
                if( this.isAuthenticated() ){
                    var user = localStorageService.get(STORAGE.user);
                    user = JSON.parse(user);
                    return user.token;
                }
                return null;
            },

            getData: function(){
                if( this.isAuthenticated() ){
                    var user = localStorageService.get(STORAGE.user);
                    user = JSON.parse(user);
                    return user.user;
                }
                return null;
            },

        };

    });
