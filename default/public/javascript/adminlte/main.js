$(function() {
    $('.sidebar-menu').tree();

    $('#my-todo-list').todoList({
        onCheck: function(checkbox) {
            // Do something when the checkbox is checked
            alert(checkbox);
        },
        onUnCheck: function(checkbox) {
            // Do something after the checkbox has been unchecked
            alert(checkbox);
        }
    });
});