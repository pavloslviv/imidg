<script type="text/javascript" src="{$HTTP_ROOT}/lib/ckeditor/ckeditor.js"></script>
{literal}
<script type="text/javascript">
    $(document).ready(function(){
        $('.editor').each(function(){
            var editor = CKEDITOR.replace(this,{});
            editor.config.allowedContent = true; // don't filter my data
        })

    });
</script>
{/literal}