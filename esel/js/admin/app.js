
var $_get=[];

var tmp=document.location.search.replace("?","").split("&");
$(tmp).each(function(){
var v=this.split("=");

$_get[v[0]]=v[1];

});

var app=new Vue({
  el:"#app",
  data: {
        pages: [],
        path:(undefined!==$_get['page'])?cutPath($_get['page']):"/",
        offset:0,
        limit:5,
        count:0,
    },
  created: function(){
    getPages(this.path,this.offset,this.limit);
    this.$on("selectNode",function(node){
      this.path=node.url;
    });
    this.$on("openFolder",function(page){
      if(page.folder){
        this.path=page.path;
      }});
    this.$on("pageChanged",function(page){
      this.offset=(page-1)*this.limit;
      getPages(this.path,this.offset,this.limit);
    });
    this.$on("limitChanged",function(limit){
      this.limit=limit;
      getPages(this.path,this.offset,this.limit);
    });
  },
  computed:{
    current_page:function(){return Math.floor(this.offset/this.limit)+1;}
  },
  watch:{
    path:function(val){
      this.offset=0;
      getPages(val,this.offset,this.limit);
    },
    offset:function(val){
      getPages(this.path,val,this.limit);
    },
    limit:function(val){
      getPages(this.path,this.offset,val);
    }
  }

});





$(window).resize(function(){
  fixFullHeight();
});
function fixFullHeight() {
    $(".fullheight").innerHeight($(window).innerHeight() - $("#header").innerHeight() - $("#footer").innerHeight());
}
fixFullHeight();


  tinymce.init({ selector:'.richText' });
