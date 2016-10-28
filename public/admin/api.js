window.getPages = function(dir, start, limit) {
  return window.sl.ajax("EselAdminPanel", "getPagesList", {
    "dir": dir,
    "start": start,
    "limit": limit,
    "all": 1
  }, function(res) {
    var i, p;
    i = 0;
    p = [];
    res.items.forEach(function(v) {
      p[i] = v;
      p[i].active = new RegExp(p[i].path + "$").test(document.location.href);
      return i++;
    });
    window.app.pages = p;
    window.app.count = res.count;
    return $(".in-loading").removeClass("in-loading");
  });
};


/*
 Istall modules
 */

window.install = function(module) {
  return sl.ajax("EselAdminPanel", "setSafe", {
    "moduleName": module
  }, document.location.href = document.location.href);
};

window.uninstall = function(module) {
  return sl.ajax("EselAdminPanel", "setUnsafe", {
    "moduleName": module
  }, document.location.href = document.location.href);
};
