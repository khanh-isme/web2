<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nike</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <?php include "includes/header.php"; ?>

    <div id="content">
        <?php include "pages/home.php"; ?>
    </div>

    <script>
        
        $(document).ready(function() {
            $(".nav-link").click(function(e) {
                e.preventDefault();
                var page = $(this).data("page");

                $.ajax({
                    url: "/web2/includes/check_ajax.php",
                    method: "POST",
                    data: {
                        page: page
                    },
                    success: function(data) {
                        $("#content").html(data);
                        console.log("Trang đã load:", page);

                        if (page === 'shop.php') {
                            console.log("Checking if shop.js is already loaded...");

                            if (typeof initShopScript === "undefined") {
                                console.log("Reloading shop.js...");
                                $.getScript("/web2/assets/js/shop.js");
                            } else {
                                console.log("shop.js is already loaded.");
                                initShopScript(); // Gọi lại nếu đã load trước đó
                            }
                        }
                    },
                    error: function() {
                        $("#content").html("<p>Lỗi khi tải trang!</p>");
                    }
                });
            });
        });
    </script>
    <script src="/web2/assets/js/shop.js"></script>
</body>

</html>