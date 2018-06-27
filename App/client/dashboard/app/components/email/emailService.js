angular.module('Dashboard')
    .service('EmailService', function($scope,  AuthFactory, RequestFactory){


        this.searchUsers = function(data){
            return RequestFactory.makeTokenRequest(
                'GET',
                "/users/search"+data,
                null,
                AuthFactory.getToken()
            );
        }

        this.getUsers = function(){
            return RequestFactory.makeTokenRequest(
                'GET',
                "/users",
                null,
                AuthFactory.getToken()
            );
        }


        this.sendEmail = function(mail, emails){
            return RequestFactory.makeTokenRequest(
                'POST',
                "/mail/send",
                data = {
                    address: emails,
                    subject: mail.subject,
                    body: mail.body,
                    plainBody: mail.body
                },
                AuthFactory.getToken()
            );
        }

    });
