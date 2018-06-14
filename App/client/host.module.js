angular.module("HostModule", [])
    //CONSTANTES
    // .constant('SERVERS',
    //     {  
    //         DEVELOPMENT:    "http://api.asesoriaspar.com",
    //         PRODUCTION:     "http://asesoriaspar.ronintopics.com",
    //         DEVELOP_MODE:    true
    //     }
    // )

    .provider('HostProvider', function(){
        var DEVELOPMENT = "http://api.asesoriaspar.com";
        var PRODUCTION = "http://asesoriaspar.ronintopics.com";
        var DEVELOP_MODE = true;


        //--------------------------Parte del provider
        this.setProductionUrl = function(prodUrl){
            PRODUCTION = prodUrl;
        };

        this.setDevelopmentUrl = function(devUrl){
            DEVELOPMENT = devUrl;
        };

        /**
         * 
         * @param {boolean} flag 
         */
        this.setProductionMode = function(){
            DEVELOP_MODE = false;
        };

        //--------------------------Parte del factory
        var getServerURL = function(){
            if( DEVELOP_MODE == true )
                return DEVELOPMENT;
            else
                return PRODUCTION;
        };

        this.$get = function(){
            return {
                getURL: function() {
                    return getServerURL()+'/index.php';
                },
                getBaseURL: function() {
                    return getServerURL();
                }    
            };
        };
    })

    // .factory('RequestFactory', function(HostProvider){
        
        
    //     var getServerURL = function(){
    //         if( SERVERS.DEVELOP_MODE == true )
    //             return SERVERS.DEVELOPMENT;
    //         else
    //             return SERVERS.PRODUCTION;
    //     };

    //     return {
    //         getURL: function() {
    //             return getServerURL()+'/index.php';
    //         },
    //         getBaseURL: function() {
    //             return getServerURL();
    //         }
    //     }
            
    // })

