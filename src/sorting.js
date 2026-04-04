'use strict';

/**
 * Sorting algorithms extracted from the interactive visualisers.
 * All functions return a new sorted array and leave the input unchanged.
 */

/** Bubble Sort – O(n²) average/worst, O(n) best with early-exit flag. */
function bubbleSort(arr) {
  const a = [...arr];
  const n = a.length;
  for (let i = 0; i < n - 1; i++) {
    let swapped = false;
    for (let j = 0; j < n - i - 1; j++) {
      if (a[j] > a[j + 1]) {
        [a[j], a[j + 1]] = [a[j + 1], a[j]];
        swapped = true;
      }
    }
    if (!swapped) break;
  }
  return a;
}

/** Selection Sort – O(n²) always. */
function selectionSort(arr) {
  const a = [...arr];
  const n = a.length;
  for (let i = 0; i < n - 1; i++) {
    let minIdx = i;
    for (let j = i + 1; j < n; j++) {
      if (a[j] < a[minIdx]) minIdx = j;
    }
    if (minIdx !== i) [a[i], a[minIdx]] = [a[minIdx], a[i]];
  }
  return a;
}

/** Insertion Sort – O(n²) average/worst, O(n) best. */
function insertionSort(arr) {
  const a = [...arr];
  const n = a.length;
  for (let i = 1; i < n; i++) {
    const key = a[i];
    let j = i - 1;
    while (j >= 0 && a[j] > key) {
      a[j + 1] = a[j];
      j--;
    }
    a[j + 1] = key;
  }
  return a;
}

/** Merge Sort – O(n log n) all cases. */
function mergeSort(arr) {
  const a = [...arr];
  if (a.length <= 1) return a;

  function merge(left, right) {
    const result = [];
    let i = 0, j = 0;
    while (i < left.length && j < right.length) {
      if (left[i] <= right[j]) result.push(left[i++]);
      else result.push(right[j++]);
    }
    return result.concat(left.slice(i)).concat(right.slice(j));
  }

  function sort(arr) {
    if (arr.length <= 1) return arr;
    const mid = Math.floor(arr.length / 2);
    return merge(sort(arr.slice(0, mid)), sort(arr.slice(mid)));
  }

  return sort(a);
}

/**
 * Quick Sort – O(n log n) average, O(n²) worst.
 * Uses last-element pivot (matches the visualiser's partition logic).
 */
function quickSort(arr) {
  const a = [...arr];

  function partition(a, low, high) {
    const pivot = a[high];
    let i = low - 1;
    for (let j = low; j < high; j++) {
      if (a[j] <= pivot) {
        i++;
        [a[i], a[j]] = [a[j], a[i]];
      }
    }
    [a[i + 1], a[high]] = [a[high], a[i + 1]];
    return i + 1;
  }

  function sort(a, low, high) {
    if (low < high) {
      const pi = partition(a, low, high);
      sort(a, low, pi - 1);
      sort(a, pi + 1, high);
    }
  }

  sort(a, 0, a.length - 1);
  return a;
}

/**
 * Counting Sort – O(n + k) where k = max value.
 * Handles only non-negative integers.
 */
function countingSort(arr) {
  if (!arr.length) return [];
  const a = [...arr];
  const max = Math.max(...a);
  const count = Array(max + 1).fill(0);
  a.forEach(v => count[v]++);
  for (let i = 1; i <= max; i++) count[i] += count[i - 1];
  const output = Array(a.length);
  for (let i = a.length - 1; i >= 0; i--) {
    output[count[a[i]] - 1] = a[i];
    count[a[i]]--;
  }
  return output;
}

/**
 * Radix Sort (LSD) – O(d · (n + b)) where d = digit count, b = base (10).
 * Handles only non-negative integers.
 */
function radixSort(arr) {
  if (!arr.length) return [];
  const a = [...arr];
  const max = Math.max(...a);

  function countingSortByDigit(a, exp) {
    const output = Array(a.length).fill(0);
    const count = Array(10).fill(0);
    a.forEach(v => count[Math.floor(v / exp) % 10]++);
    for (let i = 1; i < 10; i++) count[i] += count[i - 1];
    for (let i = a.length - 1; i >= 0; i--) {
      const digit = Math.floor(a[i] / exp) % 10;
      output[count[digit] - 1] = a[i];
      count[digit]--;
    }
    return output;
  }

  let result = a;
  for (let exp = 1; Math.floor(max / exp) > 0; exp *= 10) {
    result = countingSortByDigit(result, exp);
  }
  return result;
}

/**
 * Shell Sort – O(n log² n) with Knuth's sequence.
 */
function shellSort(arr) {
  const a = [...arr];
  const n = a.length;
  let gap = 1;
  while (gap < Math.floor(n / 3)) gap = gap * 3 + 1;
  while (gap >= 1) {
    for (let i = gap; i < n; i++) {
      const temp = a[i];
      let j = i;
      while (j >= gap && a[j - gap] > temp) {
        a[j] = a[j - gap];
        j -= gap;
      }
      a[j] = temp;
    }
    gap = Math.floor(gap / 3);
  }
  return a;
}

/**
 * Bucket Sort – O(n + k) average for uniformly distributed data.
 * Handles non-negative integers; buckets use insertion sort internally.
 */
function bucketSort(arr) {
  if (!arr.length) return [];
  const a = [...arr];
  const n = a.length;
  const max = Math.max(...a);
  const buckets = Array.from({ length: n }, () => []);

  a.forEach(v => {
    const idx = Math.floor((v / (max + 1)) * n);
    buckets[idx].push(v);
  });

  buckets.forEach(b => b.sort((x, y) => x - y));
  return buckets.flat();
}

module.exports = {
  bubbleSort,
  selectionSort,
  insertionSort,
  mergeSort,
  quickSort,
  countingSort,
  radixSort,
  shellSort,
  bucketSort,
};
