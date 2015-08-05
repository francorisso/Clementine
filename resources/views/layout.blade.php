<html ng-app="Clementine">
  <head>
    <title>Clementine</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/0.9.4/angular-material.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=RobotoDraft:300,400,500,700,400italic">
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
          <md-list-item ng-click="navigateTo('/products/import')">
            Importar Productos
          </md-list-item>

          <md-divider ></md-divider>

          <md-list-item ng-click="navigateTo('/stats')">
            Estadisticas
          </md-list-item>
        </md-list>
      </md-content>
    </md-sidenav>
    <md-content>
      <div ng-view></div>
    </md-content>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-animate.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-aria.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-route.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-cookies.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-sanitize.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angular_material/0.9.4/angular-material.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-material-icons/0.5.0/angular-material-icons.min.js"></script>
    <script src="/javascript/app.js"></script>
    <script src="/javascript/routes.js"></script>
    <script src="/javascript/controllers/orders-index-controller.js"></script>
    <script src="/javascript/controllers/orders-add-controller.js"></script>
    <script src="/javascript/controllers/products-index-controller.js"></script>
    <script src="/javascript/controllers/products-import-controller.js"></script>
    <script src="/javascript/controllers/proveedores-index-controller.js"></script>
    <script src="/javascript/directives/order-add-form.js"></script>
    <script src="/javascript/directives/product-input-search.js"></script>
    <script src="/javascript/services/product.js"></script>
    <script src="/javascript/services/order.js"></script>
  </body>
</html>