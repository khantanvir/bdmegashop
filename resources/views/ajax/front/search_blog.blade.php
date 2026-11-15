<script>
    function blog_search(){
        var title = $('#title').val();
        var category_id = $('#category_id').val();
        var tag = $('#tag').val();
        window.location = "{{ URL::to('news?title=') }}" + title + "&category_id=" + category_id + "&tag=" + tag;
    }
    function blog_search_by_category(x){
        var title = $('#title').val();
        var category_id = x;
        var tag = $('#tag').val();
        window.location = "{{ URL::to('news?title=') }}" + title + "&category_id=" + category_id + "&tag=" + tag;
    }
    function blog_search_by_tag(x){
        var title = $('#title').val();
        var category_id = $('#category_id').val();
        var tag = x;
        window.location = "{{ URL::to('news?title=') }}" + title + "&category_id=" + category_id + "&tag=" + tag;
    }
</script>