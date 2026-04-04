'use strict';

/**
 * Divide-and-conquer algorithms extracted from the interactive visualisers.
 */

/**
 * Classic binary search – returns index of target in sorted array, or -1.
 * Matches the "classic_found / classic_missing" mode in the visualiser.
 */
function binarySearch(arr, target) {
  let low = 0, high = arr.length - 1;
  while (low <= high) {
    const mid = (low + high) >> 1;
    if (arr[mid] === target) return mid;
    if (arr[mid] < target) low = mid + 1;
    else high = mid - 1;
  }
  return -1;
}

/**
 * First occurrence of target in a sorted array (with duplicates).
 * Returns the smallest index whose value equals target, or -1.
 */
function firstOccurrence(arr, target) {
  let low = 0, high = arr.length - 1, ans = -1;
  while (low <= high) {
    const mid = (low + high) >> 1;
    if (arr[mid] === target) {
      ans = mid;
      high = mid - 1;
    } else if (arr[mid] < target) {
      low = mid + 1;
    } else {
      high = mid - 1;
    }
  }
  return ans;
}

/**
 * Lower bound – returns the smallest index i such that arr[i] >= target.
 * Returns arr.length if all elements are less than target.
 */
function lowerBound(arr, target) {
  let low = 0, high = arr.length - 1, ans = arr.length;
  while (low <= high) {
    const mid = (low + high) >> 1;
    if (arr[mid] >= target) {
      ans = mid;
      high = mid - 1;
    } else {
      low = mid + 1;
    }
  }
  return ans;
}

/**
 * Find maximum sub-array sum using divide and conquer.
 * O(n log n) – matches the max_subarray_divide_and_conquer visualiser.
 */
function maxSubarrayDivideConquer(arr) {
  if (!arr.length) return 0;

  function crossingSum(arr, low, mid, high) {
    let leftSum = -Infinity, sum = 0;
    for (let i = mid; i >= low; i--) {
      sum += arr[i];
      if (sum > leftSum) leftSum = sum;
    }
    let rightSum = -Infinity;
    sum = 0;
    for (let i = mid + 1; i <= high; i++) {
      sum += arr[i];
      if (sum > rightSum) rightSum = sum;
    }
    return leftSum + rightSum;
  }

  function solve(arr, low, high) {
    if (low === high) return arr[low];
    const mid = Math.floor((low + high) / 2);
    return Math.max(
      solve(arr, low, mid),
      solve(arr, mid + 1, high),
      crossingSum(arr, low, mid, high)
    );
  }

  return solve(arr, 0, arr.length - 1);
}

/**
 * Merge sort – used here to demonstrate k-way merging.
 * Returns a single sorted array merged from multiple sorted arrays.
 */
function kWayMerge(arrays) {
  return arrays.flat().sort((a, b) => a - b);
}

module.exports = {
  binarySearch,
  firstOccurrence,
  lowerBound,
  maxSubarrayDivideConquer,
  kWayMerge,
};
