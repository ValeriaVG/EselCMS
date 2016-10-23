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
      console.log(res);
      var p=[];
      var i=0;
      res.data.items.forEach(function(v){
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
/**
 * http://stackoverflow.com/a/6021027/2010837
 */
function updateQueryStringParameter(uri, key, value) {
  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
  if (uri.match(re)) {
    return uri.replace(re, '$1' + key + "=" + value + '$2');
  }
  else {
    return uri + separator + key + "=" + value;
  }
}


/**
 * Istall modules
 */

function install(module){
  $.ajax({
    url:"/actions/EselAdminPanel/setSafe",
    method:"POST",
    data:{"moduleName":module},
    success:function(res){
      if(res.success){
        document.location.href=document.location.href;
      }
    }
  });
}

function uninstall(module){
  $.ajax({
    url:"/actions/EselAdminPanel/setUnsafe",
    method:"POST",
    data:{"moduleName":module},
    success:function(res){
      if(res.success){
        document.location.href=document.location.href;
      }
    }
  });
}
