define(["app","marionette","routers/index","controllers/index"],function(e,t,n,r){console.log("Module:Pages => Loading...");var i=e.module("Pages",function(e){this.startWithParent=!1,this.addInitializer(function(){console.log("Module:Pages => initialized"),this.router=new n({controller:r})})});return i});