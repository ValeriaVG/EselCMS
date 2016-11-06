tmp=document.location.search.replace("?","").split("&")
window.$_get=[]
$(tmp).each ()->
  v=this.split("=")
  window.$_get[v[0]]=v[1] if v.length is 2

Array.prototype.clean = (deleteValue)->
  for i in [0..this.length]
    if this[i] is deleteValue
      this.splice(i, 1)
      i--
  return this;

window.cutPath=(url)->url.replace /[^\/]+\.htm[l|]$/,""

window.cutTplPath=(url)->url.replace /[^\/]+\.twig$/,""
###
  updateQueryStringParameter copied from
  http://stackoverflow.com/a/6021027/2010837
  and translated to coffeescript
###
updateQueryStringParameter=(uri, key, value)->
  re = new RegExp("([?&])" + key + "=.*?(&|$)", "i")
  separator ="&"
  separator =  "?" if uri.indexOf('?') is -1
  if uri.match(re)
    return uri.replace(re, '$1' + key + "=" + value + '$2')
  else
    return uri + separator + key + "=" + value
