angular.module('Clementine')
.factory('User', function UserFactory($http){
  return {
    login : function(user)
    {
      return $http({
        method : 'POST',
        url    : '/auth/login',
        data : {
          email    : user.email,
          password : user.password,
        }
      });
    },

    logout: function()
    {
      return $http({
        method : 'GET',
        url    : '/auth/logout'
      });
    }

  };
});
