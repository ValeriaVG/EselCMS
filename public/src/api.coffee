window.getPages=(dir,start,limit)->
  window.sl.ajax "EselAdminPanel","getPagesList",{"dir":dir,"start":start,"limit":limit,"all":1},
    (res)->
      i=0
      p=[]
      res.items.forEach (v)->
        p[i]=v
        p[i].active=new RegExp(p[i].path+"$").test(document.location.href)
        i++
      window.app.pages=p
      window.app.count=res.count
      $(".in-loading").removeClass "in-loading"





###
 Istall modules
###

window.install=(module)->
  sl.ajax "EselAdminPanel","setSafe",{"moduleName":module},
    document.location.href=document.location.href;

window.uninstall=(module)->
  sl.ajax "EselAdminPanel","setUnsafe",{"moduleName":module},
    document.location.href=document.location.href;
