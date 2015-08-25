angular.module('Clementine')
.factory('Provider', function OrderFactory($http){
  return {
    getStats : function(parameters){
      return $http({
        method : 'GET',
        url    : '/api/provider',
        params : parameters
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
    changeStatus : function(orderId, newStatus){
      return $http({
        method : 'PUT',
        url    : '/api/order/' + orderId,
        data : {
          status : newStatus
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
