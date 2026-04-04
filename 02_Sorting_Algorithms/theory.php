<?php
$algo_key = $_GET['algo'] ?? 'bubble';

$data = [
    'bubble' => [
        'name' => 'Bubble Sort',
        'desc' => 'Bubble Sort is the simplest sorting algorithm that works by repeatedly swapping the adjacent elements if they are in the wrong order. This algorithm is not suitable for large data sets as its average and worst-case time complexity is quite high.',
        'time' => 'O(N²)',
        'space' => 'O(1)',
        'sim' => 'bubble_sort_animation.php'
    ],
    'insertion' => [
        'name' => 'Insertion Sort',
        'desc' => 'Insertion sort works similarly as we sort playing cards in our hands. The array is virtually split into a sorted and an unsorted part. Values from the unsorted part are picked and placed at the correct position in the sorted part.',
        'time' => 'O(N²)',
        'space' => 'O(1)',
        'sim' => 'insertion_sort_animation.php'
    ],
    'selection' => [
        'name' => 'Selection Sort',
        'desc' => 'Selection sort repeatedly selects the smallest (or largest) element from the unsorted portion of the list and swaps it with the first element of the unsorted portion.',
        'time' => 'O(N²)',
        'space' => 'O(1)',
        'sim' => 'selection_sort_animation.php'
    ],
    'quick' => [
        'name' => 'Quick Sort',
        'desc' => 'QuickSort is a Divide and Conquer algorithm. It picks an element as a pivot and partitions the given array around the picked pivot, placing it in its correct sorted position.',
        'time' => 'O(N log N)',
        'space' => 'O(log N)',
        'sim' => 'quick_sort_animation.php'
    ],
    'merge' => [
        'name' => 'Merge Sort',
        'desc' => 'Merge Sort is a Divide and Conquer algorithm. It divides the input array into two halves, calls itself for the two halves, and then merges the two sorted halves.',
        'time' => 'O(N log N)',
        'space' => 'O(N)',
        'sim' => 'merge_sort_animation.php'
    ],
    'count' => [
        'name' => 'Count Sort',
        'desc' => 'Counting sort is a sorting technique based on keys between a specific range. It sorts by counting the number of objects having distinct key values (a kind of hashing).',
        'time' => 'O(N + K)',
        'space' => 'O(N + K)',
        'sim' => 'count_sort_animation.php'
    ],
    'bucket' => [
        'name' => 'Bucket Sort',
        'desc' => 'Bucket sort is mainly useful when input is uniformly distributed over a range. It divides the array into buckets, sorts individual buckets, and then concatenates them.',
        'time' => 'O(N + K)',
        'space' => 'O(N + K)',
        'sim' => 'bucket_sort_animation.php'
    ],
    'radix' => [
        'name' => 'Radix Sort',
        'desc' => 'Radix sort processes digits from least significant to most significant. It uses a stable sorting algorithm (like counting sort) as a subroutine to sort the digits.',
        'time' => 'O(d * (N + K))',
        'space' => 'O(N + K)',
        'sim' => 'radix_sort_animation.php'
    ],
    'shell' => [
        'name' => 'Shell Sort',
        'desc' => 'ShellSort is mainly a variation of Insertion Sort. In insertion sort, we move elements only one position ahead. When an element has to move far ahead, many movements are involved. Shellsort allows swapping of far elements by using gap sequence.',
        'time' => 'O(N log N)',
        'space' => 'O(1)',
        'sim' => 'shell_sort_animation.php'
    ],
];

if (!isset($data[$algo_key])) {
    $algo_key = 'bubble';
}

$algo = $data[$algo_key];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
     body { 
        font-family: 'Inter', sans-serif; 
        padding: 50px; 
        color: #f8fafc; 
        background: #030712; 
        margin: 0;
     }
     body.light { 
        color: #0f172a; 
        background: #f8fafc; 
     }
     .container { 
        max-width: 800px; 
        margin: 0 auto; 
        background: #111827; 
        padding: 50px; 
        border-radius: 20px; 
        border: 1px solid rgba(148,163,184,0.15); 
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
     }
     body.light .container { 
        background: #ffffff; 
        border-color: rgba(15,23,42,0.15); 
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
     }
     h1 { 
        color: #38bdf8; 
        margin-bottom: 24px; 
        font-size: 2.8rem; 
        margin-top: 0;
     }
     body.light h1 { 
        color: #0284c7; 
     }
     p { 
        line-height: 1.8; 
        font-size: 1.15rem; 
        margin-bottom: 40px; 
        color: #94a3b8; 
     }
     body.light p { 
        color: #52637a; 
     }
     .metrics { 
        display: flex; 
        gap: 20px; 
        margin-bottom: 40px; 
     }
     .metric { 
        background: #1f2937; 
        padding: 20px 24px; 
        border-radius: 12px; 
        border: 1px solid rgba(148,163,184,0.1); 
        flex: 1;
     }
     body.light .metric { 
        background: #f1f5f9; 
     }
     .metric span { 
        display: block; 
        font-size: 0.85rem; 
        text-transform: uppercase; 
        color: #94a3b8; 
        letter-spacing: 0.05em; 
        margin-bottom: 8px;
        font-weight: 600;
     }
     body.light .metric span { 
        color: #64748b; 
     }
     .metric strong { 
        font-size: 1.4rem; 
     }
     .btn { 
        display: inline-block; 
        padding: 16px 32px; 
        background: #38bdf8; 
        color: #fff; 
        text-decoration: none; 
        border-radius: 12px; 
        font-weight: 600; 
        font-size: 1.1rem;
        transition: transform 0.2s, box-shadow 0.2s; 
     }
     body.light .btn { 
        background: #0284c7; 
     }
     .btn:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 4px 15px rgba(56, 189, 248, 0.4);
     }
     
     .header-tag {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 16px;
     }
     body.light .header-tag {
        background: rgba(2, 132, 199, 0.1);
        color: #0284c7;
     }
  </style>
</head>
<body>
  <script>
    // Inherit theme from parent window seamlessly
    if (localStorage.getItem('theme') === 'light' || (window.parent && window.parent.localStorage.getItem('theme') === 'light')) {
        document.body.classList.add('light');
    }
  </script>
  <div class="container">
     <div class="header-tag">Algorithm Theory</div>
     <h1><?= $algo['name'] ?></h1>
     <p><?= $algo['desc'] ?></p>
     <div class="metrics">
        <div class="metric">
           <span>Time Complexity</span>
           <strong><?= $algo['time'] ?></strong>
        </div>
        <div class="metric">
           <span>Space Complexity</span>
           <strong><?= $algo['space'] ?></strong>
        </div>
     </div>
     <a href="<?= $algo['sim'] ?>" target="_top" class="btn">Launch Interactive Visualizer &rarr;</a>
  </div>
</body>
</html>
