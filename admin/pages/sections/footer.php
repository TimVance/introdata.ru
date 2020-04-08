<?php
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
    $('.select_doct').on('change',function () {
        if($(this).val() != 'not'){
            $('.email_select').html('Email: '+$(this).val());
        }else{
            $('.email_select').html('');
        }
    });
    $('.set_status').on('click',function () {
        $('.set_status_form').submit();
    });
    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).val()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    $('.inputlink').click(function() {
        $('#links').val("http://link.introdata.ru/video/" + $(this).val());
    });

    window.jQuery(function($) {
        $('a[href^="?action=delete"]').click(function(e) {
            var $link = $(e.currentTarget);
            var $tr   = $link.closest('tr');
            var href  = $link.attr('href');

            $link.text('deleting...');

            $.ajax({
                url: href,
                success: function() {
                    $tr.animate({opacity: 0}, {complete: function() {
                            $tr.remove();
                        }});
                }
            });
            return false;
        });
    });

</script>

<script>
    $('.btn_reg').on('click',function () {
        $('.mask').fadeIn();
    });
    $('.close_modal').on('click',function () {
        console.log('dasdasd');
        $('.mask').fadeOut();
    });
</script>
</div>
<footer>
    <hr width="100%">
</footer>

</body
</html>