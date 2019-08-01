
<md-dialog flex="20">
<form ng-cloak action="{{url('login')}}" method="POST">
    @csrf
    <md-toolbar>
        <div class="md-toolbar-tools">
            <div layout="row" layout-align="center center">
                    <h4>Login</h4>
            </div>
          <span flex></span>
          <md-button class="md-icon-button" ng-click="cancel()">
            X
          </md-button>
        </div>
    </md-toolbar>
    <br>    
    <md-dialog-content layout="column" layout-align="end center">
        <br>
        <md-input-container  layout-gt-md>
            <label>User name</label>
            <input ng-model="user.firstName" name="username">
        </md-input-container>
        <md-input-container  layout-gt-md>
            <label>Password</label>
            <input type="password"  name="password">
        </md-input-container>
    </md-dialog-content>
    <md-dialog-actions layout="row" layout-align="end center">
        <input type="submit" class="md-button md-primary" value="Login">
        <md-button class="md-accent" ng-click="cancel()">Cancle</md-button>
    </md-dialog-actions>
    </form>
    </md-dialog>