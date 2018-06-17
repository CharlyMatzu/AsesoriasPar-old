angular.module("LoginApp").config(['$routeProvider', function ($routeProvider) {
    $routeProvider
        .when("/signin", {
            controller: "SigninController",
            templateUrl: "app/components/signin/signinView.html",
            authenticate: false
        })
        .when("/signup", {
            controller: "SignupController",
            templateUrl: "app/components/signup/signupView.html",
            authenticate: false
        })
        .when("/confirm", {
            controller: "ConfirmController",
            templateUrl: "app/components/confirm/confirmView.html",
            authenticate: true
        })
        .otherwise("/signin");


        

        //Por aquellos del CORS
        // $httpProvider.defaults.headers.common = {};
        // $httpProvider.defaults.headers.post = {};
        // $httpProvider.defaults.headers.put = {};
        // $httpProvider.defaults.headers.get = {}
        // $httpProvider.defaults.headers.patch = {};
}]);
