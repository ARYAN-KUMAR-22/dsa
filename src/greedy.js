'use strict';

/**
 * Greedy algorithms extracted from the interactive visualisers.
 */

/**
 * Fractional Knapsack.
 * items: Array of { value, weight } (positive numbers).
 * capacity: positive number.
 * Returns { maxValue, fractions } where fractions[i] is the fraction of item i taken (0–1).
 * Matches knapsack_problem_fractional.html.
 */
function fractionalKnapsack(items, capacity) {
  if (capacity <= 0 || !items.length) return { maxValue: 0, fractions: items.map(() => 0) };

  // Sort by value/weight ratio descending
  const indexed = items.map((item, i) => ({ ...item, ratio: item.value / item.weight, idx: i }));
  indexed.sort((a, b) => b.ratio - a.ratio);

  const fractions = Array(items.length).fill(0);
  let remaining = capacity;
  let maxValue = 0;

  for (const item of indexed) {
    if (remaining <= 0) break;
    const take = Math.min(item.weight, remaining);
    fractions[item.idx] = take / item.weight;
    maxValue += take * item.ratio;
    remaining -= take;
  }

  return { maxValue, fractions };
}

/**
 * Optimal Merge Pattern.
 * files: Array of file sizes (positive integers).
 * Returns minimum total merge cost (matches optimal_merge_pattern.html).
 * Uses a min-heap (simulated with a sorted array for simplicity).
 */
function optimalMergePattern(files) {
  if (files.length <= 1) return 0;

  const heap = [...files].sort((a, b) => a - b);

  function heapPush(val) {
    heap.push(val);
    heap.sort((a, b) => a - b);
  }

  function heapPop() {
    return heap.shift();
  }

  let totalCost = 0;
  while (heap.length > 1) {
    const a = heapPop();
    const b = heapPop();
    const cost = a + b;
    totalCost += cost;
    heapPush(cost);
  }

  return totalCost;
}

module.exports = {
  fractionalKnapsack,
  optimalMergePattern,
};
