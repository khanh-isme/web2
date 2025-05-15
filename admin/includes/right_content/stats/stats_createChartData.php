<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../../connect.php';

    header('Content-Type: application/json');

    $statsBy = $_POST['stats-by-slt'] ?? '';

    $statsByTimeOption = $_POST['stats-by-time-option'] ?? '';

    $categories = $_POST['category'] ?? [];

    $byDayFrom = $_POST['by-day-from'] ?? '';
    $byDayTo = $_POST['by-day-to'] ?? '';
    $timeRangeUnit = $_POST['time-range-unit'] ?? '';


    $categoriesColor = [
        'Total' => [
            'background' => 'rgba(54, 162, 235, 0.5)',
            'border' => 'rgba(54, 162, 235, 1)'
        ],
        'Sneakers' => [
            'background' => 'rgba(255, 206, 86, 0.5)',
            'border' => 'rgba(255, 206, 86, 1)'
        ],
        'Boots' => [
            'background' => 'rgba(144, 238, 144, 0.5)',
            'border' => 'rgba(144, 238, 144, 1)'
        ],
        'Sandals' => [
            'background' => 'rgba(255, 99, 132, 0.5)',
            'border' => 'rgba(255, 99, 132, 1)'
        ],
        'Loafers' => [
            'background' => 'rgba(75, 192, 192, 0.5)',
            'border' => 'rgba(75, 192, 192, 1)'
        ],
        'Athletic' => [
            'background' => 'rgba(153, 102, 255, 0.5)',
            'border' => 'rgba(153, 102, 255, 1)'
        ]
    ];

    function getTimeRange()
    {
        global $statsByTimeOption;
        if ($statsByTimeOption === 'all') {
            return ['2023-01-01 00:00:00', date('Y-m-d H:i:s')];
        } else {
            global $byDayFrom;
            global $byDayTo;
            return [$byDayFrom . ' 00:00:00', $byDayTo . ' 23:59:59'];
        }
    }

    function createTotalCategoryData()
    {
        $status = "success";
        global $conn;
        global $categoriesColor;
        $timeRange = getTimeRange();

        $sql = "  SELECT categories.name AS category, SUM(orders.total_amount / 1000000) AS categorySum
                FROM order_details
                JOIN orders ON order_details.order_id = orders.order_id
                JOIN product_size ON order_details.product_size_id = product_size.id
                JOIN products ON product_size.product_id = products.id 
                JOIN categories ON products.category_id = categories.id
                WHERE orders.status = 'delivered'
                AND orders.order_date BETWEEN ? AND ?
                GROUP BY categories.name ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $timeRange[0], $timeRange[1]);
        if (!$stmt->execute()) {
            $errorCode = $stmt->errno;

            if ($errorCode == 1264) {
                $response = [
                    'status' => 'error',
                    'message' => 'Giá trị vượt quá giới hạn (Lớn hơn 10 triệu tỷ)',
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Có lỗi xảy ra vui lòng thử lại',
                ];
            }

            echo json_encode($response);
            exit;
        }
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            $status = 'warning';
        }

        $dataArray = [];
        while ($row = $result->fetch_assoc()) {
            $dataArray[$row['category']] = $row['categorySum'];
        }

        $labels = ['Tổng'];
        $datas = [0];

        global $categories;
        foreach ($categories as $category) {
            $labels[] = $category;

            $value = (float)($dataArray[$category] ?? 0);
            $datas[0] += $value;
            $datas[] = $value;
        }

        $datasets = [[
            'label' => 'Doanh thu theo danh mục (triệu VND)',
            'data' => $datas,
            'backgroundColor' => [
                $categoriesColor['Total']['background'],
                $categoriesColor['Sneakers']['background'],
                $categoriesColor['Boots']['background'],
                $categoriesColor['Sandals']['background'],
                $categoriesColor['Loafers']['background'],
                $categoriesColor['Athletic']['background'],
            ],
            'borderColor' => [
                $categoriesColor['Total']['border'],
                $categoriesColor['Sneakers']['border'],
                $categoriesColor['Boots']['border'],
                $categoriesColor['Sandals']['border'],
                $categoriesColor['Loafers']['border'],
                $categoriesColor['Athletic']['border'],
            ],
            'borderWidth' => '1'
        ]];

        return [$labels, $datasets, $status];
    }

    function createTotalTimeData()
    {
        $status = 'success';
        global $conn;
        global $categoriesColor;
        $timeRange = getTimeRange();

        $labels = [];
        $datasets = [];
        global $categories;

        $dayFrom = new DateTime($timeRange[0]);
        $dayTo = new DateTime($timeRange[1]);
        global $timeRangeUnit;

        switch ($timeRangeUnit) {
            case 'day':
                for ($i = (clone $dayFrom); $i <= $dayTo; $i->modify("+1 day")) {
                    $labels[] = $i->format('d-m-Y');
                }

                $sql = "  SELECT 
                        DATE(orders.order_date) AS day,
                        SUM(orders.total_amount / 1000000) AS total
                        FROM order_details
                        JOIN orders ON order_details.order_id = orders.order_id
                        JOIN product_size ON order_details.product_size_id = product_size.id
                        JOIN products ON product_size.product_id = products.id 
                        JOIN categories ON products.category_id = categories.id
                        WHERE orders.status = 'delivered'
                        AND orders.order_date BETWEEN ? AND ? ";

                $categorySQL = 'AND (false ';
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $categorySQL .= "or categories.name = '$category' ";
                    }
                }
                $categorySQL .= ')';
                $sql .= $categorySQL . " GROUP BY day ORDER BY day ASC ";

                $stmt = $conn->prepare($sql);
                $day1 = $dayFrom->format('Y-m-d H:i:s');
                $day2 = $dayTo->format('Y-m-d H:i:s');
                $stmt->bind_param("ss", $day1, $day2);
                if (!$stmt->execute()) {
                    $errorCode = $stmt->errno;

                    if ($errorCode == 1264) {
                        $response = [
                            'status' => 'error',
                            'message' => 'Giá trị vượt quá giới hạn (Lớn hơn 10 triệu tỷ)',
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Có lỗi xảy ra vui lòng thử lại',
                        ];
                    }

                    echo json_encode($response);
                    exit;
                }
                $result = $stmt->get_result();
                if ($result->num_rows == 0) {
                    $status = 'warning';
                }

                $dataArray = [];
                while ($row = $result->fetch_assoc()) {
                    $dataArray[$row['day']] = $row['total'];
                }

                $datas = [];

                for ($i = (clone $dayFrom); $i <= $dayTo; $i->modify("+1 day")) {
                    $currentDay = $i->format('Y-m-d');
                    $datas[] = $dataArray[$currentDay] ?? 0;
                }

                $datasets = [[
                    'label' => 'Doanh thu theo thời gian (triệu VND)',
                    'data' => $datas,
                    'backgroundColor' => [
                        $categoriesColor['Total']['background'],
                    ],
                    'borderColor' => [
                        $categoriesColor['Total']['border'],
                    ],
                    'borderWidth' => '1'
                ]];
                return [$labels, $datasets, $status];
            case 'month':
                for ($i = (clone $dayFrom); $i <= $dayTo; $i->modify("first day of next month")) {
                    $labels[] = $i->format('m-Y');
                }

                $sql = "  SELECT 
                        DATE_FORMAT(orders.order_date, '%Y-%m') AS month,
                        SUM(orders.total_amount / 1000000) AS total
                        FROM order_details
                        JOIN orders ON order_details.order_id = orders.order_id
                        JOIN product_size ON order_details.product_size_id = product_size.id
                        JOIN products ON product_size.product_id = products.id 
                        JOIN categories ON products.category_id = categories.id
                        WHERE orders.status = 'delivered'
                        AND orders.order_date BETWEEN ? AND ? ";

                $categorySQL = 'AND (false ';
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $categorySQL .= "or categories.name = '$category' ";
                    }
                }
                $categorySQL .= ')';
                $sql .= $categorySQL . " GROUP BY month ORDER BY month ASC ";

                $stmt = $conn->prepare($sql);
                $day1 = $dayFrom->format('Y-m-d H:i:s');
                $day2 = $dayTo->format('Y-m-d H:i:s');
                $stmt->bind_param("ss", $day1, $day2);
                if (!$stmt->execute()) {
                    $errorCode = $stmt->errno;

                    if ($errorCode == 1264) {
                        $response = [
                            'status' => 'error',
                            'message' => 'Giá trị vượt quá giới hạn (Lớn hơn 10 triệu tỷ)',
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Có lỗi xảy ra vui lòng thử lại',
                        ];
                    }

                    echo json_encode($response);
                    exit;
                }
                $result = $stmt->get_result();
                if ($result->num_rows == 0) {
                    $status = 'warning';
                }

                $dataArray = [];
                while ($row = $result->fetch_assoc()) {
                    $dataArray[$row['month']] = $row['total'];
                }

                $datas = [];

                for ($i = (clone $dayFrom); $i <= $dayTo; $i->modify("first day of next month")) {
                    $currentMonth = $i->format('Y-m');
                    $datas[] = $dataArray[$currentMonth] ?? 0;
                }

                $datasets = [[
                    'label' => 'Doanh thu theo thời gian (triệu VND)',
                    'data' => $datas,
                    'backgroundColor' => [
                        $categoriesColor['Boots']['background'],
                    ],
                    'borderColor' => [
                        $categoriesColor['Boots']['border'],
                    ],
                    'borderWidth' => '1'
                ]];
                return [$labels, $datasets, $status];
            case 'year':
                for ($i = (clone $dayFrom); $i <= $dayTo; $i->modify("first day of January next year")) {
                    $labels[] = $i->format('Y');
                }

                $sql = "  SELECT 
                        DATE_FORMAT(orders.order_date,'%Y') AS year,
                        SUM(orders.total_amount / 1000000) AS total
                        FROM order_details
                        JOIN orders ON order_details.order_id = orders.order_id
                        JOIN product_size ON order_details.product_size_id = product_size.id
                        JOIN products ON product_size.product_id = products.id 
                        JOIN categories ON products.category_id = categories.id
                        WHERE orders.status = 'delivered'
                        AND orders.order_date BETWEEN ? AND ? ";

                $categorySQL = 'AND (false ';
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $categorySQL .= "or categories.name = '$category' ";
                    }
                }
                $categorySQL .= ')';
                $sql .= $categorySQL . " GROUP BY year ORDER BY year ASC ";

                $stmt = $conn->prepare($sql);
                $day1 = $dayFrom->format('Y-m-d H:i:s');
                $day2 = $dayTo->format('Y-m-d H:i:s');
                $stmt->bind_param("ss", $day1, $day2);
                if (!$stmt->execute()) {
                    $errorCode = $stmt->errno;

                    if ($errorCode == 1264) {
                        $response = [
                            'status' => 'error',
                            'message' => 'Giá trị vượt quá giới hạn (Lớn hơn 10 triệu tỷ)',
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Có lỗi xảy ra vui lòng thử lại',
                        ];
                    }

                    echo json_encode($response);
                    exit;
                }
                $result = $stmt->get_result();
                if ($result->num_rows == 0) {
                    $status = 'warning';
                }

                $dataArray = [];
                while ($row = $result->fetch_assoc()) {
                    $dataArray[$row['year']] = $row['total'];
                }

                $datas = [];

                for ($i = (clone $dayFrom); $i <= $dayTo; $i->modify("first day of January next year")) {
                    $currentYear = $i->format('Y');
                    $datas[] = $dataArray[$currentYear] ?? 0;
                }

                $datasets = [[
                    'label' => 'Doanh thu theo thời gian (triệu VND)',
                    'data' => $datas,
                    'backgroundColor' => [
                        $categoriesColor['Sneakers']['background'],
                    ],
                    'borderColor' => [
                        $categoriesColor['Sneakers']['border'],
                    ],
                    'borderWidth' => '1'
                ]];
                return [$labels, $datasets, $status];
        }
    }

    function createCategoryTimeData()
    {
        $status = 'success';
        global $conn;
        global $categoriesColor;
        $timeRange = getTimeRange();

        $labels = [];
        $datasets = [];
        global $categories;

        $dayFrom = new DateTime($timeRange[0]);
        $dayTo = new DateTime($timeRange[1]);
        global $timeRangeUnit;
        switch ($timeRangeUnit) {
            case "day":
                for ($i = (clone $dayFrom); $i <= $dayTo; $i->modify("+1 day")) {
                    $labels[] = $i->format('d-m-Y');
                }

                $sql = "  SELECT 
                        DATE(orders.order_date) AS day,
                        categories.name AS category,
                        SUM(orders.total_amount / 1000000) AS total
                        FROM order_details
                        JOIN orders ON order_details.order_id = orders.order_id
                        JOIN product_size ON order_details.product_size_id = product_size.id
                        JOIN products ON product_size.product_id = products.id 
                        JOIN categories ON products.category_id = categories.id
                        WHERE orders.status = 'delivered'
                        AND orders.order_date BETWEEN ? AND ?
                        GROUP BY day, category
                        ORDER BY day ASC ";

                $stmt = $conn->prepare($sql);
                $day1 = $dayFrom->format('Y-m-d H:i:s');
                $day2 = $dayTo->format('Y-m-d H:i:s');
                $stmt->bind_param("ss", $day1, $day2);
                if (!$stmt->execute()) {
                    $errorCode = $stmt->errno;

                    if ($errorCode == 1264) {
                        $response = [
                            'status' => 'error',
                            'message' => 'Giá trị vượt quá giới hạn (Lớn hơn 10 triệu tỷ)',
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Có lỗi xảy ra vui lòng thử lại',
                        ];
                    }

                    echo json_encode($response);
                    exit;
                }
                $result = $stmt->get_result();
                if ($result->num_rows == 0) {
                    $status = 'warning';
                }

                $dataArray = [];
                while ($row = $result->fetch_assoc()) {
                    $dataArray[$row['day']][$row['category']] = $row['total'];
                }

                $categoryData = [];
                for ($i = clone $dayFrom; $i <= $dayTo; $i->modify('+1 day')) {
                    $currentDay = $i->format('Y-m-d');
                    foreach ($categories as $category) {
                        $categoryData[$category][] = $dataArray[$currentDay][$category] ?? 0;
                    }
                }

                foreach ($categories as $category) {
                    $datasets[] = [
                        'label' => $category,
                        'data' => $categoryData[$category] ?? [],
                        'backgroundColor' => $categoriesColor[$category]['background'],
                        'borderColor' => $categoriesColor[$category]['border'],
                        'borderWidth' => '1'
                    ];
                }
                return [$labels, $datasets, $status];
            case "month":
                for ($i = (clone $dayFrom); $i <= $dayTo; $i->modify("first day of next month")) {
                    $labels[] = "Tháng {$i->format('m')} ({$i->format('Y')})";
                }

                $sql = "  SELECT 
                        DATE_FORMAT(orders.order_date, '%Y-%m') AS month,
                        categories.name AS category,
                        SUM(orders.total_amount / 1000000) AS total
                        FROM order_details
                        JOIN orders ON order_details.order_id = orders.order_id
                        JOIN product_size ON order_details.product_size_id = product_size.id
                        JOIN products ON product_size.product_id = products.id 
                        JOIN categories ON products.category_id = categories.id
                        WHERE orders.status = 'delivered'
                        AND orders.order_date BETWEEN ? AND ?
                        GROUP BY month, category
                        ORDER BY month ASC; ";

                $stmt = $conn->prepare($sql);
                $day1 = $dayFrom->format('Y-m-d H:i:s');
                $day2 = $dayTo->format('Y-m-d H:i:s');
                $stmt->bind_param("ss", $day1, $day2);
                if (!$stmt->execute()) {
                    $errorCode = $stmt->errno;

                    if ($errorCode == 1264) {
                        $response = [
                            'status' => 'error',
                            'message' => 'Giá trị vượt quá giới hạn (Lớn hơn 10 triệu tỷ)',
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Có lỗi xảy ra vui lòng thử lại',
                        ];
                    }

                    echo json_encode($response);
                    exit;
                }
                $result = $stmt->get_result();
                if ($result->num_rows == 0) {
                    $status = 'warning';
                }

                $dataArray = [];
                while ($row = $result->fetch_assoc()) {
                    $dataArray[$row['month']][$row['category']] = $row['total'];
                }

                $categoryData = [];
                for ($i = clone $dayFrom; $i <= $dayTo; $i->modify('first day of next month')) {
                    $currentMonth = $i->format('Y-m');
                    foreach ($categories as $category) {
                        $categoryData[$category][] = $dataArray[$currentMonth][$category] ?? 0;
                    }
                }

                foreach ($categories as $category) {
                    $datasets[] = [
                        'label' => $category,
                        'data' => $categoryData[$category] ?? [],
                        'backgroundColor' => $categoriesColor[$category]['background'],
                        'borderColor' => $categoriesColor[$category]['border'],
                        'borderWidth' => '1'
                    ];
                }
                return [$labels, $datasets, $status];
            case "year":
                for ($i = (clone $dayFrom); $i <= $dayTo; $i->modify("first day of January next year")) {
                    $labels[] = "Năm {$i->format('Y')}";
                }

                $sql = "  SELECT 
                        DATE_FORMAT(orders.order_date, '%Y') AS year,
                        categories.name AS category,
                        SUM(orders.total_amount / 1000000) AS total
                        FROM order_details
                        JOIN orders ON order_details.order_id = orders.order_id
                        JOIN product_size ON order_details.product_size_id = product_size.id
                        JOIN products ON product_size.product_id = products.id 
                        JOIN categories ON products.category_id = categories.id
                        WHERE orders.status = 'delivered'
                        AND orders.order_date BETWEEN ? AND ?
                        GROUP BY year, category
                        ORDER BY year ASC; ";

                $stmt = $conn->prepare($sql);
                $day1 = $dayFrom->format('Y-m-d H:i:s');
                $day2 = $dayTo->format('Y-m-d H:i:s');
                $stmt->bind_param("ss", $day1, $day2);
                if (!$stmt->execute()) {
                    $errorCode = $stmt->errno;

                    if ($errorCode == 1264) {
                        $response = [
                            'status' => 'error',
                            'message' => 'Giá trị vượt quá giới hạn (Lớn hơn 10 triệu tỷ)',
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Có lỗi xảy ra vui lòng thử lại',
                        ];
                    }

                    echo json_encode($response);
                    exit;
                }
                $result = $stmt->get_result();
                if ($result->num_rows == 0) {
                    $status = 'warning';
                }

                $dataArray = [];
                while ($row = $result->fetch_assoc()) {
                    $dataArray[$row['year']][$row['category']] = $row['total'];
                }

                $categoryData = [];
                for ($i = clone $dayFrom; $i <= $dayTo; $i->modify('first day of January next year')) {
                    $currentYear = $i->format('Y');
                    foreach ($categories as $category) {
                        $categoryData[$category][] = $dataArray[$currentYear][$category] ?? 0;
                    }
                }

                foreach ($categories as $category) {
                    $datasets[] = [
                        'label' => $category,
                        'data' => $categoryData[$category] ?? [],
                        'backgroundColor' => $categoriesColor[$category]['background'],
                        'borderColor' => $categoriesColor[$category]['border'],
                        'borderWidth' => '1'
                    ];
                }
                return [$labels, $datasets, $status];
        }
    }

    $labels = [];
    $datasets = [];
    $status = '';
    $response = [];
    switch ($statsBy) {
        case 'total-category':
            [$labels, $datasets, $status] = createTotalCategoryData();
            break;

        case 'total-time':
            [$labels, $datasets, $status] = createTotalTimeData();
            break;

        case 'category-time':
            [$labels, $datasets, $status] = createCategoryTimeData();
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Loại thống kê không hợp lệ.']);
            exit();
    }

    $response = [
        'status' => $status,
        'chartData' => [
            'labels' => $labels,
            'datasets' => $datasets
        ]
    ];


    echo json_encode($response);
} else {
    echo "Phương thức không hợp lệ!";
    exit();
}
