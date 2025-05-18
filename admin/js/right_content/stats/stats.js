function handleStats() {
    let weeklyIncomes = document.getElementById("weekly-incomes");
    let weeklyOrders = document.getElementById("weekly-orders");
    let addedToCart = document.getElementById("added-to-cart");

    fetch("includes/right_content/stats/weekly_stats.php")
        .then(response => response.text())
        .then(responseData => {
            try {
                let data = JSON.parse(responseData);
                if (data.status === "success") {
                    weeklyIncomes.innerHTML = data.weeklyIncomes;
                    weeklyOrders.innerHTML = data.weeklyOrders;
                    addedToCart.innerHTML = data.addedToCart;
                }
                else {
                    showMessageDialog(data.message);
                }
            }
            catch (error) {
                console.error(error);
                console.error(responseData);
            }
        })
        .catch(error => {
            console.error(error);
        });

    function render_ranking() {
        const selected = document.getElementById("ranking-select").value;
        if (selected == 'Khách hàng trung thành') {
            document.getElementById("loyalty-explanation").style.display = "block";
        }
        else {
            document.getElementById("loyalty-explanation").style.display = "none";
        }

        if (selected == 'Khách hàng có doanh thu cao nhất') {
            document.getElementById("ranking-time").style.display = "flex";
        }
        else {
            document.getElementById("ranking-time").style.display = "none";
        }

        fetch("includes/right_content/stats/ranking_stats.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body:   "type=" + encodeURIComponent(selected) + 
                    "&startDate=" + encodeURIComponent(document.getElementById('ranking-time-from').value) + 
                    "&endDate=" + encodeURIComponent(document.getElementById('ranking-time-to').value)
        })
            .then(res => res.text())
            .then(html => {
                document.getElementById("ranking-table").innerHTML = html;
            })
            .catch(error => console.error("Lỗi tải bảng xếp hạng:", error));
    }

    document.getElementById('ranking-time-from').addEventListener("change",render_ranking);
    document.getElementById('ranking-time-to').addEventListener("change",render_ranking);

    render_ranking();

    document.getElementById("ranking-select").addEventListener("change", render_ranking);
}