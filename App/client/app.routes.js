angular.module("LoginApp").config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {
    $routeProvider
        .when("/signin", {
            controller: "SigninController",
            templateUrl: "app/components/signin/signinView.html"
        })
        .when("/signup", {
            controller: "SignupController",
            templateUrl: "app/components/signup/signupView.html"
        })
        // .when("/confirm/:token", {
        //     controller: "ConfirmController",
        //     templateUrl: "app/components/confirm/confirmView.html"
        // })
        .otherwise("/signin");

        //Por aquellos del CORS
        // $httpProvider.defaults.headers.common = {};
        // $httpProvider.defaults.headers.post = {};
        // $httpProvider.defaults.headers.put = {};
        // $httpProvider.defaults.headers.get = {}
        // $httpProvider.defaults.headers.patch = {};
}]);
