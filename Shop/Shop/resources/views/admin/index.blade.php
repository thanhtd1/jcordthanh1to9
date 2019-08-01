<!doctype html>
<html lang="en">

@include('admin.element.head_tag')

@if ($errors->any())
	<body ng-app="MyApp" ng-controller="AppCtrl as ctrl" ng-init="showNotification({{json_encode($errors->all())}})">
@else
	<body ng-app="MyApp" ng-controller="AppCtrl as ctrl" >
@endif
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

{{-- @include('admin.element.left-sidebar')   --}}
<div class="left-sidebar-pro" ng-include="'admin/view/left-sidebar'"></div>
    <!-- Start Welcome area -->
    @if (isset($list))
        {{-- {{dd($list)}} --}}
    @endif
    <div class="all-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="index.html"><img class="main-logo" src="{{asset('img/logo/logo.png')}}" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
        {{-- @include('admin.element.header')         --}}
        <div class="header-advance-area" ng-include="'admin/view/header'"></div>
        <div id="content_html">
                {{-- <div ng-if="!new_content" class="product-status mg-b-30" ng-include="'admin/view/product-list'"></div>    
                <div ng-if="new_content" class="product-status mg-b-30" ng-include="new_content"></div>     --}}
                <div ng-if="!new_content" class="product-status mg-b-30" ng-include="ctrl.contentsBase"></div> 
            </div>
        @include('admin.element.footer')
</div>
@include('admin.element.script')
@include('admin.modal.product_detail')
</body>

</html>