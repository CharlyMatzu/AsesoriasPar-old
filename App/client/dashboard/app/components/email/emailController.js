angular.module("Dashboard")
    .controller('EmailController', function($scope, STATUS){

        $scope.page.title = "Email app";
        var editor = null;

        $scope.loading = true;
        $scope.selectedEmails = [];


        $scope.loadBox = function(){
            // hljs.configure({   // optionally configure hljs
            //     languages: ['javascript', 'ruby', 'python', 'php', 'java']
            // });

            editor = new Quill('#editor-container', {
                modules: {
                    toolbar: [
                        // [{ header: [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        ['link', 'clean', 'blockquote', 'code-block'],
                        [{ list: 'ordered' }, { list: 'bullet' }]
                        // ['image']
                    ],
                    syntax: true
                },
                placeholder: 'Compon algo epico...',
                theme: 'snow'  // or 'bubble'
            });
        };

        // $scope.selectetUser = function(email){
        //     $scope.selectedEmails.push( email );
        // };

        // // $scope.removeUser = function(email){
        // //     $scope.selectedEmails.push( email )
        // // }

        // var searchUsers = function(data){
        //     EmailService.searchUsers(data)
        //         .then(function(success){

        //         }, function(error){

        //         });
        // };

        // var getUsers = function(){
        //     EmailService.getUsers()
        //         .then(function(success){

        //         }, function(error){

        //         });
        // };

        $scope.sendEmail = function(){
            alert("Funiona");
            // console.log( JSON.stringify( editor.getContents().ops ) );
            console.log( JSON.stringify( editor.getText() ) );
            // EmailService.sendEmail(mail, $scope.selectedEmails)
            //     .then(function(success){

            //     }, function(error){

            //     });
        };

        (function(){
            $scope.loadBox();


        })();

    });