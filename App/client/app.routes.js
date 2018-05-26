app.config( function ($routeProvider) {
    $routeProvider
        .when("/", {
            controller: "",
            templateUrl: "app/views/home/homeView.html"
        })
        .when("/signup", {
            controller: "SignupController",
            templateUrl: "app/views/signup/signupView.html"
        })
        .when("/users", {
            controller: "UsersController",
            templateUrl: "app/views/users/usersView.html"
        })
        .otherwise("/");
});
