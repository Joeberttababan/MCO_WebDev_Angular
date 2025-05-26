<?php
session_start();
include_once('db.php');
// $con = connection();
$con = new mysqli('localhost', 'root', '', 'petpals');

// Query to get all post content
$sql = "SELECT content FROM posts";
$result = $con->query($sql);

// Array to store hashtag counts
$hashtags = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        preg_match_all('/#\w+/', $row['content'], $matches);
        foreach ($matches[0] as $tag) {
            $tag = strtolower($tag);
            if (isset($hashtags[$tag])) {
                $hashtags[$tag]++;
            } else {
                $hashtags[$tag] = 1;
            }
        }
    }
} else {
    echo "<p>No posts found.</p>";
}

// Sort hashtags by count descending
arsort($hashtags);

// Prepare data for JavaScript
$hashtagLabels = json_encode(array_keys($hashtags));
$hashtagCounts = json_encode(array_values($hashtags));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hashtag Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        h2 {
            color: #081b5a;
        }
        canvas {
            max-width: 600px;
            height:50%;
            margin: 30px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 40px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #081b5a;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        .barPie{
            height:50%;
            width:50%;
            display:flex;
            flex:1;
        }
    </style>
</head>
<body>

<h2>Hashtag Usage Dashboard</h2>
<a href="petfeed.php">Feed</a>

<!-- Table -->
<table>
    <thead>
        <tr>
            <th>Hashtag</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($hashtags as $tag => $count): ?>
        <tr>
            <td><?php echo htmlspecialchars($tag); ?></td>
            <td><?php echo $count; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Bar Chart -->
<div class="barPie">
<canvas id="barChart"></canvas>

<!-- Pie Chart -->
<canvas id="pieChart"></canvas>
</div>

<script>
    const labels = <?php echo $hashtagLabels; ?>;
    const data = <?php echo $hashtagCounts; ?>;

    const barConfig = {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Hashtag Count',
                data: data,
                backgroundColor: '#081b5a'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Most Used Hashtags (Bar Chart)'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    };

    const pieConfig = {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: labels.map((_, i) => `hsl(${i * 35}, 70%, 60%)`)
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Hashtag Distribution (Pie Chart)'
                }
            }
        }
    };

    new Chart(document.getElementById('barChart'), barConfig);
    new Chart(document.getElementById('pieChart'), pieConfig);
</script>

</body>
</html>
