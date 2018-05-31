app.config( function ($routeProvider) {
    $routeProvider
        .when("/", {
            controller: "HomeController",
            templateUrl: "app/components/home/homeView.html"
        })
        // .when("/signup", {
        //     controller: "SignupController",
        //     templateUrl: "app/components/signup/signupView.html"
        // })

        //--------------USUARIOS
        .when("/usuarios", {
            controller: "UsersController",
            templateUrl: "app/components/users/usersView.html"
        })
        .when("/usuarios/nuevo", {
            controller: "NewUserController",
            templateUrl: "app/components/users/newUser/newUserView.html"
        })
        .otherwise("/");
});
