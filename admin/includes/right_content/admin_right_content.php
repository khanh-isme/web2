<div id="right_content">
    <div class="STATS content-ctn">
        <div id="stats-ctn">
            <div id="chart-ctn" class="hidden">
                <canvas id="status-chart" width="300" height="150"></canvas>
                <div id="chart-input-warning" class=""><i class="fa-solid fa-triangle-exclamation"></i>Không có dữ liệu!</div>
                <div id='chart-footer'>
                    <input type="button" id="chart-reload" value="Làm mới">
                </div>
            </div>
            <div id="stats">
                <div id="chart-mode-ctn"><input type="button" id="chart-mode" value="Xem biểu đồ"></div>
                <h3>Thống kê trong tuần</h3>
                <div id="weekly-stats-ctn">
                    <div id="weekly-incomes"></div>
                    <div id="weekly-orders"></div>
                    <div id="added-to-cart"></div>
                </div>
                <div id="ranking-ctn">
                    <select name="ranking-select" id="ranking-select">
                        <option>Sản phẩm bán chạy trong tuần</option>
                        <option>Sản phẩm bán chạy trong tháng</option>
                        <option>Sản phẩm phát sinh lợi nhuận cao nhất trong tuần</option>
                        <option>Sản phẩm phát sinh lợi nhuận cao nhất trong tháng</option>
                        <option>Khách hàng có doanh thu cao nhất</option>
                        <option>Khách hàng tiềm năng</option>
                        <option>Khách hàng trung thành</option>
                    </select>
                    <table id="ranking-table">
                        
                    </table>
                    <div id="loyalty-explanation" style="padding: 10px; background-color: whitesmoke; border-left: 4px solid gray;">
                        <h4 style="margin-top: 0;">Cách tính điểm trung thành</h4>
                        <ul style="margin: 0; padding-left: 20px;">
                            <li>Mỗi đơn hàng: <strong>+2 điểm</strong></li>
                            <li>Mỗi 100.000đ chi tiêu: <strong>+1 điểm</strong></li>
                            <li>Mỗi tháng khách hàng có mua hàng (active): <strong>+5 điểm</strong></li>
                            <li>Mỗi tháng tính từ đơn đầu tiên đến hiện tại: <strong>+1 điểm</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> 
        <form id="stats-menu-option-form" class="hidden">
            <div class="stats-option-ctn">
                <label for="stats-by-slt">Thống kê theo:</label>
                <select name="stats-by-slt" id="stats-by-slt" class="stats-option-slt">
                    <option value="total-category">Tổng - Loại</option>
                    <option value="total-time">Tổng - Mốc thời gian</option>
                    <option value="category-time">Loại - Mốc thời gian</option>
                </select>
                <br>
                <div id="stats-by-options" class="stats-option-ctn border">
                    <label for="stats-by-time-option">Tùy chọn:</label>
                    <select name="stats-by-time-option" id="stats-by-time-option" class="stats-option-slt">
                        <option value="all">Tất cả</option>
                        <option value="time">Theo thời gian</option>
                    </select>
                    <div class="stats-option-ctn disabled" id="stats-time">
                        <label>Thời gian:</label>
                        <div id="time-range-option-ctn" class="stats-option-ctn border">
                            <div id="by-day-option-ctn" class="stats-option">
                                <label for="by-day-from">Từ ngày:</label>
                                <input name="by-day-from" id="by-day-from" type="date" value="<?php echo date("Y-m-d");?>" class="stats-option-slt">
                                <label for="by-day-to">Đến hết ngày:</label>
                                <input name="by-day-to" id="by-day-to" type="date" value="<?php echo date("Y-m-d");?>" class="stats-option-slt">
                            </div>
                        </div>
                    </div>
                    <div id="time-range-unit-ctn" class="disabled">
                        <label for="time-range-unit">Đơn vị thời gian:</label>
                        <select name="time-range-unit" id="time-range-unit" class="stats-option-slt">
                            <option value="day">Ngày</option>
                            <option value="month">Tháng</option>
                            <option value="year">Năm</option>
                        </select>
                    </div>
                    <div id="category-menu-ctn">
                        <label>Chọn loại:</label>
                        <div id="category-menu" class="stats-option-ctn border">
                            <label><input class="category-cb" type="checkbox" name="category[]" value="Sneakers" checked>Sneakers</label>
                            <label><input class="category-cb" type="checkbox" name="category[]" value="Boots" checked>Boots</label>
                            <label><input class="category-cb" type="checkbox" name="category[]" value="Sandals" checked>Sandals</label>
                            <label><input class="category-cb" type="checkbox" name="category[]" value="Loafers" checked>Loafers</label>
                            <label><input class="category-cb" type="checkbox" name="category[]" value="Athletic" checked>Athletic</label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="PRODUCTS content-ctn">
    PRODUCTS
    </div>
    <div class="ORDERS content-ctn">
    ORDERS
    </div>
    <div class="CUSTOMERS content-ctn">
    CUSTOMERS
    </div>
    <div class="EMPLOYEES content-ctn">
    EMPLOYEES
    </div>
    <div class="SUPPLIERS content-ctn">
    SUPPLIERS
    </div>
</div>