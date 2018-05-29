app.config( function ($routeProvider) {
    $routeProvider
        .when("/", {
            controller: "",
            templateUrl: "app/components/home/homeView.html"
        })
        .when("/signup", {
            controller: "SignupController",
            templateUrl: "app/components/signup/signupView.html"
        })
        .when("/users", {
            controller: "UsersController",
            templateUrl: "app/components/users/usersView.html"
        })
        .otherwise("/");
});
