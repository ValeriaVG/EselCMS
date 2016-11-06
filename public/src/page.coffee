if $("#page-edit").length>0
  window.pEdit=new Vue
    el: "#page-edit"
    data:
      name:$("#page-edit [name=name]").val()
      url:$("#page-edit [name=url]").val().replace(/\/\//,"/")
      saving:false
    computed:
      path:()->
        this.url.replace(/(\/|)$/,'/index.html') if (/\/index\.html$/).test(window.old_path) and this.url.split("/").length is window.old_path.split("/").length
        this.url.replace(/(\/|)$/,'.html')
    methods:
      savePage:(btn)->
        #this.saving=true;
        window.blocks={};
        $('[name^="blocks["]').each ()->
          tmp=$(this).attr("id").split("_")
          name=tmp[1]
          value=$(this).val()
          value=tinymce.get("block_"+name).getContent() if $(this).hasClass("richText")
          window.blocks[name]=value
        data=
          "path":$('[name=path]').val()
          "template":$('[name=template]').val()
          "name":$('[name=name]').val()
          "blocks":window.blocks
          "fields":[]
          "old_path":window.old_path
        sl.ajax("EselAdminPanel","savePage",data,
          ()->
            #pEdit.saving=false

            path=app.path
            app.reload()
            if data.old_path is not data.path
              document.location.href = document.location.href.replace(/\?page=(.*)/,"?page="+data.path)
            $.jGrowl "Page was successfully saved", {header:"Saved", theme: 'tealed' }
        )
      copyPage:(btn)->
        #this.saving=true
        window.blocks={}
        $('[name^="blocks["]').each ()->
          tmp=$(this).attr("id").split("_")
          name=tmp[1]
          value=$(this).val()
          value=tinymce.get("block_content").getContent() if $(this).hasClass "richText"
          window.blocks[name]=value
          data=
            "path":$('[name=path]').val()
            "template":$('[name=template]').val()
            "name":$('[name=name]').val()|'New page'
            "blocks":window.blocks
          sl.ajax "EselAdminPanel","savePage",data,
            (res)->
              #pEdit.saving=false;
              if (data.old_path is not data.path)
                document.location.href=document.location.href.replace(/\?page=(.*)/,"?page="+data.path)
              $.jGrowl "Page was successfully saved", {header:"Saved", theme: 'tealed' }
      deletePage:(btn)->
        #pEdit.saving=true;
        sl.ajax "EselAdminPanel","deletePage",{"path":window.old_path},
          (data)->
            #pEdit.saving=false
            setTimeout(()->
              document.location.href=document.location.href.replace(/[^\/]+\.html$/,"")
            ,500)
            $.jGrowl "Page was successfully deleted", {header:"Deleted", theme: 'tealed' }
      changeTemplate:()->
        document.location.href=updateQueryStringParameter document.location.href,"template",$('[name=template]').val()
