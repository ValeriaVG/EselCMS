window.sl.debug=true
window.templates= new Vue
    el:"#templates-list"
    components:['crumbs','items','tree']
    data:
      items: []
      path: (if $_get['template']? then cutTplPath($_get['template']) else "/")
      offset:0
      limit:Cookies.get("tree-limit")||10
      count:0
    created: ()->
      getTemplates this.path,this.offset,this.limit
      this.$on("selectNode",(node)->
        this.path=node.url
      )
      this.$on("openFolder",(page)->
        this.path=page.path if page.folder
      )
      this.$on("pageChanged",(page)->
        this.offset=(page-1)*this.limit;
        getTemplates this.path,this.offset,this.limit
      )
      this.$on("limitChanged",(limit)->
        this.limit=limit
        Cookies.set("tree-limit",limit)
        getTemplates this.path,this.offset,this.limit
      )

    computed:
      current_page:()-> Math.floor(this.offset/this.limit)+1
      new_file_url:()-> "editor/?template="+this.path
    watch:
      path:(val)->
        this.offset=0
        getTemplates val,this.offset,this.limit
      offset:(val)->
        getTemplates this.path,val,this.limit

      limit:(val)->
        getTemplates this.path,this.offset,val
    methods:
      reload:()->
        getTemplates this.path,this.offset,this.limit
      newFolder:()->
        d=this
        sl.askTo 'Create a folder', 'Enter a folder name', (value)->
          sl.ajax "EselAdminPanel","makeTplDir",{dir:d.path,name:value},
            (data)->
              getTemplates d.path,d.offset,d.limit
      removeFolder:()->
        d=this
        confirm 'Are you sure you want to delete '+ d.path,
          (data)->
            sl.ajax "EselAdminPanel","removeTplDir",{dir:d.path},
              (data)->
                $.jGrowl "Folder was deleted",{theme:"tealed",header:"Done!"}
                patharr=d.path.split("/").pop()
                path="/"
                if patharr.length>0
                  path=patharr.join("/")
                app.path=path
