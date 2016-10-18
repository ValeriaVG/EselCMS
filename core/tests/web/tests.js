QUnit.module("routing");
QUnit.test("index page check", function(assert) {
    var opened = assert.async();
    $.ajax({
        url: "/",
        complete: function(xhr, textStatus) {
            assert.ok(xhr.status == 200, "Page opened with code 200");
            opened();
        }
    });
});
QUnit.test("inner page check", function(assert) {
    var opened = assert.async();
    $.ajax({
        url: "/docs/meet-modules",
        complete: function(xhr, textStatus) {
            assert.ok(xhr.status == 200, "Redirect 301");
            opened();
        }
    });
});
QUnit.test("page not found check", function(assert) {
    var opened = assert.async();
    $.ajax({
        url: "/i-do-not-exists-but-i-can-has-poland",
        complete: function(xhr, textStatus) {
            assert.ok(xhr.status == 404, "Page opened with code 404");
            opened();
        }
    });
});
