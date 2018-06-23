var app = angular.module("Desktop", ['ngRoute', 'ui-notification', 'HostModule', 'AuthModule']);



app.run(function($rootScope, $window, $http, RequestFactory, AuthFactory){

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
        AuthFactory.removeUser();
        // $window.location.href = "/";
    }

    // $rootScope.setActiveMenu = function(event){
    //     $(event.currentEvent).
    // }

    //TODO: Hacer un evento para cargar datos del estudiante y avisar a otros controladores cuando esta ya este lista
    $rootScope.getStudentData = function(){
        var user = AuthFactory.getData();
        $rootScope.user = user;

        RequestFactory.makeTokenRequest(
            'GET',
            "/users/"+user.id+"/student",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    };

});