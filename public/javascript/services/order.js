angular.module('Clementine')
.factory('Order', function OrderFactory($http){
  return {
    get : function(status){
      return $http({
        method : 'GET',
        url    : '/api/order',
        params : {
          status : status
        }
      });
    },
    save : function(provider_id){
      return $http({
        method : 'POST',
        url    : '/api/order',
        data : {
          provider_id : provider_id
        }
      });
    },
    saveItem : function(order){
      return $http({
        method : 'POST',
        url    : '/api/order-item',
        data : {
          order_id    : order.id,
          product_id  : order.product.id,
          price       : order.provider.price,
          quantity    : order.quantity
        }
      });
    }
  };
});
