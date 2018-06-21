angular.module("Dashboard")

    .config( function ($routeProvider) {
        $routeProvider
            
            .when("/", {
                // controller: "HomeController",
                templateUrl: "app/components/home/homeView.html"
            })

            .when("/periodos", {
                controller: "PeriodsController",
                templateUrl: "app/components/periods/periodsView.html"
            })

            .when("/planes", {
                controller: "PlansController",
                templateUrl: "app/components/plans/plansView.html"
            })

            .when("/carreras", {
                controller: "CareersController",
                templateUrl: "app/components/careers/careersView.html"
            })

            .when("/materias", {
                controller: "SubjectsController",
                templateUrl: "app/components/subjects/subjectsView.html"
            })

            .when("/materias/nuevo", {
                controller: "NewSubjectController",
                templateUrl: "app/components/subjects/newSubject/newSubjectView.html"
            })


            //--------------USUARIOS
            .when("/usuarios", {
                controller: "UsersController",
                templateUrl: "app/components/users/usersView.html"
            })
            .when("/usuarios/nuevo", {
                controller: "NewUserController",
                templateUrl: "app/components/users/newUser/newUserView.html"
            })

            .when("/estudiantes", {
                controller: "StudentsController",
                templateUrl: "app/components/students/studentsView.html"
            })
            .when("/estudiantes/:id", {
                controller: "StudentDetailController",
                templateUrl: "app/components/students/detail/studentDetailView.html"
            })
            // .when("/estudiantes/nuevo", {
            //     controller: "NewStudentController",
            //     templateUrl: "app/components/students/newStudent/newStudentView.html"
            // })

            .when("/asesorias", {
                controller: "AdvisoriesController",
                templateUrl: "app/components/advisories/advisoriesView.html"
            })

            //----------------APP
            .when("/correo", {
                // controller: "EmailController",
                templateUrl: "app/components/email/emailView.html"
            })

            .when("/signout", {
                controller: "SignoutController"
                // templateUrl: "app/components/email/emailView.html"
            })

            .otherwise("/");
    });
