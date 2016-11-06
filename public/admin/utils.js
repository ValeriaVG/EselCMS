var tmp, updateQueryStringParameter;

tmp = document.location.search.replace("?", "").split("&");

window.$_get = [];

$(tmp).each(function() {
  var v;
  v = this.split("=");
  if (v.length === 2) {
    return window.$_get[v[0]] = v[1];
  }
});

Array.prototype.clean = function(deleteValue) {
  var i, j, ref;
  for (i = j = 0, ref = this.length; 0 <= ref ? j <= ref : j >= ref; i = 0 <= ref ? ++j : --j) {
    if (this[i] === deleteValue) {
      this.splice(i, 1);
      i--;
    }
  }
  return this;
};

window.cutPath = function(url) {
  return url.replace(/[^\/]+\.htm[l|]$/, "");
};

window.cutTplPath = function(url) {
  return url.replace(/[^\/]+\.twig$/, "");
};


/*
  updateQueryStringParameter copied from
  http://stackoverflow.com/a/6021027/2010837
  and translated to coffeescript
 */

updateQueryStringParameter = function(uri, key, value) {
  var re, separator;
  re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  separator = "&";
  if (uri.indexOf('?') === -1) {
    separator = "?";
  }
  if (uri.match(re)) {
    return uri.replace(re, '$1' + key + "=" + value + '$2');
  } else {
    return uri + separator + key + "=" + value;
  }
};
