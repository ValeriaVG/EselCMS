window.sl.debug=true
window.app= new Vue
    el:"#app"
    components:['crumbs','pages','tree']
    data:
      pages: []
      path: (if $_get['page']? then cutPath($_get['page']) else "/")
      offset:0
      limit:10
      count:0
    created: ()->
      getPages this.path,this.offset,this.limit
      this.$on("selectNode",(node)->
        this.path=node.url
      )
      this.$on("openFolder",(page)->
        this.path=page.path if page.folder
      )
      this.$on("pageChanged",(page)->
        this.offset=(page-1)*this.limit;
        getPages this.path,this.offset,this.limit
      )
      this.$on("limitChanged",(limit)->
        this.limit=limit
        getPages this.path,this.offset,this.limit
      )
    computed:
      current_page:()-> Math.floor(this.offset/this.limit)+1
      new_page_url:()-> "pages/?page="+this.path
    watch:
      path:(val)->
        this.offset=0
        getPages val,this.offset,this.limit
      offset:(val)->
        getPages this.path,val,this.limit

      limit:(val)->
        getPages this.path,this.offset,val
    methods:
      newFolder:()->
        d=this
        sl.askTo 'Create a folder', 'Enter a folder name', (value)->
          sl.ajax "EselAdminPanel","makePagesDir",{dir:d.path,name:value},
            (data)->
              getPages d.path,d.offset,d.limit
      removeFolder:()->
        d=this
        confirm 'Are you sure you want to delete '+ d.path,
          (data)->
            sl.ajax "EselAdminPanel","removePagesDir",{dir:d.path},
              (data)->
                $.jGrowl "Folder was deleted",{theme:"tealed",header:"Done!"}
                patharr=d.path.split("/").pop()
                path="/"
                if patharr.length>0
                  path=patharr.join("/")
                app.path=path
