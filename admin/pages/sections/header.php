<html>
<head>
    <title>introdata.ru:  Links admin]</title>
    <link rel="stylesheet" type="text/css" href="../css/style3.css"><link rel="stylesheet" href="/css/stylesheet.css" type="text/css">
</head>
<body>
<div align="center">
    <table width="50%" border=0><tr>
        <tr><td align="center"><p class="infotext">:: myLink - admin ::</p></td></tr>
        <tr><td align="center"><p>
                    <a href="?action=add">Add a link</a> ::
                    <a href="?action=doctors">Doctors</a> ::
                    <a href="?action=show">Show all link</a> ::
                    <a href="?action=add_lecturer">Add lecturer</a> ::
                    <a href="?action=add_video">Add Video</a> ::
                    <a href="?action=statistics">Statistics</a> ::
                    <a href="?action=mails">Rating</a> ::
                    <a href="?action=lect_mails">Questions</a> ::
                    <a href="?action=admins">Admins</a> ::
                    <a href="?exit">Log out</a></p></td></tr></table>
    <hr width="100%">
    <script type="text/javascript">
        function showMe (box) {
            var vis = (box.checked) ? "" : "none";
            var id_box = box.id;
            document.getElementById('div_'+id_box).style.display = vis;
        }
    </script>