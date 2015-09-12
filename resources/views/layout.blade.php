<html ng-app="Clementine">
  <head>
    <title>Clementine</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/stylesheets/screen.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/0.10.0/angular-material.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=RobotoDraft:300,400,500,700,400italic">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      var __token = '{{ csrf_token() }}';
    </script>
  </head>
  <body ng-controller="AppCtrl">
    <md-toolbar>
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Settings" ng-click="toggleLeft()">
          <ng-md-icon icon="menu" style="fill:#fff"></ng-md-icon>
        </md-button>
        <h2>
          <span>Clementine</span>
        </h2>
        <md-button class="md-icon-button" aria-label="Logout" ng-click="navigateTo('/logout')">
          <ng-md-icon icon="logout" style="fill:#fff"></ng-md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <md-sidenav class="md-sidenav-left md-whiteframe-z2" md-component-id="left">
      <md-toolbar class="md-theme-indigo">
        <h1 class="md-toolbar-tools">Menu</h1>
      </md-toolbar>
      <md-content layout-padding>
        <md-list>
          <md-list-item ng-click="navigateTo('/orders')">
            Pedidos
          </md-list-item>

          <md-divider ></md-divider>

          <md-list-item ng-click="navigateTo('/products')">
            Productos
          </md-list-item>
          <md-list-item ng-click="navigateTo('/providers')">
            Proveedores
          </md-list-item>

          <md-divider ></md-divider>

          <md-list-item ng-click="navigateTo('/stats')">
            Estadisticas
          </md-list-item>
        </md-list>
      </md-content>
    </md-sidenav>

    <div ng-view></div>
    <md-content style="height:90px;"></md-content>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-animate.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-aria.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-route.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-cookies.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-sanitize.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angular_material/0.10.0/angular-material.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-material-icons/0.5.0/angular-material-icons.min.js"></script>
    <script src="/javascript/app.js"></script>
    <script src="/javascript/routes.js"></script>
    <script src="/javascript/controllers/orders-index-controller.js"></script>
    <script src="/javascript/controllers/orders-add-controller.js"></script>
    <script src="/javascript/controllers/products-index-controller.js"></script>
    <script src="/javascript/controllers/providers-index-controller.js"></script>
    <script src="/javascript/controllers/stats-index-controller.js"></script>
    <script src="/javascript/controllers/user-login-controller.js"></script>
    <script src="/javascript/controllers/user-logout-controller.js"></script>
    <script src="/javascript/directives/order-add-form.js"></script>
    <script src="/javascript/directives/order-list-row.js"></script>
    <script src="/javascript/directives/product-input-search.js"></script>
    <script src="/javascript/directives/product-list.js"></script>
    <script src="/javascript/directives/product-import.js"></script>
    <script src="/javascript/directives/provider-list.js"></script>
    <script src="/javascript/directives/chart/product.js"></script>
    <script src="/javascript/directives/chart/provider.js"></script>
    <script src="/javascript/services/product.js"></script>
    <script src="/javascript/services/order.js"></script>
    <script src="/javascript/services/provider.js"></script>
    <script src="/javascript/services/stats.js"></script>
    <script src="/javascript/services/user.js"></script>
    <script src="/javascript/services/token-generator.js"></script>
    <script src="/javascript/services/auth-interceptor.js"></script>
  </body>
</html>