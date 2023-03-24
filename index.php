<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="data" method="post" enctype="multipart/form-data">
        <label for="profileIMG">image</label>
        <input type="file" name="profileIMG">

        <button type="submit">insert</button>
    </form>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>


<!-- get post by id -->
<!-- <script>
    $.ajax({
        method: "GET",
        url: "http://127.0.0.1:8000/api/post/24",
        headers: {
            Authorization: "Bearer " + "2|abTT5S1UdVSlcpHdFDARHazbPkwD5LcxMUjpwkFq",
        },
        success: function(data) {
            console.log(data);
            switch (data.status) {
                case "error":
                    break;
                case "succes":
                    break;
            }
        },
        error: function(data) {
            console.log(data)
        }
    });
</script> -->

<!-- Login -->
<!-- <script>
    $.ajax({
        method: "POST",
        url: "http://127.0.0.1:8000/api/login",
        data: {
            email: "teusbrom@gmail.com",
            password: "Kip12345!",
        },
        success: function(data) {
            console.log(data);
            switch (data.status) {
                case "error":
                    break;
                case "succes":
                    break;
            }
        },
        error: function(data) {
            console.log(data)
        }
    });
</script> -->

<!-- register -->
<!-- <script>
    $.ajax({
        method: "POST",
        url: "http://127.0.0.1:8000/api/register",
        data: {
            name: "teuseeee",
            email: "teusbrom@gmail.comeee2332e",
            password: "eeeeeee!",
        },
        success: function(data) {
            console.log(data);
            switch (data.status) {
                case "error":
                    break;
                case "succes":
                    break;
            }
        },
        error: function(data) {
            console.log(data)
        }
    });
</script> -->

<!-- get all posts -->
<!-- <script>
    $.ajax({
        method: "GET",
        url: "http://127.0.0.1:8000/api/all.posts",
        headers: {
            Authorization: "Bearer " + "2|abTT5S1UdVSlcpHdFDARHazbPkwD5LcxMUjpwkFq",
        },
        success: function(data) {
            console.log(data);
            switch (data.status) {
                case "error":
                    break;
                case "succes":
                    break;
            }
        },
        error: function(data) {
            console.log(data)
        }
    });
</script> -->

<!-- add Post -->
<!-- <script>
    $.ajax({
        method: "POST",
        url: "http://127.0.0.1:8000/api/store.post",
        headers: {
            Authorization: "Bearer " + "2|abTT5S1UdVSlcpHdFDARHazbPkwD5LcxMUjpwkFq",
        },
        data: {
            title: "ik ben mel hallo",
            content: "en ik ben een tea pot",
        },
        success: function(data) {
            console.log(data);
            switch (data.status) {
                case "error":
                    break;
                case "succes":
                    break;
            }
        },
        error: function(data) {
            console.log(data)
        }
    });
</script> -->


<!-- insert Comment -->
<!-- <script>
    $.ajax({
        method: "POST",
        url: "http://127.0.0.1:8000/api/store.comment",
        headers: {
            Authorization: "Bearer " + "2|abTT5S1UdVSlcpHdFDARHazbPkwD5LcxMUjpwkFq",
        },
        data: {
            title: "hallo tea pot",
            content: "dit is gwn puure noob shit verwijder dit",
            idPost: 24,
        },
        success: function(data) {
            console.log(data);
            switch (data.status) {
                case "error":
                    break;
                case "succes":
                    break;
            }
        },
        error: function(data) {
            console.log(data)
        }
    });
</script> -->

<!-- update user -->
<!-- <script>
    $.ajax({
        method: "POST",
        url: "http://127.0.0.1:8000/api/updateUser",
        headers: {
            Authorization: "Bearer " + "9|4JrXYDz2Ifw1kYeNJGDVVW7GG3VOcMJEM9ZHcmFO",
        },
        data: {
            password: "Teus12345!",
        },
        success: function(data) {
            console.log(data);
            switch (data.status) {
                case "error":
                    break;
                case "succes":
                    break;
            }
        },
        error: function(data) {
            console.log(data)
        }
    });
</script> -->

<!-- upload image -->
<script>
    $("form#data").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            method: "POST",
            url: "http://127.0.0.1:8000/api/updateProfileIMG",
            headers: {
                Authorization: "Bearer " + "1|oDylZg10vV74lH18TLo2wkIEOwB2E5RvV6FG7IPV",
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                console.log(data);
                switch (data.status) {
                    case "error":
                        break;
                    case "succes":
                        break;
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    })
</script>