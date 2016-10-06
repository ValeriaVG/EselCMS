QUnit.module( "routing" );
QUnit.test("index page check", function(assert) {
  var opened = assert.async();
  $.ajax(
    url:"/",
    complete:function(jqXHR,textStatus){
      assert.ok(textStatus==success , "Page opened with code 200");
      opened();
    }
  );


  });
