Array.prototype.clean = function(deleteValue) {
  for (var i = 0; i < this.length; i++) {
    if (this[i] == deleteValue) {
      this.splice(i, 1);
      i--;
    }
  }
  return this;
};

function getPages(dir,start,limit){
  console.log([dir,start,limit]);
  $.ajax({
    url:"/actions/EselAdminPanel/getPagesList",
    method:"POST",
    data:{"dir":dir,"start":start,"limit":limit,"all":1},
    success:function(res){
      var p=[];
      var i=0;
      res.data.pages.forEach(function(v){
        p[i]=v;
        p[i].active=new RegExp(p[i].path+"$").test(document.location.href);
        i++;
      });
      app.pages=p;

      app.count=res.data.count;
      $(".in-loading").removeClass("in-loading");
    }
  }).always(function(res){
    console.log(res);
  });
}

function cutPath(url){
return url.replace(/[^\/]+\.html$/,"");
}
