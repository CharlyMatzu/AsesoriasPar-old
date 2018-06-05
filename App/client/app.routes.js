app.config( function ($routeProvider) {
    $routeProvider
        .when("/signin", {
            controller: "SigninController",
            templateUrl: "app/components/signin/signinView.html"
        })
        .when("/signup", {
            controller: "SignupController",
            templateUrl: "app/components/signup/signupView.html"
        })
        .when("/confirm/:token", {
            controller: "ConfirmController",
            templateUrl: "app/components/confirm/confirmView.html"
        })
        .otherwise("/signin");
});
