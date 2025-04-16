<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    require 'connect.php';

    header('Content-Type: application/json');
    require_once 'connect.php';

    $statsBy = $_POST['stats-by-slt'] ?? '';

    $statsByTimeOption = $_POST['stats-by-time-option'] ?? '';

    $categories = $_POST['category'] ?? [];

    $byDayFrom = $_POST['by-day-from'] ?? '';
    $byDayTo = $_POST['by-day-to'] ?? '';
    $timeRangeUnit = $_POST['time-range-unit'] ?? '';


    $categoriesColor = [
        'Total' => [  
            'background' => 'rgba(54, 162, 235, 0.5)', // xanh dương nhạt
            'border' => 'rgba(54, 162, 235, 1)'
        ],
        'Sneakers' => [
            'background' => 'rgba(255, 206, 86, 0.5)', // vàng nhạt
            'border' => 'rgba(255, 206, 86, 1)'
        ],
        'Boots' => [
            'background' => 'rgba(144, 238, 144, 0.5)', // Xanh lá cây nhạt
            'border' => 'rgba(144, 238, 144, 1)'
        ],
        'Sandals' => [
            'background' => 'rgba(255, 99, 132, 0.5)', // đỏ nhạt
            'border' => 'rgba(255, 99, 132, 1)'
        ],
        'Loafers' => [
            'background' => 'rgba(75, 192, 192, 0.5)', // xanh ngọc nhạt
            'border' => 'rgba(75, 192, 192, 1)'
        ],
        'Athletic' => [
            'background' => 'rgba(153, 102, 255, 0.5)', // tím nhạt
            'border' => 'rgba(153, 102, 255, 1)'
        ]
    ];


    function getTimeRange()
    {
        global $statsByTimeOption;
        if ($statsByTimeOption === 'all') 
        {
            return ['2023-01-01 00:00:00', date('Y-m-d H:i:s')];
        } 
        else 
        {
            global $byDayFrom;
            global $byDayTo;
            return [$byDayFrom.' 00:00:00', $byDayTo.' 23:59:59'];
        }
    }

    function createTotalCategoryData()
    {
        $status="success";
        global $conn;
        global $categoriesColor;
        $timeRange=getTimeRange();

        $sql="  SELECT categories.name AS category, SUM(orders.total_amount) AS categorySum
                FROM order_details
                JOIN orders ON order_details.order_id = orders.order_id
                JOIN product_size ON order_details.product_size_id = product_size.id
                JOIN products ON product_size.product_id = products.id 
                JOIN categories ON products.category_id = categories.id
                WHERE orders.status = 'delivered'
                AND orders.order_date BETWEEN ? AND ?
                GROUP BY categories.name ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$timeRange[0],$timeRange[1]);
        $stmt->execute();
        $result=$stmt->get_result();
        if($result->num_rows==0)
        {
            $status='warning';
        }

        $dataArray=[];
        while($row=$result->fetch_assoc())
        {
            $dataArray[$row['category']]=$row['categorySum'];
        }
        
        $labels = ['Tổng'];
        $datas = [0];

        global $categories;
        foreach($categories as $category)
        {
            $labels[]=$category;

            $value = (float)($dataArray[$category] ?? 0)/1000000;
            $datas[0] += $value;
            $datas[] = $value;
        }

        $datasets=[[
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

        return [$labels,$datasets,$status];
    }

    function createTotalTimeData() 
    {
        $status='success';
        global $conn;
        global $categoriesColor;
        $timeRange=getTimeRange();

        $labels = [];
        $datasets=[];
        global $categories;
        $dayFrom=new DateTime($timeRange[0]);
        $dayTo=new DateTime($timeRange[1]);
        global $timeRangeUnit;
        switch($timeRangeUnit)
        {
            case "day":
                for($i = (clone $dayFrom);$i<=$dayTo;$i->modify("+1 day"))
                {
                    $labels[]=$i->format('d-m-Y');
                }
                
                $sql="  SELECT 
                        DATE(orders.order_date) AS day,
                        categories.name AS category,
                        SUM(orders.total_amount) AS total
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
                $day1=$dayFrom->format('Y-m-d H:i:s');
                $day2=$dayTo->format('Y-m-d H:i:s');
                $stmt->bind_param("ss",$day1,$day2);
                $stmt->execute();
                $result=$stmt->get_result();
                if($result->num_rows==0)
                {
                    $status='warning';
                }

                $dataArray=[];
                while($row=$result->fetch_assoc())
                {
                    $dataArray[$row['day']][$row['category']] = $row['total']/1000000; 
                }

                $categoryData=[];
                for ($i = clone $dayFrom ; $i<=$dayTo ; $i->modify('+1 day'))
                {
                    $currentDay=$i->format('Y-m-d');
                    foreach($categories as $category)
                    {
                        $categoryData[$category][]=$dataArray[$currentDay][$category] ?? 0;        
                    }
                }

                foreach($categories as $category)
                {
                    $datasets[]=[
                        'label' => $category,
                        'data' => $categoryData[$category]??[],
                        'backgroundColor' => $categoriesColor[$category]['background'],
                        'borderColor' => $categoriesColor[$category]['border'],
                        'borderWidth' => '1'
                    ];
                }
                return [$labels,$datasets, $status];
            case "month":
                for($i = (clone $dayFrom);$i<=$dayTo;$i->modify("first day of next month"))
                {
                    $labels[]="Tháng {$i->format('m')} ({$i->format('Y')})";
                }
                
                $sql="  SELECT 
                        DATE_FORMAT(orders.order_date, '%Y-%m') AS month,
                        categories.name AS category,
                        SUM(orders.total_amount) AS total
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
                $day1=$dayFrom->format('Y-m-d H:i:s');
                $day2=$dayTo->format('Y-m-d H:i:s');
                $stmt->bind_param("ss",$day1,$day2);
                $stmt->execute();
                $result=$stmt->get_result();
                if($result->num_rows==0)
                {
                    $status='warning';
                }

                $dataArray=[];
                while($row=$result->fetch_assoc())
                {
                    $dataArray[$row['month']][$row['category']] = $row['total']/1000000; 
                }
                
                $categoryData=[];
                for ($i = clone $dayFrom ; $i<=$dayTo ; $i->modify('first day of next month'))
                {
                    $currentMonth=$i->format('Y-m');
                    foreach($categories as $category)
                    {
                        $categoryData[$category][]=$dataArray[$currentMonth][$category] ?? 0;        
                    }
                }

                foreach($categories as $category)
                {
                    $datasets[]=[
                        'label' => $category,
                        'data' => $categoryData[$category]??[],
                        'backgroundColor' => $categoriesColor[$category]['background'],
                        'borderColor' => $categoriesColor[$category]['border'],
                        'borderWidth' => '1'
                    ];
                }
                return [$labels,$datasets, $status];
            case "year":

                break;
        }
    }

    function createCategoryTimeData() 
    {

    }

    $labels = [];
    $datasets = [];
    $status='';
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
