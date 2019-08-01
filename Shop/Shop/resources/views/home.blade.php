
<body>
        <md-content ng-app="MyApp" ng-controller="AppCtrl as ctrl" layout-padding layout-margin>
          <div layout-gt-xs="row">
            <div flex-gt-xs>
              <md-button class="md-raised md-primary" ng-click="showPrompt($event)">Login</md-button>
            </div>
          </div>
          <div layout-gt-xs="row">
            <div flex-gt-xs>
                <h4>Date-picker default</h4>
              <md-datepicker ng-model="ctrl.myDate" ng-change="ctrl.onDateChanged()"
                             md-placeholder="Enter date">
              </md-datepicker>Date: <%ctrl.myDate | date:shortDate%>
              </div>
            </div>
            <div layout-gt-xs="row">
              <div flex-gt-xs>
                <h4>Date-picker with min date and max date</h4>
                <md-datepicker ng-model="ctrl.myDate" md-placeholder="Enter date"
                               md-min-date="ctrl.minDate" md-max-date="ctrl.maxDate">
                </md-datepicker>Date: <%ctrl.myDate | date:shortDate%>
              </div>
            </div>
            <div layout-gt-xs="row">
              <div flex-gt-xs>
                <h4>Date-picker with min date and max date</h4>
                <md-datepicker ng-model="ctrl.myDate" md-placeholder="Enter date"
                               md-min-date="ctrl.minDate" md-max-date="ctrl.maxDate">
                </md-datepicker>Date: <%ctrl.myDate | date:shortDate%>
              </div>
            </div>
          
        </md-content>
        
        </body>