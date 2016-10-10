Array.prototype.clean = function(deleteValue) {
  for (var i = 0; i < this.length; i++) {
    if (this[i] == deleteValue) {
      this.splice(i, 1);
      i--;
    }
  }
  return this;
};

function buildCrumbs(){
  var $_get=[];
  var folder="";
  var page="";
var tmp=document.location.search.replace("?","").split("&");
$(tmp).each(function(){
  var v=this.split("=");
  $_get[v[0]]=v[1];

});

var crumbs=[{name:"/",path:"/",url:"/"}];
try{
page=$_get['page'];
var path=$_get['page'].split("/");
  path.clean("");
  path.pop();

  folder=path.join("/")+"/";

  var i=path.length-1;
  var j=1;
  while(path.length>0){
    crumbs[j]={name:path[i],path:(path.join("/")+"/").replace(/\/\//,"/"),url:"/"+path.join("/")};
    j++;
    i--;
    path.shift();
  }
  app.crumbs=crumbs;
  app.current_page=page;
  getPages(folder);
}catch(e){

getPages("");
}
}
var app=new Vue({
  el:"#app",
  data: {
        pages: [],
        crumbs: [{name:"/",path:"/",url:"/"}],
        current_page:""
    },
});
buildCrumbs();


$(window).resize(function(){
  fixFullHeight();
});
function fixFullHeight(){
  $(".fullheight").innerHeight($(window).innerHeight()-$("#header").innerHeight()-$("#footer").innerHeight());
}
fixFullHeight();
  $(".button-collapse").sideNav();

  tinymce.init({ selector:'.richText' });
