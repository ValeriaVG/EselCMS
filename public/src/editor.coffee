if $("#file-edit").length>0
  window.fEdit=new Vue(
    el: "#file-edit"
    data:
      saving:false
      file:window.editor_file
    methods:
      saveTemplate:()->
        console.log this.file
  )
