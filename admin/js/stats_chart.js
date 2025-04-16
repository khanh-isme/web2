let chart;

function createChart(chartData)
{
    let context=document.querySelector('canvas').getContext('2d');
    chart=new Chart(context,{
        type: 'bar',
        data: chartData,
        options: {
            responsive: true, 
            scales: {
              x: {
                beginAtZero: true, 
                stacked: false,
                ticks: {
                    autoSkip: true,       
                    maxTicksLimit: 10,
                    display: true,
                }
              },
              y: {
                  beginAtZero: true,
                  stacked: false,
              }
            },
            animation: {
              duration: 500, 
              easing: 'ease',
            },
            
        }
    });
}

function drawChart()
{
    let statsMenuOptionForm=document.querySelector("#stats-menu-option-form");
    let formData=new FormData(statsMenuOptionForm);
    let warningDialog=document.querySelector("#chart-input-warning");
    warningDialog.classList.remove("active");

    fetch('includes/stats_createChartData.php',{
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(responseData => {
        try
        {
            let data=JSON.parse(responseData);
            if (chart)
            {
                chart.destroy();
            }
            if (data.status==='success')
            {
                createChart(data.chartData);
            }
            else 
            {
                if(data.status==='warning')
                {
                    warningDialog.classList.add("active");
                    createChart(data.chartData);
                }
                else
                {
                    if (chart)
                    {
                        chart.destroy();
                    }
                    warningDialog.classList.add("active");
                }
            }

        }
        catch(error)
        {
            console.error("Có lỗi xảy ra: ", error);
            console.error(responseData);
        }
    });
}

function handleChart()
{
    drawChart();
    let statsMenuOptionForm=document.querySelector("#stats-menu-option-form");
    statsMenuOptionForm.addEventListener("change",drawChart);
    let statsBySlt=document.querySelector("#stats-by-slt");
    statsBySlt.addEventListener("change",()=>{
        let timeRangeUnit=document.querySelector("#time-range-unit-ctn");
        if(statsBySlt.value==="total-category")
        {
            timeRangeUnit.classList.add("disabled");
        }
        else
        {
            timeRangeUnit.classList.remove("disabled");
        }
    });

    window.addEventListener('resize', function () {
        chart.resize();
    });
}

