</div>
<hr>

<!-- Footer -->
<footer>

  <div align="center">
    <div class="row">
      <p>
        <strong>
          <i class="fa fa-copyright"></i>
          2017 - DineroSeguroHF.com - All Rights Reserved
        </strong>
      </p>
    </div>
  </div>

</footer>
</div>

<hr>

<!-- Bootstrap Core JavaScript -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Bootstrap Datepicker JavaScript -->
<script src="bower_components/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>
<!-- DataTables JavaScript -->
<script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

<!-- Morris Charts JavaScript -->
<script src="bower_components/raphael/raphael-min.js"></script>
<script src="bower_components/morrisjs/morris.min.js"></script>
<!--<script src="js/morris-data.js"></script>-->

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>

<!-- Custom JavaScript -->
<script src="js/custom.js<?= "?v=".CoreConfig::CACHE_VERSION ?>"></script>

<!-------------------------------- Library's -------------------------------->
<script src="public/lib/angular/angular.js"></script>
<script src="public/lib/angular/angular-resource.js"></script>
<script src="public/lib/angular/angular-ui-router.js"></script>
<script src="public/lib/angular/angular-translate.js"></script>
<script src="public/lib/angular/angular-translate-loader-static-files.js"></script>
<script src="public/lib/angular/angular-animate.js"></script>

<script src="public/lib/bootstrap/js/ui-bootstrap-0.11.0.js"></script>
<script src="public/lib/bindonce/bindonce.js"></script>

<!-------------------------------- Application -------------------------------->
<script src="public/app/system/SystemModule.js"></script>
<script src="public/app/system/controller/SystemCtrl.js"></script>

<script src="public/app/config/ClientConfig.js"></script>
<script src="public/app/ws/httpTimeoutModule.js"></script>
<script src="public/app/ws/Connector.js"></script>
<script src="public/app/ws/WS.js"></script>

<script src="public/app/interface/InterfaceManager.js"></script>

<script src="public/directives/DirectiveModule.js"></script>
<script src="public/directives/MidLoading.js"></script>
<script src="public/directives/notification/NotificationManager.js"></script>
<script src="public/directives/notification/MidNotification.js"></script>

<script src="public/app/client/ClientModule.js"></script>
<script src="public/app/client/ClientManager.js"></script>
<script src="public/app/client/controller/ClientCtrl.js"></script>

<script src="public/app.js"></script>

<input type="hidden" id="sid" ng-model="sid" value="<?php echo session_id() ?>">
</body>

</html>
